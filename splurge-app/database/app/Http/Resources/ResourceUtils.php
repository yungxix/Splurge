<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;

class ResourceUtils {
    public static function safeAssetUrl($path) {
        if (empty($path)) {
            return $path;
        }
        if (Str::startsWith($path, 'http')) {
            return $path;
        }

        return asset($path);
    }
}