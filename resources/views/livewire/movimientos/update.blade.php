<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Editar Movimiento</h5>
                <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" wire:click.prevent="cancel()"></button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" wire:model="selected_id" value="{{ $selected_id }}">
                    <div class="form-group mb-3">
                        <label for="update_cliente_id" class="font-weight-bold">Cliente</label>
                        <select wire:model="cliente_id" id="update_cliente_id" name="cliente_id" class="form-control">
                            <option value="">Elija un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" @if($cliente_id == $cliente->id) selected @endif>{{ $cliente->cod_fca }} - {{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                        @error('cliente_id') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="update_tipo_mov" class="font-weight-bold">Tipo de Movimiento</label>
                        <select wire:model="tipo_mov" class="form-control" id="update_tipo_mov">
                            <option value="">Elija tipo de movimiento</option>
                            <option value="Ingreso" @if($tipo_mov == 'Ingreso') selected @endif>Ingreso</option>
                            <option value="Salida" @if($tipo_mov == 'Salida') selected @endif>Salida</option>
                        </select>
                        @error('tipo_mov') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="update_detalle" class="font-weight-bold">Detalle</label>
                        <input wire:model="detalle" type="text" class="form-control" id="update_detalle" placeholder="Detalle" value="{{ $detalle }}">
                        @error('detalle') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="update_cantidad" class="font-weight-bold">Cantidad</label>
                        <input wire:model="cantidad" type="number" step="0.01" class="form-control" id="update_cantidad" placeholder="Cantidad" value="{{ $cantidad }}">
                        @error('cantidad') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="update_fecha" class="font-weight-bold">Fecha</label>
                        <input wire:model="fecha" type="date" class="form-control" id="update_fecha" placeholder="Fecha" value="{{ $fecha }}">
                        @error('fecha') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="update()" class="btn btn-primary">Guardar Cambios</button>
            </div>
       </div>
    </div>
</div>
