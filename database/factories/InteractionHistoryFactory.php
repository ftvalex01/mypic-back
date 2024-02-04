<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\InteractionHistory;
use App\Models\Post;
use App\Models\User;

class InteractionHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InteractionHistory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'post_id' => Post::factory(),
            'interaction_type' => $this->faker->randomElement(["reaction","comment","share"]),
            'interaction_date' => $this->faker->dateTime(),
        ];
    }
}
