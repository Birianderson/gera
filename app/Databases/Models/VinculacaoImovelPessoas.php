<?php

namespace App\Databases\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VinculacaoImovelPessoas extends Model
{
    use SoftDeletes;
    protected $primaryKey = "id";
    protected $table = 'vinculacao_imovel_pessoas';
    public string $sequence = 'vinculacao_imovel_pessoas_id_seq';
    protected $guarded = [];

    public function imovel()
    {
        return $this->belongsTo(Imovel::class);
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
}
