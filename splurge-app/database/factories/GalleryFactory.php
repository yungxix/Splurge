<?php

namespace Database\Factories;

use App\Models\Gallery;
use App\Models\GalleryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gallery>
 */
class GalleryFactory extends Factory
{

    static $GALLERY_IMAGES = [
        'images/v2/gallery1.jpg',
        'images/v2/gallery10.jpg',
        'images/v2/gallery11.jpg',
        'images/v2/gallery12.jpg',
        'images/v2/gallery13.jpg',
        'images/v2/gallery14.jpg',
        'images/v2/gallery15.jpg',
        'images/v2/gallery16.jpg',
        'images/v2/gallery17.jpg',
        'images/v2/gallery18.jpg',
        'images/v2/gallery2.jpg',
        'images/v2/gallery3.jpg',
        'images/v2/gallery4.jpg',
        'images/v2/gallery5.jpg',
        'images/v2/gallery6.jpg',
        'images/v2/gallery7.jpg',
        'images/v2/gallery8.jpg',
        'images/v2/gallery9.jpg',
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'caption' => $this->faker->words(5, true),
            'description' => $this->faker->paragraphs(5, true),
            'image_url' => $this->faker->randomElement(static::$GALLERY_IMAGES)
        ];
    }

    public function configure()
    {
       return $this->afterCreating(function (Gallery $gallery) {
            GalleryItem::factory($this->faker->numberBetween(5, 10), ['gallery_id' => $gallery->id])->create();
        });
    }
}
