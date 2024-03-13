<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataTableItem>
 */
class DataTableItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'textbox' => $this->faker->name,
            'textarea' => $this->faker->realText(50),
            'radiobtn' => 1,
            'select' => 1,
            'checkbox' => 1,
        ];
    }
}
