<?php

namespace App\Models;

trait HasTags {
    public function taggables() {
        return $this->morphMany(Taggable::class, 'taggeable');
    }
}