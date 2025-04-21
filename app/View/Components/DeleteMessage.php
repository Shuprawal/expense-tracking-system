<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DeleteMessage extends Component
{
    public $message;
    public $title;
    public $route;
    /**
     * Create a new component instance.
     */
    public function __construct( $message, $title, $route )
    {
        $this->message = $message;
        $this->title = $title;
        $this->route = $route;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.delete-message');
    }
}
