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
            // 'user_id' => User::factory()->create()->id, <- この書き方だとfactory使用時にuser_idを上書きしてもUser作成処理が動いてしまう
            'user_id' => User::factory(),
            'body' => fake()->realText(20),
            'status' => 1,
        ];
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 0,
        ]);
    }
}
