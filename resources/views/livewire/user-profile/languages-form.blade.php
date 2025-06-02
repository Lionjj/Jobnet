<div>
    <h3 class="font-bold text-lg mb-4">Competenze Linguistiche</h3>

    @foreach($languages as $index => $lang)
        <div x-data="{ open: false }" class="mb-4 bg-white border rounded-lg shadow-sm">

            {{-- Header collapsabile --}}
            <div class="p-4 bg-gray-100 flex justify-between items-center cursor-pointer" @click="open = !open">
                <div>
                    <span class="font-semibold">
                        {{ $availableLanguages[$lang['language_id']] ?? 'Lingua #' . ($index + 1) }}
                    </span>
                    @if (!empty($lang['level']))
                        <span class="text-gray-600 italic ml-2">
                            ({{ $lang['level'] }})
                        </span>
                    @endif
                </div>
                <span x-text="open ? '−' : '+'"></span>
            </div>

            {{-- Corpo del pannello --}}
            <div x-show="open" class="p-6 space-y-4">

                {{-- Lingua --}}
                <div>
                    <x-input-label for="language_{{ $index }}" :value="'Lingua'" />
                    <select id="language_{{ $index }}"
                            wire:model.defer="languages.{{ $index }}.language_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                        <option value="">-- Seleziona Lingua --</option>
                        @foreach($availableLanguages as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('languages.' . $index . '.language_id')" class="mt-2" />
                </div>

                {{-- Livello --}}
                <div>
                    <x-input-label for="level_{{ $index }}" :value="'Livello (es. B2, C1)'" />
                    <x-text-input type="text" id="level_{{ $index }}"
                                  wire:model.defer="languages.{{ $index }}.level"
                                  class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('languages.' . $index . '.level')" class="mt-2" />
                </div>

                {{-- Certificato --}}
                <div>
                    <x-input-label for="certificate_{{ $index }}" :value="'Certificazione (opzionale)'" />
                    <x-text-input type="text" id="certificate_{{ $index }}"
                                  wire:model.defer="languages.{{ $index }}.certificate"
                                  class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('languages.' . $index . '.certificate')" class="mt-2" />
                </div>

                <div class="text-right">
                    <button type="button" wire:click="removeLanguage({{ $index }})"
                            class="text-red-600 hover:underline text-sm">Rimuovi</button>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Pulsanti --}}
    <div class="flex justify-between mt-6">
        <x-primary-button wire:click="addLanguage">+ Aggiungi lingua</x-primary-button>
        <x-primary-button wire:click="save" class="bg-green-600 hover:bg-green-700 transition">Salva</x-primary-button>
    </div>

    @if (session()->has('message'))
        <div class="mt-4 text-green-600 font-semibold">
            {{ session('message') }}
        </div>
    @endif
</div>
