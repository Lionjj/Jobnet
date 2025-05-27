<x-app-layout>
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">

        <h1 class="text-2xl font-bold text-gray-900 mb-6">
            ✏️ Modifica offerta: {{ $job->title }}
        </h1>

        <form action="{{ route('jobs.update', $job) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Titolo --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">📝 Titolo</label>
                <input type="text" name="title" class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('title', $job->title) }}" required>
            </div>

            {{-- Descrizione --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">📄 Descrizione</label>
                <textarea name="description" rows="4" class="w-full border border-gray-300 rounded px-3 py-2" required>{{ old('description', $job->description) }}</textarea>
            </div>

            {{-- Luogo --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">📍 Luogo</label>
                <input type="text" name="location" class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('location', $job->location) }}" required>
            </div>

            {{-- Tipo di lavoro --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">💼 Tipo di lavoro</label>
                <input type="text" name="job_type" class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('job_type', $job->job_type) }}" required>
            </div>

            {{-- Contratto --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">📃 Tipo di contratto</label>
                <input type="text" name="contract_type" class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('contract_type', $job->contract_type) }}" required>
            </div>

            {{-- Esperienza --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">🎓 Esperienza</label>
                <input type="text" name="experience_level" class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('experience_level', $job->experience_level) }}" required>
            </div>

            {{-- RAL --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">💰 RAL (opzionale)</label>
                <input type="number" step="0.01" name="ral" class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('ral', $job->ral) }}">
            </div>
            
            {{-- Skills e Benefit --}}
            @php
                $benefits = old('benefits') ?? (
                    is_array($job->benefits) ? $job->benefits : json_decode($job->benefits ?? '[]', true)
                ); 
                $skills = old('skills_required') ?? (
                    is_array($job->skills_required) ? $job->skills_required : json_decode($job->skills_required ?? '[]', true)
                );
            @endphp

            @livewire('job-offert-fields', [
                'benefits' => $benefits,
                'skills' => $skills,
            ])
            
            <div class="pt-4">
                <button type="submit"
                        class="bg-yellow-600 text-white px-5 py-2 rounded hover:bg-yellow-700 transition">
                    Salva modifiche
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
