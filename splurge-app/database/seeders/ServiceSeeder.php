<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $factory = Service::factory();
        foreach (['Weddings', 'Anniverseries', 'Dinners', 'Parties', 'Memorable momments'] as $name) {
            $factory->create(['name' => $name]);
        }
    }
}
