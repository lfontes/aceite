<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'clientes';

    protected $fillable = ['cod_fca','nombre','domicilio','telefono','email','contacto'];
	
}
