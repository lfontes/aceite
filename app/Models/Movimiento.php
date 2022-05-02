<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'movimientos';

    protected $fillable = ['cliente_id','tipo_mov','detalle','cantidad','fecha'];

    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }
	
}
