<?php

namespace App\Http\Controllers\Traccar;

use App\Http\Controllers\Controller;
use App\Models\Traccar\DriverDevice;

class MotoristaController extends Controller
{

    function listDevicesMotorista ($driverId){
        return DriverDevice::where('driverId', $driverId)->with('device')->get();
    }

    function listMotoristasDevice ($deviceId){
        return DriverDevice::where('deviceId', $deviceId)->with('user')->get();
    }
}
