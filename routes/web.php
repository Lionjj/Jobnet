<?php

use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobOffertController;
use App\Http\Controllers\SavedJobController;
use App\Http\Controllers\InterviewProposalController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

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

    Route::get('/notifications/{id}/read', [NotificationController::class, 'redirectNotification'])->name('notifications.read');

});

Route::middleware(['auth', 'role:recruiter'])->group(function () {
    Route::resource('companies', CompanyController::class);
    Route::resource('jobs', JobOffertController::class);
    Route::patch('/applications/{id}/status', [JobApplicationController::class, 'updateStatus'])->name('applications.updateStatus');
    Route::post('/chat/{thread}/interview/propose', [InterviewProposalController::class, 'propose'])
    ->name('interviews.propose');
});

Route::middleware(['auth', 'role:candidate'])->group(function () {
    Route::get('/offert', [JobOffertController::class, 'publicIndex'])->name('jobs.publicIndex');
    Route::get('/offert/{id}', [JobOffertController::class, 'publicShow'])->name('jobs.publicShow');
    Route::get('/offert/companies/{id}', [CompanyController::class, 'publicShow'])->name('companies.publicShow');
    Route::post('/saved-jobs/{jobId}', [SavedJobController::class, 'store'])->name('saved-jobs.store');
    Route::delete('/saved-jobs/{jobId}', [SavedJobController::class, 'destroy'])->name('saved-jobs.destroy');
    Route::get('/saved-jobs', [SavedJobController::class, 'savedJobs'])->name('saved-jobs.index');
    Route::post('/applications', [JobApplicationController::class, 'store'])->name('applications.store');
    Route::post('/chat/{thread}/interview/{response}', [InterviewProposalController::class, 'respond'])
    ->name('interviews.respond');

});

require __DIR__.'/auth.php';
