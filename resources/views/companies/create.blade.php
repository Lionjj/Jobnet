<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-6 mt-10">

        {{-- Titolo allineato al contenitore --}}
        <h1 class="text-2xl font-bold text-gray-900">Crea una nuova azienda</h1>

        {{-- Form --}}
        <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data" class="max-w-3xl mx-auto">
            @csrf

            <div class="space-y-6 bg-white p-6 rounded-lg shadow">
                <x-company.form />

                {{-- Pulsante Salva dentro la card, allineato a destra --}}
                <div class="flex justify-end pt-4">
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                        Salva
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
