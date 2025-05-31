<x-app-layout>
    <div class="max-w-3xl mx-auto bg-white p-6 mt-10 rounded-lg shadow">

        <h1 class="text-2xl font-bold text-gray-900 mb-2">
            {{ $job->title }}
        </h1>
        <p class="text-gray-500 mb-6">
            <a href="{{ route('companies.publicShow', ['id' => $job->company->id, 'from_job' => $job->id])}}" class="inline-block text-blue-500 hover:underline">{{ $job->company->name }}</a> — {{ $job->location }}
        </p>

        {{-- Descrizione --}}
        <div class="mb-4">
            <h2 class="text-gray-900 font-semibold flex items-center gap-2">
                📝 Descrizione
            </h2>
            <p class="text-gray-600">{{ $job->description }}</p>
        </div>

        {{-- Dettagli posizione --}}
        <div class="mb-4">
            <h2 class="text-gray-900 font-semibold flex items-center gap-2">
                📌 Dettagli posizione
            </h2>
            <ul class="list-disc list-inside text-gray-600 mt-1 space-y-1">
                <li><strong>Tipo di lavoro:</strong> {{ ucfirst($job->job_type) }}</li>
                <li><strong>Contratto:</strong> {{ ucfirst($job->contract_type) }}</li>
                <li><strong>Esperienza richiesta:</strong> {{ ucfirst($job->experience_level) }}</li>
                @if($job->ral)
                    <li><strong>RAL:</strong> € {{ number_format($job->ral, 2, ',', '.') }}</li>
                @endif
            </ul>
        </div>

        @php
            $skills = is_array($job->skills) ? $job->skills : json_decode($job->skills, true) ?? [];
            $benefits = is_array($job->benefits) ? $job->benefits : json_decode($job->benefits, true) ?? [];
        @endphp

        @if(!empty($benefits))
            <div class="mb-4">
                <h2 class="text-gray-900 font-semibold flex items-center gap-2">
                    🎁 Benefit aziendali
                </h2>
                <ul class="list-disc list-inside text-gray-600 mt-1 space-y-1">
                    @foreach ($benefits as $benefit)
                        <li>{{ is_array($benefit) ? $benefit['name'] : $benefit }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(!empty($skills))
            <div class="mb-4">
                <h2 class="text-gray-900 font-semibold flex items-center gap-2">
                    💼 Competenze richieste
                </h2>
                <ul class="list-disc list-inside text-gray-600 mt-1 space-y-1">
                    @foreach ($skills as $skill)
                        <li>{{ is_array($skill) ? $skill['name'] : $skill }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        @php
            $user = auth()->user();
            $saved = $user && $user->savedJobs->contains($job->id);
        @endphp

        <div class="mt-6 flex justify-between items-center">
            <div class="mt-6">
                <a href="{{ route('jobs.publicIndex') }}"
                class="inline-block text-sm text-blue-600 hover:underline">
                    ← Torna alle offerte
                </a>
            </div>
            
            <form method="POST" action="{{ $saved ? route('saved-jobs.destroy', $job->id) : route('saved-jobs.store', $job->id) }}">
                @csrf
                @if($saved)
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Rimuovi dai preferiti</button>
                @else
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Salva offerta</button>
                @endif
            </form>
        </div>

    </div>
</x-app-layout>
