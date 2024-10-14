<?php

use App\Databases\Models\SolicitacaoMensagem;
use App\Http\Controllers\Auth\LoginSolicitacaoController;
use App\Http\Controllers\Auth\RegisterSolicitacaoController;
use App\Http\Controllers\Cidade\CidadeController;
use App\Http\Controllers\HelpdeskCategoria\HelpdeskCategoriaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Imovel\ImovelController;
use App\Http\Controllers\Arquivo\ArquivoController;
use App\Http\Controllers\TipoArquivo\TipoArquivoController;
use App\Http\Controllers\UserImovel\UserImovelController;
use App\Http\Controllers\Loteamento\LoteamentoController;
use App\Http\Controllers\Mapa\MapaController;
use App\Http\Controllers\UserPessoa\UserPessoaController;
use App\Http\Controllers\UserSolicitacao\UserSolicitacaoController;
use App\Http\Controllers\SolicitacaoMensagem\SolicitacaoMensagemController;
use App\Http\Controllers\Pessoa\PessoaController;
use App\Http\Controllers\Solicitacao\SolicitacaoController;
use App\Http\Controllers\Upload\UploadController;
use App\Http\Controllers\UserSolicitacaoMensagem\UserSolicitacaoMensagemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('publico.index');
});

// Rotas de login
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::group(['prefix' => 'vinculacao'], function () {
    Route::get('/verificaConta/{cpf}', [LoginSolicitacaoController::class, 'verificaConta'])->name('solicitacao.verificaConta');
    Route::get('/verificaImovel/{imovel_id}', [LoginSolicitacaoController::class, 'verificaImovel'])->name('solicitacao.verificaImovel');
    Route::get('/login/{id}', [LoginSolicitacaoController::class, 'showLoginForm'])
        ->where('id', '[a-zA-Z0-9]{60,}')
        ->name('solicitacao_login');

    Route::post('/cadastro', [RegisterSolicitacaoController::class, 'create']);
    Route::post('/loginSolicitacao', [LoginSolicitacaoController::class, 'solicitacao_login'])
        ->where('id', '[a-zA-Z0-9]{60,}');

});

// Rotas de registro
Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Rotas de redefinição de senha
Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset']);

