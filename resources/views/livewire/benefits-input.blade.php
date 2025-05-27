<div>
    <label class="block font-medium mb-1">Benefit</label>

    <div class="flex gap-2 mb-2">
        <input type="text" wire:model.defer="newBenefit" class="border rounded px-3 py-2 w-full" placeholder="Es. Buoni pasto, orari flessibili">
        <button type="button" wire:click="addBenefit" class="bg-blue-500 text-white px-3 py-2 rounded">Aggiungi</button>
    </div>

    <div class="flex flex-wrap gap-2">
        @foreach ($benefits as $index => $benefit)
            <span class="bg-gray-200 text-sm px-3 py-1 rounded-full flex items-center">
                {{ $benefit }}
                <button type="button" wire:click="removeBenefit({{ $index }})" class="ml-2 text-red-500 hover:text-red-700">&times;</button>
            </span>
        @endforeach
    </div>

    <input type="hidden" name="benefits" value='@json($benefits)'>
</div>
