<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\AdminController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return redirect()->route('login');
});


// Client Portal ==============//
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/portal', [ClientsController::class, 'clientPortal'])->middleware(['auth', 'verified'])->name('client.portal');

    Route::post('/client/message_submit', [ClientsController::class, 'storeClientMessage'])->name('client.message.submit');

    Route::post('/client/client-mark-as-read', [ClientsController::class, 'markAsRead'])->name('client.mark.as.read');
});

// Admin Dashboard =================///
Route::middleware(['auth', 'verified'])->group(function () {

    // dashboard
    Route::get('/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');

    // clients list
    Route::get('/clients', [AdminController::class, 'clients'])->name('admin.clients');

    // single client
    Route::get('/client/{id}', [AdminController::class, 'showClient'])->name('admin.show.client');

    // single client message
    Route::get('/client/{id}', [AdminController::class, 'showClient'])->name('admin.show.client');

    // mark as read
    Route::post('/client/admin-mark-as-read', [AdminController::class, 'markAsRead'])->name('admin.mark.as.read');
    
    // Send message
    Route::post('/client/admin-submit-message', [AdminController::class, 'sendMessage'])->name('admin.submit.message');

});




// Profile Pages ==============
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




require __DIR__.'/auth.php';
