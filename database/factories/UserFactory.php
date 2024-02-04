<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'username' => $this->faker->userName(),
            'email' => $this->faker->safeEmail(),
            'password' => $this->faker->password(),
            'birth_date' => $this->faker->date(),
            'register_date' => $this->faker->dateTime(),
            'bio' => $this->faker->text(),
            'email_verified_at' => $this->faker->dateTime(),
            'available_pines' => $this->faker->numberBetween(-10000, 10000),
            'profile_picture' => $this->faker->text(),
            'accumulated_points' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
