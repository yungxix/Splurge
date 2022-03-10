<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Taggable extends Model
{
    use HasFactory;

    protected $fillable = ['tag_id', 'taggeable_id', 'taggeable_type'];

    public function tag() {
        return $this->belongsTo(Tag::class);
    }

    public function taggable() {
        return $this->morphTo('taggeable');
    }

    public function scopeTagged(Builder $builder, $tags) {
        if (is_string($tags)) {
            return $builder->whereHas('tag', fn ($query) => $query->where('name', 'like', "%$tags%"));
        }

        if (is_array($tags)) {
            if (count($tags) > 5) {
                return $builder->whereHas('tag', fn ($query) => $query->whereIn('name', $tags));
            }
            return $builder->whereHas('tag', function ($query) use ($tags) {
                foreach ($tags as $index => $tag) {
                    if ($index === 0) {
                        $query = $query->where('name', 'like', "%$tag%");
                    } else {
                        $query = $query->orWhere('name', 'like', "%$tag%");
                    }
                }
            });
        }
        return $this->scopeTagged($builder, $tags->toArray());
    }
}
