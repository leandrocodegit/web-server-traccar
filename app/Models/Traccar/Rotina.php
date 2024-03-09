<?php

namespace App\Models\Traccar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Rotina extends Model
{
    use HasFactory;

    protected $table = "tc_rotinas";
    protected $appends = ['evento'];

    protected $fillable = [
        'id',
        'nome',
        'notificacaoId',
        'horaInicial',
        'horaFinal',
        'userid',
        'ativo',
        'conta'
    ];


    public function getEventoAttribute(){
        $data = DB::select('CALL VALIDA_EVENTO_ROTINA(? , ?)', [$this->id, 'rotinaIncompleta']);
        return $data != null ? $data[0] : null;
    }

    public function device(){
        return $this->belongsTo(Device::class, 'deviceid')->with('responsavel');
    }

    public function geofence(){
        return $this->belongsTo(Geofence::class, 'geofenceId');
    }

    public function dias(){
        return $this->hasMany(DiasSemana::class, 'rotinaid')->orderBy('dia');
    }

    public function excecoes(){
        return $this->hasMany(DiaExcecao::class,'rotinaid')->orderBy('dia');
    }

    public function eventos(){
        return $this->hasMany(Event::class, 'rotinaid');
    }

    public function diasSemana(){
        return $this->belongsToMany(DiasSemana::class,'tc_rotinas_dias_semana', 'rotinaid', 'dia')->orderBy('dia');
    }

    public function notificacao(){
        return $this->belongsTo(Notificacao::class, 'notificacaoId');
    }
}
