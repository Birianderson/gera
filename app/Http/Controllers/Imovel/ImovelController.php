<?php

namespace App\Http\Controllers\Imovel;

use App\Databases\Models\Imovel;
use App\Databases\Models\Pessoa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Databases\Contracts\ImovelContract;
use App\Http\Requests\ImovelRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ImovelController extends Controller
{
    public function __construct(private readonly ImovelContract $ImovelRepository)
    {
    }

    /**
     * Unidade de Atendimento index
     * @return View
     */
    public function index(): View
    {
        return view('imovel.index');
    }

    /**
     * Lista Unidade de Atendimento
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse

    {
        $dados = $this->ImovelRepository->paginate($request->all())->toArray();
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
     * @param ImovelRequest $request
     * @return JsonResponse
     */
    public function create(ImovelRequest $request): JsonResponse
    {
        $params = $request->except('_token');

        $this->ImovelRepository->create($params);

        return response()->json('success', 201);
    }

    /**
     * Editar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function findCPF(string $cpf): JsonResponse
    {
        $categoria = $this->ImovelRepository->getByCPF($cpf);
        return response()->json($categoria);
    }

    public function findByQuadraLote($loteamento_id, $quadra, $lote)
    {
       $imovel = $this->ImovelRepository->findByQuadraLote($loteamento_id, $quadra, $lote);
       return $imovel;
    }

    public function gerarQrCode($imovelId)
    {
        $imovelIdCriptografado = Crypt::encryptString($imovelId);
        $urlFormulario = route('formulario.imovel', ['id' => $imovelIdCriptografado]);
        return QrCode::size(300)->generate($urlFormulario);
    }


    /**
     * Editar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        $categoria = $this->ImovelRepository->getById($id);
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
        $vincula = $this->ImovelRepository->vincula($params);
        return response()->json($vincula);
    }



    /**
     * Atualizar Unidade de Atendimento
     * @param ImovelRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ImovelRequest $request, int $id): JsonResponse
    {
        $params = $request->except('_token');
        $this->ImovelRepository->update($id, $params);
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
            $this->ImovelRepository->destroy($id);
        });

        return response()->json('success');
    }
}
