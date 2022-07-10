<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MediaOwner>
 */
class MediaOwnerFactory extends Factory
{
    static $IMAGES = [
        'images/2.jpg',
        'images/3.jpg',
        'images/e1.jpeg',
        'images/e2.jpeg',
        'images/e3.jpeg',
        'images/e4.jpeg',
        'images/e5.jpeg',
        'images/e6.jpeg',
        'images/img01.jpg',
        'images/l1.jpg',
        'images/m1.jpg',
        'images/m2.jpg',
        'images/m3.jpg',
        'images/m5.jpg',
        'images/m6.jpg',
        'images/m7.jpg',
        'images/m8.jpg',
        'images/m9.jpg',
        'images/o1.jpg',
        'images/o2.jpg',
        'images/o3.jpg',
        'images/o4.jpg',
        'images/o5.jpg',
        'images/o6.jpg',
        'images/o7.jpg',
        'images/p1.jpeg',
        'images/p2.jpeg',
        'images/p3.jpg',
        'images/p4.jpeg',
        'images/p5.jpeg',
        'images/q1.jpeg',
        'images/q2.jpeg',
        'images/q3.jpeg',
        'images/showcase.jpg',
        'images/w1.jpeg',
        'images/w2.jpeg',
        'images/w3.jpeg',
        'images/w4.webp',
        'images/w5.jpeg',
        'images/w6.jpeg',
        'images/w7.webp',
        'images/w8.jpeg',
        'images/w9.jpeg',
        'images/Wedding.jpg',

    ];
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'url' => $this->faker->randomElement(static::$IMAGES),
            'media_type' => 'image',
            'fs' => 'local'
        ];
    }
}
