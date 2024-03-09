<?php

namespace App\Http\Controllers\Traccar;

use App\Http\Controllers\Controller;
use App\Models\Account\User;
use App\Models\Traccar\Device;
use Illuminate\Http\Request;


class PermissionsController extends Controller
{


    function associar(Request $request)
    {

        $user = new User();
        $user->email = "lpoliveira.ti@gmail.com";
        $user->password = "admin";

        return  ServerController::create($user, response()->json($request)->content(), "permissions");
    }

    function desassociar(Request $request)
    {

        $user = new User();
        $user->email = "lpoliveira.ti@gmail.com";
        $user->password = "admin";

        return  ServerController::remove($user, response()->json($request)->content(), "permissions");
    }
}
