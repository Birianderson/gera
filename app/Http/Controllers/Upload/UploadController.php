<?php

namespace App\Http\Controllers\Upload;

use App\Databases\Contracts\UploadContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UploadController extends Controller
{
    public function __construct(private readonly UploadContract $UploadRepository)
    {
    }

    /**
     * Unidade de Atendimento index
     * @return View
     */
    public function index(): View
    {
        return view('upload.index');
    }

    /**
     * Salvar ordem Unidade de Atendimento
     * @param Request $request
     * @return JsonResponse
     */
    public function Terreno(Request $request): JsonResponse
    {
        $data = $request->all();
        $this->UploadRepository->uploadCoordenadas($data);
        return response()->json(['message' => 'File imported successfully'], 200);
    }

    /**
     * Salvar ordem Unidade de Atendimento
     * @param Request $request
     * @return JsonResponse
     */
    public function Coordenadas(Request $request): JsonResponse
    {
        $data = $request->all();
        $this->UploadRepository->uploadTerreno($data);
        return response()->json(['message' => 'File imported successfully'], 200);
    }
}
