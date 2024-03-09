<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traccar\ServerController;
use App\Jobs\EnviarEmail;
use App\Models\Account\TokenAccess;
use App\Models\Account\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;


class UserController extends Controller
{

    function store(Request $request)
    {
        try {

            $device = User::firstWhere('id', $request->id);

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
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'bail|required',
                'name' => 'bail|required',
                'email' => 'bail|required',
                'conta' => 'bail|required',
            ],
            [
                'id.required' => 'Id é obrigatório!',
                'name.required' => 'Nome é obrigatório!',
                'email.required' => 'Email é obrigatório!',
                'conta.required' => 'Conta é obrigatório!',
            ]
        );

        if ($validator->fails())
            return response()->json(['errors' => $validator->messages(), 'status' => 400], 400);
        $user = User::firstWhere('id', $request->id);
        return $user;

        $user = new User();
        $user->email = "lpoliveira.ti@gmail.com";
        $user->password = "admin";

        $nomes = explode(' ', $request->name);
        $nomeFormatado = "";

        foreach ($nomes as $nome)
            $nomeFormatado .=  ucwords($nome) . ' ';

        if ($user != null) {
            return  ServerController::update($user, json_encode([
                'id' => $request->id,
                'name' => $nomeFormatado,
                'email' => $request->email,
                'conta' => 100,
                'phone' => $request->phone,
                'administrator' => $user->administrator,
            ]), "users/" . $request->id);
        }
    }


    public function editPassword(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',
                'password' => ['required', 'confirmed', Password::min(8)->mixedCase(1)->symbols(1)->numbers(1)]
            ],
            [
                'id.required' => 'Id é obrigatório!',
            ]
        );

        if ($validator->fails())
            return response()->json(['erros' => MapUtil::format($validator->messages()), 'status' => 400], 400);

        $user = new User();
        $user->email = "lpoliveira.ti@gmail.com";
        $user->password = "admin";

        if ($user != null) {
            return  ServerController::update($user, json_encode([
                'id' => $request->id,
                'password' => $request->password
            ]), "users/password");
        }
    }

    public function show($id)
    {
        $request = new Request([
            'id' => $id
        ]);
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'bail|numeric'
            ],
            [
                'id.numeric' => 'Id inválido!'
            ]
        );

        if ($validator->fails())
            return response()->json(['errors' => $validator->messages(), 'status' => 400], 400);

        return User::with('conta')->findOrFail($id);
    }

    public function search(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'bail|required'
            ],
            [
                'name.required' => 'Nome é obrigatório!'
            ]
        );

        if ($validator->fails())
            return response()->json(['errors' => $validator->messages(), 'status' => 400], 400);

        if ($request['name'] == "all")
            return DB::table('tc_users')->paginate(20);

        if (Str::length($request->name) > 2)
            return User::where('name', 'LIKE', '%' . $request->name . '%')
                ->orWhere('email', 'LIKE', '%' . $request->name . '%')
                ->simplePaginate(20);
        return response()->json(['message' => 'Necessário ao menos 3 caracteres!'], 201);
    }

    public function edit(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'bail|required',
                'email' => 'bail|required',
            ],
            [
                'name.required' => 'Nome é obrigatório!',
                'email.required' => 'Email é obrigatório!'
            ]
        );

        if ($validator->fails())
            return response()->json(['errors' => $validator->messages(), 'status' => 400], 400);

        try {
            $userAuth = auth()->user();

            if ($userAuth->perfil->role !== 'ROOT' && $userAuth->perfil->role !== 'ADMIN')
                if ($userAuth->id !== $request->id)
                    return response()->json(['errors' => 'Operação não permitida!', 'status' => 403], 403);

            $userDB = User::firstWhere('id', $request->id)
                ->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone
                ]);

            Log::channel('db')->info(
                'Editado usuario' . $request->email . ' com usuario ' . auth()->user()->nome . ' e previlégios ' . auth()->user()->perfil->role
            );

            return response()->json([
                'message' => 'Usuário atualizado com sucesso!',
                'status' => 200
            ], 200);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Falha ao atualizar cadastro!']);
        }
    }

    public function destroy($id)
    {

        try {
            $token = JWTAuth::parseToken();
            $userAuth = $token->authenticate();

            if ($userAuth->perfil->role === 'ROOT') {
                User::destroy($id);
                Log::channel('db')->info(
                    'Delete usuario' . $id . ' com usuario ' . auth()->user()->nome . ' e previlégios ' . auth()->user()->perfil->role
                );
            } else {
                Log::channel('db')->info(
                    'Delete não conlcuido usuario' . $id . ' com usuario ' . auth()->user()->nome . ' e previlégios ' . auth()->user()->perfil->role
                );
            }
        } catch (Throwable $e) {
            return response()->json(['error' => 'Falha ao remover cadastro!']);
        }
    }

    public function active($id)
    {
        $userAuth = auth()->user();

        if ($userAuth->perfil->id !== 1000 && $userAuth->perfil->id !== 2)
            return response()->json(['errors' => 'Operação não permitida', 'status' => 403], 403);

        $active = $this->show($id)->active ? false : true;

        User::findOrFail($id)
            ->update([
                'active' => $active
            ]);

        Log::channel('db')->info(
            'Alterado status de usuario ' . $active . ' ' . $id . ' com usuario ' . $userAuth->nome . ' e previlégios ' . $userAuth->perfil->nome
        );

        return response()->json(['active' => $active, 'status' => 200], 200);
    }
}
