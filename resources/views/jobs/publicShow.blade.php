<x-app-layout>
    <div class="max-w-3xl mx-auto bg-white p-6 mt-10 rounded-lg shadow">
        <div x-data="{ tab: 'offerta' }">
            {{-- Tabs --}}
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <button @click="tab = 'offerta'"
                        class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm"
                        :class="tab === 'offerta' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                        Detagli offerta
                    </button>
                    <button @click="tab = 'candidatura'"
                        class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm"
                        :class="tab === 'candidatura' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                        Invia candidatura
                    </button>
                </nav>
            </div>

            {{-- Contenuto tab "Offerta" --}}
            <div x-show="tab === 'offerta'">
                 <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    {{ $job->title }}
                </h1>
                <p class="text-gray-500 mb-6">
                    <a href="{{ route('companies.publicShow', ['id' => $job->company->id, 'from_job' => $job->id])}}" class="inline-block text-blue-500 hover:underline">{{ $job->company->name }}</a> — {{ $job->location }}
                </p>

                {{-- Descrizione --}}
                <div class="mb-4">
                    <h2 class="text-gray-900 font-semibold flex items-center gap-2">
                        📝 Descrizione
                    </h2>
                    <p class="text-gray-600">{{ $job->description }}</p>
                </div>

                {{-- Dettagli posizione --}}
                <div class="mb-4">
                    <h2 class="text-gray-900 font-semibold flex items-center gap-2">
                        📌 Dettagli posizione
                    </h2>
                    <ul class="list-disc list-inside text-gray-600 mt-1 space-y-1">
                        <li><strong>Tipo di lavoro:</strong> {{ ucfirst($job->job_type) }}</li>
                        <li><strong>Contratto:</strong> {{ ucfirst($job->contract_type) }}</li>
                        <li><strong>Esperienza richiesta:</strong> {{ ucfirst($job->experience_level) }}</li>
                        @if($job->ral)
                            <li><strong>RAL:</strong> € {{ number_format($job->ral, 2, ',', '.') }}</li>
                        @endif
                    </ul>
                </div>

                @php
                    $skills = is_array($job->skills) ? $job->skills : json_decode($job->skills, true) ?? [];
                    $benefits = is_array($job->benefits) ? $job->benefits : json_decode($job->benefits, true) ?? [];
                @endphp

                @if(!empty($benefits))
                    <div class="mb-4">
                        <h2 class="text-gray-900 font-semibold flex items-center gap-2">
                            🎁 Benefit aziendali
                        </h2>
                        <ul class="list-disc list-inside text-gray-600 mt-1 space-y-1">
                            @foreach ($benefits as $benefit)
                                <li>{{ is_array($benefit) ? $benefit['name'] : $benefit }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(!empty($skills))
                    <div class="mb-4">
                        <h2 class="text-gray-900 font-semibold flex items-center gap-2">
                            💼 Competenze richieste
                        </h2>
                        <ul class="list-disc list-inside text-gray-600 mt-1 space-y-1">
                            @foreach ($skills as $skill)
                                <li>{{ is_array($skill) ? $skill['name'] : $skill }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                @php
                    $user = auth()->user();
                    $saved = $user && $user->savedJobs->contains($job->id);
                @endphp

                <div class="mt-6 flex justify-between items-center">
                    <div class="mt-6">
                        <a href="{{ route('jobs.publicIndex') }}"
                        class="inline-block text-sm text-blue-600 hover:underline">
                            ← Torna alle offerte
                        </a>
                    </div>
                    
                    <form method="POST" action="{{ $saved ? route('saved-jobs.destroy', $job->id) : route('saved-jobs.store', $job->id) }}">
                        @csrf
                        @if($saved)
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Rimuovi dai preferiti</button>
                        @else
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Salva offerta</button>
                        @endif
                    </form>
                </div>

            </div>

             {{-- Contenuto tab "Candidatura" --}}
            <div x-show="tab === 'candidatura'">
                @php
                    $hasApplied = $user && $job->applications()->where('user_id', $user->id)->exists();
                    $application = $job->applications()->where('user_id', auth()->id())->first();
                @endphp

                @if($hasApplied)
                    @if ($application)
                        <div class="mt-6">
                            @if ($application->status === 'sent')
                                <div class="mt-6">
                                    <div class="flex items-start gap-4 bg-blue-50 border border-blue-200 rounded-lg p-4 shadow-sm">
                                        <div class="text-blue-600 text-2xl">📨</div>

                                        <div class="flex-1">
                                            <h3 class="text-blue-800 font-semibold text-lg">Candidatura inviata</h3>
                                            <p class="text-blue-700 text-sm mt-1">
                                                La tua candidatura è stata correttamente inviata ed è in attesa di revisione da parte del team di selezione.
                                                Riceverai un aggiornamento non appena verrà presa una decisione.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($application->status === 'accepted')
                                <div class="flex items-start gap-4 bg-green-50 border border-green-200 rounded-lg p-4 shadow-sm">
                                    <div class="text-green-600 text-2xl">🎉</div>

                                    <div class="flex-1">
                                        <h3 class="text-green-800 font-semibold text-lg">Candidatura accettata</h3>
                                        <p class="text-green-700 text-sm mt-1">
                                            La tua candidatura è stata accettata! Un recruiter ti contatterà a breve per proseguire con la selezione. 
                                            Assicurati di controllare la tua email nei prossimi giorni. 📩
                                        </p>
                                    </div>
                                </div>
                            @elseif ($application->status === 'rejected')
                                <div class="mt-6">
                                    <div class="flex items-start gap-4 bg-red-50 border border-red-200 rounded-lg p-4 shadow-sm">
                                        <div class="text-red-600 text-2xl">😞</div>

                                        <div class="flex-1">
                                            <h3 class="text-red-800 font-semibold text-lg">Candidatura non selezionata</h3>
                                            <p class="text-red-700 text-sm mt-1">
                                                Purtroppo la tua candidatura non è stata selezionata per questa posizione. 
                                                Ti invitiamo a tenere d’occhio altre offerte disponibili su Jobnet!
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                @else
                    <form method="POST" action="{{ route('applications.store') }}" class="mt-6 w-full">
                        @csrf
                        <input type="hidden" name="job_offert_id" value="{{ $job->id }}">
                        <div class="mb-4">
                            <label for="cover_letter" class="block text-gray-700 font-medium mb-1">Lettera di presentazione (opzionale)</label>
                            <textarea name="cover_letter" id="cover_letter" rows="4"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Spiega brevemente perché ti interessa questa posizione...">{{ old('cover_letter') }}</textarea>
                            @error('cover_letter')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-6 flex justify-between items-center">
                            <div class="mt-6">
                                <a href="{{ route('jobs.publicIndex') }}"
                                class="inline-block text-sm text-blue-600 hover:underline">
                                    ← Torna alle offerte
                                </a>
                            </div>

                            <button type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                Invia candidatura
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
