<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cliente;

class SelectCliente extends Component
{
    public $cliente_id;

    public function render()
    {
        return view('livewire.select-cliente', ['clientes' => Cliente::all()]);
    }
}
