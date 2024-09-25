<?php

namespace App\Http\Controllers\UserPessoa;

use App\Databases\Contracts\UserPessoaContract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Databases\Contracts\PessoaContract;
use App\Http\Requests\PessoaRequest;


class UserPessoaController extends Controller
{
    public function __construct(private readonly UserPessoaContract $UserPessoaRepository)
    {
    }

    /**
     * Unidade de Atendimento index
     * @return View
     */
    public function index(): View
    {
        $cpf = $user_id = Auth::user()->cpf;
        return view('user_pessoa.index',compact('cpf'));
    }

    /**
     * Lista Unidade de Atendimento
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse

    {
        $dados = $this->UserPessoaRepository->paginate($request->all())->toArray();
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

        $this->UserPessoaRepository->create($params);

        return response()->json('success', 201);
    }

    /**
     * Editar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function findCPF(): JsonResponse
    {
        $categoria = $this->UserPessoaRepository->getByCPF();
        return response()->json($categoria);
    }

    /**
     * Editar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        $categoria = $this->UserPessoaRepository->getById($id);
        return response()->json($categoria);
    }

    /**
     * Ordenar Unidade de Atendimento
     * @return JsonResponse
     */
    public function imoveis($id): JsonResponse
    {
        $data = $this->UserPessoaRepository->getImoveisByID($id);
        return response()->json([
            'data' => $data->all(),
        ]);

    }



    /**
     * Atualizar Unidade de Atendimento
     * @param PessoaRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(PessoaRequest $request): JsonResponse
    {
        $params = $request->except('_token');
        $this->UserPessoaRepository->update($params);
        return response()->json(['success', $params]);
    }


    /**
     * Deletar Unidade de Atendimento
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $this->UserPessoaRepository->destroy($id);

        return response()->json('success');
    }
}
