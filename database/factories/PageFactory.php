<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    protected $model = \App\Models\Page::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'slug' => fake()->unique()->slug(2),
            'body' => fake()->paragraphs(4, true),
            'is_published' => true,
        ];
    }
}
