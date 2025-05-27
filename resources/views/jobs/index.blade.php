<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6 py-10">

        {{-- Titolo e bottone --}}
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Le tue offerte di lavoro</h1>
            
        </div>

        {{-- Lista o messaggio --}}
        @if ($jobs->isEmpty())
           <div class="bg-white shadow rounded-lg p-8 text-center text-gray-700">
                <p class="text-lg font-medium mb-2">Non hai ancora pubblicato nessuna offerta di lavoro.</p>
                <p class="text-sm mb-6 text-gray-500">Inizia creando una nuova offerta per attirare i candidati ideali.</p>

                <a href="{{ route('jobs.create') }}"
                   class="inline-block bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 transition">
                    + Nuova Offerta
                </a>
            </div>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($jobs as $job)
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h2 class="text-xl font-semibold text-gray-900">{{ $job->title }}</h2>
                        <p class="text-gray-700 mb-1">
                            {{ $job->location }} • {{ $job->contract_type }} • {{ $job->experience_level }}
                        </p>
                        <p class="text-sm text-gray-500 mb-3">
                            {{ Str::limit($job->description, 100) }}
                        </p>

                        <div class="flex gap-4 text-sm">
                            <a href="{{ route('jobs.show', $job) }}" class="text-blue-600 hover:underline">Visualizza</a>
                            <a href="{{ route('jobs.edit', $job) }}" class="text-yellow-600 hover:underline">Modifica</a>
                            <form action="{{ route('jobs.destroy', $job) }}" method="POST"
                                  onsubmit="return confirm('Sei sicuro di voler eliminare questa offerta?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Elimina</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
