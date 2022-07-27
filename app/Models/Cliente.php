<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Cliente extends Model
{
	use HasFactory, LogsActivity;

    public $timestamps = true;

    protected $table = 'clientes';

    protected $fillable = ['cod_fca','nombre','domicilio','telefono','email','contacto','rut'];


// opciones de log 
    protected static $recordEvents = ['created','deleted','updated'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['nombre', 'email'])
        ->dontSubmitEmptyLogs();
        // Chain fluent methods for configuration options
    }

    /**
     * Get all of the movimientos for the Cliente
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'cliente_id', 'id');
    }
	
}
