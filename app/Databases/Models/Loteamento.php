<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loteamento extends Model
{
    use SoftDeletes;

    protected $primaryKey = "id";
    protected $table = 'loteamento';
    public string $sequence = 'loteamento_id_seq';
    protected $guarded = [];

    public function cidade()
    {
        return $this->belongsTo(Cidade::class);
    }
}
