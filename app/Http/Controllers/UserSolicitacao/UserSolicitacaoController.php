<?php

namespace App\Http\Controllers\UserSolicitacao;


use App\Databases\Contracts\UserSolicitacaoContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class UserSolicitacaoController extends Controller
{
    public function __construct(private readonly UserSolicitacaoContract $SolicitacaoRepository)
    {
    }

    /**
     * Unidade de Atendimento index
     * @return View
     */
    public function index(): View
    {
        return view('user_solicitacao.index');
    }

    /**
     * Editar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function aprovado(Request $request): JsonResponse
    {
        $params = $request->except('_token');
        $vincula = $this->SolicitacaoRepository->aprovado($params);
        return response()->json($vincula);
    }


    /**
     * Lista Unidade de Atendimento
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse

    {
        $dados = $this->SolicitacaoRepository->paginate($request->all())->toArray();
        $dados['filter_options'] = [
            'cidade.nome' => [
                'type' => 'text',
            ],
            'loteamento.nome' => [
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
     * @param SolicitacaoRequest $request
     * @return JsonResponse
     */
    public function create(SolicitacaoRequest $request): JsonResponse
    {
        $params = $request->except('_token');

        $this->SolicitacaoRepository->create($params);

        return response()->json('success', 201);
    }

    /**
     * Editar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function findCPF(string $cpf): JsonResponse
    {
        $categoria = $this->SolicitacaoRepository->getByCPF($cpf);
        return response()->json($categoria);
    }

    public function findByQuadraLote($loteamento_id, $quadra, $lote)
    {
        $Solicitacao = $this->SolicitacaoRepository->findByQuadraLote($loteamento_id, $quadra, $lote);
        return $Solicitacao;
    }

    /**
     * Editar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        $categoria = $this->SolicitacaoRepository->getById($id);
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
        $vincula = $this->SolicitacaoRepository->vincula($params);
        return response()->json($vincula);
    }



    /**
     * Atualizar Unidade de Atendimento
     * @param SolicitacaoRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(SolicitacaoRequest $request, int $id): JsonResponse
    {
        $params = $request->except('_token');
        $this->SolicitacaoRepository->update($id, $params);
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
            $this->SolicitacaoRepository->destroy($id);
        });

        return response()->json('success');
    }
}
