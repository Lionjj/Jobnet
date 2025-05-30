<x-app-layout>
    @php
        // Default tab dalla query string, fallback 'jobs'
        $defaultTab = request('tab', 'jobs');
    @endphp

    <div class="max-w-4xl mx-auto p-6 mt-10 bg-white rounded shadow"
         x-data="{ tab: '{{ $defaultTab }}' }">

        {{-- Selettore tab --}}
        <div class="flex space-x-4 border-b border-gray-200 mb-6">
            <button
                @click="tab = 'jobs'; window.history.replaceState(null, null, '?tab=jobs')"
                :class="tab === 'jobs' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-500'"
                class="px-3 py-2 text-sm"
            >
                Offerte di lavoro
            </button>

            <button
                @click="tab = 'saved'; window.history.replaceState(null, null, '?tab=saved')"
                :class="tab === 'saved' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-500'"
                class="px-3 py-2 text-sm"
            >
                Offerte salvate
            </button>
        </div>

        {{-- Tab offerte di lavoro --}}
        <div x-show="tab === 'jobs'">
            
        <div x-data="{ open: false }" x-init="open = new URLSearchParams(window.location.search).has('open_filters')" class="max-w-4xl mx-auto mb-6">

            <button
                @click="open = !open"
                class="mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
            >
                <span x-text="open ? 'Nascondi filtri' : 'Mostra filtri'"></span>
            </button>

            <form
                x-show="open"
                x-transition
                method="GET"
                action="{{ route('jobs.publicIndex') }}"
                class="space-y-4 bg-gray-100 p-4 rounded"
            >
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input
                        type="text"
                        name="keyword"
                        placeholder="Parola chiave"
                        value="{{ request('keyword') }}"
                        class="border rounded px-3 py-2 w-full"
                    />

                    <select name="job_type" class="border rounded px-3 py-2 w-full">
                        <option value="">Tipo di lavoro</option>
                        @foreach (['smart working', 'ibrido', 'in sede'] as $type)
                            <option value="{{ $type }}" @selected(request('job_type') == $type)>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>

                    <select name="contract_type" class="border rounded px-3 py-2 w-full">
                        <option value="">Tipo di contratto</option>
                        @foreach (['tempo indeterminato', 'tempo determinato', 'stage', 'apprendistato'] as $contract)
                            <option value="{{ $contract }}" @selected(request('contract_type') == $contract)>{{ ucfirst($contract) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <select name="experience_level" class="border rounded px-3 py-2 w-full">
                        <option value="">Esperienza</option>
                        @foreach (['Junior', 'Middle', 'Senior'] as $level)
                            <option value="{{ $level }}" @selected(request('experience_level') == $level)>{{ $level }}</option>
                        @endforeach
                    </select>

                    <input
                        type="text"
                        name="location"
                        placeholder="Località"
                        value="{{ request('location') }}"
                        class="border rounded px-3 py-2 w-full"
                    />
                    
                    <select name="skill" class="border rounded px-3 py-2 w-full">
                        <option value="">Seleziona una skill</option>
                        @foreach ($allSkills as $skill)
                            <option value="{{ $skill }}" @selected(strtolower(request('skill')) == $skill)>{{ ucfirst($skill) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="benefit" class="border rounded px-3 py-2 w-full">
                        <option value="">Seleziona un benefit</option>
                        @foreach ($allBenefits as $benefit)
                            <option value="{{ $benefit }}" @selected(request('benefit') == $benefit)>{{ ucfirst($benefit) }}</option>
                        @endforeach
                    </select>
                </div>

                <button
                    type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition"
                >
                    Filtra
                </button>

                <button
                    type="button"
                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400 transition"
                    onclick="window.location.href='{{ route('jobs.publicIndex', array_merge(request()->except('open_filters'), ['open_filters' => 1])) }}'"
                >
                    Reset
                </button>
            </form>
        </div>

            <h1 class="text-3xl font-bold mb-6">Offerte di lavoro</h1>

            @forelse ($jobs as $job)
                <div class="mb-6 border-b pb-4">
                    <a href="{{ route('jobs.publicShow', $job) }}" class="text-xl text-blue-600 hover:underline">
                        {{ $job->title }}
                    </a>
                    <p class="text-gray-700">{{ $job->company->name }} - {{ $job->location }}</p>
                    <p class="text-gray-600 text-sm mt-1">{{ Str::limit($job->description, 150) }}</p>
                </div>
            @empty
                <p>Nessuna offerta disponibile al momento.</p>
            @endforelse

            {{ $jobs->withQueryString()->links() }}
        </div>

        {{-- Tab offerte salvate --}}
        <div x-show="tab === 'saved'">
            <h1 class="text-3xl font-bold mb-6">Offerte salvate</h1>

            @if($savedJobs->isEmpty())
                <p>Non hai ancora salvato offerte.</p>
            @else
                @foreach($savedJobs as $savedJob)
                    <div class="mb-6 border-b pb-4">
                        <a href="{{ route('jobs.publicShow', $savedJob) }}" class="text-xl text-blue-600 hover:underline">
                            {{ $savedJob->title }}
                        </a>
                        <p class="text-gray-700">{{ $savedJob->company->name }} - {{ $savedJob->location }}</p>
                        <p class="text-gray-600 text-sm mt-1">{{ Str::limit($savedJob->description, 150) }}</p>
                    </div>
                @endforeach

                {{-- Se hai la paginazione per le offerte salvate --}}
                {{ $savedJobs->links() }}
            @endif
        </div>

    </div>
</x-app-layout>