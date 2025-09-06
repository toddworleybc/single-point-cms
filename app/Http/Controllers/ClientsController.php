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
        $admin = User::where('id', 1)->first(); 


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
            'adminMessages' => $adminMessages,
            'adminName' => $admin->name,
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
            'subject' => 'required|string',
            'replied_to' => 'nullable|integer|exists:messages,id'
        ]);

        Messages::create([
            'message' => $request->message,
            'user_id' => $request->user()->id,
            'subject' => $request->subject,
            'message_to' => "1", // always lawyer/admin
            'message_from' => $request->user()->id,
            'replied_to' => $request->replied_to,
        ]);

        return redirect()->back();
    }   

}
