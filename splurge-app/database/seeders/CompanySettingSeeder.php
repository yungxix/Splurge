<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = new CompanySetting(['contact_email' => 'kemaj2001@yahoo.com', 'contact_phone' => '+234 (708) 4151648']);
        
        $setting->saveOrFail();

        $setting->locations()->create([
            'line1' => '2 Samuel Olaolu Close Magodo Isheri',
            'line2' => '',
            'state' => 'Lagos',
            'country' => 'NG'
        ]);
    }
}
