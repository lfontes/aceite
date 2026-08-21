<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Crear Nuevo Movimiento</h5>
                <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group mb-3">
                        <label for="create_cliente_id" class="font-weight-bold">Cliente</label>
                        <select wire:model="cliente_id" id="create_cliente_id" name="cliente_id" class="form-control">
                            <option value="">Elija un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->cod_fca }} - {{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                        @error('cliente_id') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="create_tipo_mov" class="font-weight-bold">Tipo de Movimiento</label>
                        <select wire:model="tipo_mov" class="form-control" id="create_tipo_mov">
                            <option value="">Elija tipo de movimiento</option>
                            <option value="Ingreso">Ingreso</option>
                            <option value="Salida">Salida</option>
                        </select>
                        @error('tipo_mov') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="create_detalle" class="font-weight-bold">Detalle</label>
                        <input wire:model="detalle" type="text" class="form-control" id="create_detalle" placeholder="Detalle">
                        @error('detalle') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="create_cantidad" class="font-weight-bold">Cantidad</label>
                        <input wire:model="cantidad" type="number" step="0.01" class="form-control" id="create_cantidad" placeholder="Cantidad">
                        @error('cantidad') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="create_fecha" class="font-weight-bold">Fecha</label>
                        <input wire:model="fecha" type="date" class="form-control" id="create_fecha" placeholder="Fecha">
                        @error('fecha') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Guardar</button>
            </div>
        </div>
    </div>
</div>
