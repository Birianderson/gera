<?php

namespace App\View\Components\Layout;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Principal extends Component
{
    public function render(): View
    {
        return view('components.layout.principal');
    }
}
