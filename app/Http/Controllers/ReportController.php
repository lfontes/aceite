<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\Movimiento;
use App\Models\Cliente;

class ReportController extends Controller
{
    public function saldos()
    {
        
           
        $clientes = Cliente::withSum('movimientos as total','cantidad')
                ->groupBy('id')    
                ->get();
        //$clientes = Movimiento::sum('cantidad as total')->groupBy('cliente_id')->get();
        //$clientes = Cliente::All();
        
        $data = [
            'title' => 'Reporte de saldos por cliente',
            'date' => date('m/d/Y'),
            'saldos' => $clientes
         
        ];

    $pdf = PDF::loadView('reportes.saldosreport', $data)
    ->setPaper('A4', 'portrait')
    ->set_option('DefaultFont', 'Helvetica');

    return $pdf->stream();
   //return $pdf->download('saldosreport.pdf');
    }
}
