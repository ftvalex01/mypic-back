<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Album;
use App\Models\User;
use App\Models\Video;

class VideoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Video::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'album_id' => Album::factory(),
            'url' => $this->faker->url(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->text(),
            'duration' => $this->faker->numberBetween(-10000, 10000),
            'upload_date' => $this->faker->dateTime(),
        ];
    }
}
