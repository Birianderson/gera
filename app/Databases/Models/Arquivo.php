<?php

namespace App\Databases\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arquivo extends Model
{
    use SoftDeletes;

    protected $table = 'arquivo';
    protected $primaryKey = 'id';
    public string $sequence = 'arquivo_id_seq';
    protected $guarded = [];

}
