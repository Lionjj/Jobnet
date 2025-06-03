<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ConversationStarted extends Notification
{
    use Queueable;

    public string $recruiterName;

    public function __construct(string $recruiterName)
    {
        $this->recruiterName = $recruiterName;
    }

    public function via($notifiable)
    {
        return ['database']; // Puoi aggiungere anche 'mail'
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Nuova conversazione avviata',
            'message' => "Il recruiter {$this->recruiterName} ha iniziato una conversazione con te.",
            'link' => route('chat.redirect') // oppure route('chat.with', [$recruiterId])
        ];
    }
}
