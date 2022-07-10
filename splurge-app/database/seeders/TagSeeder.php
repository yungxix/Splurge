<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Tag;


class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['Weddings', 'Wedding', 'Bridal Shower', 'Baby Shower', 'Introduction', 'Corporate event', 'Traditional', 'Vendor', 'Advisory'] as $name) {
            Tag::create(['name' => $name, 'category' => 'event']);
            Tag::create(['name' => $name, 'category' => 'service']);
        }
    }
}
