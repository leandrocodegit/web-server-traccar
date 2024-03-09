<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Exceptions\NoTypeDetectedException;
use InvalidArgumentException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;


class Handler extends ExceptionHandler
{

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'errors' => array('Recurso não encontrado!'),
                ], 404);
            }else{
                return response(view('angular'), 200);
            }
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'errors' => array('Recurso não encontrado!')
                ], 404);
            }
        });

        $this->renderable(function (QueryException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'errors' => array('Falha na requisição!', $e->errorInfo),
                    'codigo' => $e->getCode()
                ], 404);
            }
        });


        $this->renderable(function (InvalidArgumentException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'errors' => array('Formato inválido!')
                ], 404);
            }
        });

        $this->renderable(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'errors' => array('A requisição não foi processada!')
                ], 422);
            }
        });

 

    }
}
