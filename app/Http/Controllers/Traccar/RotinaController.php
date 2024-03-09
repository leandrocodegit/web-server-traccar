<?php

namespace App\Http\Controllers\Traccar;

use App\Http\Controllers\Controller;
use App\Models\Account\User;
use App\Models\Traccar\Device;
use App\Models\Traccar\DiaExcecao;
use App\Models\Traccar\Rotina;
use App\Models\Traccar\Geofence;
use App\Models\Traccar\Notificacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RotinaController extends Controller
{

    function find($id)
    {
        return Rotina::with('excecoes')->firstWhere('id', $id);
    }

    function store(Request $request)
    {

        $rotina = Rotina::firstWhere('id', '=', $request->id);

        $request['userid'] = 1;

        if ($rotina == null) {
            $rotina =  Rotina::create([
                'nome' => $this->criarMensagem($request),
                'notificacaoId' => $request->notificacaoId,
                'horaInicial' => $request->horaInicial,
                'horaFinal' => $request->horaFinal,
                'ativo' => $request->ativo,
                'conta' => $request->conta,
            ]);
        } else {
            $rotina->update([
                'id' => $request->id,
                'nome' => $this->criarMensagem($request),
                'notificacaoId' => $request->notificacaoId,
                'horaInicial' => $request->horaInicial,
                'horaFinal' => $request->horaFinal,
                'ativo' => $request->ativo,
            ]);
        }

        $rotina->device()->associate($request['deviceid']);
        $rotina->geofence()->associate($request['geofenceId']);
        $rotina->save();

        $rotina->diasSemana()->detach();
        $rotina->diasSemana()->attach($request->dias);

        $rotina->excecoes()->delete();
        $rotina->excecoes()->createMany($request->excecoes);
    }

    function listGeofencesDevice($deviceId)
    {
        return Device::where('id', $deviceId)->with('geofences')->get();
    }

    function listRotinasUsers($userid, $order)
    {

        if ($order == 'hora')
            return Rotina::where('userid', $userid)->with('geofence', 'dias', 'device', 'excecoes')
                ->orderBy('horaInicial')
                ->orderBy('horaFinal')
                ->get();
        if ($order == 'nome')
            return Rotina::where('userid', $userid)->with('geofence', 'dias', 'device', 'excecoes')
                ->orderBy('nome')
                ->get();
    }

    function remover($rotinaId)
    {
        Rotina::destroy($rotinaId);
    }

    function listGeofencesUser(Request $request)
    {
        return DB::table('tc_users')
            ->join('tc_user_geofence', 'tc_users.id', '=', 'tc_user_geofence.userid')
            ->join('tc_geofences', 'tc_user_geofence.geofenceid', '=', 'tc_geofences.id')
            ->select('tc_geofences.*')
            ->where('tc_users.id', '=', $request->userid)
            ->where('tc_geofences.type', '=', $request->tipoGeofence)
            ->get();
    }

    private function criarMensagem(Request $request)
    {
        $nome = $request['device.responsavel.name'];
        $geofence = Geofence::find($request['geofenceId']);
        $notificacao = Notificacao::find($request['notificacaoId']);

        $tipo = "Saída de ";
        $preposicao = " de ";
        if ($notificacao->type == 'geofenceEnter'){
            $preposicao = " em ";
            $tipo = "Entrada de ";
        }

        return $tipo . ($nome =! null ? explode(' ', $nome)[0] : '') . $preposicao . strtolower($geofence->name);
    }
}
