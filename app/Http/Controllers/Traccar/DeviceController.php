<?php

namespace App\Http\Controllers\Traccar;

use App\Http\Controllers\Controller;
use App\Models\Account\User;
use App\Models\Traccar\Device;
use App\Models\Traccar\Driver;
use App\Models\Traccar\UserDevice;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Uuid;


class DeviceController extends Controller
{

    function find($id)
    {
        return Device::with('position', 'group', 'calendar', 'drivers', 'geofences', 'notificacoes', 'compartilhados', 'users')->firstWhere('id', $id);
    }

    function store(Request $request)
    {
        try {

            $device = Device::firstWhere('id', $request->id);

            $user = new User();
            $user->email = "lpoliveira.ti@gmail.com";
            $user->password = "admin";

            $nomes = explode(' ', $request->name);
            $nomeFormatado = "";

            foreach ($nomes as $nome)
                $nomeFormatado .=  ucwords($nome) . ' ';

            if ($device == null) {
                return  ServerController::create($user, json_encode([
                    'name' => $nomeFormatado,
                    'uniqueId' => (string) Uuid::uuid4(),
                    'category' => $request->category,
                    'conta' => $request->conta,
                    'responsavelId' => $request->responsavelId == null ? 0 : $request->responsavelId,
                ]), "devices");
            }
        } catch (\Exception $e) {
            return $e;
        }
    }

    function update(Request $request)
    {
            $device = Device::firstWhere('id', $request->id);

            $user = new User();
            $user->email = "lpoliveira.ti@gmail.com";
            $user->password = "admin";

            $nomes = explode(' ', $request->name);
            $nomeFormatado = "";

            foreach ($nomes as $nome)
                $nomeFormatado .=  ucwords($nome) . ' ';

            if ($device != null) {

                return  ServerController::update($user, json_encode([
                    'id' => $request->id,
                    'name' => $nomeFormatado,
                    'category' => $request->category,
                    'responsavelId' => $request->responsavelId,
                    'disabled' => $request->disabled,
                    'uniqueId' => $device->uniqueid,
                    'conta' => $device->conta,
                    'phone' => $request->phone,
                    'model' => $request->model,
                    'contact' => $request->contact,
                    'expirationTime' => $request->expirationTime,
                    'lastUpdate' => $device->lastUpdate,
                    'attributes' => null,
                    'groupId' => 0,
                    'calendarId' => 0,

                ]), "devices/" . $request->id);
            }
        }

    function delete(Request $request)
    {
            $device = Device::firstWhere('id', $request->id);

            $user = new User();
            $user->email = "lpoliveira.ti@gmail.com";
            $user->password = "admin";

            $nomes = explode(' ', $request->name);
            $nomeFormatado = "";

            foreach ($nomes as $nome)
                $nomeFormatado .=  ucwords($nome) . ' ';

            if ($device != null) {
                return  ServerController::delete($user, "devices/" . $request->id);
            }
    }

    function listDevicesUser($userId)
    {
        return UserDevice::where('userId', $userId)->with('device')->get();
    }

    function listUsersDevice($deviceId)
    {
        return UserDevice::where('deviceId', $deviceId)->with('user')->get();
    }

    function associarDriver(Request $request)
    {
        $driver = Device::with('drivers')->firstWhere('id', '=', $request->id);
        $driver->drivers()->detach();
        $driver->drivers()->attach($request->drivers);
    }

    function associarGeofence(Request $request)
    {
        $driver = Device::with('geofences')->firstWhere('id', '=', $request->id);
        $driver->geofences()->detach();
        $driver->geofences()->attach($request->geofences);
    }

    function associarNotificacao(Request $request)
    {
        $driver = Device::with('notificacoes')->firstWhere('id', '=', $request->id);
        $driver->notificacoes()->detach();
        $driver->notificacoes()->attach($request->notificacoes);
    }
}
