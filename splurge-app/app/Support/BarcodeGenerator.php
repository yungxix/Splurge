<?php

namespace App\Support;

use Laminas\Barcode\Barcode;

use Illuminate\Support\Str;

class BarcodeGenerator {
    public static function create(string $value, string $destination = 'images/bcs'): string {
        $dir = public_path($destination);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $filename = sprintf('bc%s-g%s.png', Str::random(), "bc1");

        $real_path = $dir . DIRECTORY_SEPARATOR . $filename;
        $barcodeOptions = ['text' => $value, 'drawText' => false, 'barHeight' => 60];
        $rendererOptions = [];

        $renderer = Barcode::factory(
            'code39',
            'image',
            $barcodeOptions,
            $rendererOptions
        );

        $image = $renderer->draw();
        imagepng($image, $real_path);
        @imagedestroy($image);
        $path = sprintf('%s/%s', $destination, $filename);
        return asset($path);
    }
}