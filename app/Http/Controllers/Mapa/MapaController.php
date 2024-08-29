<?php

namespace App\Http\Controllers\Mapa;

use App\Databases\Contracts\MapaContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;


class MapaController extends Controller
{
    public function __construct(private readonly MapaContract $MapaRepository)
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

    public function getByCidade(String $municipio): JsonResponse
    {
        $dados = $this->MapaRepository->getByCidade($municipio);

        return response()->json($dados);
    }
}
