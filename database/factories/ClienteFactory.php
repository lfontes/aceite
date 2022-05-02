<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition()
    {
        return [
			'cod_fca' => $this->faker->name,
			'nombre' => $this->faker->name,
			'domicilio' => $this->faker->name,
			'telefono' => $this->faker->name,
			'email' => $this->faker->name,
			'contacto' => $this->faker->name,
        ];
    }
}
