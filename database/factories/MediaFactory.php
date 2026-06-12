<?php

namespace Database\Factories;

/*
|--------------------------------------------------------------------------
| MediaFactory — désactivée sur cette branche
|--------------------------------------------------------------------------
| Le modèle/migration Media est porté par la branche du collaborateur.
| À réactiver (décommenter) après le merge.
*/

// use App\Models\Media;
// use App\Models\Post;
// use App\Models\User;
// use Illuminate\Database\Eloquent\Factories\Factory;
//
// /**
//  * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
//  */
// class MediaFactory extends Factory
// {
//     protected $model = Media::class;
//
//     public function definition(): array
//     {
//         $name = $this->faker->unique()->slug(3);
//
//         return [
//             'user_id'       => User::factory(),
//             'mediable_id'   => Post::factory(),
//             'mediable_type' => Post::class,
//             'filename'      => $name . '.jpg',
//             'original_name' => $name . '.jpg',
//             'mime_type'     => 'image/jpeg',
//             'path'          => 'media/' . $name . '.jpg',
//             'size'          => $this->faker->numberBetween(10_000, 2_000_000),
//             'alt_text'      => $this->faker->optional()->sentence(4),
//             'is_featured'   => false,
//         ];
//     }
//
//     public function featured(): static
//     {
//         return $this->state(fn () => ['is_featured' => true]);
//     }
// }
