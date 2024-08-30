<?php

namespace App\Databases\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Imovel extends Model
{
    use SoftDeletes;
    protected $primaryKey = "id";
    protected $table = 'imovel';
    public string $sequence = 'imovel_id_seq';
    protected $guarded = [];
    public function coordenadas()
    {
        return $this->hasOne(Coordenadas::class);
    }

    public function vinculacaoImovelPessoa()
    {
        return $this->hasOne(VinculacaoImovelPessoas::class);
    }

    public function pessoa()
    {
        return $this->hasOneThrough(
            Pessoa::class,
            VinculacaoImovelPessoas::class,
            'imovel_id', // Chave estrangeira em VinculacaoImovelPessoa que referencia Imovel
            'id', // Chave estrangeira em PEssoa que referencia VinculacaoImovelPessoa
            'id', // Chave primária local em Imovel
            'pessoa_id' // Chave estrangeira em VinculacaoImovelPessoa que referencia Pessoa
        );
    }
}
