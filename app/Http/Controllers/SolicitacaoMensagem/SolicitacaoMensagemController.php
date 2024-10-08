<?php

namespace App\Http\Controllers\SolicitacaoMensagem;

use App\Databases\Contracts\SolicitacaoMensagemContract;
use App\Http\Requests\SolicitacaoMensagemRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SolicitacaoMensagemController extends Controller
{
    /**
     * @param SolicitacaoMensagemContract $SolicitacaoMensagemRepository
     */
    public function __construct(private readonly SolicitacaoMensagemContract $SolicitacaoMensagemRepository)
    {
    }

    /**
     * @param $id
     * @return View
     */
    public function index($id): View
    {
        $user_id = Auth::user()->id;
        return view('solicitacao_mensagem.index', compact(['id','user_id']));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function chat(int $id): JsonResponse
    {
        $categoria = $this->SolicitacaoMensagemRepository->getById($id);
        return response()->json($categoria);
    }

    /**
     * Criar Unidade de Atendimento
     * @param SolicitacaoMensagemRequest $request
     * @return JsonResponse
     */
    public function create(SolicitacaoMensagemRequest $request): JsonResponse
    {
        $params = $request->except('_token');
        $this->SolicitacaoMensagemRepository->create($params);

        return response()->json('success', 201);
    }

    /**
     * Criar Unidade de Atendimento
     * @param SolicitacaoMensagemRequest $request
     * @return JsonResponse
     */
    public function mudarSituacao(Request $request): JsonResponse
    {
        $params = $request->except('_token');
        $this->SolicitacaoMensagemRepository->mudarSituacao($params);

        return response()->json('success', 201);
    }


    /**
     * @param Request $request
     * @param int $id
     * @param mixed ...$params
     * @return BinaryFileResponse
     */
    public function download(int $id, mixed ...$params): BinaryFileResponse
    {
        $arquivo = $this->SolicitacaoMensagemRepository->getArquivoById($id);
        if(!$arquivo) abort(404);
        $hash = implode("/", $params);
        if($arquivo->hash != $hash) abort(404);
        return response()->download(public_path("/storage/{$hash}"), $arquivo->nome);
    }


}
