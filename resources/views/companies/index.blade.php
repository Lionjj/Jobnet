<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <h1 class="text-2xl font-extrabold text-gray-900 mb-4 sm:mb-0">Le tue aziende</h1>
        </div>

        {{-- Nessuna azienda --}}
        <div class="bg-white rounded-lg shadow p-8 text-center text-gray-700 space-y-4">
            <p class="text-xl font-semibold">Non hai ancora registrato nessuna azienda</p>
            <p class="text-sm text-gray-400">Inizia aggiungendone una.</p>
            <a href="{{ route('companies.create') }}"
            class="inline-block mt-2 bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 transition">
                + Nuova azienda
            </a>
        </div>
    </div>
</x-app-layout>
