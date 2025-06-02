<div>
    <h3 class="font-bold text-lg mb-4">Esperienze Lavorative</h3>

    @foreach($experiences as $index => $exp)
        <div x-data="{ open: false }" class="bg-white border rounded-xl mb-6 shadow-sm">

            {{-- Header collapsabile --}}
            <div class="p-4 bg-gray-100 flex justify-between items-center cursor-pointer" @click="open = !open">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                    <span class="font-semibold">
                        {{ $exp['role'] ?: 'Esperienza #' . ($index + 1) }}
                    </span>

                    @if (!empty($exp['company']))
                        <span class="text-gray-600 italic">
                            @ {{ $exp['company'] }}
                        </span>
                    @endif

                    @if (!empty($exp['start_date']) || !empty($exp['end_date']) || ($exp['is_current'] ?? false))
                        <span class="text-sm text-gray-500">
                            ({{ $exp['start_date'] ?? '?' }} – {{ ($exp['is_current'] ?? false) ? 'oggi' : ($exp['end_date'] ?? '?') }})
                        </span>
                    @endif
                </div>

                <span x-text="open ? '−' : '+'"></span>
            </div>

            {{-- Corpo collapsabile --}}
            <div x-show="open" class="p-6 space-y-4">

                {{-- Ruolo --}}
                <div>
                    <x-input-label for="role_{{ $index }}" :value="'Ruolo'" />
                    <x-text-input type="text" id="role_{{ $index }}" name="experiences[{{ $index }}][role]"
                                  class="mt-1 block w-full"
                                  wire:model.defer="experiences.{{ $index }}.role"
                                  autocomplete="off" />
                    <x-input-error class="mt-2" :messages="$errors->get('experiences.' . $index . '.role')" />
                </div>

                {{-- Azienda --}}
                <div>
                    <x-input-label for="company_{{ $index }}" :value="'Azienda'" />
                    <x-text-input wire:model.defer="experiences.{{ $index }}.company"
                                  name="experiences[{{ $index }}][company]"
                                  id="company_{{ $index }}" type="text" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('experiences.' . $index . '.company')" />
                </div>

                {{-- Descrizione --}}
                <div>
                    <x-input-label for="description_{{ $index }}" :value="'Descrizione'" />
                    <textarea id="description_{{ $index }}"
                              name="experiences[{{ $index }}][description]"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              wire:model.defer="experiences.{{ $index }}.description"
                              rows="3"></textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('experiences.' . $index . '.description')" />
                </div>

                {{-- Date --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="start_date_{{ $index }}" :value="'Data inizio'" />
                        <x-text-input type="date" id="start_date_{{ $index }}" name="experiences[{{ $index }}][start_date]"
                                      class="mt-1 block w-full"
                                      wire:model.defer="experiences.{{ $index }}.start_date" />
                        <x-input-error class="mt-2" :messages="$errors->get('experiences.' . $index . '.start_date')" />
                    </div>

                    <div>
                        <x-input-label for="end_date_{{ $index }}" :value="'Data fine'" />
                        <x-text-input type="date" id="end_date_{{ $index }}" name="experiences[{{ $index }}][end_date]"
                                      class="mt-1 block w-full"
                                      wire:model.defer="experiences.{{ $index }}.end_date"
                                      :disabled="$exp['is_current'] ?? false" />
                        <x-input-error class="mt-2" :messages="$errors->get('experiences.' . $index . '.end_date')" />
                    </div>
                </div>

                {{-- Checkbox: esperienza attuale --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox"
                        id="is_current_{{ $index }}"
                        wire:model.defer="experiences.{{ $index }}.is_current"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                    <x-input-label for="is_current_{{ $index }}" :value="'Esperienza attuale'" class="mb-0" />
                </div>

                {{-- Rimuovi --}}
                <div class="text-right">
                    <button type="button" wire:click="removeExperience({{ $index }})"
                            class="text-red-600 hover:underline text-sm">Rimuovi esperienza</button>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Azioni --}}
    <div class="flex items-center justify-between mt-6">
        <x-primary-button wire:click="addExperience">+ Aggiungi Esperienza</x-primary-button>
        <x-primary-button wire:click="save" class="bg-green-600 hover:bg-green-700 transition">Salva Esperienze</x-primary-button>
    </div>

    {{-- Messaggio di conferma --}}
    @if (session()->has('message'))
        <div class="mt-4 text-green-600 font-semibold">
            {{ session('message') }}
        </div>
    @endif
</div>
