<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Modifica azienda: {{ $company->name }}</h1>

        <form action="{{ route('companies.update', $company) }}" method="POST" enctype="multipart/form-data"
              class="space-y-6 bg-white p-6 rounded-lg shadow">
            @csrf
            @method('PUT')

            <x-company.form :company="$company" />

            {{-- Azioni in fondo alla card --}}
            <div class="flex justify-between items-center pt-4">
                {{-- Bottone torna indietro --}}
                <a href="{{ route('companies.show', $company) }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition">
                    ← Torna alla tua azienda
                </a>

                {{-- Bottone salva --}}
                <button type="submit"
                        class="bg-yellow-600 text-white px-6 py-2 rounded-md hover:bg-yellow-700 transition">
                    Aggiorna azienda
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
