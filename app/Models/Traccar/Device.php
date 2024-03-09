<?php

namespace App\Models\Traccar;

use App\Models\Account\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = "tc_devices";

    protected $hidden = [
        'pivot'
    ];

    protected $casts = [
        'disabled' => 'boolean',
    ];

    protected $fillable = [
        'id',
        'name',
        'category',
        'disabled',
        'responsavelId'
    ];

    protected $appends = array('share');

    public function position(){
        return $this->belongsTo(Position::class, 'positionid');
    }

    public function group(){
        return $this->belongsTo(Group::class, 'groupid');
    }

    public function calendar(){
        return $this->belongsTo(Calendar::class, 'calendarid');
    }

    public function drivers(){
        return $this->belongsToMany(Driver::class, 'tc_device_driver','deviceid', 'driverid');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'tc_user_device','deviceid', 'userid');
    }

    public function geofences(){
        return $this->belongsToMany(Geofence::class, 'tc_device_geofence','deviceid', 'geofenceid');
    }

    public function events(){
        return $this->hasMany(Event::class, 'deviceid');
    }

    public function responsavel(){
        return $this->belongsTo(User::class, 'responsavelId');
    }

    public function notificacoes(){
        return $this->belongsToMany(Notificacao::class, 'tc_device_notification','deviceid', 'notificationid');
    }

    public function compartilhados(){
        return $this->hasMany(Share::class, 'deviceId')->with('device','user');
    }

    public function getShareAttribute()
    {
        return $this->compartilhados()->exists();
    }



}
