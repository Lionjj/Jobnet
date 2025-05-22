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
                <div class="bg-blue-50 border-l-4 border-blue-400 p-6 mb-6 rounded">
                    <h2 class="text-lg font-semibold text-blue-800 mb-2">Area recruiter</h2>
                    <ul class="list-disc list-inside text-blue-700 space-y-1">
                        <li><a href="#" class="underline">Pubblica un'offerta di lavoro</a></li>
                        <li><a href="#" class="underline">Gestisci le tue offerte</a></li>
                        <li><a href="#" class="underline">Visualizza candidature</a></li>
                    </ul>
                </div>
            @endrole

            @role('candidate')
                <div class="bg-green-50 border-l-4 border-green-400 p-6 rounded">
                    <h2 class="text-lg font-semibold text-green-800 mb-2">Area candidato</h2>
                    <ul class="list-disc list-inside text-green-700 space-y-1">
                        <li><a href="#" class="underline">Sfoglia offerte di lavoro</a></li>
                        <li><a href="#" class="underline">Visualizza candidature inviate</a></li>
                        <li><a href="#" class="underline">Aggiorna il tuo profilo</a></li>
                    </ul>
                </div>
            @endrole

        </div>
    </div>
</x-app-layout>
