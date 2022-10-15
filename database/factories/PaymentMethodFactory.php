<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentMethod>
 */
class PaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "name" => "Foo",
            "payment_id" => "bar",
            "payment_type" => "bar",
            "icon_url" => fake()->imageUrl(),
            "transaction_fee_amount" => null,
            "transaction_fee_percent" => null,
        ];
    }
}
