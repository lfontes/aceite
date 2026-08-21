<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Crear Nuevo Cliente</h5>
                <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group mb-3">
                        <label for="create_cod_fca" class="font-weight-bold">Cód. FCA</label>
                        <input wire:model="cod_fca" type="text" class="form-control" id="create_cod_fca" placeholder="Cod Fca">
                        @error('cod_fca') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="create_nombre" class="font-weight-bold">Nombre</label>
                        <input wire:model="nombre" type="text" class="form-control" id="create_nombre" placeholder="Nombre">
                        @error('nombre') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="create_domicilio" class="font-weight-bold">Domicilio</label>
                        <input wire:model="domicilio" type="text" class="form-control" id="create_domicilio" placeholder="Domicilio">
                        @error('domicilio') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="create_telefono" class="font-weight-bold">Teléfono</label>
                        <input wire:model="telefono" type="text" class="form-control" id="create_telefono" placeholder="Telefono">
                        @error('telefono') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="create_email" class="font-weight-bold">Email</label>
                        <input wire:model="email" type="email" class="form-control" id="create_email" placeholder="Email">
                        @error('email') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="create_contacto" class="font-weight-bold">Contacto</label>
                        <input wire:model="contacto" type="text" class="form-control" id="create_contacto" placeholder="Contacto">
                        @error('contacto') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="create_rut" class="font-weight-bold">RUT</label>
                        <input wire:model="rut" type="text" class="form-control" id="create_rut" placeholder="RUT">
                        @error('rut') <span class="error text-danger">{{ $message }}</span> @enderror
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
