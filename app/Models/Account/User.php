<?php

namespace App\Models\Account;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Catalogo\Catalogo;
use App\Models\Traccar\Device;
use App\Models\Traccar\Geofence;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "tc_users";
    protected $casts = [
        'email_verified_at' => 'datetime',
        'readonly' => 'boolean',
        'administrator' => 'boolean',

    ];

    protected $fillable = [
        'name',
        'email',
        'hashedpassword',
        'salt',
        'readonly',
        'administrator',
        'map',
        'latitude',
        'longitude',
        'zoom',
        'twelvehourformat',
        'attributes',
        'coordinateformat',
        'disabled',
        'expirationtime',
        'devicelimit',
        'userlimit',
        'devicereadonly',
        'limitcommands',
        'login',
        'poilayer',
        'disablereports',
        'fixedemail'
    ];

    protected $hidden = [ 
        'remember_token',
        'perfil_id',
        'email_verificado'
    ];

    use Notifiable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'nome' => $this->nome,
            'email' => $this->email,
            'role' => $this->perfil->role
        ];
    }

    public function conta(){
        return $this->belongsTo(Conta::class, 'conta');
    }

    public function devices(){
        return $this->belongsToMany(Device::class, 'tc_user_device', 'userid', 'deviceid');
    }

    public function geofences(){
        return $this->belongsToMany(Geofence::class, 'tc_user_geofence', 'userid', 'geofenceid');
    }

}
