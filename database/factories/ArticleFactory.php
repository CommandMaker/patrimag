<?php

namespace Database\Factories;

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
        return [
            'title' => $this->faker->sentence($this->faker->numberBetween(5, 15)),
            'content' => $this->faker->sentences($this->faker->numberBetween(5, 50), true),
            'author' => $this->faker->userName(),
            'likes' => 0,
            'dislikes' => 0
        ];
    }
}
