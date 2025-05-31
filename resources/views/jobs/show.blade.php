<x-app-layout>
    <div class="max-w-3xl mx-auto bg-white p-6 mt-10 rounded-lg shadow">

        <h1 class="text-2xl font-bold text-gray-900 mb-2">
            {{ $job->title }}
        </h1>
        <p class="text-gray-500 mb-6">
            {{ $job->company->name }} — {{ $job->location }}
        </p>

        {{-- Descrizione --}}
        <div class="mb-4">
            <h2 class="text-gray-900 font-semibold flex items-center gap-2">
                📝 Descrizione
            </h2>
            <p class="text-gray-600">{{ $job->description }}</p>
        </div>

        {{-- Dettagli tecnici --}}
        <div class="mb-4">
            <h2 class="text-gray-900 font-semibold flex items-center gap-2">
                📌 Dettagli posizione
            </h2>
            <ul class="list-disc list-inside text-gray-600 mt-1 space-y-1">
                <li><strong>Tipo di lavoro:</strong> {{ $job->job_type }}</li>
                <li><strong>Contratto:</strong> {{ $job->contract_type }}</li>
                <li><strong>Esperienza richiesta:</strong> {{ $job->experience_level }}</li>
                @if($job->ral)
                    <li><strong>RAL:</strong> € {{ number_format($job->ral, 2, ',', '.') }}</li>
                @endif
            </ul>
        </div>
        @php
            $skills = $job->skills;   
            $benefits = $job->benefits; 
        @endphp

        @if($benefits->isNotEmpty())
            <div class="mb-4">
                <h2 class="text-gray-900 font-semibold flex items-center gap-2">🎁 Benefit aziendali</h2>
                <ul class="list-disc list-inside text-gray-600 mt-1 space-y-1">
                    @foreach ($benefits as $benefit)
                        <li>{{ $benefit->name }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($skills->isNotEmpty())
            <div class="mb-4">
                <h2 class="text-gray-900 font-semibold flex items-center gap-2">💼 Competenze richieste</h2>
                <ul class="list-disc list-inside text-gray-600 mt-1 space-y-1">
                    @foreach ($skills as $skill)
                        <li>{{ $skill->name }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <div class="mt-6">
            <a href="{{ route('jobs.index') }}"
               class="inline-block text-sm text-blue-600 hover:underline">
                ← Torna alla lista
            </a>
        </div>
    </div>
</x-app-layout>
