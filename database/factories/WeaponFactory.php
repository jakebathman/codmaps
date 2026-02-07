<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Weapon>
 */
class WeaponFactory extends Factory
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
            'type' => $this->faker->randomElement([
                'assault rifle',
                'submachine gun',
                'light machine gun',
                'sniper rifle',
                'shotgun',
                'pistol',
                'marksman rifle',
                'melee',
            ]),
            'code_prefix' => null,
        ];
    }
}
