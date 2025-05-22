<!DOCTYPE html>
<html lang="it">
@include('layouts.head')
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen">

    {{-- Barra in alto --}}
    <nav class="bg-white px-6 py-3 flex items-center justify-between shadow-sm border-b border-gray-200">
        {{-- Logo + titolo --}}
        <div class="flex items-center space-x-2">
            <img src="{{ asset('images/jobnet-logo.png') }}" alt="Jobnet Logo" class="h-6 w-auto">
            <span class="text-lg font-semibold text-slate-800">Jobnet</span>
        </div>

        {{-- Bottoni --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('login') }}"
            class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-md hover:bg-slate-100 transition">
                Accedi
            </a>
            <a href="{{ route('register') }}"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition">
                Registrati
            </a>
        </div>
    </nav>

    {{-- Contenuto principale --}}
    <main class="flex flex-col items-center justify-center text-center px-4 py-16">

        {{-- Immagine centrale --}}
        <div class="w-full max-w-4xl relative">
            <img src="{{ asset('images/jobnet-welcome.png') }}" alt="Illustrazione Jobnet" class="w-full h-auto rounded-xl shadow-lg">
        </div>
    </main>

</body>
</html>
