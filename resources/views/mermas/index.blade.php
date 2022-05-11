@extends('layouts.app')
@section('content')
    <div class="container-fluid col-md-10">
        <div class="">
            <form name="mermas-form" id="mermas-form" method="post" action="{{ url('mermasstore') }}">
                @csrf

                <div class="form-group">
                    <label for="Porcentaje %">Porcentaje %</label>0
                    <input type="number" class="form-control col-md-2" id="porcentaje" name="porcentaje" placeholder="Ingrese %">
                    @error('poncentaje')
                        <span class="error text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="cliente">Cliente</label>
                    <select wire:model="cliente_id" name="cliente_id" class="p-2 bg-white">
                        <option value=''>Elija un cliente</option>
                        @foreach ($clientes as $cliente)
                            <option value={{ $cliente->id }}>{{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="todos">Aplicar a todos los clientes</label>
                    <input type="checkbox" name="todos" id="todos">
                </div>

                <div class="form-group">
                    <label for="detalle">Detalle:</label>
                    <input wire:model="detalle" type="text" name="detalle" class="form-control col-md-10" id="detalle"
                        placeholder="Detalle">
                    @error('detalle')
                        <span class="error text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary close-modal">Aplicar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
