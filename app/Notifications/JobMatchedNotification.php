<?php

namespace App\Notifications;

use App\Models\JobOffert;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class JobMatchedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public JobOffert $job)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Nuova offerta di lavoro',
            'message' => 'L\'azienda "' . $this->job->company->name . '" ha pubblicato un’offerta di lavoro che potrebbe interessarti.',
            'job_id' => $this->job->id,
            'job_title' => $this->job->title,
        ];
    }
}
