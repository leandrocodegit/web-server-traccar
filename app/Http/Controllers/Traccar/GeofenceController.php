<?php

namespace App\Http\Controllers\Traccar;

use App\Http\Controllers\Controller;
use App\Models\Account\User;
use App\Models\Traccar\Device;
use App\Models\Traccar\Geofence;
use App\Models\Traccar\UserDevice;
use App\Models\Traccar\UserGeofence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class GeofenceController extends Controller
{

    function find($id)
    {
        return Geofence::with('calendar', 'users')->firstWhere('id', $id);
    }

    function listGeofencesDevice($deviceId)
    {
        return Device::where('id', $deviceId)->with('geofences')->get();
    }

    function listGeofencesUsers($userid)
    {
        return User::with('geofences')->firstWhere('id', $userid)->geofences()->get();
    }

    function listGeofencesUser(Request $request)
    {



        $geofences = new Collection();

        $users = DB::table('tc_user_user')->where("userid", "=", $request->userid)->get();

        foreach ($users as $user) {

            $lista = DB::table('tc_users')
                ->join('tc_user_geofence', 'tc_users.id', '=', 'tc_user_geofence.userid')
                ->join('tc_geofences', 'tc_user_geofence.geofenceid', '=', 'tc_geofences.id')
                ->leftJoin('tc_rotinas', 'tc_rotinas.geofenceId', '=',  'tc_geofences.id')
                ->select('tc_geofences.*', DB::raw('COUNT(tc_rotinas.id) as rotinas'))
                ->where('tc_users.id', '=', $user->manageduserid)
                ->groupBy('tc_geofences.id')
                ->get();

            $geofences = $lista->concat($geofences)->unique()->values()->all();
        }

        return collect($geofences)->sortBy('id')->values()->all();
    }

    function store(Request $request)
    {

        $geofence = Geofence::firstWhere('id', '=', $request->id);

        $request['userid'] = 1;

        if ($geofence == null) {
            $geofence =  Geofence::create([
                'name' => ucwords($request->name),
                'description' => ucwords($request->description),
                'telefone' => $request->telefone,
                'conta' => $request->conta
            ]);
        } else {
            $geofence->update([
                'id' => $request->id,
                'name' => ucwords($request->name),
                'description' => ucwords($request->description),
                'telefone' => $request->telefone,
                'conta' => $request->conta
            ]);
        }
    }

    function alterarArea(Request $request)
    {
        $geofence = Geofence::firstWhere('id', '=', $request->id);

        if ($geofence != null) {
            $geofence->update([
                'id' => $request->id,
                'area' => $request->area,
            ]);
        }
    }
}
