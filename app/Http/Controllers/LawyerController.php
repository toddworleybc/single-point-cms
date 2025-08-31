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

        $client = User::find($id)->only('id', 'name');


        return Inertia::render( 'Lawyer/ShowClient', [
            'client' => $client
        ] );

    }//


}
