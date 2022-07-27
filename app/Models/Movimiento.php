<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Movimiento extends Model
{
	use HasFactory, LogsActivity;
	
    public $timestamps = true;

    protected $table = 'movimientos';

    protected $fillable = ['cliente_id','tipo_mov','detalle','cantidad','fecha'];

    // opciones de log 
    protected static $recordEvents = ['created','deleted','updated'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['cliente_id','tipo_mov','detalle','cantidad','fecha'])
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
        // Chain fluent methods for configuration options
    }



    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'id','cliente_id');
    }

  
	
}
