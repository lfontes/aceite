<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use App\Models\Movimiento;

class Mermas extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('nombre', 'asc')->get();
        return view('mermas.index', compact('clientes'));
    }

    public function aplicaratodos(Request $request)
    {
        $request->validate([
            'porcentaje' => 'required|numeric|min:0.01|max:100',
            'detalle' => 'required|string|max:255',
            'multi_cliente' => 'required_without:todos|array',
            'multi_cliente.*' => 'exists:clientes,id',
            'todos' => 'nullable|in:on',
        ], [
            'multi_cliente.required_without' => 'Debe seleccionar al menos un cliente o marcar "Aplicar a todos".',
            'porcentaje.required' => 'El porcentaje es obligatorio.',
            'porcentaje.numeric' => 'El porcentaje debe ser un número.',
            'porcentaje.min' => 'El porcentaje debe ser mayor a 0.',
            'porcentaje.max' => 'El porcentaje no puede ser mayor a 100.',
            'detalle.required' => 'El detalle es obligatorio.',
        ]);

        if ($request->todos === "on") {
            $clientes = Cliente::all();
            foreach ($clientes as $cliente) {
                $this->mermasstore($request, $cliente->id);
            }
        } else {
            $clientes = $request->input('multi_cliente', []);
            foreach ($clientes as $clienteId) {
                $this->mermasstore($request, $clienteId);
            }
        }

        session()->flash('message', 'Mermas aplicadas con éxito.');
        return redirect('/movimientos');
    }

    public function mermasstore(Request $request, $cliente)
    {
        $cliente_stock = DB::table('movimientos')
            ->where('cliente_id', $cliente)
            ->sum('cantidad');

        if ($cliente_stock > 0) {
            $porcentaje = floatval($request->input('porcentaje'));
            $cantidad = - ($cliente_stock * $porcentaje / 100);

            Movimiento::create([
                'cliente_id' => $cliente,
                'tipo_mov' => "Salida",
                'detalle' => $request->detalle,
                'cantidad' => $cantidad,
                'fecha' => today()
            ]);
        }

        return redirect('/movimientos');
    }
}

