<?php

use App\Http\Controllers\Cidade\CidadeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Imovel\ImovelController;
use App\Http\Controllers\Loteamento\LoteamentoController;
use App\Http\Controllers\Mapa\MapaController;
use App\Http\Controllers\Pessoa\PessoaController;
use App\Http\Controllers\Upload\UploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();


Route::middleware(['auth','admin'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');


    Route::group(['prefix' => 'pessoa'], function () {
        Route::get('/', [PessoaController::class, 'index'])->name('pessoa.index');
        Route::get('/list', [PessoaController::class, 'list'])->name('pessoa.list');
        Route::get('/imoveis/{id}', [PessoaController::class, 'imoveis'])->name('pessoa.imoveis');
        Route::get('/findCPF/{CPF}', [PessoaController::class, 'findCPF'])->name('pessoa.findCPF');
        Route::get('/{id}', [PessoaController::class, 'edit'])->name('pessoa.edit');
        Route::post('/ordem', [PessoaController::class, 'salvarOrdem'])->name('pessoa.salvar-ordem')->can('painel.unidades_atendimento.update');
        Route::post('/create', [PessoaController::class, 'create'])->name('pessoa.create');
        Route::post('/{id}', [PessoaController::class, 'update'])->name('pessoa.update');
        Route::delete('/{id}', [PessoaController::class, 'delete'])->name('pessoa.delete');
    });

    Route::group(['prefix' => 'imovel'], function () {
        Route::get('/', [ImovelController::class, 'index'])->name('imovel.index');
        Route::get('/list', [ImovelController::class, 'list'])->name('imovel.list');
        Route::get('/ordem', [ImovelController::class, 'ordem'])->name('imovel.ordem');
        Route::get('/findCPF/{CPF}', [ImovelController::class, 'findCPF'])->name('imovel.findCPF');
        Route::get('/{id}', [ImovelController::class, 'edit'])->name('imovel.edit');
        Route::post('/create', [ImovelController::class, 'create'])->name('imovel.create');
        Route::post('/vincula', [ImovelController::class, 'vincula'])->name('imovel.vincula');
        Route::post('/{id}', [ImovelController::class, 'update'])->name('imovel.update');
        Route::delete('/{id}', [ImovelController::class, 'delete'])->name('imovel.delete');
    });


    Route::group(['prefix' => 'cidade'], function () {
        Route::get('/', [CidadeController::class, 'index'])->name('cidade.index');
        Route::get('/list', [CidadeController::class, 'list'])->name('cidade.list');
        Route::get('/getAll', [CidadeController::class, 'getAll'])->name('cidade.getAll');
        Route::get('/ordem', [CidadeController::class, 'ordem'])->name('cidade.ordem');
        Route::get('/{id}', [CidadeController::class, 'edit'])->name('cidade.edit');
        Route::post('/create', [CidadeController::class, 'create'])->name('cidade.create');
        Route::post('/vincula', [CidadeController::class, 'vincula'])->name('cidade.vincula');
        Route::post('/{id}', [CidadeController::class, 'update'])->name('cidade.update');
        Route::delete('/{id}', [CidadeController::class, 'delete'])->name('cidade.delete');
    });

    Route::group(['prefix' => 'loteamento'], function () {
        Route::get('/{cidade_id}/cidade', [LoteamentoController::class, 'index'])->name('loteamento.index');
        Route::get('/list', [LoteamentoController::class, 'list'])->name('loteamento.list');
        Route::get('/findCidade/{cidade_id}', [LoteamentoController::class, 'findCidade'])->name('cidade.findCidade');
        Route::get('/findLoteamentoByCidade/{cidade_id}', [LoteamentoController::class, 'findLoteamentoByCidade'])->name('cidade.findLoteamentoByCidade');
        Route::get('/ordem', [LoteamentoController::class, 'ordem'])->name('loteamento.ordem');
        Route::get('/findCPF/{CPF}', [LoteamentoController::class, 'findCPF'])->name('loteamento.findCPF');
        Route::get('/{id}', [LoteamentoController::class, 'edit'])->name('loteamento.edit');
        Route::post('/create', [LoteamentoController::class, 'create'])->name('loteamento.create');
        Route::post('/vincula', [LoteamentoController::class, 'vincula'])->name('loteamento.vincula');
        Route::post('/{id}', [LoteamentoController::class, 'update'])->name('loteamento.update');
        Route::delete('/{id}', [LoteamentoController::class, 'delete'])->name('loteamento.delete');
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
