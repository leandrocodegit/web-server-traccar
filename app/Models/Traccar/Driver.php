<?php

namespace App\Models\Traccar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $table = "tc_drivers";

     protected $fillable = [
        'id',
        'name',
        'telefone',
        'cnh',
        'cpf',
        'attributes',
        'conta'
    ];

    public function devices(){
        return $this->belongsToMany(Device::class, 'tc_device_driver', 'driverid', 'deviceid');
    }


}
