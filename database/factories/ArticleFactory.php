<?php

namespace Database\Factories;

use Cocur\Slugify\Slugify;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = $this->faker->sentence($this->faker->numberBetween(5, 15));
        /** @var Slugify $slugify */
        $slugify = Container::getInstance()->make(Slugify::class);

        return [
            'title' => $title,
            'slug' => $slugify->slugify($title),
            'image' => 'https://picsum.photos/1920/1080?random=12965',
            'content' => $this->faker->sentences($this->faker->numberBetween(5, 50), true),
            'author' => $this->faker->userName(),
            'likes' => 0,
            'dislikes' => 0
        ];
    }
}
