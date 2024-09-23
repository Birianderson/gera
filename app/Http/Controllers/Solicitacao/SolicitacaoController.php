<?php

namespace App\Http\Controllers\Solicitacao;

use App\Databases\Models\Imovel;
use App\Databases\Models\Pessoa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Databases\Contracts\SolicitacaoContract;
use App\Http\Requests\SolicitacaoRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class SolicitacaoController extends Controller
{
    public function __construct(private readonly SolicitacaoContract $SolicitacaoRepository)
    {
    }

    /**
     * Unidade de Atendimento index
     * @return View
     */
    public function index(): View
    {
        return view('solicitacao.index');
    }

    public function mostrarFormulario(string $id)
    {
        $imovelIdDescriptografado = Crypt::decryptString($id);
        $imovel = Imovel::query()->where('id','=', $imovelIdDescriptografado)->with(['pessoa','loteamento','cidade'])->first();;
        return view('pessoa.formulario', compact('imovel'));
    }

    public function salvarFormulario(Request $request)
    {
        // Salvar informações do morador
        $morador = new Pessoa();
        $morador->nome = $request->nome;
        $morador->telefone = $request->telefone;
        $morador->save();

        return response()->json(['message' => 'Informações salvas com sucesso']);
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
