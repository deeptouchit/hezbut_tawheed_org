<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentTransactionFactory extends Factory
{
    protected $model = PaymentTransaction::class;

    public function definition(): array
    {
        $statuses = [
            PaymentTransaction::STATUS_PENDING,
            PaymentTransaction::STATUS_VERIFIED,
            PaymentTransaction::STATUS_FAILED,
            PaymentTransaction::STATUS_REFUNDED,
            PaymentTransaction::STATUS_CANCELLED
        ];

        $methods = [
            PaymentTransaction::METHOD_BKASH,
            PaymentTransaction::METHOD_NAGAD,
            PaymentTransaction::METHOD_ROCKET,
            PaymentTransaction::METHOD_CARD,
            PaymentTransaction::METHOD_BANK_TRANSFER,
            PaymentTransaction::METHOD_COD
        ];

        return [
            'order_id' => Order::exists() ? Order::inRandomOrder()->first()->id : 1,
            'customer_id' => User::exists() ? User::inRandomOrder()->first()->id : 1,
            'payment_method' => $this->faker->randomElement($methods),
            'amount' => $this->faker->randomFloat(2, 100, 50000),
            'currency' => 'BDT',
            'transaction_id' => $this->faker->unique()->bothify('TXN-####-????'),
            'reference_no' => $this->faker->optional()->bothify('REF-#####'),
            'mobile_number' => $this->faker->optional()->numerify('01##########'),
            'screenshot' => $this->faker->optional()->imageUrl(),
            'status' => $this->faker->randomElement($statuses),
            'verified_by' => $this->faker->optional()->randomElement([1, 2, 3]),
            'verified_at' => $this->faker->optional()->dateTimeThisMonth(),
            'verification_note' => $this->faker->optional()->sentence(),
            'failure_reason' => $this->faker->optional()->sentence(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentTransaction::STATUS_PENDING,
            'verified_by' => null,
            'verified_at' => null,
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentTransaction::STATUS_VERIFIED,
            'verified_by' => 1,
            'verified_at' => now(),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentTransaction::STATUS_FAILED,
            'failure_reason' => 'Insufficient balance',
        ]);
    }
}
