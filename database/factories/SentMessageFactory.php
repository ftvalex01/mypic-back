<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Message;
use App\Models\SentMessage;
use App\Models\User;

class SentMessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SentMessage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'sender_id' => User::factory(),
            'message_id' => Message::factory(),
            'user_id' => User::factory(),
        ];
    }
}
