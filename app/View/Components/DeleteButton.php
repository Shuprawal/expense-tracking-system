<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DeleteButton extends Component
{
//    public $route;
//    public $parameters;

    public string $route;
    public mixed $parameters;
    public string $title;
    public string $message;
    /**
     * Create a new component instance.
     */
    public function __construct(
         $route, $parameters = [],
        string $title = '',
        string $message = ''
    ) {
        $this->route = $route;
        $this->parameters = $parameters;
        $this->title = $title;
        $this->message = $message;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.delete-button');
    }
}
