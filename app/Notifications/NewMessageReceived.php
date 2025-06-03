<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Cmgmyr\Messenger\Models\Message;

class NewMessageReceived extends Notification
{
    use Queueable;

    public function __construct(public Message $message) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Nuovo messaggio da: ' . $this->message->user->name,
            'message' => \Str::limit($this->message->body, 50),
            'thread_id' => $this->message->thread_id,
        ];
    }
}

