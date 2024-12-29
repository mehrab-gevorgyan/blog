<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first(),
            'title' => fake()->text($maxNbChars = 30),
            'desc' => fake()->text($maxNbChars = rand(100, 1000)) ,
            'image' => 'post_img/'.rand(1, 20).'.jpg',
        ];
    }
}
