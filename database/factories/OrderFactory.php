<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'invoice_no' => 'INV-' . $this->faker->unique()->numberBetween(10000, 99999),
            'user_id' => User::factory(),
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->email(),
            'customer_phone' => $this->faker->numerify('01##########'),
            'customer_address' => $this->faker->address(),
            'customer_city' => $this->faker->city(),
            'customer_area' => $this->faker->streetName(),
            'customer_postal_code' => $this->faker->postcode(),
            'subtotal' => $this->faker->randomFloat(2, 500, 10000),
            'delivery_charge' => $this->faker->randomFloat(2, 50, 200),
            'discount' => $this->faker->randomFloat(2, 0, 500),
            'tax' => $this->faker->randomFloat(2, 0, 500),
            'total_amount' => $this->faker->randomFloat(2, 600, 12000),
            'payment_method' => $this->faker->randomElement(['bkash', 'nagad', 'cash_on_delivery']),
            'payment_status' => $this->faker->randomElement(['paid', 'unpaid']),
            'order_status' => $this->faker->randomElement(['pending', 'confirmed', 'processing', 'shipped', 'delivered']),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
