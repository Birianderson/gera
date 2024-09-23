<?php

namespace App\Http\Controllers\Loteamento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Databases\Contracts\LoteamentoContract;
use App\Http\Requests\LoteamentoRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class LoteamentoController extends Controller
{
    public function __construct(private readonly LoteamentoContract $LoteamentoRepository)
    {
    }

    /**
     * Unidade de Atendimento index
     * @return View
     */
    public function index(int $cidade_id): View
    {
        $cidade = $this->LoteamentoRepository->getCidadeByid($cidade_id);
        $cidade_nome = $cidade->nome;
        return view('loteamento.index',compact(['cidade_nome','cidade_id']));
    }

    /**
     * Lista Unidade de Atendimento
     * @param Request $request
     * @return JsonResponse
     */
    public function list(String $cidade_id, Request $request): JsonResponse

    {
        $dados = $this->LoteamentoRepository->paginate($cidade_id, $request->all())->toArray();
        $dados['filter_options'] = [
            'nome' => [
                'type' => 'text',
            ],
            'sigla' => [
                'type' => 'text',
            ],

        ];
        return response()->json($dados);
    }
    /**
     * Criar Unidade de Atendimento
     * @param LoteamentoRequest $request
     * @return JsonResponse
     */
    public function create(LoteamentoRequest $request): JsonResponse
    {
        $params = $request->except('_token');

        $this->LoteamentoRepository->create($params);

        return response()->json('success', 201);
    }

    /**
     * Editar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function findCidade(string $id): JsonResponse
    {
        $cidade = $this->LoteamentoRepository->getCidadeByid($id);
        return response()->json($cidade);
    }

    /**
     * Editar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function findLoteamentoByCidade(string $id): JsonResponse
    {
        $loteamento = $this->LoteamentoRepository->getByCidadeId($id);
        return response()->json($loteamento);
    }

    /**
     * Editar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        $categoria = $this->LoteamentoRepository->getById($id);
        return response()->json($categoria);
    }

    /**
     * Editar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function vincula(Request $request): JsonResponse
    {
        $params = $request->except('_token');
        $vincula = $this->LoteamentoRepository->vincula($params);
        return response()->json($vincula);
    }



    /**
     * Atualizar Unidade de Atendimento
     * @param LoteamentoRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(LoteamentoRequest $request, int $id): JsonResponse
    {
        $params = $request->except('_token');
        $this->LoteamentoRepository->update($id, $params);
        return response()->json(['success', $params]);
    }


    /**
     * Deletar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        DB::transaction(function () use ($id) {
            $this->LoteamentoRepository->destroy($id);
        });

        return response()->json('success');
    }
}
