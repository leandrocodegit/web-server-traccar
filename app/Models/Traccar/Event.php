<?php

namespace App\Models\Traccar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = "tc_events";

    protected $fillable = [
        'type',
        'eventtime',
        'deviceid',
        'positionid',
        'geofenceid',
        'attributes',
        'maintenanceid'
    ];

    public function device(){
        return $this->belongsTo(Device::class, 'deviceid')->with('responsavel');
    }
}
