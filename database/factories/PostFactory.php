<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(6);
        $status = $this->faker->randomElement(PostStatus::cases());

        return [
            'user_id'      => User::factory(),
            'category_id'  => Category::factory(),
            'title'        => rtrim($title, '.'),
            'slug'         => Str::slug($title),
            'summary'      => $this->faker->optional()->text(160),
            'content'      => $this->faker->paragraphs(5, true),
            'status'       => $status,
            'published_at' => $status === PostStatus::PUBLISHED ? $this->faker->dateTimeBetween('-1 year') : null,
            'views_count'  => $this->faker->numberBetween(0, 5000),
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status'       => PostStatus::PUBLISHED,
            'published_at' => $this->faker->dateTimeBetween('-1 year'),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status'       => PostStatus::DRAFT,
            'published_at' => null,
        ]);
    }
}
