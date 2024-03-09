<?php

namespace App\Models\Traccar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

 enum Dias:string
{
    case Direct = 1;
    case Corporate = 2;
}
