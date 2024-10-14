<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class HelpdeskCategoria extends Model
{
    use SoftDeletes;
    protected $table = 'helpdesk_categoria';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public string $sequence = 'helpdesk_categoria_id_seq';



}

