<?php

namespace App\Models;

use Illuminate\Support\Arr;


trait HasImageOptions {

    public function getImageWidth($thumbnail = FALSE) {
        if (is_null($this->image_options)) {
            return null;
        }
        $key = $thumbnail ? 'thumbnail_width' : 'width';
        return Arr::get($this->image_options, $key);
    }

    public function getImageHeight($thumbnail = FALSE) {
        if (is_null($this->image_options)) {
            return null;
        }
        $key = $thumbnail ? 'thumbnail_height' : 'height';
        return Arr::get($this->image_options, $key);
    }

    public function scopeHasImageOptions($builder) {
        return $builder->whereNotNull('image_options');
    }

    public function scopeNoImageOptions($builder) {
        return $builder->whereNull('image_options');
    }
}