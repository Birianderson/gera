<?php

namespace App\Http\Controllers\TipoArquivo;

use App\Databases\Contracts\TipoArquivoContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\TipoArquivoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TipoArquivoController extends Controller
{
    public function __construct(private TipoArquivoContract $repository){}


    public function index(): View
    {
        return view('tipo_arquivo.index');
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
        $TipoArquivo = $this->repository->getByTabela($tabela);
        return response()->json($TipoArquivo);
    }


    public function create(TipoArquivoRequest $request){
        $params = $request->except('_token');
        $this->repository->create($params);
        return response()->json('success', 201);
    }

    public function edit(int $id): JsonResponse
    {
        $TipoArquivo = $this->repository->getById($id);
        return response()->json($TipoArquivo);
    }

    /**
     *
     */
    public function update(TipoArquivoRequest $request, int $id): JsonResponse
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

