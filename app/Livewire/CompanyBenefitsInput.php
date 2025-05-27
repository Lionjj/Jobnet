<?php

namespace App\Livewire;

use Livewire\Component;

class CompanyBenefitsInput extends Component
{
    public array $benefits = [];

    public function addBenefit()
    {
        $this->benefits[] = '';
    }

    public function removeBenefit($index)
    {
        unset($this->benefits[$index]);
        $this->benefits = array_values($this->benefits); // resetta gli indici
    }

    public function render()
    {
        return view('livewire.company-benefits-input');
    }
}
