<?php

namespace App\Http\Controllers\Mapa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;


class MapaController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Unidade de Atendimento index
     * @return View
     */
    public function index(): View
    {
        return view('mapa.index');
    }

}
