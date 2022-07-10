<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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

    public function configure()
    {
        // parent::configure();

        return $this->afterCreating(function (Service $service)  {
            foreach (["Silver", "Gold", "Platinum"] as $index => $tier) {
                $service->tiers()->create([
                    'name' => $tier,
                     'position' => $index + 1,
                     'code' => Str::random(8),
                     'price' => (10000 + floor($this->faker->randomNumber(5)) % 1000000) ,
                     'description' => $this->faker->text(254),
                     'footer_message' => $this->faker->text(200),
                     'options' => array_map(function () {
                        return ['text' => $this->faker->text(100)];
                     }, range(0,  random_int(2, 4)))
                    ]);
            }
        });
    }
}
