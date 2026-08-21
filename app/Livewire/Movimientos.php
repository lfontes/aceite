<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\Movimiento;
use App\Models\Cliente;

class Movimientos extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Model and form properties
    public $selected_id, $cliente_id, $tipo_mov, $detalle, $cantidad, $fecha;
    public $updateMode = false;

    // Filter properties
    public $keyWord = '';
    public $filtro_cliente_id = '';
    public $filtro_tipo_mov = '';
    public $fecha_desde = '';
    public $fecha_hasta = '';

    public function updatedKeyWord()
    {
        $this->resetPage();
    }

    public function updatedFiltroClienteId()
    {
        $this->resetPage();
    }

    public function updatedFiltroTipoMov()
    {
        $this->resetPage();
    }

    public function updatedFechaDesde()
    {
        $this->resetPage();
    }

    public function updatedFechaHasta()
    {
        $this->resetPage();
    }

    public function limpiarFiltros()
    {
        $this->keyWord = '';
        $this->filtro_cliente_id = '';
        $this->filtro_tipo_mov = '';
        $this->fecha_desde = '';
        $this->fecha_hasta = '';
        $this->resetPage();
    }

    public function getMovimientosQuery()
    {
        $query = Movimiento::with('cliente');

        // Filter by Client
        if (!empty($this->filtro_cliente_id)) {
            $query->where('cliente_id', $this->filtro_cliente_id);
        }

        // Filter by Movement Type (Ingreso / Salida)
        if (!empty($this->filtro_tipo_mov)) {
            $query->where('tipo_mov', $this->filtro_tipo_mov);
        }

        // Filter by Date Range (Desde / Hasta)
        if (!empty($this->fecha_desde)) {
            $query->whereDate('fecha', '>=', $this->fecha_desde);
        }

        if (!empty($this->fecha_hasta)) {
            $query->whereDate('fecha', '<=', $this->fecha_hasta);
        }

        // Search Keyword
        if (!empty($this->keyWord)) {
            $keyWord = '%' . trim($this->keyWord) . '%';
            $query->where(function ($q) use ($keyWord) {
                $q->where('detalle', 'ILIKE', $keyWord)
                  ->orWhere('tipo_mov', 'ILIKE', $keyWord)
                  ->orWhereRaw('CAST(cliente_id AS TEXT) ILIKE ?', [$keyWord])
                  ->orWhereHas('cliente', function ($cq) use ($keyWord) {
                      $cq->where('nombre', 'ILIKE', $keyWord)
                         ->orWhere('cod_fca', 'ILIKE', $keyWord);
                  });
            });
        }

        return $query;
    }

    public function render()
    {
        $baseQuery = $this->getMovimientosQuery();

        $tott = (clone $baseQuery)->sum('cantidad');

        $movimientos = (clone $baseQuery)
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(25);

        return view('livewire.movimientos.view', [
            'movimientos' => $movimientos,
            'clientes' => Cliente::orderBy('nombre', 'asc')->get(),
            'tott' => number_format($tott, 2, '.', ''),
        ]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    private function resetInput()
    {
        $this->selected_id = null;
        $this->cliente_id = null;
        $this->tipo_mov = null;
        $this->detalle = null;
        $this->cantidad = null;
        $this->fecha = null;
    }

    public function store()
    {
        $this->validate([
            'cliente_id' => 'required',
            'tipo_mov' => 'required',
            'detalle' => 'required',
            'cantidad' => 'required|numeric',
            'fecha' => 'required|date',
        ]);

        $cantidad = floatval($this->cantidad);
        if ($this->tipo_mov == "Salida" && $cantidad > 0) {
            $cantidad = $cantidad * -1;
        } elseif ($this->tipo_mov == "Ingreso" && $cantidad < 0) {
            $cantidad = abs($cantidad);
        }

        Movimiento::create([
            'cliente_id' => $this->cliente_id,
            'tipo_mov' => $this->tipo_mov,
            'detalle' => $this->detalle,
            'cantidad' =>  $cantidad,
            'fecha' => $this->fecha
        ]);

        $this->resetInput();
        $this->dispatch('closeModal');
        session()->flash('message', 'Movimiento creado con éxito.');
    }

    public function edit($id)
    {
        $record = Movimiento::findOrFail($id);

        $this->selected_id = $id;
        $this->cliente_id = $record->cliente_id;
        $this->tipo_mov = $record->tipo_mov;
        $this->detalle = $record->detalle;
        $this->cantidad = abs($record->cantidad);
        $this->fecha = $record->fecha;

        $this->updateMode = true;
        $this->dispatch('showUpdateModal');
    }

    public function update()
    {
        $this->validate([
            'cliente_id' => 'required',
            'tipo_mov' => 'required',
            'detalle' => 'required',
            'cantidad' => 'required|numeric',
            'fecha' => 'required|date',
        ]);

        if ($this->selected_id) {
            $cantidad = floatval($this->cantidad);
            if ($this->tipo_mov == "Salida" && $cantidad > 0) {
                $cantidad = $cantidad * -1;
            } elseif ($this->tipo_mov == "Ingreso" && $cantidad < 0) {
                $cantidad = abs($cantidad);
            }

            $record = Movimiento::findOrFail($this->selected_id);
            $record->update([
                'cliente_id' => $this->cliente_id,
                'tipo_mov' => $this->tipo_mov,
                'detalle' => $this->detalle,
                'cantidad' => $cantidad,
                'fecha' => $this->fecha
            ]);

            $this->resetInput();
            $this->updateMode = false;
            $this->dispatch('closeModal');
            session()->flash('message', 'Movimiento actualizado con éxito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Movimiento::findOrFail($id);
            $record->delete();
            session()->flash('message', 'Movimiento eliminado.');
        }
    }
}
