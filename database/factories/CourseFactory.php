<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'price' => fake()->numberBetween(100, 1000) . '000',
            'sell' => fake()->numberBetween(0, 20),
            'detail' => fake()->paragraph(),
            'mentor' => fake()->name(),
            'status' => fake()->boolean(),
        ];
    }

    public function category($category)
    {
        $category = Category::where('name', $category)->first();
        return $this->state(fn (array $attributes) => [
            'fk_category' => $category->id,
        ]);
    }
}
