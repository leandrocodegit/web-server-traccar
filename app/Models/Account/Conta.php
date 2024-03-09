<?php

namespace App\Models\Account;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Catalogo\Catalogo;
use App\Models\Traccar\Share;
use App\Models\Traccar\Device;
use App\Models\Traccar\Driver;
use App\Models\Traccar\Geofence;
use App\Models\Traccar\Rotina;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    use HasFactory;

    protected $table = "tc_contas";

    protected $fillable = [
        'id',
        'status'
    ];

    public function devices(){
        return $this->hasMany(Device::class, 'conta')->with('position','drivers');
    }

    public function geofences(){
        return $this->hasMany(Geofence::class, 'conta');
    }

    public function rotinas(){
        return $this->hasMany(Rotina::class, 'conta')
        ->with('geofence', 'dias', 'device', 'excecoes', 'notificacao')
        ->orderBy('horaInicial')
        ->orderBy('horaFinal');
    }

    public function users(){
        return $this->hasMany(User::class, 'conta');
    }

    public function drivers(){
        return $this->hasMany(Driver::class, 'conta')->orderBy('name');
    }

    public function compartilhados(){
        return $this->hasMany(Share::class, 'contaOrigem')->with('device','user');
    }
}
