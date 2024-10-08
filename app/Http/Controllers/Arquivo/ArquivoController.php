<?php

namespace App\Http\Controllers\Arquivo;

use App\Databases\Models\Arquivo;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ArquivoController extends Controller
{
    /**
     * @param Request $request
     * @param string $ano
     * @param string $mes
     * @param string $dia
     * @param string $hash
     * @return BinaryFileResponse
     */
    public function download(Request $request, string $ano, string $mes, string $dia, string $hash): BinaryFileResponse
    {
        return response()->download(storage_path('app/public/uploads/' . $ano . '/' . $mes . '/' . $dia . '/' . $hash));
    }


    public function edit(string $id): JsonResponse
    {
        $arquivo = Arquivo::query()->where('id', $id)->first();
        return response()->json($arquivo);
    }

    public function update(Request $request): JsonResponse
    {
        $params = $request->except('_token');
        $arquivo = Arquivo::query()->where('id', $params['arquivo_id'])->first();
        unset($params['arquivo_id']);
        $arquivo->update($params);
        return response()->json($arquivo);
    }
}
