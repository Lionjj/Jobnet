<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function redirectNotification($id){
        $notification = Auth::user()->unreadNotifications()->findOrFail($id);
        $notification->markAsRead();

        // Redirezione dinamica
        if (isset($notification->data['thread_id'])) {
            return redirect()->route('messages.show', $notification->data['thread_id']);
        }

        if (isset($notification->data['job_id'])) {
            return redirect()->route('jobs.publicShow', $notification->data['job_id']);
        }

        if (isset($notification->data['job_application_id'])){
            return redirect()->route('jobs.show', $notification->data['job_application_id']);
        }

        // Fallback in caso di notifica generica
        return redirect()->route('dashboard');
    }
}
