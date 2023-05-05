<?php

namespace Database\Seeders;

use App\Models\CustomerEvent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerEventsSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomerEvent::factory(20)->create();
    }
}
