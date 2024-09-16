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
    public function getByLoteamento(string $loteamento_id): Collection
    {
        return Coordenadas::query()->where('loteamento_id','=',$loteamento_id)->with('imovel.pessoa')->get();
    }

}
