<x-app-layout>
    @if ($errors->any())
    <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="max-w-3xl mx-auto mt-10">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Crea nuova offerta di lavoro</h1>

        <form action="{{ route('jobs.store') }}" method="POST" enctype="multipart/form-data"
              class="space-y-6 bg-white p-6 rounded-lg shadow">
            @csrf

            {{-- Componente del form --}}
            <x-job-offert.form />

            {{-- Azioni --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition">
                    Pubblica Offerta
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
