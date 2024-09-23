<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitacaoMensagem extends Model
{
    use SoftDeletes;

    protected $table = 'solicitacao_mensagem';
    protected $primaryKey = 'id';
    public string $sequence = 'solicitacao_mensagem_id_seq';
    protected $guarded = [];
    protected $with = ['arquivos','user'];

    public function solicitacao()
    {
        return $this->belongsTo(Solicitacao::class, 'solicitacao_id', 'id');
    }

    public function arquivos(): HasMany
    {
        return $this->hasMany(Arquivo::class, 'chave', 'id')->where('tabela', '=', 'solicitacao_atendimento')->orderBy('id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }


}
