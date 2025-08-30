<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Welcome Page ==========
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard Page ============
Route::get('/dashboard', function () {

    if(Auth::user()->role === 'client'){
        // Client Dashboard
        return Inertia::render('Clients/Portal');
    } else { 
        // Lawyer Admin Dashboard
         return Inertia::render('Dashboard');
    }

})->middleware(['auth', 'verified'])->name('dashboard');



// Profile Pages ==============
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Client Pages ==============
Route::middleware('auth')->group(function () {
    Route::get('/clients', [ClientsController::class, 'index'])->name('clients.index');
    Route::get('/client/{id}', [ClientsController::class, 'show'])->name('client.show');
});


require __DIR__.'/auth.php';
