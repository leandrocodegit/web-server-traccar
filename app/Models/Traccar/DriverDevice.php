<?php

namespace App\Models\Traccar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverDevice extends Model
{
    use HasFactory;

    protected $table = "tc_device_driver";

    protected $fillable = [
        'deviceid',
        'driverid'
    ];

    public function device(){
        return $this->belongsTo(Device::class, 'deviceid');
    }

}
