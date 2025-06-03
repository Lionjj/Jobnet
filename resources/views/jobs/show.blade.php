<x-app-layout>
    <div class="max-w-3xl mx-auto bg-white p-6 mt-10 rounded-lg shadow">


            <div x-data="{ tab: '{{ request()->get('tab', 'offerta') }}' }">
                {{-- Tabs --}}
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <button @click="tab = 'offerta'"
                            class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm"
                            :class="tab === 'offerta' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                            Detagli offerta
                        </button>
                        <button @click="tab = 'candidature'; window.history.replaceState(null, null, '?tab=candidature')"
                                class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm"
                                :class="tab === 'candidature' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                            Lista candidature
                        </button>
                    </nav>
                </div>
                <div x-show="tab === 'offerta'">

                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        {{ $job->title }}
                    </h1>
                    <p class="text-gray-500 mb-6">
                        {{ $job->company->name }} — {{ $job->location }}
                    </p>

                    {{-- Descrizione --}}
                    <div class="mb-4">
                        <h2 class="text-gray-900 font-semibold flex items-center gap-2">
                            📝 Descrizione
                        </h2>
                        <p class="text-gray-600">{{ $job->description }}</p>
                    </div>

                    {{-- Dettagli tecnici --}}
                    <div class="mb-4">
                        <h2 class="text-gray-900 font-semibold flex items-center gap-2">
                            📌 Dettagli posizione
                        </h2>
                        <ul class="list-disc list-inside text-gray-600 mt-1 space-y-1">
                            <li><strong>Tipo di lavoro:</strong> {{ $job->job_type }}</li>
                            <li><strong>Contratto:</strong> {{ $job->contract_type }}</li>
                            <li><strong>Esperienza richiesta:</strong> {{ $job->experience_level }}</li>
                            @if($job->ral)
                                <li><strong>RAL:</strong> € {{ number_format($job->ral, 2, ',', '.') }}</li>
                            @endif
                        </ul>
                    </div>
                    @php
                        $skills = $job->skills;   
                        $benefits = $job->benefits; 
                    @endphp

                    @if($benefits->isNotEmpty())
                        <div class="mb-4">
                            <h2 class="text-gray-900 font-semibold flex items-center gap-2">🎁 Benefit aziendali</h2>
                            <ul class="list-disc list-inside text-gray-600 mt-1 space-y-1">
                                @foreach ($benefits as $benefit)
                                    <li>{{ $benefit->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($skills->isNotEmpty())
                        <div class="mb-4">
                            <h2 class="text-gray-900 font-semibold flex items-center gap-2">💼 Competenze richieste</h2>
                            <ul class="list-disc list-inside text-gray-600 mt-1 space-y-1">
                                @foreach ($skills as $skill)
                                    <li>{{ $skill->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <div class="mt-6">
                        <a href="{{ route('jobs.index') }}"
                        class="inline-block text-sm text-blue-600 hover:underline">
                            ← Torna alla lista
                        </a>
                    </div>
                </div>


                <div x-show="tab === 'candidature'">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Candidature ricevute</h2>

                    {{-- Filtri --}}
                    <div x-data="{ openFilters: false }" class="mb-6">
                        <button @click="openFilters = !openFilters"
                                class="mb-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                            <span x-show="!openFilters">Mostra filtri</span>
                            <span x-show="openFilters">Nascondi filtri</span>
                        </button>

                        <form method="GET" action="{{ route('jobs.show', ['job' => $job->id]) }}" class="space-y-4 p-6 bg-gray-50 border rounded-lg shadow-sm" x-show="openFilters">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <input type="hidden" name="tab" value="candidature">
                                
                                <input type="text" name="name" placeholder="Nome candidato"
                                    value="{{ request('name') }}"
                                    class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 w-full" />

                                <select name="status" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 w-full">
                                    <option value="">Stato candidatura</option>
                                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Inviata</option>
                                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accettata</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rifiutata</option>
                                </select>

                                <select name="language" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 w-full">
                                    <option value="">Lingua parlata</option>
                                    @foreach($allLanguages as $lang)
                                        <option value="{{ $lang->id }}" {{ request('language') == $lang->id ? 'selected' : '' }}>
                                            {{ $lang->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="skill" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 w-full">
                                    <option value="">Skill tecnica</option>
                                    @foreach($allSkills as $skill)
                                        <option value="{{ $skill->id }}" {{ request('skill') == $skill->id ? 'selected' : '' }}>
                                            {{ $skill->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="has_experience" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 w-full">
                                    <option value="">Esperienza lavorativa</option>
                                    <option value="1" {{ request('has_experience') == '1' ? 'selected' : '' }}>Con esperienza</option>
                                    <option value="0" {{ request('has_experience') == '0' ? 'selected' : '' }}>Senza esperienza</option>
                                </select>
                            </div>

                            <div class="flex gap-3 mt-4">
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Filtra</button>
                                    <a href="{{ route('jobs.show', ['job' => $job->id, 'tab' => 'candidature']) }}"
                                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Reset</a>
                            </div>
                        </form>
                    </div>

                    @forelse ($applications as $application)
                        <div x-data="{ open: false }" class="mb-4 bg-white border rounded-lg shadow-sm">
                            {{-- Intestazione collapsabile --}}
                            <div class="p-4 bg-gray-100 flex justify-between items-center cursor-pointer" @click="open = !open">
                                <div>
                                    <span class="font-semibold">{{ $application->user->name }}</span>
                                    <span class="text-sm text-gray-500">{{ $application->user->email }}</span>
                                </div>
                                <span class="text-sm text-gray-500">{{ $application->created_at->format('d/m/Y H:i') }}</span>
                            </div>

                            {{-- Contenuto collapsabile --}}
                            <div x-show="open" class="p-6 space-y-6">

                                {{-- Lettera di presentazione --}}
                                <div>
                                    <h3 class="font-semibold text-gray-700">Lettera di presentazione</h3>
                                    <p class="text-gray-600">{{ $application->cover_letter ? $application->cover_letter : '-' }}</p>
                                </div>

                                 {{-- Esperienze lavorative --}}
                                <div>
                                    <h4 class="text-gray-900 font-semibold mb-1">Esperienze lavorative</h4>
                                    @if ($application->user->experiences->isNotEmpty())
                                            <ul class="text-gray-700 list-disc list-inside">
                                                @foreach ($application->user->experiences as $exp)
                                                    <li><strong>{{ $exp->role }}</strong> @ {{ $exp->company }} ({{ $exp->start_date }} – {{ $exp->is_current ? 'oggi' : $exp->end_date }})</li>
                                                @endforeach
                                            </ul>
                                    @else
                                       <p class="text-gray-600"> - </p> 
                                    @endif
                                </div>

                                {{-- Competenze linguistiche --}}
                                <div>
                                <h3 class="font-semibold text-gray-700">Competenze linguistiche</h3>
                                    @if($application->user->languages->isNotEmpty())
                                        <ul class="list-disc list-inside text-gray-600">
                                            @foreach ($application->user->languages as $language)
                                                <li>{{ $language->name }} ({{ ucfirst($language->pivot->level) }})</li>
                                                @if($language->certification)
                                                    – {{ $language->certification }}
                                                @endif
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-gray-600"> - </p> 
                                    @endif
                                </div>

                                {{-- Competenze tecniche --}}
                                <div>
                                    <h3 class="font-semibold text-gray-700">Competenze tecniche</h3>
                                    @if($application->user->skills->isNotEmpty())
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($application->user->skills as $skill)
                                                <span class="bg-gray-200 text-sm px-2 py-1 rounded">{{ strtoupper($skill->name) }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-600"> - </p> 
                                    @endif
                                </div>

                                {{-- Profili professionali --}}
                                <div>
                                    <h3 class="font-semibold text-gray-700">Profili professionali</h3>
                                    @if($application->user->profiles->isNotEmpty())
                                        <ul class="list-inside text-blue-600 underline space-y-1">
                                            @foreach ($application->user->profiles as $profile)
                                                <li><a href="{{ $profile->url }}" target="_blank">{{ ucfirst($profile->type) }}</a></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-gray-600"> - </p> 
                                    @endif
                                </div>

                                <div class="flex justify-between mt-6">
                                    <form action="{{ route('applications.updateStatus', $application->id) }}?tab=candidature" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <x-danger-button type="submit">
                                            Rifiuta
                                        </x-danger-button>
                                    </form>

                                    @if($application->status === 'accepted')
                                        <a href="{{ route('chat.avvia', $application->user->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            💬 Inizia conversazione
                                        </a>
                                    @else
                                        <form action="{{ route('applications.updateStatus', $application->id) }}?tab=candidature" method="POST" class="mt-2 flex gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="accepted">
                                            <x-primary-button type="submit" class="bg-green-600 hover:bg-green-700 transition">
                                                Accetta
                                            </x-primary-button>
                                        </form>
                                    @endif
                                </div>    
                            </div>
                        </div>
                    <div class="mt-6">
                        {{ $applications->appends(request()->except('page') + ['tab' => 'candidature'])->links() }}
                    </div>
    
                    @empty
                        <p class="text-gray-500">Nessuna candidatura ricevuta per questa offerta.</p>
                    @endforelse
                </div>
            </div>
    </div>
</x-app-layout>
