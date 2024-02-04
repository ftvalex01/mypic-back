<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Message;
use App\Models\ReceivedMessage;
use App\Models\User;

class ReceivedMessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ReceivedMessage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'receiver_id' => User::factory(),
            'message_id' => Message::factory(),
            'user_id' => User::factory(),
        ];
    }
}
