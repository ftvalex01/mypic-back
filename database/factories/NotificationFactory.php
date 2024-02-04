<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Notification;
use App\Models\User;

class NotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(["reaction","comment","follow","message"]),
            'related_id' => $this->faker->numberBetween(-10000, 10000),
            'read' => $this->faker->boolean(),
            'notification_date' => $this->faker->dateTime(),
        ];
    }
}
