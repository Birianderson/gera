<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Solicitacao extends Model
{
    use SoftDeletes;

    protected $primaryKey = "id";
    protected $table = 'solicitacao';
    public string $sequence = 'solicitacao_id_seq';
    protected $guarded = [];
}
