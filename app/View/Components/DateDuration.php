<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DateDuration extends Component
{

    public $route;
    public $parameters;
    /**
     * Create a new component instance.
     */
    public function __construct( $route, $parameters = [])
    {

        $this->route = $route;
        $this->parameters = $parameters;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.date-duration');
    }
}
