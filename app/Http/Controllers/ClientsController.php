<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\ClientMessages;
use App\Models\User;
use App\Http\Requests\StoreClientsRequest;
use App\Http\Requests\UpdateClientsRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    

    // index view for clientPortal to send data 

    public function clientPortal(Request $request) {

        $client = $request->user()->only(['id', 'name']);

         // Client Dashboard
        return Inertia::render('Client/Portal', [
            'client' => $client
        ]);


    }



    public function storeClientMessage(Request $request)
    {
     

        $request->validate([
            'message' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        ClientMessages::create([
            'message' => $request->message,
            'user_id' => $request->user_id
        ]);

        return redirect()->back();
    }   

}
