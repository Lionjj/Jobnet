<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Titolo --}}
            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                Benvenuto, {{ Auth::user()->name }}!
            </h1>
            {{-- Sezione info utente --}}
            <div class="bg-white overflow-hidden shadow rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-2">Informazioni utente</h2>
                <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                <p><strong>Ruolo:</strong> {{ Auth::user()->getRoleNames()->first() }}</p>
            </div>

            {{-- Sezioni condizionali in base al ruolo --}}
            @role('recruiter')
            {{-- Azioni rapide --}}
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">⚙️ Azioni rapide</h2>
                <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                    <li><a href="{{ route('jobs.create') }}" class="text-blue-600 hover:underline">Crea una nuova offerta</a></li>
                    <li><a href="{{ route('companies.index') }}" class="text-blue-600 hover:underline">Modifica profilo aziendale</a></li>
                    <li><a href="{{ route('chat.redirect') }}" class="text-blue-600 hover:underline">Vai alla chat</a></li>
                </ul>
            </div>

            {{-- Offerte pubblicate --}}
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">📌 Interazioni recenti sulle offerte di lavoro</h2>
                @forelse ($jobOffers as $offer)
                <a href="{{ route('jobs.show', $offer) }}" class="text-blue-600 hover:underline">
                    <div class="border border-gray-200 rounded p-4 mb-3 bg-gray-50">
                        <p class="text-sm"><strong>Titolo:</strong> {{ $offer->title }}</p>
                        <p class="text-sm text-gray-600">{{ Str::limit($offer->description, 80) }}</p>
                        <p class="text-xs text-gray-400">Pubblicata il {{ $offer->created_at->format('d/m/Y') }}</p>
                    </div>
                </a>
                @empty
                    <p class="text-sm text-gray-500">Non ci sono ultime interazioni.</p>
                @endforelse
            </div>
            @endrole

            @role('candidate')
                {{-- Attività --}}
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">🧾 Le tue attività</h2>
                <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                    <li><a href="{{ route('jobs.publicIndex') }}" class="text-blue-600 hover:underline">Sfoglia offerte di lavoro</a></li>
                    <li><a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline">Aggiorna il tuo profilo</a></li>
                </ul>
            </div>

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">📅 Prossimi colloqui</h2>
                @forelse ($upcomingInterviews as $interview)
                    <div class="border border-gray-100 rounded p-4 mb-3 bg-gray-50">
                        <p class="text-sm text-gray-700"><strong>Ruolo:</strong> {{ $interview->job->title }}</p>
                        <p class="text-sm text-gray-700"><strong>Data:</strong> {{ \Carbon\Carbon::parse($interview->interview_datetime)->translatedFormat('d F Y, H:i') }}</p>
                        <p class="text-sm text-gray-700"><strong>Azienda:</strong> {{ $interview->job->company->name }}</p>
                        <p class="text-sm text-gray-700"><strong>Recruiter:</strong> {{ $interview->job->company->user->name }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Non hai colloqui programmati.</p>
                @endforelse
            </div>
            @endrole

        </div>
    </div>

    
</x-app-layout>
