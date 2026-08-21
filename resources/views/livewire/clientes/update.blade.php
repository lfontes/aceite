<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Editar Cliente</h5>
                <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" wire:click.prevent="cancel()"></button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" wire:model="selected_id" value="{{ $selected_id }}">
                    <div class="form-group mb-3">
                        <label for="update_cod_fca" class="font-weight-bold">Cód. FCA</label>
                        <input wire:model="cod_fca" type="text" class="form-control" id="update_cod_fca" placeholder="Cod Fca" value="{{ $cod_fca }}">
                        @error('cod_fca') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="update_nombre" class="font-weight-bold">Nombre</label>
                        <input wire:model="nombre" type="text" class="form-control" id="update_nombre" placeholder="Nombre" value="{{ $nombre }}">
                        @error('nombre') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="update_domicilio" class="font-weight-bold">Domicilio</label>
                        <input wire:model="domicilio" type="text" class="form-control" id="update_domicilio" placeholder="Domicilio" value="{{ $domicilio }}">
                        @error('domicilio') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="update_telefono" class="font-weight-bold">Teléfono</label>
                        <input wire:model="telefono" type="text" class="form-control" id="update_telefono" placeholder="Telefono" value="{{ $telefono }}">
                        @error('telefono') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="update_email" class="font-weight-bold">Email</label>
                        <input wire:model="email" type="email" class="form-control" id="update_email" placeholder="Email" value="{{ $email }}">
                        @error('email') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="update_contacto" class="font-weight-bold">Contacto</label>
                        <input wire:model="contacto" type="text" class="form-control" id="update_contacto" placeholder="Contacto" value="{{ $contacto }}">
                        @error('contacto') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="update_rut" class="font-weight-bold">RUT</label>
                        <input wire:model="rut" type="text" class="form-control" id="update_rut" placeholder="RUT" value="{{ $rut }}">
                        @error('rut') <span class="error text-danger">{{ $message }}</span> @enderror
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
