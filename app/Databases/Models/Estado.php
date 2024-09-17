<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estado extends Model
{
    use SoftDeletes;

    protected $primaryKey = "id";
    protected $table = 'estado';
    public string $sequence = 'estado_id_seq';
    protected $guarded = [];
}
