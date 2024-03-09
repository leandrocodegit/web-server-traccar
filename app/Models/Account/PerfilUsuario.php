<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilUsuario extends Model
{
    use HasFactory;

    protected $table = 'perfil_usuario';

    protected $fillable = [
        'id',
        'role',
        'nome'
    ];

    public function usuarios(){
        return $this->hasMany(Usuario::class, 'perfil_id');
    }
}
