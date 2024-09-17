<?php

namespace App\Http\Controllers\Cidade;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Databases\Contracts\CidadeContract;
use App\Http\Requests\CidadeRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CidadeController extends Controller
{
    public function __construct(private readonly CidadeContract $CidadeRepository)
    {
    }

    /**
     * Unidade de Atendimento index
     * @return View
     */
    public function index(): View
    {
        return view('cidade.index');
    }

    /**
     * Lista Unidade de Atendimento
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse

    {
        $dados = $this->CidadeRepository->paginate($request->all())->toArray();
        $dados['filter_options'] = [
            'municipio' => [
                'type' => 'text',
            ],
            'loteamento' => [
                'type' => 'text',
            ],
            'prefixo_titulo' => [
                'type' => 'text',
            ],
            'quadra' => [
                'type' => 'text',
            ],
            'lote' => [
                'type' => 'text',
            ],
            'pessoa.nome' => [
                'type' => 'text',
            ],

        ];
        return response()->json($dados);
    }
    /**
     * Criar Unidade de Atendimento
     * @param CidadeRequest $request
     * @return JsonResponse
     */
    public function create(CidadeRequest $request): JsonResponse
    {
        $params = $request->except('_token');

        $this->CidadeRepository->create($params);

        return response()->json('success', 201);
    }

    /**
     * Editar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function findCPF(string $cpf): JsonResponse
    {
        $categoria = $this->CidadeRepository->getByCPF($cpf);
        return response()->json($categoria);
    }

    /**
     * Editar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        $categoria = $this->CidadeRepository->getById($id);
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
        $vincula = $this->CidadeRepository->vincula($params);
        return response()->json($vincula);
    }


    /**
     * Editar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        $cidade = $this->CidadeRepository->getAll();
        return response()->json($cidade);
    }


    /**
     * Atualizar Unidade de Atendimento
     * @param CidadeRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CidadeRequest $request, int $id): JsonResponse
    {
        $params = $request->except('_token');
        $this->CidadeRepository->update($id, $params);
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
            $this->CidadeRepository->destroy($id);
        });

        return response()->json('success');
    }
}
