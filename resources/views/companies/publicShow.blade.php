<x-app-layout>
    <div class="max-w-3xl mx-auto bg-white p-6 mt-10 rounded-lg shadow">

         {{-- Titolo + Logo --}}
        <div class="flex items-start justify-between gap-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $company->name }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $company->industry ?? '—' }} — {{ $company->location ?? '—' }}</p>
            </div>

            @if ($company->logo)
                <div class="shrink-0">
                    <img src="{{ asset('storage/' . $company->logo) }}"
                         alt="Logo {{ $company->name }}"
                         class="h-20 w-auto rounded shadow-md" />
                </div>
            @endif
        </div>
        
        {{-- Descrizione --}}
        @if ($company->description)
        <div class="mb-4">
            <h2 class="text-gray-900 font-semibold flex items-center gap-2">
                📝 Descrizione
            </h2>
            <p class="text-gray-600">{{ $company->description }}</p>
        </div>
        @endif

        {{-- Settore e sito web --}}
        <div class="mb-4">
            <h2 class="text-gray-900 font-semibold flex items-center gap-2">
                🏢 Dettagli Azienda
            </h2>
            <ul class="list-disc list-inside text-gray-600 mt-1 space-y-1">
                @if($company->industry)
                <li><strong>Settore:</strong> {{ ucfirst($company->industry) }}</li>
                @endif

                @if($company->website)
                <li><strong>Sito web:</strong> 
                    <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">
                        {{ $company->website }}
                    </a>
                </li>
                @endif
            </ul>
        </div>

        {{-- Mission e Vision --}}
        @if ($company->mission)
        <div class="mb-4">
            <h2 class="text-gray-900 font-semibold flex items-center gap-2">
                🎯 Mission
            </h2>
            <p class="text-gray-600">{{ $company->mission }}</p>
        </div>
        @endif

        @if ($company->vision)
        <div class="mb-4">
            <h2 class="text-gray-900 font-semibold flex items-center gap-2">
                🌟 Vision
            </h2>
            <p class="text-gray-600">{{ $company->vision }}</p>
        </div>
        @endif

        {{-- Cultura aziendale --}}
        @if ($company->company_culture)
        <div class="mb-4">
            <h2 class="text-gray-900 font-semibold flex items-center gap-2">
                🧩 Cultura Aziendale
            </h2>
            <p class="text-gray-600">{{ $company->company_culture }}</p>
        </div>
        @endif

        {{-- Benefit --}}
        @php
            $benefits = is_array($company->benefits) ? $company->benefits : json_decode($company->benefits, true) ?? [];
        @endphp

        @if (!empty($benefits))
        <div class="mb-4">
            <h2 class="text-gray-900 font-semibold flex items-center gap-2">
                🎁 Benefit aziendali
            </h2>
            <ul class="list-disc list-inside text-gray-600 mt-1 space-y-1">
                @foreach ($benefits as $benefit)
                    <li>{{ ucfirst($benefit) }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @php
            $returnToJobId = request('from_job'); // recupera id job dalla query string
        @endphp

        <div class="mt-6">
            @if($returnToJobId)
                <a href="{{ route('jobs.publicShow', $returnToJobId) }}" class="inline-block text-sm text-blue-600 hover:underline">
                    ← Torna all'offerta di lavoro
                </a>
            @endif
        </div>
</x-app-layout>
