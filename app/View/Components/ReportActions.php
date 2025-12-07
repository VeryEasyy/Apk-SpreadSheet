<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ReportActions extends Component
{
    public $report;
    public bool $isAdmin;
    /**
     * Create a new component instance.
     */
    public function __construct($report)
    {
        $this->report = $report;
        $this->isAdmin = auth()->user()->role === 'admin';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.report-actions');
    }
}
