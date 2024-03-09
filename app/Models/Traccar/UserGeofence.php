<?php

namespace App\Models\Traccar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGeofence extends Model
{
    use HasFactory;
    protected $table = "tc_user_geofence";

    protected $fillable = [
        'userid',
        'geofenceid'
        ];

    protected $hidden = [
        'userid',
        'geofenceid'
    ];

    public function geofences(){
        return $this->belongsTo(Geofence::class, 'geofenceid');
    }

    public function user(){
        return $this->belongsTo(User::class, 'userid');
    }
}
