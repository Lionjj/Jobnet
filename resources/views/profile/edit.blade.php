<x-app-layout>
    @php
        // Default tab dalla query string, fallback 'base_info'
        $defaultTab = request('tab', 'base_info');
    @endphp
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
         <div class="max-w-4xl mx-auto p-6 mt-10 bg-white rounded shadow"
         x-data="{ tab: '{{ $defaultTab }}' }">

        {{-- Selettore tab --}}
        <div class="flex space-x-4 border-b border-gray-200 mb-6">
            <button
                @click="tab = 'base_info'; window.history.replaceState(null, null, '?tab=base_info')"
                :class="tab === 'base_info' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-500'"
                class="px-3 py-2 text-sm"
            >
                Dati di base
            </button>

            @if(Auth::user()->hasRole('candidate'))
                <button
                    @click="tab = 'experience'; window.history.replaceState(null, null, '?tab=experience')"
                    :class="tab === 'experience' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-500'"
                    class="px-3 py-2 text-sm"
                >
                    Esperienze
                </button>

                <button
                    @click="tab = 'languages'; window.history.replaceState(null, null, '?tab=languages')"
                    :class="tab === 'languages' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-500'"
                    class="px-3 py-2 text-sm"
                >
                    Cometenze linguistiche Lingue
                </button>

                <button
                    @click="tab = 'skills'; window.history.replaceState(null, null, '?tab=skills')"
                    :class="tab === 'skills' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-500'"
                    class="px-3 py-2 text-sm"
                >
                    Competenze tecinche
                </button>
                
                <button
                    @click="tab = 'profiles'; window.history.replaceState(null, null, '?tab=profiles')"
                    :class="tab === 'profiles' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-500'"
                    class="px-3 py-2 text-sm"
                >
                    Profili professionali
                </button>
            @endif
            
        </div>

        {{-- Tab dati di base --}}
        <div x-show="tab === 'base_info'">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
        {{-- Tab dati avanzati solo per i candidati--}}
    @if(Auth::user()->hasRole('candidate'))
        <div x-show="tab === 'experience'">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
               @livewire('user-profile.experiences-form')
            </div>
        </div>

        <div x-show="tab === 'languages'">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
               @livewire('user-profile.languages-form')
            </div>
        </div>

        <div x-show="tab === 'skills'">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
               @livewire('user-profile.skills-form')
            </div>
        </div>

        <div x-show="tab === 'profiles'">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
               @livewire('user-profile.profiles-form')
            </div>
        </div>

    @endif
        </div>
    </div>
</x-app-layout>
