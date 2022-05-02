
    <!-- The best way to take care of the future is to take care of the present moment. - Thich Nhat Hanh -->
 


<input
    x-data
    x-ref="input"
    x-init="new Pikaday({ field: $refs.input })"
    type="text"
    {{ $attributes }}
>