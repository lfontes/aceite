<?php

namespace Database\Factories;

use App\Models\Movimiento;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MovimientoFactory extends Factory
{
    protected $model = Movimiento::class;

    public function definition()
    {
        return [
			'cliente_id' => $this->faker->name,
			'tipo_mov' => $this->faker->name,
			'detalle' => $this->faker->name,
			'cantidad' => $this->faker->name,
			'fecha' => $this->faker->name,
        ];
    }
}
