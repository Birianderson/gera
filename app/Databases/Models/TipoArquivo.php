<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TipoArquivo extends Model
{
    use SoftDeletes;
    protected $table = 'tipo_arquivo';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public string $sequence = 'tipo_arquivo_id_seq';

    public function arquvios()
    {
        return $this->hasMany(Arquivo::class, 'tipo_arquivo_id');
    }

}

