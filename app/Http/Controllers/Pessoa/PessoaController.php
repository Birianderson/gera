<?php

namespace App\Http\Controllers\Pessoa;

use App\Databases\Models\Imovel;
use App\Databases\Models\Pessoa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Databases\Contracts\PessoaContract;
use App\Http\Requests\PessoaRequest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class PessoaController extends Controller
{
    public function __construct(private readonly PessoaContract $PessoaRepository)
    {
    }

    /**
     * Unidade de Atendimento index
     * @return View
     */
    public function index(): View
    {
        return view('pessoa.index');
    }

    /**
     * Lista Unidade de Atendimento
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse

    {
        $dados = $this->PessoaRepository->paginate($request->all())->toArray();
        $dados['filter_options'] = [
            'nome' => [
                'type' => 'text',
            ],
            'cpf' => [
                'type' => 'text',
            ],
            'telefone' => [
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
     * @param PessoaRequest $request
     * @return JsonResponse
     */
    public function create(PessoaRequest $request): JsonResponse
    {
        $params = $request->except('_token');

        $this->PessoaRepository->create($params);

        return response()->json('success', 201);
    }

    /**
     * Editar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function findCPF(string $cpf): JsonResponse
    {
        $categoria = $this->PessoaRepository->getByCPF($cpf);
        return response()->json($categoria);
    }

    /**
     * Editar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        $categoria = $this->PessoaRepository->getById($id);
        return response()->json($categoria);
    }

    /**
     * Ordenar Unidade de Atendimento
     * @return JsonResponse
     */
    public function imoveis($id): JsonResponse
    {
        $data = $this->PessoaRepository->getImoveisByID($id);
        return response()->json([
            'data' => $data->all(),
        ]);

    }

    /**
     * Salvar ordem Unidade de Atendimento
     * @param Request $request
     * @return JsonResponse
     */
    public function salvarOrdem(Request $request): JsonResponse
    {
        $data = $request->all();
        $this->PessoaRepository->saveOrder($data);
        return response()->json([]);
    }


    /**
     * Atualizar Unidade de Atendimento
     * @param PessoaRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(PessoaRequest $request, int $id): JsonResponse
    {
        $params = $request->except('_token');
        $this->PessoaRepository->update($id, $params);
        return response()->json(['success', $params]);
    }


    /**
     * Deletar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $this->PessoaRepository->destroy($id);

        return response()->json('success');
    }
}
