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

                    <div class="form-group mb-3">
                        <label for="porcentaje"><strong>Porcentaje %</strong></label>
                        <input type="number" step="0.01" min="0.01" max="100" class="form-control col-md-2" id="porcentaje" name="porcentaje"
                            value="{{ old('porcentaje') }}" placeholder="Ingrese %">
                        @error('porcentaje')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="cliente"><strong>Cliente</strong></label>
                        <select multiple name="multi_cliente[]" id="cliente" class="form-control col-md-4" style="height: 200px;">
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ (is_array(old('multi_cliente')) && in_array($cliente->id, old('multi_cliente'))) ? 'selected' : '' }}>{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted d-block">(Seleccionar varios con "CTRL+Click")</small>
                        @error('multi_cliente')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                
                    <div class="form-group mb-3">
                        <label for="todos"><strong>Aplicar a todos los clientes</strong></label>
                        <input type="checkbox" name="todos" id="todos" {{ old('todos') ? 'checked' : '' }}>
                        @error('todos')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="detalle"><strong>Detalle:</strong></label>
                        <input type="text" name="detalle" class="form-control col-md-10" id="detalle"
                            value="{{ old('detalle') }}" placeholder="Detalle">
                        @error('detalle')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="card-footer">

                        <div class="form-group">
                            <button type="button" class="btn btn-secondary close-btn"  onclick="location.href='{{ url('/movimientos') }}'">Cancelar</button>
                            <button type="submit" class="btn btn-primary float-right">Aplicar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
