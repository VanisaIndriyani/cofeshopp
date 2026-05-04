<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice' => null,
            'table_id' => Table::factory(),
            'created_by_user_id' => null,
            'customer_name' => $this->faker->name(),
            'customer_note' => $this->faker->boolean(30) ? $this->faker->sentence(8) : null,
            'status' => Order::STATUS_PENDING,
            'subtotal' => 0,
            'tax_percent' => '0.00',
            'tax_amount' => 0,
            'service_percent' => '0.00',
            'service_amount' => 0,
            'grand_total' => 0,
            'ordered_at' => now(),
            'confirmed_at' => null,
            'completed_at' => null,
        ];
    }
}
