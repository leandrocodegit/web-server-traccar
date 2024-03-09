<?php

namespace App\Http\Controllers\Traccar;

use App\Http\Controllers\Controller;
use App\Models\Account\User;
use App\Models\Traccar\Device;
use App\Models\Traccar\Driver;
use App\Models\Traccar\DriverDevice;
use App\Models\Traccar\Geofence;
use App\Models\Traccar\UserDevice;
use App\Models\Traccar\UserGeofence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class DriverController  extends Controller
{

    function find ($id){
        return Driver::firstWhere('id', $id);
    }

    function store(Request $request)
    {

        $driver = Driver::firstWhere('id', '=', $request->id);

        if ($driver == null) {
            $driver =  Driver::create([
                'name' => ucwords($request->name),
                'telefone' => $request->telefone,
                'conta' => $request->conta,
            ]);
        } else {
            $driver->update([
                'id' => $request->id,
                'name' => ucwords($request->name),
                'telefone' => $request->telefone
            ]);
        }
    }

    function remover($driverId)
    {
        Driver::destroy($driverId);
    }

    function desassociarDevice(Request $request)
    {
        $driver = Driver::firstWhere('id', '=', $request->id);

        if($driver != null){
            $driver->devices()->detach($request->deviceId);
        }
    }

}
