<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cmgmyr\Messenger\Models\Thread;
use Cmgmyr\Messenger\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewMessageReceived;

class MessageController extends Controller
{
    public function index() {
        $threads = Thread::forUser(Auth::id())
        ->latest('updated_at')
        ->paginate(10);
        return view('messages.index', compact('threads'));
    }

    public function show(Thread $thread) {
        $thread->markAsRead(Auth::id());
        return view('messages.show', compact('thread'));
    }

    public function store(Request $request, Thread $thread) {
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $request->input('body')
        ]);

        $thread->activateAllParticipants();

        // Trova l’unico destinatario (cioè l’altro partecipante diverso dal mittente)
        $recipient = $thread->participants()
            ->where('user_id', '!=', Auth::id())
            ->with('user')
            ->first();

        // Notifica solo se esiste un destinatario
        if ($recipient && $recipient->user) {
            $recipient->user->notify(new NewMessageReceived($message));
        }


        return redirect()->route('messages.show', $thread->id);
    }
}

