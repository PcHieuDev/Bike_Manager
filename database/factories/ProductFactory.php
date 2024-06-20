<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $carNames = [
            'Toyota Camry', 'Honda Civic', 'Ford Mustang', 'Chevrolet Impala',
            'Tesla Model S', 'Nissan Altima', 'Hyundai Sonata', 'Kia Optima',
            'BMW 3 Series', 'Mercedes-Benz C-Class'
        ];
        return [
            'name' => $this->faker->randomElement($carNames),
            'note' => $this->faker->text(100),
            'price' => $this->faker->randomFloat(1, 1, 100000),
            'category_id' => $this->faker->numberBetween(1, 4),
            'brand_id' => $this->faker->numberBetween(1, 3),
            'image' => '/storage/images/1718606692.png',
        ];
    }
}
