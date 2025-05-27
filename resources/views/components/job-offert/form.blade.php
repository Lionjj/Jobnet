@props(['jobOffert' => null])

@php
    $jobOffert = $jobOffert ?? new \App\Models\JobOffert;
@endphp

<div class="space-y-6 bg-white p-6 rounded-lg shadow">

    {{-- Titolo --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Titolo dell'offerta</label>
        <input type="text" name="title" required
               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
               value="{{ old('title', $jobOffert->title) }}"
               placeholder="Es. Frontend Developer, Addetto alla logistica...">
    </div>

    {{-- Descrizione --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Descrizione</label>
        <textarea name="description" rows="4"
                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
                  placeholder="Descrivi il ruolo, le responsabilità, gli obiettivi...">{{ old('description', $jobOffert->description) }}</textarea>
    </div>

    {{-- Sede --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Luogo di lavoro</label>
        <input type="text" name="location"
               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
               value="{{ old('location', $jobOffert->location) }}"
               placeholder="Es. Milano, Roma, Remoto...">
    </div>

    {{-- Tipo di lavoro --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo di lavoro</label>
        <select name="job_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
            <option value="">Seleziona</option>
            @foreach (['smart working', 'ibrido', 'in sede'] as $type)
                <option value="{{ $type }}" @selected(old('job_type', $jobOffert->job_type) === $type)>
                    {{ ucfirst($type) }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Tipo di contratto --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo di contratto</label>
        <select name="contract_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
            <option value="">Seleziona</option>
            @foreach (['tempo indeterminato', 'tempo determinato', 'stage', 'apprendistato'] as $contract)
                <option value="{{ $contract }}" @selected(old('contract_type', $jobOffert->contract_type) === $contract)>
                    {{ ucfirst($contract) }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Esperienza --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Livello di esperienza</label>
        <select name="experience_level" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
            <option value="">Seleziona</option>
            @foreach (['Junior', 'Middle', 'Senior'] as $level)
                <option value="{{ $level }}" @selected(old('experience_level', $jobOffert->experience_level) === $level)>
                    {{ $level }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- RAL --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">RAL (opzionale)</label>
        <input type="number" name="ral" step="0.01"
               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
               value="{{ old('ral', $jobOffert->ral) }}"
               placeholder="Es. 32000">
    </div>

    {{-- Skills e Benefit --}}
    <div>
        @livewire('job-offert-fields', [
            'skills' => old('skills_required', $jobOffert->skills_required ?? []),
            'benefits' => old('benefits', $jobOffert->benefits ?? []),
        ])
    </div>

    {{-- Stato attivo (solo in edit) --}}
    @if ($jobOffert->exists)
        <div class="flex items-center space-x-2">
            <input type="checkbox" name="is_active" value="1"
                   class="rounded text-blue-600 border-gray-300"
                   @checked(old('is_active', $jobOffert->is_active))>
            <label class="text-sm text-gray-700">Offerta attiva</label>
        </div>
    @endif

</div>
