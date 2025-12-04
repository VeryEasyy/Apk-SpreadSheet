<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarDropdown extends Component
{
    public string $icon;
    public string $label;
    public string $id;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $icon,
        string $label,
        ?string $id = null
    ) {
        $this->icon = $icon;
        $this->label = $label;
        $this->id = $id ?? strtolower(str_replace(' ', '-', $label));
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar-dropdown');
    }
}
