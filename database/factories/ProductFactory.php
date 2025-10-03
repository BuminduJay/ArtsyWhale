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
    public function definition(): array
        {
            $name = fake()->unique()->words(3, true);
            return [
                'category_id'  => \App\Models\Category::inRandomOrder()->value('id') ?? \App\Models\Category::factory(),
                'name'         => ucfirst($name),
                'slug'         => \Str::slug($name).'-'.fake()->unique()->randomNumber(4),
                'description'  => fake()->paragraph(),
                'price_cents'  => fake()->numberBetween(500, 15000),
                'stock'        => fake()->numberBetween(0, 50),
                'is_active'    => true,
            ];
        }

}
