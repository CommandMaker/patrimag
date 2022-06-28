<?php

namespace Database\Factories;

use Cocur\Slugify\Slugify;
use Illuminate\Support\Str;
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
        $content = $this->faker->sentences($this->faker->numberBetween(5, 50), true);
        /** @var Slugify $slugify */
        $slugify = Container::getInstance()->make(Slugify::class);

        return [
            'title' => $title,
            'slug' => $slugify->slugify($title),
            'description' => Str::limit($content, 700),
            'image' => 'https://picsum.photos/1920/1080?random=12965',
            'content' => $content,
            'author_id' => 1,
            'likes' => 0,
            'dislikes' => 0
        ];
    }
}
