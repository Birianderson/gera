<?php

namespace App\Providers;

use App\Databases\Contracts\ImovelContract;
use App\Databases\Contracts\MapaContract;
use App\Databases\Contracts\PessoaContract;
use App\Databases\Contracts\UploadContract;
use App\Databases\Repositories\ImovelRepository;
use App\Databases\Repositories\MapaRepository;
use App\Databases\Repositories\PessoaRepository;
use App\Databases\Repositories\UploadRepository;
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
    }
}
