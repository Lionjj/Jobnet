<div>
    <h3 class="font-bold text-lg mb-4">Profili Professionali</h3>

    @foreach($profiles as $index => $profile)

        @php
            $type = strtolower($profile['type'] ?? '');
            $icon = match(true) {
                str_contains($type, 'github') => 'code-bracket',
                str_contains($type, 'linkedin') => 'briefcase',
                str_contains($type, 'twitter') => 'chat-bubble-left',
                str_contains($type, 'portfolio') => 'globe-alt',
                str_contains($type, 'facebook') => 'user-group',
                str_contains($type, 'instagram') => 'camera',
                str_contains($type, 'xing') => 'user',
                str_contains($type, 'behance') => 'paint-brush',
                str_contains($type, 'dribbble') => 'sparkles',
                default => 'link',
            };
        @endphp

        <div x-data="{ open: false }" class="mb-4 bg-white border rounded-lg shadow-sm">

            {{-- Header collapsabile --}}
            <div class="p-4 bg-gray-100 flex justify-between items-center cursor-pointer" @click="open = !open">

                <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2">
                    @if ($profile['type'])
                        <span class="font-semibold">{{ ucfirst($profile['type']) }}</span>
                    @else
                        <span class="text-gray-400">Profilo</span>
                        <span class="text-gray-400">#{{ $index + 1 }}</span>
                    @endif
                    <x-heroicon-o-{{ $icon }} class="w-5 h-5 text-gray-500" />

                </div>


                <span x-text="open ? '−' : '+'"></span>
            </div>

            {{-- Corpo collapsabile --}}
            <div x-show="open" class="p-6 space-y-4">

                {{-- Tipo profilo --}}
                <div>
                    <x-input-label for="type_{{ $index }}" :value="'Tipo (es. GitHub, LinkedIn, Portfolio)'" />
                    <input type="text"
                           id="type_{{ $index }}"
                           name="profiles[{{ $index }}][type]"
                           list="profile-types"
                           wire:model.defer="profiles.{{ $index }}.type"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500"
                           placeholder="GitHub, LinkedIn, Portfolio..." />
                    <x-input-error :messages="$errors->get('profiles.' . $index . '.type')" class="mt-2" />
                </div>

                {{-- URL profilo --}}
                <div>
                    <x-input-label for="url_{{ $index }}" :value="'URL profilo'" />
                    <x-text-input type="url"
                                  id="url_{{ $index }}"
                                  name="profiles[{{ $index }}][url]"
                                  wire:model.defer="profiles.{{ $index }}.url"
                                  class="mt-1 block w-full"
                                  placeholder="https://..." />
                    <x-input-error :messages="$errors->get('profiles.' . $index . '.url')" class="mt-2" />
                </div>

                {{-- Rimuovi --}}
                <div class="text-right">
                    <button type="button" wire:click="removeProfile({{ $index }})"
                            class="text-red-600 hover:underline text-sm">Rimuovi profilo</button>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Suggerimenti per i tipi di profilo --}}
    <datalist id="profile-types">
        <option value="GitHub">
        <option value="LinkedIn">
        <option value="Portfolio">
        <option value="Twitter">
        <option value="Facebook">
        <option value="Instagram">
        <option value="Personal Website">
        <option value="Xing">
        <option value="Behance">
        <option value="Dribbble">
    </datalist>

    {{-- Azioni --}}
    <div class="flex justify-between mt-6">
        <x-primary-button wire:click="addProfile">+ Aggiungi Profilo</x-primary-button>
        <x-primary-button wire:click="save" class="bg-green-600 hover:bg-green-700 transition">Salva</x-primary-button>
    </div>

    {{-- Messaggio di conferma --}}
    @if (session('message'))
        <div class="mt-4 text-green-600 font-semibold">
            {{ session('message') }}
        </div>
    @endif
</div>
