<?php

namespace App\Http\Controllers\Traccar;

use App\Http\Controllers\Controller;
use App\Models\Account\User;
use App\Models\Traccar\Device;
use App\Models\Traccar\Driver;
use App\Models\Traccar\UserDevice;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Uuid;


class ServerController
{

     static $host = 'http://localhost:8082/api/';

    static function create(User $user, string $body, string $url)
    {
        try {

                $res =  Http::withHeaders([
                    'Authorization' => 'Basic ' . base64_encode($user->email . ':' . $user->password),
                    'Content-Type' => 'application/json',
                ])
                ->post(ServerController::$host . $url,  json_decode($body));
                return $res;
        } catch (\Exception $e) {
            return $e;
        }
    }


    static function update(User $user, string $body, string $url)
    {
        try {
                $res =  Http::withHeaders([
                    'Authorization' => 'Basic ' . base64_encode($user->email . ':' . $user->password),
                    'Content-Type' => 'application/json',
                ])
                ->put(ServerController::$host . $url, json_decode($body));
                return $res;
        } catch (\Exception $e) {
            return $e;
        }
    }

    static function delete(User $user, string $url)
    {
        try {
                $res =  Http::withHeaders([
                    'Authorization' => 'Basic ' . base64_encode($user->email . ':' . $user->password),
                    'Content-Type' => 'application/json',
                ])
                ->delete(ServerController::$host . $url);
                return $res;
        } catch (\Exception $e) {
            return $e;
        }
    }


    static function remove(User $user, string $body, string $url)
    {
        try {

                $res =  Http::withHeaders([
                    'Authorization' => 'Basic ' . base64_encode($user->email . ':' . $user->password),
                    'Content-Type' => 'application/json',
                ])
                ->delete(ServerController::$host . $url,  json_decode($body));
                return $res;
        } catch (\Exception $e) {
            return $e;
        }
    }

}
