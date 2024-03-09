<?php

namespace App\Http\Controllers\Traccar;

use App\Http\Controllers\Controller;
use App\Models\Account\Conta;
use App\Models\Account\User;
use App\Models\Traccar\Notificacao;
use App\Models\Traccar\Share;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContaController extends Controller
{

    function user($id)
    {
        return User::firstWhere('id', $id);
    }


    function find($contaid)
    {
        return Conta::firstWhere('id', $contaid);
    }

    function geofences($contaid)
    {
        return Conta::with('geofences')->firstWhere('id', $contaid)->geofences()->get();
    }

    function devices($contaid)
    {
        return Conta::with('devices')->firstWhere('id', $contaid)->devices()->get();
    }

    function rotinas($contaid)
    {
        return Conta::with('rotinas')->firstWhere('id', $contaid)->rotinas()->get();
    }

    function users($contaid, $readonly)
    {
        if ($readonly == "true")
            return DB::table('tc_contas')
                ->join('tc_users', 'tc_contas.id', '=', 'tc_users.conta')
                ->where('readonly', true)
                ->select('tc_contas.id', 'tc_users.*')
                ->get();
        return Conta::with('users')->firstWhere('id', $contaid)->users()->get();
    }

    function drivers($contaid)
    {
        return Conta::with('drivers')->firstWhere('id', $contaid)->drivers()->get();
    }

    function compartilhados($contaid)
    {
        return Share::with('device','user')
        ->where('contaOrigem', $contaid)
        ->orWhere('contaDestino', $contaid)
        ->get();
    }

    function notificacoes()
    {
        return Notificacao::orderBy('nome')->get();
    }

    function removerCompartilhado($conta, $user, $device)
    {
        Share::where("contaOrigem", $conta)
            ->where("userId", $user)
            ->where("deviceId", $device)
            ->delete();
    }

    function alterarAtivoCompartilhado(Request $request)
    {
        $share = Share::firstWhere("id", $request->id);

        if ($share != null) {
            $share->update([
                "ativo" => $request->ativo
            ]);
        }
    }


    function salvarShare(Request $request)
    {

        $user = User::firstWhere("email", $request['user.email']);

        $request['userid'] = 1;

        if ($user != null) {
            Share::create([
                "contaOrigem" => $request->contaOrigem,
                "contaDestino" => $request->contaDestino,
                "userId" => $user->id,
                "deviceId" => $request->deviceId
            ]);
            return response()->json(['errors' => 'Solicitação enviada para ' . $user->name, 'status' => 200], 200);
        }

        return response()->json(['errors' => 'Email não encontrado ou inativo', 'status' => 400], 400);
    }

    function editarShare(Request $request)
    {

        $share = Share::firstWhere("id", $request->id);

        $request['userid'] = 1;

        if ($share == null) {
            Share::create([
                "conta" => $request->conta,
                "userId" => $request->userId,
                "deviceId" => $request->deviceId,
                "ativo" => $request->ativo
            ]);
        } else {
            $share->update([
                "id" => $request->id,
                "conta" => $request->conta,
                "userId" => $request->userId,
                "deviceId" => $request->deviceId,
                "ativo" => $request->ativo
            ]);
        }
    }
}
