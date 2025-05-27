<div>
    <label class="block font-medium mb-1">Competenze richieste</label>

    <div class="flex gap-2 mb-2">
        <input type="text" wire:model.defer="newSkill" class="border rounded px-3 py-2 w-full" placeholder="Es. Laravel, PHP">
        <button type="button" wire:click="addSkill" class="bg-blue-500 text-white px-3 py-2 rounded">Aggiungi</button>
    </div>

    <div class="flex flex-wrap gap-2">
        @foreach ($skills as $index => $skill)
            <span class="bg-gray-200 text-sm px-3 py-1 rounded-full flex items-center">
                {{ $skill }}
                <button type="button" wire:click="removeSkill({{ $index }})" class="ml-2 text-red-500 hover:text-red-700">&times;</button>
            </span>
        @endforeach
    </div>

    <input type="hidden" name="skills_required" value='@json($skills)'>
</div>
