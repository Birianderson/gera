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

    /**
     * Unidade de Atendimento index
     * @return View
     */
    public function solicitacao_mapa(string $imovel_id): View
    {
        return view('mapa.solicitacao_mapa', compact('imovel_id'));
    }

    public function getByLoteamento(String $loteamento_id): JsonResponse
    {
        $dados = $this->MapaRepository->getByLoteamento($loteamento_id);

        return response()->json($dados);
    }

    public function getByHash(String $imovel_id): JsonResponse
    {
        $dados = $this->MapaRepository->getByHash($imovel_id);
        return response()->json($dados);
    }
}
