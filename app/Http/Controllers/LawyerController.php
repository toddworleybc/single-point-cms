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


class LawyerController extends Controller
{
    //

    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
            
    //         if($request->user()->role !== 'client') {
    //             return $next($request);
    //         } 
            
    //     });
    // }




    public function lawyerDashboard()
    {


        return Inertia::render('Lawyer/Dashboard');

    }//====


    // Show client list page ====

    public function clients() {


        $users = User::all();

        $users = $users->map( function($value, $key) {
            return $value->only(['id', 'name', 'email']);
        } );


        return Inertia::render('Lawyer/Clients', [
            'users' => $users
        ]);



    }//==========


    function showClient($id) {


        $client = User::select('id', 'name')->findOrFail($id);
        $clientMessages = $client->messages()->get();

        $adminMessages = Messages::where('user_id', "1")->get();


        return Inertia::render( 'Lawyer/ShowClient', [
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
