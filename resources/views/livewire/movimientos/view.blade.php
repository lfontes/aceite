@section('title', __('Movimientos'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card shadow-sm">
				<div class="card-header bg-info text-white">
					<div class="d-flex justify-content-between align-items-center flex-wrap">
						<div class="d-flex align-items-center">
							<h4 class="mb-0 text-white"><i class="fa fa-exchange-alt me-2"></i> Movimientos de Aceite</h4>
						</div>
					
						@if (session()->has('message'))
						<div wire:poll.4s class="alert alert-success py-1 px-3 mb-0 text-white bg-success border-0"> 
							{{ session('message') }} 
						</div>
						@endif

						<div class="d-print-none">
							<button class="btn btn-sm btn-light text-info font-weight-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createDataModal">
								<i class="fa fa-plus-circle"></i> Agregar Movimiento
							</button>
						</div>
					</div>
				</div>
				
				<div class="card-body">
					@include('livewire.movimientos.create')
					@include('livewire.movimientos.update')

					<!-- Filter Toolbar (d-print-none) -->
					<div class="card bg-light border-0 mb-3 d-print-none shadow-sm">
						<div class="card-body py-2 px-3">
							<div class="row g-2 align-items-end">
								<!-- Filtro Cliente -->
								<div class="col-md-3 col-sm-6">
									<label class="form-label small font-weight-bold mb-1"><i class="fa fa-user"></i> Cliente:</label>
									<select wire:model.live="filtro_cliente_id" class="form-control form-control-sm">
										<option value="">-- Todos los clientes --</option>
										@foreach($clientes as $cliente)
											<option value="{{ $cliente->id }}">{{ $cliente->cod_fca }} - {{ $cliente->nombre }}</option>
										@endforeach
									</select>
								</div>

								<!-- Filtro Tipo Movimiento -->
								<div class="col-md-2 col-sm-6">
									<label class="form-label small font-weight-bold mb-1"><i class="fa fa-tag"></i> Tipo Mov:</label>
									<select wire:model.live="filtro_tipo_mov" class="form-control form-control-sm">
										<option value="">-- Todos los tipos --</option>
										<option value="Ingreso">Ingreso</option>
										<option value="Salida">Salida</option>
									</select>
								</div>

								<!-- Filtro Fecha Desde -->
								<div class="col-md-2 col-sm-6">
									<label class="form-label small font-weight-bold mb-1"><i class="fa fa-calendar-alt"></i> Desde:</label>
									<input type="date" wire:model.live="fecha_desde" class="form-control form-control-sm">
								</div>

								<!-- Filtro Fecha Hasta -->
								<div class="col-md-2 col-sm-6">
									<label class="form-label small font-weight-bold mb-1"><i class="fa fa-calendar-alt"></i> Hasta:</label>
									<input type="date" wire:model.live="fecha_hasta" class="form-control form-control-sm">
								</div>

								<!-- Búsqueda por texto -->
								<div class="col-md-2 col-sm-6">
									<label class="form-label small font-weight-bold mb-1"><i class="fa fa-search"></i> Buscar texto:</label>
									<input wire:model.live.debounce.300ms="keyWord" type="text" class="form-control form-control-sm" placeholder="Detalle o nombre...">
								</div>

								<!-- Botón Limpiar Filtros -->
								<div class="col-md-1 col-sm-12 text-end">
									<button wire:click="limpiarFiltros" class="btn btn-sm btn-outline-secondary w-100" title="Limpiar todos los filtros">
										<i class="fa fa-eraser"></i> Limpiar
									</button>
								</div>
							</div>
						</div>
					</div>

					<!-- Table -->
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover table-sm align-middle">
							<thead class="thead-dark bg-dark text-white">
								<tr> 
									<th style="width: 80px;" class="text-center">Cliente Id</th>
									<th style="width: 90px;" class="text-center">Cod-FCA</th>
									<th>Nombre del Cliente</th>
									<th style="width: 100px;" class="text-center">Tipo Mov</th>
									<th>Detalle</th>
									<th style="width: 110px;" class="text-end text-right">Cantidad</th>
									<th style="width: 110px;" class="text-center">Fecha</th>
									<th class="d-print-none text-center" style="width: 100px;">Acciones</th>
								</tr>
							</thead>
							<tbody>
								@forelse($movimientos as $row)
								<tr>
									<td class="text-center">{{ $row->cliente_id }}</td>
									<td class="text-center font-weight-bold">{{ $row->cliente->cod_fca ?? '-' }}</td>
									<td>{{ $row->cliente->nombre ?? 'Cliente no encontrado' }}</td>
									<td class="text-center">
										@if($row->tipo_mov == 'Ingreso')
											<span class="badge bg-success text-white px-2 py-1">Ingreso</span>
										@else
											<span class="badge bg-danger text-white px-2 py-1">Salida</span>
										@endif
									</td>
									<td>{{ $row->detalle }}</td>
									<td class="text-end text-right font-weight-bold {{ $row->cantidad < 0 ? 'text-danger' : 'text-success' }}">
										{{ number_format($row->cantidad, 2, ',', '.') }}
									</td>
									<td class="text-center">{{ $row->fecha }}</td>													
									<td class="d-print-none text-center" width="100">
										<div class="btn-group">
											<button type="button" class="btn btn-info btn-sm dropdown-toggle text-white" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												Acciones
											</button>
											<div class="dropdown-menu dropdown-menu-right dropdown-menu-end shadow-sm">
												<a class="dropdown-item" wire:click="edit({{$row->id}})" role="button">
													<i class="fa fa-edit text-primary me-2"></i> Editar
												</a>							 
												<a class="dropdown-item text-danger" onclick="confirm('¿Está seguro de eliminar el movimiento id {{$row->id}}? \n¡Esta acción no se puede deshacer!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})" role="button">
													<i class="fa fa-trash text-danger me-2"></i> Borrar
												</a>   
											</div>
										</div>
									</td>
								</tr>
								@empty
								<tr>
									<td colspan="8" class="text-center py-4 text-muted">
										<i class="fa fa-info-circle me-1"></i> No se encontraron movimientos que coincidan con los filtros seleccionados.
									</td>
								</tr>
								@endforelse
							</tbody>
							<tfoot>
								<tr class="bg-light font-weight-bold">
									<td colspan="4" class="text-muted small">Mostrando {{ $movimientos->count() }} de {{ $movimientos->total() }} registros</td>
									<td class="text-end text-right"><strong>TOTAL FILTRADO:</strong></td>
									<td class="text-end text-right font-weight-bold h6 mb-0 text-primary">
										<strong>{{ number_format((float)$tott, 2, ',', '.') }}</strong>
									</td>
									<td colspan="2"></td>
								</tr>
							</tfoot>
						</table>

						<div class="d-flex justify-content-between align-items-center flex-wrap mt-3 d-print-none">
							<div class="small text-muted" wire:poll.60s>
								<i class="fa fa-clock me-1"></i> {{ now()->format('d/m/Y H:i:s') }}
							</div>						
							<div>
								{{ $movimientos->links() }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
