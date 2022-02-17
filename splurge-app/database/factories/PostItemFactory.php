<?php

namespace Database\Factories;

use App\Models\MediaOwner;
use App\Models\Post;
use App\Models\PostItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostItem>
 */
class PostItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content' => $this->faker->text(),
            'name' => $this->faker->sentences(3, true)
        ];
    }

    public function forPost(Post $post) {
        return $this->state(function ($attributes) use ($post){
            return ['post_id' => $post->id];
        });
    }


    public function configure()
    {
        return $this->afterCreating(function (PostItem $item) {
            $media = MediaOwner::factory()->make();
            $item->mediaItems()->save($media);
        });
    }

    
}
