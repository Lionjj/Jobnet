<?php

namespace App\Livewire;

use Livewire\Component;

class JobOffertFields extends Component
{
    public array $skills = [];
    public array $benefits = [];

    public function addSkill()
    {
        $this->skills[] = '';
    }

    public function removeSkill($index)
    {
        unset($this->skills[$index]);
        $this->skills = array_values($this->skills);
    }

    public function addBenefit()
    {
        $this->benefits[] = '';
    }

    public function removeBenefit($index)
    {
        unset($this->benefits[$index]);
        $this->benefits = array_values($this->benefits);
    }

    public function render()
    {
        return view('livewire.job-offert-fields');
    }
}
