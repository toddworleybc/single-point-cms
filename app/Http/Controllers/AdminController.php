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

class AdminController extends Controller
{
    //
     //

    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
            
    //         if($request->user()->role !== 'client') {
    //             return $next($request);
    //         } 
            
    //     });
    // }




    public function adminDashboard()
    {


        return Inertia::render('Admin/Dashboard');

    }//====


    // Show client list page ====

    public function clients() {


        $users = User::where('role', '!=', 'ebd_admin')
                        ->orderBy('name', 'asc')
                        ->get();

        $users = $users->map( function($value, $key) {
            return $value->only(['id', 'name', 'email']);
        } );


        return Inertia::render('Admin/Clients', [
            'users' => $users
        ]);



    }//==========


    function showClient($id) {


        $client = User::select('id', 'name')->findOrFail($id);
        $clientMessages = $client->messages()
                                ->orderBy('created_at', 'desc')
                                ->get();

        $adminMessages = Messages::where('user_id', "1")
                                ->where('message_to', $id)
                                ->orderBy('created_at', 'desc')
                                ->get();


        return Inertia::render( 'Admin/ShowClient', [
            'client' => $client,
            'clientMessages' => $clientMessages,
            'adminMessages' => $adminMessages
        ] );

    }//


    function markAsRead(Request $request) {

        $message = Messages::findOrFail($request->id);
        $message->is_read = $request->is_read;
        $message->save();

        return redirect()->back();
    }

    function sendMessage(Request $request) {

        // validate
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'client_id' => 'required|integer|exists:users,id',
            'replied_to' => 'nullable|integer|exists:messages,id'
        ]);



        // store message
        Messages::create([
            'title' => $request->title,
            'user_id' => $request->user()->id,
            'message' => $request->message,
            'message_to' => $request->client_id,
            'message_from' => $request->user()->id,
            'replied_to' => $request->replied_to,
        ]);

        return redirect()->back();  


    }

}
