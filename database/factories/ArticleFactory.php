<?php

namespace Database\Factories;

use Cocur\Slugify\Slugify;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Storage;

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
            'image' => 'articles_image/generic-image.jpg',
            'content' => $content,
            'author_id' => 1,
            'likes' => 0,
            'dislikes' => 0,
        ];
    }
}
