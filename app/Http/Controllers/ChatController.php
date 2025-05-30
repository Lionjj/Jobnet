<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Cmgmyr\Messenger\Models\Thread;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Illuminate\Http\Request;

class ChatController extends Controller
{
   public function utenti()
    {
        $users = User::role('candidate') 
            ->where('id', '!=', auth()->id())
            ->paginate(10);

        return view('chat.index', compact('users'));
    }


public function startChat(User $user)
{
    $me = Auth::user();

    // Cerca se esiste già una chat 1-a-1 tra questi due utenti
    $thread = Thread::forUser($me->id)
        ->whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->has('participants', 2) // ✅ chat 1-a-1
        ->first();

    // Se non esiste, la crea
    if (!$thread) {
        $thread = Thread::create([
            'subject' => "Chat tra {$me->name} e {$user->name}"
        ]);

        Participant::create(['thread_id' => $thread->id, 'user_id' => $me->id]);
        Participant::create(['thread_id' => $thread->id, 'user_id' => $user->id]);
    }

    return redirect()->route('messages.show', $thread->id);
}

public function redirect(Request $request)
{
    $me = auth()->user();
    $search = $request->input('search');
    $tab = $request->input('tab', 'threads'); // ← legge il tab attivo dalla richiesta

    // fallback vuoti per uniformare la view
    $users = null;
    $threads = null;

    // Se recruiter
    if ($me->hasRole('recruiter')) {

        // Threads: filtra solo se tab=threads
        if ($tab === 'threads') {
            $threads = Thread::forUser($me->id)
                ->when($search, function ($query) use ($me, $search) {
                    $query->whereHas('participants.user', function ($q) use ($me, $search) {
                        $q->where('id', '!=', $me->id)
                            ->where('name', 'like', "%{$search}%");
                    });
                })
                ->latest('updated_at')
                ->paginate(10)
                ->appends(['tab' => 'threads', 'search' => $search]);
        }

        // Users (candidati): filtra solo se tab=users
        if ($tab === 'users') {
            $existingUserIds = Thread::forUser($me->id)
                ->with('participants')
                ->get()
                ->flatMap(fn($thread) => $thread->participants->pluck('user_id'))
                ->unique()
                ->values();

            $users = User::role('candidate')
                ->where('id', '!=', $me->id)
                ->whereNotIn('id', $existingUserIds)
                ->when($search, function ($query) use ($search) {
                    return $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(10)
                ->appends(['tab' => 'users', 'search' => $search]);
        }

        return view('chat.index', compact('users', 'threads'));
    }
    if ($me->hasRole('candidate')) {
        if ($tab === 'threads') {
            $threads = Thread::forUser($me->id)
                ->when($search, function ($query) use ($me, $search) {
                    $query->whereHas('participants.user', function ($q) use ($me, $search) {
                        $q->where('id', '!=', $me->id)
                            ->where('name', 'like', "%{$search}%");
                    });
                })
                ->latest('updated_at')
                ->paginate(10)
                ->appends(['tab' => 'threads', 'search' => $search]);
        }

        return view('chat.index', compact('threads'));
    }

    abort(403);
}

}
