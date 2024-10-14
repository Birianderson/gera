<?php

namespace App\Http\Controllers\HelpdeskCategoria;

use App\Databases\Contracts\HelpdeskCategoriaContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\HelpdeskCategoriaRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HelpdeskCategoriaController extends Controller
{
    public function __construct(private HelpdeskCategoriaContract $repository){}


    public function index(): View
    {
        return view('helpdesk_categoria.index');
    }

    public function list(Request $request): JsonResponse
    {
        $dados = $this->repository->getAll($request->all())->toArray();
        $dados['filter_options'] = [
            'nome' => [
                'type' => 'text',
            ],
            'tabela' => [
                'type' => 'text',
            ],

        ];
        return response()->json($dados);
    }

    public function getByTabela(string $tabela): JsonResponse
    {
        $HelpdeskCategoria = $this->repository->getByTabela($tabela);
        return response()->json($HelpdeskCategoria);
    }


    public function create(HelpdeskCategoriaRequest $request){
        $params = $request->except('_token');
        $this->repository->create($params);
        return response()->json('success', 201);
    }

    public function edit(int $id): JsonResponse
    {
        $HelpdeskCategoria = $this->repository->getById($id);
        return response()->json($HelpdeskCategoria);
    }

    /**
     *
     */
    public function update(HelpdeskCategoriaRequest $request, int $id): JsonResponse
    {
        $params = $request->except('_token');
        $this->repository->update($id, $params);
        return response()->json('success', 201);
    }
    public function delete(int $id){
        $this->repository->destroy($id);
        return response()->json('success', 201);
    }

}

