<?php

namespace App\Databases\Repositories;

use App\Databases\Contracts\MapaContract;
use App\Databases\Models\Coordenadas;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Exception;


class MapaRepository implements MapaContract
{
    /**
     * @throws Exception
     */
    public function getByCidade(string $municipio): Collection
    {
        return Coordenadas::query()->where('municipio','=',$municipio)->with('imovel.pessoa')->get();
    }

}
