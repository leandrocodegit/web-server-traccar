<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Account\User;
use GuzzleHttp\Client;
use Illuminate\Http\Response;



class AuthController extends Controller
{

    private $expires = 30;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {

        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $output->write($request);


        return $this->auth($request);

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);


        $credentials = $request->only(['email', 'hashedpassword']);

        if (!auth()->validate($credentials))
            return response()->json(['error' => 'Unauthorized'], 401);

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    private function auth(Request $request){
        $client = new Client();


        try {

            $response = $client->request('POST', 'http://localhost:8082/api/session', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    "Access-Control-Allow-Headers" => "origin, content-type, accept, authorization",
                    "Access-Control-Allow-Origin" => "*",
                    "Access-Control-Allow-Methods" => "GET, POST, PUT, DELETE, OPTIONS"
                ],
                'form_params' => [
                    'email' => $request->email,
                    'password' => $request->password,
                ],
            ]);

           // return $response->getHeaders();
            // Obtenha o corpo da resposta como uma string JSON
            $data = $response->getBody()->getContents();

            $output = new \Symfony\Component\Console\Output\ConsoleOutput();
            $output->write( "##### ");

            $responses = new Response($data);

// Adicionar um cookie à resposta
            $responses->cookie('JSESSIONID', substr($response->getHeaders()['Set-Cookie'][0], 11, strlen($response->getHeaders()['Set-Cookie'][0])),1000);

            return $responses;

            // Decodifique a string JSON em um objeto ou array PHP
           return  json_decode($data, true); // Use true para obter um array associativo
        } catch (\Exception $e) {
            return $e;
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'type' => 'Bearer',
            'validate' => $this->expires
        ]);
    }


}
