@extends('layouts.app')
@section('content')
    <div class="container-fluid col-md-8">
        <div class="card">
            <div class="card-header bg-info">
                <h1>Mermas</h1>
            </div>
            <div class="card-body">


                <form name="mermas-form" id="mermas-form" method="post" action="{{ url('aplicaratodos') }}">
                    @csrf

                    <div class="form-group">
                        <label for="Porcentaje %"><strong>Porcentaje %</strong></label>
                        <input type="number" class="form-control col-md-2" id="porcentaje" name="porcentaje"
                            placeholder="Ingrese %">
                        @error('poncentaje')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="cliente"><strong>Cliente</strong></label>
                        <select wire:model="cliente_id" name="cliente_id" class="form-control col-md-4">
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
                        <label for="detalle"><strong>Detalle:</strong></label>
                        <input wire:model="detalle" type="text" name="detalle" class="form-control col-md-10" id="detalle"
                            placeholder="Detalle">
                        @error('detalle')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="card-footer">

                        <div class="form-group">
                            <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary float-right">Aplicar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
