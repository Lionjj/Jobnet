<div class="space-y-2">
    <label class="block text-sm font-medium text-gray-700 mb-1">Benefit aziendali</label>

    @foreach ($benefits as $index => $benefit)
        <div class="flex gap-2">
            <input type="text"
                   wire:model="benefits.{{ $index }}"
                   name="benefits[]"
                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
                   placeholder="Es. buoni pasto, orario flessibile" />

            <button type="button"
                    wire:click="removeBenefit({{ $index }})"
                    class="text-red-500 hover:text-red-700">
                ✕
            </button>
        </div>
    @endforeach

    <button type="button"
            wire:click="addBenefit"
            class="mt-2 text-sm text-blue-600 hover:underline">
        + Aggiungi benefit
    </button>
</div>
