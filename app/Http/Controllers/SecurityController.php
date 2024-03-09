<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Account\User;
use App\Models\Account\TokenAccess;  
use App\Jobs\EnviarEmail;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;
use Throwable;


class SecurityController extends Controller
{
    public function forgot(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'bail|required' 
          ],
          [
              'email.required' => 'Email é obrigatório!' 
          ]);
      
          if ($validator->fails())
              return response()->json(['errors' => $validator->messages(), 'status' => 400], 400);
    
        if (User:: where('email', $request -> email)
          -> where('active', '=', true)
          -> exists()) {
          $user = User:: where('email', $request -> email) -> first();
          $tokenAcess = TokenAccess:: create([
            'user_id' => $user -> id,
            'tipo' => 'RESET',
            'token' => Str:: random(254),
            'validade' => Carbon:: now() -> addMinutes(10)
          ]);
          EnviarEmail::dispatch($user, $tokenAcess, 'RESET');

          return response() -> json(['message' => 'Redefinição de senha enviada com sucesso!']);
        }
        return response() -> json(['message' => 'Usuário não encontrado ou inativo']);
      }
    
      public function resend(Request $request) {
    
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required' 
          ],
          [
              'email.required' => 'Email é obrigatório!' 
          ]);
      
          if ($validator->fails())
              return response()->json(['errors' => $validator->messages(), 'status' => 400], 400);
    
        if (User:: where('email', $request -> email)
          -> where('active', '=', false)
          -> exists()) {
          $user = User:: where('email', $request -> email) -> first();
          $tokenAcess = TokenAccess:: create([
            'user_id' => $user -> id,
            'tipo' => 'ACTIVE',
            'token' => Str:: random(254),
            'validade' => Carbon:: now() -> addMinutes(10)
          ]);
          EnviarEmail:: dispatch($user, $tokenAcess, 'CHECK');
          return response() -> json(['message' => 'Ativação enviada com sucesso!']);
        }
        return response() -> json(['message' => 'Usuário não encontrado ou ativo'], 400);
      }
    
      public function reset(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'bail|required',
            'token' => 'bail|required',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase(1)->symbols(1)->numbers(1)] 
          ],
          [
            'id.required' => 'Id é obrigatório!',
            'token.required' => 'Token é inválido!'
          ]);
      
          if ($validator->fails())
              return response()->json(['errors' => $validator->messages(), 'status' => 400], 400);    
  
        if (TokenAccess:: where('user_id', $request -> id)
          -> where('token', $request -> token)
          -> where('validade', '>=', Carbon:: now())
          -> where('active', false)
          -> exists()) {
    
          if ($user = User:: firstWhere('id', $request -> id)
          -> where('active', true)
          -> where('email_verificado', true) -> exists()) {
            User:: firstWhere('id', $request -> id)
              -> update(['password' => Hash:: make($request -> password)]);

              TokenAccess:: where(['token' => $request -> token])
            -> delete ();

            Log::channel('db')->info(
              'Reset senha de usuario ' .$request -> id);  
            return response() -> json(['message' => 'Senha alterada com sucesso!'], 200);
          }             
        }

        Log::channel('db')->info(
          'Reset de senha de usuario não concluida para o usuario id ' .$request -> id); 
        return response()->json(['errors' => 'Falha ao atualiza senha!', 'status' => 400], 400);
      }

      public function editPassword(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required', 
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase(1)->symbols(1)->numbers(1)] 
          ],
          [
            'id.required' => 'Id é obrigatório!', 
          ]);
      
          if ($validator->fails())
              return response()->json(['errors' => $validator->messages(), 'status' => 400], 400);    
   
          if (User:: firstWhere('id', $request -> id) -> exists()) {
            User:: firstWhere('id', $request -> id)
              -> update(['password' => Hash:: make($request -> password)]);

              Log::channel('db')->info(
                'Alterado senha de usuario ' .$request -> id. ' com usuario ' . auth()->user()->nome. ' e previlégios ' .auth()->user()->perfil->role); 

           return response() -> json(['message' => 'Senha alterada com sucesso!'], 200);
          }
    
          Log::channel('db')->info(
            'Alteração de senha de usuario não concluida ' .$request -> id. ' com usuario ' . auth()->user()->nome. ' e previlégios ' .auth()->user()->perfil->role); 
        return response()->json(['errors' => 'Falha ao atualiza senha!', 'status' => 400], 400);
      }
    
      public function valid($id, $token) {
        if (TokenAccess:: where('user_id', $id)
          -> where('token', $token)
          -> where('validade', '>=', Carbon:: now())
          -> where('email_verificado', '=', false)
          -> exists()) {
          return view('reset-password', ['id' => $id, 'token' => $token]);
        }
        abort(404);
      }
    
      public function active($id, $token)
      {
          if (TokenAccess::where('user_id', $id)
          ->where('token', $token)
          ->where('validade', '>=', Carbon::now())
          ->where('email_verificado', '=', false)
          ->exists()){
    
              if (User::firstWhere('id', $id)->exists()){
                  User::firstWhere('id', $id)
                      ->update(['email-verificado' => true]);
              }
    
              TokenAccess::where(['token' => $token])
              ->update(['active' => true]);

              Log::channel('db')->info(
                'Ativação de usuario ' .$id. ' com usuario ' . auth()->user()->nome. ' e previlégios ' .auth()->user()->perfil->role); 

              return view('active-account');       
          }

          Log::channel('db')->info(
            'Ativação de usuario não concluida ' .$id. ' com usuario ' . auth()->user()->nome. ' e previlégios ' .auth()->user()->perfil->role); 

          return 'Link expirou ou é inválido!'; 
      }    
}
