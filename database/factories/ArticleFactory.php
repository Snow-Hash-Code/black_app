<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(4),
            'slug' => $this->faker->slug,
            'content' => $this->faker->paragraphs(3, true),
            // 'category_id' => Category::factory(),
            // 'user_id' => User::factory(),
        ];
    }
}
