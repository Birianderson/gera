<?php

namespace App\Providers;

use App\Databases\Contracts\CidadeContract;
use App\Databases\Contracts\ImovelContract;
use App\Databases\Contracts\LoteamentoContract;
use App\Databases\Contracts\MapaContract;
use App\Databases\Contracts\PessoaContract;
use App\Databases\Contracts\SolicitacaoContract;
use App\Databases\Contracts\SolicitacaoMensagemContract;
use App\Databases\Contracts\UploadContract;
use App\Databases\Contracts\UserImovelContract;
use App\Databases\Contracts\UserPessoaContract;
use App\Databases\Contracts\UserSolicitacaoContract;
use App\Databases\Contracts\UserSolicitacaoMensagemContract;
use App\Databases\Repositories\CidadeRepository;
use App\Databases\Repositories\ImovelRepository;
use App\Databases\Repositories\LoteamentoRepository;
use App\Databases\Repositories\MapaRepository;
use App\Databases\Repositories\PessoaRepository;
use App\Databases\Repositories\SolicitacaoMensagemRepository;
use App\Databases\Repositories\SolicitacaoRepository;
use App\Databases\Repositories\UploadRepository;
use App\Databases\Repositories\UserImovelRepository;
use App\Databases\Repositories\UserPessoaRepository;

use App\Databases\Repositories\UserSolicitacaoMensagemRepository;
use App\Databases\Repositories\UserSolicitacaoRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app()->bind(PessoaContract::class, PessoaRepository::class);
        app()->bind(ImovelContract::class, ImovelRepository::class);
        app()->bind(UploadContract::class, UploadRepository::class);
        app()->bind(MapaContract::class, MapaRepository::class);
        app()->bind(CidadeContract::class, CidadeRepository::class);
        app()->bind(LoteamentoContract::class, LoteamentoRepository::class);
        app()->bind(SolicitacaoContract::class, SolicitacaoRepository::class);
        app()->bind(SolicitacaoMensagemContract::class, SolicitacaoMensagemRepository::class);
        app()->bind(UserSolicitacaoContract::class, UserSolicitacaoRepository::class);
        app()->bind(UserPessoaContract::class, UserPessoaRepository::class);
        app()->bind(UserImovelContract::class, UserImovelRepository::class);
        app()->bind(UserSolicitacaoMensagemContract::class, UserSolicitacaoMensagemRepository::class);
    }
}
