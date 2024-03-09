<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Illuminate\Support\Facades\Log;


class JWTValidade extends BaseMiddleware
{
 
    public function handle(Request $request, Closure $next, ...$roles)
    {
        try { 

            $output = new \Symfony\Component\Console\Output\ConsoleOutput();
            
            $token = JWTAuth::parseToken();    
            $user = $token->authenticate();
            

            $payload = auth()->payload();
            $isValidRole = false;

            foreach ($roles as $role) {
                if($user->perfil->role == $role){
                    $isValidRole = true; 
                }                 
            }

            if($isValidRole == false)
                return $this->unauthorized('Usuário sem permissão!');
       
            } catch (TokenExpiredException $e) {       
                return $this->unauthorized('Token expirado!');
            } catch (TokenInvalidException $e) { 
                return $this->unauthorized('Token inválido!');
            }catch (JWTException $e) { 
                return $this->unauthorized('Não autorizado!');
            }             
            return $next($request); 
        
            return $this->unauthorized();
        }
    
        private function unauthorized($message = null){
            return response()->json([
                'message' => $message ? $message : 'Não autorizado!'
            ], 401);
        }
    }