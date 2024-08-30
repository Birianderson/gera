<?php

namespace App\Databases\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coordenadas extends Model
{
    use SoftDeletes;
    protected $primaryKey = "id";
    protected $table = 'coordenadas';
    public string $sequence = 'coordenadas_id_seq';
    protected $guarded = [];

    public function imovel()
    {
        return $this->belongsTo(Imovel::class);
    }
}
