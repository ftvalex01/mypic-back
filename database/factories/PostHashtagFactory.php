<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Hashtag;
use App\Models\Post;
use App\Models\PostHashtag;

class PostHashtagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostHashtag::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'post_id' => Post::factory(),
            'hashtag_id' => Hashtag::factory(),
        ];
    }
}
