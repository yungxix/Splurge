<?php 

namespace App\Http\Requests\Support\Import;

use App\Models\MenuPreference;

class GuestMenuPreferenceColumn extends Column {
    protected function parseValue($value) {
        $lines = preg_split('/\r|\n/', (string)$value);
        $models = [];
        foreach ($lines as $line) {
            if (empty($line)) {
                continue;
            }
            $models[] = new MenuPreference(['name' => trim($line)]);
        }
        return $models;
    }
}