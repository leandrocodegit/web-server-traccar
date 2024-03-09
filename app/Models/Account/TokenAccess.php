<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'tipo',
        'active',
        'token',
        'validade' 
    ];
 
    protected $table = "token_access";
}
