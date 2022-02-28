<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

trait HasTags {
    public function taggables() {
        return $this->morphMany(Taggable::class, 'taggeable');
    }

    public function scopeTagged(Builder $builder, $tag) {
        return $builder->whereHas('taggables', function ($query) use ($tag) {
            if (preg_match('/^\d+$/', $tag)) {
                return $query->where('tag_id', '=', $tag);
            }
            return $query->whereHas('tag', function ($query2) use ($tag) {
                return $query2->where('name', 'like', "%$tag%");
            });
        });
    }
}