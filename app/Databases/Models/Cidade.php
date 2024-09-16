<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cidade extends Model
{
    use SoftDeletes;

    protected $primaryKey = "id";
    protected $table = 'cidade';
    public string $sequence = 'cidade_id_seq';
    protected $guarded = [];
}
