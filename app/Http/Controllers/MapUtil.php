<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;

class MapUtil
{

    public function __construct()
    {
    }

    public static function format(MessageBag $erros)
    {
        return collect($erros)->map(function (array $name) {
            return ($name[0]);
        })->groupBy('nome')->first();
    }

    public static function merge(Collection $list, string $atr1, string $atr2)
    {
        $colect = collect($list)->map(function ($descricao) use ($atr1, $atr2) {
            return $descricao[$atr1] . ' ' . $descricao[$atr2];
        })->all();

        return implode($colect);
    }

}
