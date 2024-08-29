<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Imovel\ImovelController;
use App\Http\Controllers\Pessoa\PessoaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
        Route::post('/upload-excel', [ImovelController::class, 'upload'])->name('imovel.upload');
        Route::post('/', [ImovelController::class, 'create'])->name('imovel.create');
        Route::post('/{id}', [ImovelController::class, 'update'])->name('imovel.update');
        Route::delete('/{id}', [ImovelController::class, 'delete'])->name('imovel.delete');
    });

});
