<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\JobApplication;

class JobApplicationNotification extends Notification
{
    public $application;

    public function __construct(JobApplication $application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return ['database']; // Puoi aggiungere anche 'mail'
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Nuova candidatura ricevuta',
            'message' => 'Hai ricevuto una nuova candidatura per " ' . $this->application->job->title . '".',
            'job_application_id' => $this->application->job_offert_id,
        ];
    }
}