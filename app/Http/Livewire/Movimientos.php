<?php

namespace App\Http\Livewire;

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
    
    public function subtotal() {
        return DB::table('movimientos')->sum('cantidad');
    }

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
       

        return view('livewire.movimientos.view', [
            'movimientos' => Movimiento::latest()
						->orWhere('cliente_id', 'LIKE', $keyWord)
						//->orWhere('tipo_mov', 'LIKE', $keyWord)
						->orWhere('detalle', 'LIKE', $keyWord)
						//->orWhere('cantidad', 'LIKE', $keyWord)
						//->orWhere('fecha', 'LIKE', $keyWord)
                        ->orWhereHas('cliente',function($query) use($keyWord){
                            $query->where('nombre','LIKE', $keyWord);
                        })
						->paginate(25),
            'clientes' => Cliente::all(),
            'tott' => Movimiento::where('cliente_id', 'LIKE', $keyWord)
                                ->orWhereHas('cliente',function($query) use($keyWord){
                                    $query->where('nombre','LIKE', $keyWord);
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
		'cantidad' => 'required',
		'fecha' => 'required',
        ]);

        if ($this->tipo_mov == "Salida") {
            $this->cantidad = $this->cantidad * -1;
        }

        Movimiento::create([ 
			'cliente_id' => $this-> cliente_id,
			'tipo_mov' => $this-> tipo_mov,
			'detalle' => $this-> detalle,
			'cantidad' =>  $this-> cantidad,
			'fecha' => $this-> fecha
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Movimiento Successfully created.');
    }

    public function edit($id)
    {
        $record = Movimiento::findOrFail($id);

        $this->selected_id = $id; 
		$this->cliente_id = $record-> cliente_id;
		$this->tipo_mov = $record-> tipo_mov;
		$this->detalle = $record-> detalle;
		$this->cantidad = $record-> cantidad;
		$this->fecha = $record-> fecha;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'cliente_id' => 'required',
		'tipo_mov' => 'required',
		'detalle' => 'required',
		'cantidad' => 'required',
		'fecha' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Movimiento::find($this->selected_id);
            $record->update([ 
			'cliente_id' => $this-> cliente_id,
			'tipo_mov' => $this-> tipo_mov,
			'detalle' => $this-> detalle,
			'cantidad' => $this-> cantidad,
			'fecha' => $this-> fecha
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Movimiento Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Movimiento::findOrFail($id);
            $record->delete();
        }
    }

    
}
