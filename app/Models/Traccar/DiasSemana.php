<?php

namespace App\Models\Traccar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiasSemana extends Model
{
    use HasFactory;

    protected $table = "tc_rotinas_dias_semana";

    protected $fillable = [
        'rotinaid',
        'dia'
    ];

}
