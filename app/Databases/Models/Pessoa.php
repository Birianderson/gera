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
        return $this->hasManyThrough(
            Imovel::class,
            VinculacaoImovelPessoas::class,
            'pessoa_id', // Chave estrangeira em VinculacaoImovelPessoa que referencia Pessoa
            'id', // Chave estrangeira em Imovel que referencia VinculacaoImovelPessoa
            'id', // Chave primária local em Pessoa
            'imovel_id' // Chave estrangeira em VinculacaoImovelPessoa que referencia Imovel
        );
    }

    public function conjuge(){
        return $this->hasOne(Pessoa::class,'id','conjuge_id');
    }
}
