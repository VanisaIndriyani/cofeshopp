<?php

namespace Database\Factories;

use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Table>
 */
class TableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $code = $this->faker->unique()->randomElement(['A', 'B', 'C', 'D']).$this->faker->numberBetween(1, 15);

        return [
            'code' => $code,
            'name' => null,
            'is_active' => true,
        ];
    }
}
