<?php

namespace App\Databases\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface MapaContract
{
    public function getByCidade(String $cidade): Collection;
}

