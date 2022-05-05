<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Create New Movimiento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label for="cliente_id"></label>
             
              <select wire:model="cliente_id" name="cliente" class="p-2 bg-white">
                <option value=''>Elija un cliente</option>
                @foreach($clientes as $cliente)
                    <option value={{ $cliente->id }}>{{ $cliente->nombre }}</option>
                @endforeach
            </select>
                <input wire:model="cliente_id" type="text" class="form-control" id="cliente_id" placeholder="Cliente Id">@error('cliente_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="tipo_mov"></label>
                <select wire:model="tipo_mov" type="text" class="form-control" id="tipo_mov" placeholder="Tipo Mov">@error('tipo_mov') <span class="error text-danger">{{ $message }}</span> @enderror
                    <option value="alta">Alta</option>
                    <option value="baja">Baja</option>
                </select>
            </div>
            <div class="form-group">
                <label for="detalle"></label>
                <input wire:model="detalle" type="text" class="form-control" id="detalle" placeholder="Detalle">@error('detalle') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="cantidad"></label>
                <input wire:model="cantidad" type="text" class="form-control" id="cantidad" placeholder="Cantidad">@error('cantidad') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="fecha"></label>
                <input wire:model="fecha" type="date" class="form-control datepicker" id="fecha" placeholder="Fecha">@error('fecha') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>

                </form>ap
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Save</button>
            </div>
        </div>
    </div>
</div>
