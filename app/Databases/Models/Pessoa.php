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

}
