<?php

namespace App\Http\Controllers\Imovel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Databases\Contracts\ImovelContract;
use App\Http\Requests\ImovelRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

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
            'nome' => [
                'type' => 'text',
            ],
            'cpf' => [
                'type' => 'text',
            ],
            'estado_civil' => [
                'type' => 'select',
                'options' => [
                    ['id' => 'D', 'name' => 'DIVORCIADO(A)'],
                    ['id' => 'E', 'name' => 'SEPARADO(A)'],
                    ['id' => 'O', 'name' => 'SOLTEIRO(A)'],
                    ['id' => 'U', 'name' => 'UNIÃO ESTÁVEL'],
                    ['id' => 'V', 'name' => 'VIÚVO(A)'],
                ]
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
     * Ordenar Unidade de Atendimento
     * @return JsonResponse
     */
    public function ordem(): JsonResponse
    {
        $data = $this->ImovelRepository->getAllOrdem();
        return response()->json($data);
    }

    /**
     * Salvar ordem Unidade de Atendimento
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        $data = $request->all();
        $this->ImovelRepository->upload($data);
        return response()->json(['message' => 'File imported successfully'], 200);
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
            $this->ImovelRepository->updateOrderAfterDeletion();
        });

        return response()->json('success');
    }
}
