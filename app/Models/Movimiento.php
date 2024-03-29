<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Movimiento extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'movimientos';

    protected $fillable = ['cliente_id','tipo_mov','detalle','cantidad','fecha'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id','id');
    }


	
}
