<x-app-layout>
    <div class="max-w-4xl mx-auto bg-white p-8 mt-10 rounded shadow space-y-8">

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

        {{-- Website --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-1">🌐 Website</h2>
            @if ($company->website)
                <a href="{{ $company->website }}" target="_blank" class="text-blue-600 underline">
                    {{ $company->website }}
                </a>
            @else
                <p class="text-gray-500">—</p>
            @endif
        </div>

        {{-- Descrizione --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-1">📄 Descrizione</h2>
            <p class="text-gray-700">{{ $company->description ?? '—' }}</p>
        </div>

        {{-- Mission --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-1">🎯 Mission</h2>
            <p class="text-gray-700">{{ $company->mission ?? '—' }}</p>
        </div>

        {{-- Vision --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-1">🔭 Vision</h2>
            <p class="text-gray-700">{{ $company->vision ?? '—' }}</p>
        </div>

        {{-- Cultura aziendale --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-1">🌱 Cultura aziendale</h2>
            @php
                $culture = explode(';', $company->company_culture ?? '');
            @endphp

            @if (!empty($culture[0]))
                <ul class="list-disc list-inside text-gray-700 space-y-1">
                    @foreach ($culture as $point)
                        @if (trim($point))
                            <li>{{ trim($point) }}</li>
                        @endif
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">—</p>
            @endif
        </div>

        {{-- Benefit --}}
        @php
            $benefits = is_array($company->benefits) ? $company->benefits : json_decode($company->benefits ?? '[]', true);
        @endphp

        @if (!empty($benefits))
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-1">🎁 Benefit aziendali</h2>
                <ul class="list-disc list-inside text-gray-700 space-y-1">
                    @foreach ($benefits as $benefit)
                        <li>{{ $benefit }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Link back --}}
        <a href="{{ route('companies.edit', $company) }}"
        class="text-blue-600 hover:underline mt-6 inline-block">
            ✏️ Modifica azienda
        </a>

        <form action="{{ route('companies.destroy', $company) }}" method="POST"
              onsubmit="return confirm('Vuoi davvero eliminare questa azienda?')">
            @csrf
            @method('DELETE')
            <button class="text-red-600 hover:underline font-medium" type="submit">
                Elimina
            </button>
        </form>

    </div>
</x-app-layout>
