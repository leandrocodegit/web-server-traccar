<?php

namespace App\Models\Traccar;

use App\Models\Account\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    use HasFactory;

    protected $table = "tc_shares";

     protected $fillable = [
        'id',
        'deviceId',
        'userId',
        'contaOrigem',
        'contaDestino',
        'expirated_at',
        'ativo',
        'aceitar'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'aceitar' => 'boolean',
    ];


    public function device(){
        return $this->belongsTo(Device::class, 'deviceId');
    }

    public function user(){
        return $this->belongsTo(User::class, 'userId');
    }

}
