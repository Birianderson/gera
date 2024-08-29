<?php

namespace App\Http\Controllers\Upload;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Databases\Contracts\UploadContract;
use App\Http\Requests\UploadRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class UploadController extends Controller
{
    public function __construct()
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

}
