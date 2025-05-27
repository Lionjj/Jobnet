<?php

namespace App\Livewire;

use Livewire\Component;

class BenefitsInput extends Component
{
    public array $benefits = [];
    public string $newBenefit = '';

    public function addBenefit()
    {
        if ($this->newBenefit && !in_array($this->newBenefit, $this->benefits)) {
            $this->benefits[] = $this->newBenefit;
        }

        $this->newBenefit = '';
    }

    public function removeBenefit($index)
    {
        unset($this->benefits[$index]);
        $this->benefits = array_values($this->benefits);
    }

    public function render()
    {
        return view('livewire.benefits-input');
    }
}
