<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\Messages;
use App\Models\User;
use App\Http\Requests\StoreClientsRequest;
use App\Http\Requests\UpdateClientsRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    

    // index view for clientPortal to send data 

    public function clientPortal(Request $request) {

        $client = $request->user()->only(['id', 'name']);
        $messagesSent = $request->user()->messages()
                    ->orderBy('created_at', 'desc')
                    ->get();

        $adminMessages = Messages::where('message_to', $request->user()->id)
                         ->orderBy('created_at', 'desc')
                         ->get();
    

         // Client Dashboard
        return Inertia::render('Client/Portal', [
            'client' => $client,
            'messagesSent' => $messagesSent,
            'adminMessages' => $adminMessages
        ]);


    }


    function markAsRead(Request $request) {

        $message = Messages::findOrFail($request->id);
        $message->is_read = $request->is_read;
        $message->save();

        return redirect()->back();
    }



    public function storeClientMessage(Request $request)
    {
     

        $request->validate([
            'message' => 'required|string',
            'title' => 'required|string'
        ]);

        Messages::create([
            'message' => $request->message,
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'message_to' => "1", // always lawyer/admin
            'message_from' => $request->user()->id,
            'is_read' => "Not Read"
        ]);

        return redirect()->back();
    }   

}
