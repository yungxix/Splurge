<?php

namespace Database\Factories;

use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\MediaOwner;
use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GalleryItem>
 */
class GalleryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content' => $this->faker->text(300),
            'heading' => Str::ucfirst($this->faker->sentence())
        ];
    }

    public function forGallery(Gallery $gallery) {
        return $this->state(function ($attributes) use ($gallery) {
            return ['gallery_id' => $gallery->id];
        });
    }

    public function configure()
    {
        return $this->afterCreating(function (GalleryItem $item) {
            $mediaList = MediaOwner::factory($this->faker->numberBetween(5, 15), [
                'owner_type' => GalleryItem::class,
                'owner_id' => $item->id
            ])->count($this->faker->numberBetween(5, 15))->create();
        });
    }
}
