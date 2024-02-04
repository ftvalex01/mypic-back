<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Album;
use App\Models\Photo;
use App\Models\User;

class PhotoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Photo::class;

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
            'upload_date' => $this->faker->dateTime(),
        ];
    }
}