// Verificação de email
Route::get('email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->name('verification.resend');





Route::middleware(['solicitacao'])->group(function () {
    Route::group(['prefix' => 'solicitacao'], function () {
        Route::get('/', [SolicitacaoController::class, 'index'])->name('solicitacao.index');
        Route::get('/list', [SolicitacaoController::class, 'list'])->name('solicitacao.list');
        Route::get('/formulario/{id}', [SolicitacaoController::class, 'mostrarFormulario'])->name('formulario.imovel');
        Route::post('/api/formulario', [SolicitacaoController::class, 'salvarFormulario']);
        Route::post('/create', [SolicitacaoController::class, 'create'])->name('solicitacao.create');
        Route::post('/{id}', [SolicitacaoController::class, 'update'])->name('solicitacao.update');
        Route::delete('/{id}', [SolicitacaoController::class, 'delete'])->name('solicitacao.delete');
    });
});


Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    Route::group(['prefix' => 'pessoa'],  function () {
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
        Route::get('/gerarQrcode/{id}', [ImovelController::class, 'gerarQrCode'])->name('imovel.gerarQrCode');
        Route::get('/findByQuadraLote/{loteamento_id}/{quadra}/{lote}', [ImovelController::class, 'findByQuadraLote'])->name('imovel.findByQuadraLote');
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
        Route::get('/list/{cidade_id}', [LoteamentoController::class, 'list'])->name('loteamento.list');
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
        Route::get('/getByLoteamento/{loteamento_id}', [MapaController::class, 'getByLoteamento'])->name('mapa.getByCidade');
    });

    Route::group(['prefix' => 'solicitacoes'], function () {
        Route::get('/', [SolicitacaoController::class, 'index'])->name('solicitacao.index');
        Route::get('/list', [SolicitacaoController::class, 'list'])->name('solicitacao.list');
    });

    Route::group(['prefix' => 'mensagem_solicitacao'], function () {
        Route::get('/solicitacao/{id}/chat', [SolicitacaoMensagemController::class, 'index'])->name('mensagem_solicitacao.index');
        Route::get('/list', [SolicitacaoMensagemController::class, 'list'])->name('mensagem_solicitacao.list');
        Route::get('/{id}', [SolicitacaoMensagemController::class, 'chat'])->name('mensagem_solicitacao.chat');
        Route::get('/download/{id}/{basedir}/{ano}/{mes}/{dia}/{arquivo}', [SolicitacaoMensagemController::class, 'download'])->name('mensagem_solicitacao.download');
        Route::post('/create', [SolicitacaoMensagemController::class, 'create'])->name('mensagem_solicitacao.create');
        Route::post('/mudar_situacao', [SolicitacaoMensagemController::class, 'mudarSituacao'])->name('mensagem_solicitacao.mudarSituacao');
    });

    Route::group(['prefix' => 'tipo_arquivo'], function() {
        Route::get('/', [TipoArquivoController::class, 'index'])->name('tipo_arquivo.index');
        Route::get('/list', [TipoArquivoController::class, 'list'])->name('tipo_arquivo.list');
        Route::get('/getByTabela/{tabela}',[TipoArquivoController::class, 'getByTabela'])->name('tipo_arquivo.getByTabela');
        Route::post('/create',[TipoArquivoController::class,'create'])->name('tipo_arquivo.create');
        Route::get('/historico/{id}',[TipoArquivoController::class, 'historico'])->name('tipo_arquivo.historico');
        Route::get('/{id}',[TipoArquivoController::class, 'edit'])->name('tipo_arquivo.edit');
        Route::post('/{id}',[TipoArquivoController::class, 'update'])->name('tipo_arquivo.update');
        Route::delete('/delete/{id}',[TipoArquivoController::class, 'delete'])->name('tipo_arquivo.delete');
    });

    Route::group(['prefix' => 'helpdesk_categoria'], function() {
        Route::get('/', [HelpdeskCategoriaController::class, 'index'])->name('helpdesk_categoria.index');
        Route::get('/list', [HelpdeskCategoriaController::class, 'list'])->name('helpdesk_categoria.list');
        Route::post('/create',[HelpdeskCategoriaController::class,'create'])->name('helpdesk_categoria.create');
        Route::get('/{id}/edit',[HelpdeskCategoriaController::class, 'edit'])->name('helpdesk_categoria.edit');
        Route::post('/{id}/update',[HelpdeskCategoriaController::class, 'update'])->name('helpdesk_categoria.update');
        Route::delete('/delete/{id}',[HelpdeskCategoriaController::class, 'delete'])->name('helpdesk_categoria.delete');
    });



    Route::group(['prefix' => 'arquivo'], function() {
        Route::get('/', [ArquivoController::class, 'index'])->name('arquivo.index');
        Route::get('/{id}/edit',[ArquivoController::class, 'edit'])->name('arquivo.edit');
        Route::post('/update',[ArquivoController::class, 'update'])->name('arquivo.update');
        Route::delete('/delete/{id}',[ArquivoController::class, 'delete'])->name('arquivo.delete');
    });

});


