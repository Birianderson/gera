<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitacaoMensagem extends Model
{
    use SoftDeletes;

    protected $table = 'solicitacao_mensagem';
    protected $primaryKey = 'id';
    public string $sequence = 'solicitacao_mensagem_id_seq';
    protected $guarded = [];
    protected $with = ['arquivos','usuario'];

    public function solicitacao()
    {
        return $this->belongsTo(Solicitacao::class, 'solicitacao_id', 'id');
    }

    public function arquivos(): HasOne
    {
        return $this->HasOne(Arquivo::class, 'chave', 'id')->where('tabela', '=', 'solicitacao_mensagem')->orderBy('id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class,'usuario_id','id');
    }


}
