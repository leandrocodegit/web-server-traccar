<?php

namespace App\Http\Controllers\Traccar;

use App\Http\Controllers\Controller;
use App\Models\Account\User;
use App\Models\Traccar\Device;
use App\Models\Traccar\Position;
use App\Models\Traccar\UserDevice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class PositionController extends Controller
{
    function find ($id){
       return Position::firstWhere('id', $id);
    }

    function findDevice ($deviceId){
        //return now();
       // return Position::where('deviceId', $deviceId)->whereDate('devicetime', now())->get();
       return Position::where('deviceId', $deviceId)->get();

     }

}
