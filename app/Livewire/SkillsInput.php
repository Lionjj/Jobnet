<?php

namespace App\Livewire;

use Livewire\Component;

class SkillsInput extends Component
{
    public array $skills = [];
    public string $newSkill = '';

    public function addSkill()
    {
        if ($this->newSkill && !in_array($this->newSkill, $this->skills)) {
            $this->skills[] = $this->newSkill;
        }

        $this->newSkill = '';
    }

    public function removeSkill($index)
    {
        unset($this->skills[$index]);
        $this->skills = array_values($this->skills);
    }

    public function render()
    {
        return view('livewire.skills-input');
    }
}
