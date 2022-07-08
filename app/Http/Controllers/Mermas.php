<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use App\Models\Movimiento;

class Mermas extends Controller
{
    public $multi_cliente = [];
    public function index() {
        
         $clientes = Cliente::all();
        return view('mermas.index', compact('clientes'));
    }

    public function aplicaratodos(Request $request){
        
         if ($request->todos == "on") {
             $clientes = Cliente::all();
             foreach ($clientes as $cliente) {
                 $this->mermasstore($request, $cliente->id);
             }
         } else {
            $input = $request->all();
            foreach ($input['multi_cliente'] as $cliente) {
                $this->mermasstore($request, $cliente);
             }
         }
         return redirect('/movimientos');
    }

    public function mermasstore(Request $request, $cliente){
        
        $cliente_stock = DB::table('movimientos')
                ->where('cliente_id', $cliente)
                ->sum('cantidad'); 

       if ($cliente_stock > 0) {
        $cantidad =  - ($cliente_stock * intval($request->input('porcentaje')) /100);
        
        Movimiento::create([ 
			'cliente_id' => $cliente,
			'tipo_mov' => "Salida",
			'detalle' => $request-> detalle,
			'cantidad' => $cantidad,
			'fecha' => today()
        ]);
    }
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
