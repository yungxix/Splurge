<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(5)->create();
        $user = User::create([
            'name' => 'Serene Admin',
            'email' => 'admin@splurgeevents.com',
            'password' => '$2y$10$tNGj/G13dFtqfYIxo/fpAeJSVZRqmXFnThPelC7E8DzOr7iqdYuUG'
        ]);
        $user->roles()->create(['name' => 'admin']);
        $user->roles()->create(['name' => 'system']);
    }
}
