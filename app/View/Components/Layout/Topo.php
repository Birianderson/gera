<?php

namespace App\View\Components\Layout;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Topo extends Component
{
    public function render(): View
    {
        $usuario = session('info');
        return view('components.layout.topo', compact('usuario'));
    }
}
