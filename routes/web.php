<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Imovel\ImovelController;
use App\Http\Controllers\Mapa\MapaController;
use App\Http\Controllers\Pessoa\PessoaController;
use App\Http\Controllers\Upload\UploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();


Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');


    Route::group(['prefix' => 'pessoa'], function () {
        Route::get('/', [PessoaController::class, 'index'])->name('pessoa.index');
        Route::get('/list', [PessoaController::class, 'list'])->name('pessoa.list');
        Route::get('/imoveis/{id}', [PessoaController::class, 'imoveis'])->name('pessoa.imoveis');
        Route::get('/findCPF/{CPF}', [PessoaController::class, 'findCPF'])->name('pessoa.findCPF');
        Route::get('/{id}', [PessoaController::class, 'edit'])->name('pessoa.edit');
        Route::post('/ordem', [PessoaController::class, 'salvarOrdem'])->name('pessoa.salvar-ordem')->can('painel.unidades_atendimento.update');
        Route::post('/', [PessoaController::class, 'create'])->name('pessoa.create');
        Route::post('/{id}', [PessoaController::class, 'update'])->name('pessoa.update');
        Route::delete('/{id}', [PessoaController::class, 'delete'])->name('pessoa.delete');
    });

    Route::group(['prefix' => 'imovel'], function () {
        Route::get('/', [ImovelController::class, 'index'])->name('imovel.index');
        Route::get('/list', [ImovelController::class, 'list'])->name('imovel.list');
        Route::get('/ordem', [ImovelController::class, 'ordem'])->name('imovel.ordem');
        Route::get('/findCPF/{CPF}', [ImovelController::class, 'findCPF'])->name('imovel.findCPF');
        Route::get('/{id}', [ImovelController::class, 'edit'])->name('imovel.edit');
        Route::post('/', [ImovelController::class, 'create'])->name('imovel.create');
        Route::post('/{id}', [ImovelController::class, 'update'])->name('imovel.update');
        Route::delete('/{id}', [ImovelController::class, 'delete'])->name('imovel.delete');
    });

    Route::group(['prefix' => 'upload'], function () {
        Route::get('/', [UploadController::class, 'index'])->name('upload.index');
        Route::post('/terrenos', [UploadController::class, 'terrenos'])->name('imovel.Terreno');
        Route::post('/coordenadas', [UploadController::class, 'coordenadas'])->name('imovel.Coordenadas');
    });

    Route::group(['prefix' => 'mapa'], function () {
        Route::get('/', [MapaController::class, 'index'])->name('mapa.index');
        Route::get('/getByCidade/{cidade}', [MapaController::class, 'getByCidade'])->name('mapa.getByCidade');
    });

});
