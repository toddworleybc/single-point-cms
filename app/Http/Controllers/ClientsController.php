<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\User;
use App\Http\Requests\StoreClientsRequest;
use App\Http\Requests\UpdateClientsRequest;
use Inertia\Inertia;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        $users = User::all();

        $users = $users->map( function($value, $key) {
            return $value->only(['id', 'name', 'email']);
        } );


        return Inertia::render('Clients/Index', [
            'users' => $users
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $client = User::find($id);

    
        
        return Inertia::render('Clients/Show', [
            'client' => $client
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clients $clients)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientsRequest $request, Clients $clients)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clients $clients)
    {
        //
    }
}
