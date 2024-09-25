<?php

namespace App\Http\Controllers\UserImovel;

use App\Databases\Contracts\UserImovelContract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ImovelRequest;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserImovelController extends Controller
{
    public function __construct(private readonly UserImovelContract $UserImovelRepository)
    {
    }

    /**
     * Unidade de Atendimento index
     * @return View
     */
    public function index(): View
    {
        return view('user_imovel.index');
    }

    /**
     * Lista Unidade de Atendimento
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse

    {
        $dados = $this->UserImovelRepository->paginate($request->all())->toArray();
        $dados['filter_options'] = [
            'cidade_nome' => [
                'type' => 'text',
            ],
            'loteamento_nome' => [
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
            'pessoa_nome' => [
                'type' => 'text',
            ],

        ];
        return response()->json($dados);
    }

    /**
     * Lista Unidade de Atendimento
     * @param Request $request
     * @return JsonResponse
     */
    public function listMobile(Request $request): JsonResponse

    {
        $dados = $this->UserImovelRepository->paginate($request->all())->toArray();
        $dados['filter_options'] = [
            'cidade_nome' => [
                'type' => 'text',
            ],
            'loteamento_nome' => [
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
            'pessoa_nome' => [
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

        $this->UserImovelRepository->create($params);

        return response()->json('success', 201);
    }

    /**
     * Editar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function findCPF(string $cpf): JsonResponse
    {
        $categoria = $this->UserImovelRepository->getByCPF($cpf);
        return response()->json($categoria);
    }

    public function findByQuadraLote($loteamento_id, $quadra, $lote)
    {
        $imovel = $this->UserImovelRepository->findByQuadraLote($loteamento_id, $quadra, $lote);
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
        $categoria = $this->UserImovelRepository->getById($id);
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
        $vincula = $this->UserImovelRepository->vincula($params);
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
        $this->UserImovelRepository->update($id, $params);
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
            $this->UserImovelRepository->destroy($id);
        });

        return response()->json('success');
    }
}
