<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('layouts.head')
    @livewireStyles
    @livewireScripts
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            {{-- Global flash message handler --}}
            @if (session('success') || session('error') || session('info'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 3000)"  {{-- dopo 4 secondi scompare --}}
                    x-transition
                    class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-md"
                >
                    <div
                        class="flex items-start px-4 py-3 rounded-lg shadow-lg
                            @if (session('success')) bg-green-100 border border-green-300 text-green-800
                            @elseif (session('error')) bg-red-100 border border-red-300 text-red-800
                            @else bg-blue-100 border border-blue-300 text-blue-800 @endif"
                        role="alert"
                    >
                        <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            @if (session('success'))
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5 13l4 4L19 7" />
                            @elseif (session('error'))
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12" />
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13 16h-1v-4h-1m0-4h.01M12 9h.01" />
                            @endif
                        </svg>

                        <div class="flex-1 text-sm">
                            {{ session('success') ?? session('error') ?? session('info') }}
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </body>
</html>
