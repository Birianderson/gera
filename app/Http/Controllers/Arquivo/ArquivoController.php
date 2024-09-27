<?php

namespace App\Http\Controllers\Arquivo;

use App\Http\Controllers\Controller;
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
}
