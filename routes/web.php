<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\LawyerController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Welcome Page ==========
// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {
    return redirect()->route('login');
});


// Dashboard Page ============
// Route::get('/dashboard', function () {

//     if(Auth::user()->role === 'client'){
//         // Client Dashboard
//         return Inertia::render('Client/Portal', [
//             'client' => Auth::user()
//         ]);
//     } else { 
//         // Lawyer Admin Dashboard
//          return Inertia::render('Dashboard');
//     }

// })->middleware(['auth', 'verified'])->name('dashboard');

// Backend Routing ========

// client portal ---


// Client Portal ==============
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/portal', [ClientsController::class, 'clientPortal'])->middleware(['auth', 'verified'])->name('client.portal');

    Route::post('/client/message_submit', [ClientsController::class, 'storeClientMessage'])->name('client.message.submit');
});

// Lawyer Dashboard
Route::middleware(['auth', 'verified'])->group(function () {

    // dashboard
    Route::get('/dashboard', [LawyerController::class, 'lawyerDashboard'])->name('lawyer.dashboard');

    // clients list
    Route::get('/clients', [LawyerController::class, 'clients'])->name('lawyer.clients');

    // single client
    Route::get('/client/{id}', [LawyerController::class, 'showClient'])->name('lawyer.show.client');

});




// Profile Pages ==============
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




require __DIR__.'/auth.php';
