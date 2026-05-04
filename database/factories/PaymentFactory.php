<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'method' => $this->faker->randomElement([Payment::METHOD_CASH, Payment::METHOD_QRIS]),
            'status' => $this->faker->randomElement([Payment::STATUS_UNPAID, Payment::STATUS_PAID]),
            'amount' => $this->faker->numberBetween(10000, 150000),
            'qris_proof_path' => null,
            'paid_at' => null,
        ];
    }
}
