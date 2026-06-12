<?php

namespace Database\Factories;

use App\Enums\CommentStatus;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(CommentStatus::cases());

        return [
            'user_id'     => User::factory(),
            'post_id'     => Post::factory(),
            'parent_id'   => null,
            'content'     => $this->faker->paragraph(),
            'status'      => $status,
            'edited_at'   => null,
            'approved_at' => $status === CommentStatus::APPROVED ? now() : null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn () => [
            'status'      => CommentStatus::APPROVED,
            'approved_at' => now(),
        ]);
    }
}
