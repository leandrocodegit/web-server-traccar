<?php

namespace App\Models\Traccar;

use App\Models\Account\Conta;
use App\Models\Account\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geofence extends Model
{
    use HasFactory;

    protected $table = "tc_geofences";
    protected $appends = array('rotinas');


    protected $fillable = [
        'id',
        'name',
        'description',
        'area',
        'telefone',
        'conta'
    ];

    protected $hidden = [
        'pivot'
    ];

    public function getRotinasAttribute()
    {
        return $this->rotinas()->count();
    }

    public function conta(){
        return $this->belongsTo(Conta::class, 'conta');
    }

    public function calendar(){
        return $this->belongsTo(Calendar::class, 'calendarid');
    }

    public function users(){
        return $this->belongsToMany(User::class,'tc_user_geofence', 'geofenceid', 'userid');
    }

    public function responsavel(){
        return $this->belongsTo(User::class, 'responsavelid');
    }

    public function rotinas(){
        return $this->hasMany(Rotina::class, 'geofenceid');
    }
}
