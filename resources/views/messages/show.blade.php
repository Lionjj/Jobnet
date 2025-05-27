<x-app-layout>
    <div class="p-4 max-w-3xl mx-auto">
        
        <h1 class="text-xl font-semibold mb-4">{{ $thread->subject }}</h1>
        <a href="{{ route('chat.redirect') }}"
            class="text-sm text-blue-600 hover:underline inline-flex items-center">
         ← Torna alla chat
        </a>

        {{-- Lista dei messaggi --}}
        <x-chat-messages :messages="$thread->messages" />

        {{-- Form per inviare messaggi --}}
        <x-chat-form :thread="$thread" />
    </div>
</x-app-layout>
