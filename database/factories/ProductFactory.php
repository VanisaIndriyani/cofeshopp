<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => $this->faker->unique()->words(2, true),
            'slug' => null,
            'price' => $this->faker->numberBetween(12000, 45000),
            'description' => $this->faker->sentence(10),
            'photo_path' => null,
            'stock' => $this->faker->numberBetween(0, 60),
            'low_stock_threshold' => 5,
            'is_active' => true,
            'is_featured' => $this->faker->boolean(20),
        ];
    }
}
