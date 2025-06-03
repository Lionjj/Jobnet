@props(['message'])

@php
    use Illuminate\Support\Str;

    $isInterviewProposal = Str::contains($message->body, '📢');
    $isRecipient = auth()->id() !== $message->user_id;
    $alreadyResponded = $message->thread
        ->messages()
        ->where('id', '>', $message->id) // messaggi successivi
        ->where('user_id', auth()->id()) // inviati dal destinatario
        ->where(function ($q) {
            $q->where('body', 'like', '%✅%')
              ->orWhere('body', 'like', '%❌%');
        })
        ->exists();
@endphp

<div class="p-3 rounded {{ $message->user_id === auth()->id() ? 'bg-blue-100 text-right' : 'bg-gray-100 text-left' }}">
    <div class="text-sm text-gray-600">
        {{ $message->user->name }} – {{ $message->created_at->diffForHumans() }}
    </div>

    <div class="mt-1 whitespace-pre-line">
        {{ $message->body }}
    </div>

    {{-- Se è una proposta e non è già stata accettata/rifiutata --}}
    @if ($isInterviewProposal && $isRecipient && !$alreadyResponded)
        <div class="mt-2 flex gap-2 justify-{{ auth()->id() === $message->user_id ? 'end' : 'start' }}">
            <form method="POST" action="{{ route('interviews.respond', ['thread' => $message->thread_id, 'response' => 'accept']) }}">
                @csrf
                <x-primary-button>✅ Accetta</x-primary-button>
            </form>
            <form method="POST" action="{{ route('interviews.respond', ['thread' => $message->thread_id, 'response' => 'decline']) }}">
                @csrf
                <x-danger-button>❌ Rifiuta</x-danger-button>
            </form>
        </div>
    @endif
</div>
