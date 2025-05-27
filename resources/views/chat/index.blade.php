<x-app-layout>
    @php
        $defaultTab = request('tab', 'threads'); // threads è il fallback
    @endphp

    <div class="max-w-5xl mx-auto p-6 space-y-6" x-data="{ tab: '{{ $defaultTab }}' }">
        <h1 class="text-2xl font-bold mb-6">{{ (Auth::user()->hasRole('recruiter')? __('Chat Recruiter') : __('Chat ')) }}</h1>

        {{-- Selettore tab --}}
        <div class="flex space-x-4 border-b border-gray-200 mb-4">
            <button @click="window.location='{{ route('chat.redirect', ['tab' => 'threads']) }}'"
    class="px-3 py-2 text-sm font-medium"
    :class="tab === 'threads' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500'">
    Le tue conversazioni
</button>
            @if (Auth::user()->hasRole('recruiter'))
                <button @click="window.location='{{ route('chat.redirect', ['tab' => 'users']) }}'"
    class="px-3 py-2 text-sm font-medium"
    :class="tab === 'users' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500'">
    Candidati
</button>
            @endif
        </div>

        {{-- Lista conversazioni --}}
        <div x-show="tab === 'threads'" class="space-y-4">
            <x-search-input :action="route('chat.redirect')" placeholder="Cerca conversazione..." tab="threads"/>
            @isset($threads)
                <div class="bg-white shadow rounded p-4">
                    @forelse ($threads as $thread)
                        @php
                            $altro = $thread->participants->where('user_id', '!=', auth()->id())->first()?->user;
                        @endphp

                        <div class="border-b py-3">
                            <a href="{{ route('messages.show', $thread->id) }}"
                               class="text-blue-600 hover:underline font-semibold">
                                Chat con {{ $altro?->name ?? 'Utente' }}
                            </a>
                            <div class="text-sm text-gray-500">
                                Ultimo messaggio: {{ $thread->updated_at->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">Nessuna conversazione avviata.</p>
                    @endforelse
                    @if($threads instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-4">
                            {{ $threads->appends(['tab' => 'threads', 'search' => request('search')])->links() }}
                        </div>
                    @endif
                </div>
            @endisset
        </div>

        {{-- Lista candidati --}}
        <div x-show="tab === 'users'" class="space-y-4">
            <x-search-input :action="route('chat.redirect')" placeholder="Cerca candidato..." tab="users"/>

            @isset($users)
                <div class="bg-white shadow rounded p-4">
                    <h2 class="text-lg font-semibold mb-3">Seleziona un candidato</h2>
                    <ul class="divide-y divide-gray-200">
                        @foreach ($users as $user)
                            <li>
                                <a href="{{ route('chat.avvia', $user->id) }}"
                                   class="block px-4 py-3 hover:bg-gray-100">
                                    {{ $user->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-4">
                            {{ $users->appends(['tab' => 'users', 'search' => request('search')])->links() }}
                        </div>
                    @endif

                </div>
            @endisset
        </div>
    </div>

</x-app-layout>
