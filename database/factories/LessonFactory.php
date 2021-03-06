<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

final class LessonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lesson::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle,
            'information' => $this->faker->paragraphs(rand(10, 15), true),
        ];
    }
}
