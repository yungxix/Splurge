<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    static $IMAGE_URLS = ['images/2.jpg',
     'images/e1.jpeg', 'images/e2.jpeg',
      'images/e5.jpeg', 'images/w9.jpeg',
       'images/w6.jpeg', 'images/w8.jpeg', 'images/w9.jpeg'];
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => $this->faker->text(255),
            'image_url' => $this->faker->randomElement(static::$IMAGE_URLS)
        ];
    }
}
