<?php

namespace App\Models\Traccar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory;

    protected $table = "tc_calendars";

    protected $fillable = [
        'id',
        'name',
        'data',
        'attributes'
    ];
}
