<div>
    {{-- Success is as dangerous as failure. --}}
    
    <div class="mb-8">
        <label class="inline-block w-32 ">Cliente:</label>
        <select name="cliente" wire:model="cliente_id" class="p-2 bg-white">
            <option value=''>Elija un cliente</option>
            @foreach($clientes as $cliente)
                <option value={{ $cliente->id }}>{{ $cliente->nombre }}</option>
            @endforeach
        </select>
        <p>{{ $cliente_id }}</p>
    </div> 
</div>
