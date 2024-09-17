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

    public function getByLoteamento(String $loteamento_id): JsonResponse
    {
        $dados = $this->MapaRepository->getByLoteamento($loteamento_id);

        return response()->json($dados);
    }
}
