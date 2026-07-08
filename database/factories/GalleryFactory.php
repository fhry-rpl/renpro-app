<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GalleryFactory extends Factory
{
    protected $model = \App\Models\Gallery::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'is_published' => true,
            'published_at' => fake()->dateTimeThisYear(),
        ];
    }
}
