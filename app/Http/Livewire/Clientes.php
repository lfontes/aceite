<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cliente;

class Clientes extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $cod_fca, $nombre, $domicilio, $telefono, $email, $contacto, $rut;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.clientes.view', [
            'clientes' => Cliente::latest()
						->orWhere('cod_fca', 'LIKE', $keyWord)
						->orWhere('nombre', 'LIKE', $keyWord)
						->orWhere('domicilio', 'LIKE', $keyWord)
						->orWhere('telefono', 'LIKE', $keyWord)
						->orWhere('email', 'LIKE', $keyWord)
						->orWhere('contacto', 'LIKE', $keyWord)
                        ->orWhere('rut', 'LIKE', $keyWord)
                        ->orderby('nombre', 'ASC')
						->paginate(10),
                        
        ]);
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->cod_fca = null;
		$this->nombre = null;
		$this->domicilio = null;
		$this->telefono = null;
		$this->email = null;
		$this->contacto = null;
        $this->rut = null;
    }

    public function store()
    {
        $this->validate([
		'cod_fca' => 'required',
		'nombre' => 'required',
		'domicilio' => 'required',
		'telefono' => 'required',
		'email' => 'required',
		'contacto' => 'required',
        ]);

        Cliente::create([ 
			'cod_fca' => $this-> cod_fca,
			'nombre' => $this-> nombre,
			'domicilio' => $this-> domicilio,
			'telefono' => $this-> telefono,
			'email' => $this-> email,
			'contacto' => $this-> contacto,
            'rut' => $this-> rut
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Cliente Successfully created.');
    }

    public function edit($id)
    {
        $record = Cliente::findOrFail($id);

        $this->selected_id = $id; 
		$this->cod_fca = $record-> cod_fca;
		$this->nombre = $record-> nombre;
		$this->domicilio = $record-> domicilio;
		$this->telefono = $record-> telefono;
		$this->email = $record-> email;
		$this->contacto = $record-> contacto;
        $this->rut = $record-> rut;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'cod_fca' => 'required',
		'nombre' => 'required',
		'domicilio' => 'required',
		'telefono' => 'required',
		'email' => 'required',
		'contacto' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Cliente::find($this->selected_id);
            $record->update([ 
			'cod_fca' => $this-> cod_fca,
			'nombre' => $this-> nombre,
			'domicilio' => $this-> domicilio,
			'telefono' => $this-> telefono,
			'email' => $this-> email,
			'contacto' => $this-> contacto,
            'rut' => $this-> rut

            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Cliente Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Cliente::where('id', $id);
            $record->delete();
        }
    }
}
