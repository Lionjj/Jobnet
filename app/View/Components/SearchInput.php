<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SearchInput extends Component
{
    public string $action;
    public string $placeholder;
    /**
     * Create a new component instance.
     */
    public function __construct(string $action, string $placeholder = 'Cerca...')
    {
        $this->action = $action;
        $this->placeholder = $placeholder;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.search-input');
    }
}
