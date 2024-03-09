<?php

namespace App\Models\Traccar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $table = "tc_positions";
    protected $appends = array('status');


    protected $fillable = [
        'id',
        'protocol',
        'deviceid',
        'servertime',
        'devicetime',
        'fixtime',
        'valid',
        'latitude',
        'longitude',
        'altitude',
        'speed',
        'course',
        'address',
        'attributes',
        'accuracy',
        'network',
        'geofenceids'
    ];

    public function getStatusAttribute()
    {
        return json_decode($this['attributes'], true);;
    }
}