Route::middleware(['auth'])->prefix('user')->group(function () {

    Route::group(['prefix' => 'pessoa'],  function () {
        Route::get('/', [UserPessoaController::class, 'index'])->name('user.pessoa.index');
        Route::get('/list', [UserPessoaController::class, 'list'])->name('user.pessoa.list');
        Route::get('/documentos', [UserPessoaController::class, 'documentos'])->name('user.pessoa.documentos');
        Route::get('/documentos/{tipo_arquivo_id}', [UserPessoaController::class, 'documentos_tipo'])->name('user.pessoa.documentos_tipo');
        Route::get('/all_documentos', [UserPessoaController::class, 'all_documentos'])->name('user.pessoa.all_documentos');
        Route::get('/meus_documentos', [UserPessoaController::class, 'meus_documentos'])->name('user.pessoa.meus_documentos');
        Route::post('/upload_documentos', [UserPessoaController::class, 'upload_documentos'])->name('user.pessoa.upload_documentos');
        Route::get('/imoveis/{id}', [UserPessoaController::class, 'imoveis'])->name('user.pessoa.imoveis');
        Route::get('/findCPF', [UserPessoaController::class, 'findCPF'])->name('user.pessoa.findCPF');
        Route::get('/{id}', [UserPessoaController::class, 'edit'])->name('user.pessoa.edit');
        Route::post('/ordem', [UserPessoaController::class, 'salvarOrdem'])->name('user.pessoa.salvar-ordem')->can('painel.unidades_atendimento.update');
        Route::post('/create', [UserPessoaController::class, 'create'])->name('user.pessoa.create');
        Route::post('/update', [UserPessoaController::class, 'update'])->name('user.pessoa.update');
        Route::delete('/{id}', [UserPessoaController::class, 'delete'])->name('user.pessoa.delete');
    });

    Route::group(['prefix' => 'imovel'], function () {
        Route::get('/', [UserImovelController::class, 'index'])->name('user.imovel.index');
        Route::get('/list', [UserImovelController::class, 'list'])->name('user.imovel.list');
        Route::get('/listMobile', [UserImovelController::class, 'listMobile'])->name('user.imovel.listMobile');
        Route::get('/ordem', [UserImovelController::class, 'ordem'])->name('user.imovel.ordem');
        Route::get('/gerarQrcode/{id}', [UserImovelController::class, 'gerarQrCode'])->name('user.imovel.gerarQrCode');
        Route::get('/findByQuadraLote/{loteamento_id}/{quadra}/{lote}', [UserImovelController::class, 'findByQuadraLote'])->name('user.imovel.findByQuadraLote');
        Route::get('/findCPF/{CPF}', [UserImovelController::class, 'findCPF'])->name('user.imovel.findCPF');
        Route::get('/{id}', [UserImovelController::class, 'edit'])->name('user.imovel.edit');
        Route::post('/create', [UserImovelController::class, 'create'])->name('user.imovel.create');
        Route::post('/vincula', [UserImovelController::class, 'vincula'])->name('user.imovel.vincula');
        Route::post('/{id}', [UserImovelController::class, 'update'])->name('user.imovel.update');
        Route::delete('/{id}', [UserImovelController::class, 'delete'])->name('user.imovel.delete');
    });



    Route::group(['prefix' => 'solicitacoes'], function () {
        Route::get('/', [UserSolicitacaoController::class, 'index'])->name('user.solicitacao.index');
        Route::get('/list', [UserSolicitacaoController::class, 'list'])->name('user.solicitacao.list');
        Route::post('/aprovado', [UserSolicitacaoController::class, 'aprovado'])->name('user.solicitacao.vincular');
    });

    Route::group(['prefix' => 'mensagem_solicitacao'], function () {
        Route::get('/solicitacao/{id}', [UserSolicitacaoMensagemController::class, 'index'])->name('user.mensagem_solicitacao.index');
        Route::get('/list', [UserSolicitacaoMensagemController::class, 'list'])->name('user.mensagem_solicitacao.list');
        Route::get('/all_documentos', [UserSolicitacaoMensagemController::class, 'all_documentos'])->name('user.mensagem_solicitacao.all_documentos');
        Route::get('/chat/{id}', [UserSolicitacaoMensagemController::class, 'chat'])->name('user.mensagem_solicitacao.chat');
        Route::get('/download/{id}/{basedir}/{ano}/{mes}/{dia}/{arquivo}', [UserSolicitacaoMensagemController::class, 'download'])->name('user.mensagem_solicitacao.download');
        Route::post('/', [UserSolicitacaoMensagemController::class, 'create'])->name('user.mensagem_solicitacao.create');
        Route::post('/mudar_situacao', [UserSolicitacaoMensagemController::class, 'mudarSituacao'])->name('user.mensagem_solicitacao.mudarSituacao');
    });

    Route::group(['prefix' => 'mapa'], function () {
        Route::get('/solicitacao_mapa/{id}', [MapaController::class, 'solicitacao_mapa'])->name('mapa.solicitacao_localizacao');
        Route::get('/getByHash/{id}', [MapaController::class, 'getByHash']);
    });
});

Route::group(['prefix' => 'storage/public'], function () {
    Route::get('/uploads/{ano}/{mes}/{dia}/{hash}', [ArquivoController::class, 'download'])->name('storage.download');
});
