
    <!-- The best way to take care of the future is to take care of the present moment. - Thich Nhat Hanh -->
<div
    x-data=""
    x-on:change="value = $event.target.value"
    x-init="new Pikaday({ field: $refs.input })"
    >
    <input
    x-ref="fecha"
    x-bind:value="fecha"
    type="date"
    placeholder="Select date"
    wire:model="fecha"
    />
</div>
<p>Fecha: {{ $fecha }}</p>