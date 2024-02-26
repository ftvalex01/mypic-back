<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash; // Asegúrate de incluir Hash
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
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'username' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('Pringles15.'), // Usa Hash para encriptar la contraseña
            'birth_date' => $this->faker->date(),
            'register_date' => now(),
            'bio' => $this->faker->text,
            'is_private' => $this->faker->boolean,
            'is_2fa_enabled' => $this->faker->boolean,
            'two_fa_code' => null,
            'two_fa_expires_at' => null,
            'email_verified_at' => now(),
            'available_pines' => $this->faker->numberBetween(1, 5),
            'profile_picture' => null,
            'accumulated_points' => $this->faker->numberBetween(0, 100),
            // 'rememberToken' => Str::random(10),
            'github_id' => null,
            'github_token' => null,
            'github_refresh_token' => null,
            'google2fa_secret' => null,
            'google_id' => null,
        ];
    }
}
