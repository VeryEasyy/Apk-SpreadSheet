<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarLink extends Component
{
    public string $href;
    public string $icon;
    public string $label;
    public bool $active;
    public bool $isSubLink;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $href,
        string $icon,
        string $label,
        ?string $route = null,
        bool $isSubLink = false
    ) {
        $this->href = $href;
        $this->icon = $icon;
        $this->label = $label;
        $this->isSubLink = $isSubLink;

        // Check if link is active
        if ($route) {
            $this->active = request()->routeIs($route);
        } else {
            $this->active = request()->url() === $href;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar-link');
    }
}
