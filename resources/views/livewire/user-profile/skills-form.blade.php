<div>
    @if ($errors->has('skills.duplicate'))
        <div class="mb-4 text-red-600 font-semibold">
            {{ $errors->first('skills.duplicate') }}
        </div>
    @endif
    <h3 class="font-bold text-lg mb-4">Competenze Tecniche</h3>

    @foreach($skills as $index => $item)
        <div x-data="{ open: false }" class="mb-4 bg-white border rounded-lg shadow-sm">

            {{-- Header collapsabile --}}
            <div class="p-4 bg-gray-100 flex justify-between items-center cursor-pointer" @click="open = !open">
                <div>
                    <span class="font-semibold uppercase">
                        {{ $item['name'] ?: 'Competenza #' . ($index + 1) }}
                    </span>
                </div>
                <span x-text="open ? '−' : '+'"></span>
            </div>

            {{-- Corpo collapsabile --}}
            <div x-show="open" class="p-6 space-y-4">

                {{-- Campo testo con suggerimenti --}}
                <div>
                    <x-input-label for="skill_{{ $index }}" :value="'Competenza'" />
                    <input type="text"
                           id="skill_{{ $index }}"
                           list="skills-list"
                           wire:model.defer="skills.{{ $index }}.name"
                           autocomplete="off"
                           class="mt-1 block w-full uppercase border-gray-300 rounded-md shadow-sm focus:ring-indigo-500"
                           placeholder="Es: LARAVEL, DOCKER, SQL"
                           style="text-transform: uppercase;"/>
                    <x-input-error :messages="$errors->get('skills.' . $index . '.name')" class="mt-2" />
                </div>

                {{-- Rimuovi --}}
                <div class="text-right">
                    <button type="button" wire:click="removeSkill({{ $index }})"
                            class="text-red-600 hover:underline text-sm">Rimuovi competenza</button>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Datalist globale per suggerimenti --}}
    <datalist id="skills-list">
        @foreach(array_unique($availableSkills) as $skill)
            <option value="{{ strtoupper($skill) }}"></option>
        @endforeach
    </datalist>

    {{-- Azioni --}}
    <div class="flex justify-between mt-6">
        <x-primary-button wire:click="addSkill">+ Aggiungi Competenza</x-primary-button>
        <x-primary-button wire:click="save" class="bg-green-600 hover:bg-green-700 transition">Salva</x-primary-button>
    </div>

    {{-- Messaggio di conferma --}}
    @if (session()->has('message'))
        <div class="mt-4 text-green-600 font-semibold">
            {{ session('message') }}
        </div>
    @endif
</div>
