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
    public $selected_id, $keyWord, $cliente_id, $tipo_mov, $detalle, $cantidad, $fecha;
    public $updateMode = false;

    public function subtotal()
    {
        return DB::table('movimientos')->sum('cantidad');
    }

    public function render()
    {
        $keyWord = '%' . $this->keyWord . '%';

        return view('livewire.movimientos.view', [
            'movimientos' => Movimiento::latest()
                ->orWhere('cliente_id', 'LIKE', $keyWord)
                ->orWhere('detalle', 'LIKE', $keyWord)
                ->orWhere('fecha', 'LIKE', $keyWord)
                ->orWhereHas('cliente', function ($query) use ($keyWord) {
                    $query->where('nombre', 'LIKE', $keyWord);
                })
                ->paginate(25),
            'clientes' => Cliente::orderBy('nombre', 'asc')->get(),
            'tott' => Movimiento::where('cliente_id', 'LIKE', $keyWord)
                ->orWhereHas('cliente', function ($query) use ($keyWord) {
                    $query->where('nombre', 'LIKE', $keyWord);
                })
                ->sum('cantidad')
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
