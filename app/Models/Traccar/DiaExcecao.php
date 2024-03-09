<?php

namespace App\Models\Traccar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaExcecao extends Model
{
    use HasFactory;

    protected $table = "tc_dias_excecao";
    protected $hidden = [
        "rotinaid"
    ];

    protected $fillable = [
        'id',
        'dia',
        'mes'
    ];

}
