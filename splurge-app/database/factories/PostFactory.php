<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\PostItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    static $IMAGE_URLS = [
        'images/v2/slider1.jpg',
        'images/v2/slider2.jpg',
        'images/v2/slider3.jpg',
        'images/v2/slider4.jpg',
        'images/v2/slider5.jpg',
        'images/v2/slider6.jpg',
        'images/v2/slider7.jpg'
    ];
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(),
            'image_url' => $this->faker->randomElement(static::$IMAGE_URLS),
            'description' => $this->faker->paragraphs(4, true)
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Post $post) {
            PostItem::factory($this->faker->numberBetween(1, 10), ['post_id' => $post->id])->create();
        });
    }
}
