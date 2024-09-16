<?php

namespace App\Databases\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface MapaContract
{
    public function getByLoteamento(String $cidade): Collection;
}

