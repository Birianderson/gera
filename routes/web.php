<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Pessoa\PessoaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');


    Route::group(['prefix' => 'pessoa', 'middleware' => ['web', 'auth']], function () {
        Route::get('/', [PessoaController::class, 'index'])->name('pessoa.index');
        Route::get('/list', [PessoaController::class, 'list'])->name('pessoa.list');
        Route::get('/ordem', [PessoaController::class, 'ordem'])->name('pessoa.ordem')->can('painel.unidades_atendimento.update');
        Route::get('/findCPF/{CPF}', [PessoaController::class, 'findCPF'])->name('pessoa.findCPF');
        Route::get('/{id}', [PessoaController::class, 'edit'])->name('pessoa.edit');
        Route::post('/ordem', [PessoaController::class, 'salvarOrdem'])->name('pessoa.salvar-ordem')->can('painel.unidades_atendimento.update');
        Route::post('/', [PessoaController::class, 'create'])->name('pessoa.create');
        Route::post('/{id}', [PessoaController::class, 'update'])->name('pessoa.update');
        Route::delete('/{id}', [PessoaController::class, 'delete'])->name('pessoa.delete');
    });

});
