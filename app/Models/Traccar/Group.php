<?php

namespace App\Models\Traccar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = "tc_groups";

    protected $fillable = [
        'id',
        'name',
        'groupid',
        'attributes'
        ];
}
