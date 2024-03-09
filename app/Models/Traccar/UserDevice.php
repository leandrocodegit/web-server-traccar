<?php

namespace App\Models\Traccar;

use App\Models\Account\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;

    protected $table = "tc_user_device";

    protected $fillable = [
        'userid',
        'deviceid'
        ];

    protected $hidden = [
        'userid',
        'deviceid'
    ];

    public function device(){
        return $this->belongsTo(Device::class, 'deviceid')->with('position','drivers', 'events', 'geofences');
    }

    public function user(){
        return $this->belongsTo(User::class, 'userid');
    }

}
