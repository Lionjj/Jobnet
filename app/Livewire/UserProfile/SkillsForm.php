<?php

namespace App\Livewire\UserProfile;

use Livewire\Component;
use App\Models\Skill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SkillsForm extends Component
{
    public array $skills = [];
    public array $availableSkills = [];

    public function mount()
    {
        $this->availableSkills = Skill::orderBy('name')->pluck('name')->unique()->toArray();

        $this->skills = Auth::user()?->skills->map(function ($skill) {
            return ['name' => $skill->name];
        })->toArray();
    }

    public function addSkill()
    {
        $this->skills[] = ['name' => ''];
    }

    public function removeSkill($index)
    {
        unset($this->skills[$index]);
        $this->skills = array_values($this->skills);
    }

    public function save()
    {
        $this->validate([
            'skills.*.name' => 'required|string|max:255',
        ], [
            'skills.*.name.required' => 'Il campo competenza è obbligatorio.',
        ]);

        // Verifica duplicati all'interno del form
        $skillNames = collect($this->skills)
            ->pluck('name')
            ->map(fn($n) => strtolower(trim($n)))
            ->filter()
            ->toArray();

        if (count($skillNames) !== count(array_unique($skillNames))) {
            $this->addError('skills.duplicate', 'La competenza appena inserita è gia presente tra quelle che possiedi.');
            return;
        }

        $user = Auth::user();
        $skillIds = [];

        foreach ($this->skills as $skillData) {
            $name = strtolower(trim($skillData['name']));
            if (!$name) continue;

            $skill = Skill::firstOrCreate(['name' => $name]);
            $skillIds[] = $skill->id;
        }

        $user->skills()->sync($skillIds);

        session()->flash('message', 'Competenze tecniche aggiornate!');
    }

    public function render()
    {
        return view('livewire.user-profile.skills-form');
    }
}
