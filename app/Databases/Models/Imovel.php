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

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function loteamento()
    {
        return $this->belongsTo(Loteamento::class);
    }

    public function cidade()
    {
        // O imóvel se relaciona com a cidade através do loteamento
        return $this->hasOneThrough(Cidade::class, Loteamento::class, 'id', 'id', 'loteamento_id', 'cidade_id');
    }
}
