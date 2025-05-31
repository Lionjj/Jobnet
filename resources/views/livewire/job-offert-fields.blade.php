<div class="space-y-6">
    {{-- Skills --}}
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">Competenze richieste</label>

        @foreach ($skills as $index => $skill)
            <div class="flex gap-2">
                <input type="text" wire:model="skills.{{ $index }}.name" name="skills_required[]"
                       class="flex-1 border rounded-md px-3 py-2"
                       placeholder="Es. PHP, Laravel, MySQL..." />
                <button type="button" wire:click="removeSkill({{ $index }})" class="text-red-600 hover:text-red-800">✕</button>
            </div>
        @endforeach

        <button type="button" wire:click="addSkill" class="text-blue-600 text-sm hover:underline">+ Aggiungi skill</button>
    </div>

    {{-- Benefits --}}
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">Benefit aziendali</label>

        @foreach ($benefits as $index => $benefit)
            <div class="flex gap-2">
                <input type="text" wire:model="benefits.{{ $index }}.name" name="benefits[]"
                       class="flex-1 border rounded-md px-3 py-2"
                       placeholder="Es. Buoni pasto, orari flessibili..." />
                <button type="button" wire:click="removeBenefit({{ $index }})" class="text-red-600 hover:text-red-800">✕</button>
            </div>
        @endforeach

        <button type="button" wire:click.prevent="addBenefit" class="text-blue-600 text-sm hover:underline">+ Aggiungi benefit</button>
    </div>
</div>
