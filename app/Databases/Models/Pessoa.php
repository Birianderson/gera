<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pessoa extends Model
{
    use SoftDeletes;

    protected $primaryKey = "id";
    protected $table = 'pessoa';
    public string $sequence = 'pessoa_id_seq';
    protected $guarded = [];
    public function imoveis()
    {
        return $this->hasMany(Imovel::class, 'pessoa_id', 'id');
    }


    public function conjuge(){
        return $this->hasOne(Pessoa::class,'id','conjuge_id');
    }
}
