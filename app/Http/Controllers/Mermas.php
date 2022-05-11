<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Movimiento;

class Mermas extends Controller
{
    public function index() {
         $clientes = Cliente::all();
        return view('mermas.index', compact('clientes'));
    }



    public function mermasstore(Request $request){
        
        $cliente_stock = Movimiento::find(1)->sum('cantidad');      
        $cantidad =  - ($cliente_stock * intval($request->input('porcentaje')) /100);
       //dd($cliente_stock ,$request->porcentaje, $cantidad);
        
        Movimiento::create([ 
			'cliente_id' => $request-> cliente_id,
			'tipo_mov' => "baja",
			'detalle' => $request-> detalle,
			'cantidad' => $cantidad,
			'fecha' => today()
        ]);
    }

    /*  TO DO
        select cliente
        input porcentaje
        input detalle
        hidden input tipo_mov="baja"
        hidden input fecha = hoy()

        funcion cantidad
            stock_cliente = cliente->stock() **hacerlo en el model movimiento
            cantidad = stock_cliente - (stock_cliente * porcentaje / 100)


        store (request)
    */
}
