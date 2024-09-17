<?php

namespace App\View\Components\Layout;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Publico extends Component {
    public function render(): View
    {
        return view('components.layout.publico');
    }
}
