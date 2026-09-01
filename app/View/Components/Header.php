<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    /**
     * Public property to hold the configuration data passed to the component.
     * @var array
     */
    public $config;
    /**
     * Create a new component instance.
     */
    public function __construct($config = [])
    {
        //
        $this->config = $config;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.header');
    }
}
