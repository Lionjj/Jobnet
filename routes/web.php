<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobOffertController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{thread}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{thread}', [MessageController::class, 'store'])->name('messages.store');
    
    Route::get('/utenti', [ChatController::class, 'utenti'])->name('chat.utenti');
    Route::get('/chat/{user}', [ChatController::class, 'startChat'])->name('chat.avvia');
    Route::get('/chat', [ChatController::class, 'redirect'])->name('chat.redirect');

});

Route::middleware(['auth', 'role:recruiter'])->group(function () {
    Route::resource('companies', CompanyController::class);
    Route::resource('jobs', JobOffertController::class);
});

require __DIR__.'/auth.php';
