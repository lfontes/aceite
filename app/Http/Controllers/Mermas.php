<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use App\Models\Movimiento;

class Mermas extends Controller
{
    public function index() {
         $clientes = Cliente::all();
        return view('mermas.index', compact('clientes'));
    }

    public function aplicaratodos(Request $request){
        
         if ($request->todos == "on") {
             $clientes = Cliente::all();
             //dd($clientes);
             foreach ($clientes as $cliente) {
                $request->cliente_id = $cliente->id;
                $this->mermasstore($request);
             }
         } else {
             $this->mermasstore($request);
         }
         return redirect('/movimientos');
    }

    public function mermasstore(Request $request){
        
        $cliente_stock = DB::table('movimientos')
                ->where('cliente_id', $request->cliente_id)
                ->sum('cantidad'); 

       // $cliente_stock = Movimiento::stock($request->cliente_id);
        $cantidad =  - ($cliente_stock * intval($request->input('porcentaje')) /100);
       //dd($request->cliente_id, $cliente_stock ,$request->porcentaje, $cantidad);
        
        Movimiento::create([ 
			'cliente_id' => $request-> cliente_id,
			'tipo_mov' => "baja",
			'detalle' => $request-> detalle,
			'cantidad' => $cantidad,
			'fecha' => today()
        ]);
        return redirect('/movimientos');
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
