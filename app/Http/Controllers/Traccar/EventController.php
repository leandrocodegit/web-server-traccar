<?php

namespace App\Http\Controllers\Traccar;

use App\Http\Controllers\Controller;
use App\Models\Account\User;
use App\Models\Traccar\Device;
use App\Models\Traccar\Event;
use App\Models\Traccar\Position;
use App\Models\Traccar\UserDevice;
use Illuminate\Http\Request;

class EventController extends Controller
{

    function list ($deviceId){
        return Event::where('deviceid', $deviceId)->get();
    }
    function find ($id){
       return Event::firstWhere('id', $id);
    }

}
