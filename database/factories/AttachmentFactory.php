<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attachment>
 */
class AttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'label' => $this->faker->word(),
            'type' => $this->faker->randomElement([
                'barrel',
                'comb',
                'fire mods',
                'laser',
                'magazine',
                'muzzle',
                'optic',
                'rear grip',
                'stock',
                'underbarrel',
            ]),
            'code_base34' => null,
            'code_base10' => null,
        ];
    }
}
