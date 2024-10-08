@section('title', __('Movimientos'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header bg-info">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4 class="text-white">Movimientos de aceite </h4>
						</div>
					
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div class="md-col-6">
							<input wire:model='keyWord' type="text" class="form-control md-col-6 d-print-none" name="search" id="search" placeholder="Buscar por nombre">
						</div>
						<div class="btn btn-sm btn-info d-print-none" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Agregar Movimiento
						</div>
					</div>
				</div>
				
				<div class="card-body">
						@include('livewire.movimientos.create')
						@include('livewire.movimientos.update')
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead class="thead">
							<tr> 
								<th style="width: 80px;">Cliente Id</th>
								<th style="width: 80px;">FCA</th>
								<th >Nombre</th>
								<th>Tipo Mov</th>
								<th>Detalle</th>
								<th>Cantidad</th>
								<th>Fecha</th>
								<td class="d-print-none">Acciones</td>
							</tr>
						</thead>
						<tbody>
							@foreach($movimientos as $row)
							<tr>
								<td style="text-align: center;">{{ $row->cliente_id }}</td>
								<td style="text-align: center;"><em>{{ $row->cliente->cod_fca }}</em></td>
								<td>{{ $row->cliente->nombre }}</td>
								<td>{{ $row->tipo_mov }}</td>
								<td>{{ $row->detalle }}</td>
								<td style="text-align: right;">{{ $row->cantidad }}</td>
								<td style="text-align: right;">{{ $row->fecha }}</td>													
								<td  class="d-print-none" width="90">
								<div class="btn-group">
									<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Acciones
									</button>
									<div class="dropdown-menu dropdown-menu-right">
									<a data-toggle="modal" data-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa fa-edit"></i> Editar </a>							 
									<a class="dropdown-item" onclick="confirm('Confirm Delete Movimiento id {{$row->id}}? \nDeleted Movimientos cannot be recovered!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"><i class="fa fa-trash"></i> Borrar </a>   
									</div>
								</div>
								</td>
							@endforeach
						</tbody>
						<tfoot>
							<tr class="bg-light">
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td><strong>TOTAL</strong></td>
								<td style="text-align: right;"><strong>{{ $tott }} </strong> </td>
							</tr>
						</tfoot>
					</table>

					<div class="card-footer">
						<div wire:poll.60s>
							<code><h5>{{ now()->format('H:i:s') }}</h5></code>
					</div>						
					{{ $movimientos->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
