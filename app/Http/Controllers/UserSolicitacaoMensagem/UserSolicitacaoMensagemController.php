<?php

namespace App\Http\Controllers\UserSolicitacaoMensagem;

use App\Databases\Contracts\UserSolicitacaoMensagemContract;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserSolicitacaoMensagemController extends Controller
{
    /**
     * @param UserSolicitacaoMensagemContract $UserSolicitacaoMensagemRepository
     */
    public function __construct(private readonly UserSolicitacaoMensagemContract $UserSolicitacaoMensagemRepository)
    {
    }

    /**
     * @param $id
     * @return View
     */
    public function index($id): View
    {
        $user_id = Auth::user()->id;
        return view('user_solicitacao_mensagem.index', compact(['id','user_id']));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function chat(int $id): JsonResponse
    {
        $categoria = $this->UserSolicitacaoMensagemRepository->getById($id);
        return response()->json($categoria);
    }

    /**
     * Criar Unidade de Atendimento
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $params = $request->except('_token');
        $this->UserSolicitacaoMensagemRepository->create($params);

        return response()->json('success', 201);
    }

    /**
     * Criar Unidade de Atendimento
     * @param Request $request
     * @return JsonResponse
     */
    public function mudarSituacao(Request $request): JsonResponse
    {
        $params = $request->except('_token');
        $this->UserSolicitacaoMensagemRepository->mudarSituacao($params);

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
        $arquivo = $this->UserSolicitacaoMensagemRepository->getArquivoById($id);
        if(!$arquivo) abort(404);
        $hash = implode("/", $params);
        if($arquivo->hash != $hash) abort(404);
        return response()->download(public_path("/storage/{$hash}"), $arquivo->nome);
    }


}
