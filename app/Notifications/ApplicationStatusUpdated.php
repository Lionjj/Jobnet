<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(public JobApplication $application)
    {
        //
    }

    public function via($notifiable)
    {
        return ['database']; // Puoi aggiungere anche 'mail' se vuoi
    }

    public function toArray($notifiable)
    {
        $status = match ($this->application->status) {
            'accepted' => 'accettata 🎉',
            'rejected' => 'rifiutata ❌',
            default => 'aggiornata',
        };

        return [
            'title' => 'Aggiornamento candidatura',
            'message' => "La tua candidatura per il ruolo di \"{$this->application->job->title}\" è stata $status.",
            'job_id' => $this->application->job->id,
        ];
    }
}
