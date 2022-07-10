<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

trait HasTags {
    public function taggables() {
        return $this->morphMany(Taggable::class, 'taggeable');
    }

    public function scopeTagged(Builder $builder, $tag) {
        return $builder->whereHas('taggables', function ($query) use ($tag) {
            if (is_array($tag)) {
                if (empty($tag)) {
                    return $query->whereRaw('1=0');
                }
                if (is_string($tag[0]) && !preg_match('/^\d+$/', $tag[0])) {
                    $tag_table = (new Tag())->getTable();
                    $taggable_table = (new Taggable())->getTable();
                    $query = $query->join($tag_table, "{$taggable_table}.tag_id", "=", "{$tag_table}.id");
                    foreach ($tag as $index => $name) {
                        if ($index === 0) {
                            $query = $query->where("{$tag_table}.name", "like", $name);
                        } else {
                            $query = $query->orWhere("{$tag_table}.name", "like", $name);
                        }
                    }
                    return $query;
                }

                return $query->whereIn('tag_id', $tag);
            }
            if (preg_match('/^\d+$/', $tag)) {
                return $query->where('tag_id', '=', $tag);
            }
            return $query->whereHas('tag', function ($query2) use ($tag) {
                return $query2->where('name', 'like', "%$tag%");
            });
        });
    }

    public function tags() {
        return $this->hasManyThrough(Tag::class, Taggable::class, 'tag_id', 'id')
        ->where('taggeable_type', get_class($this));
    }

    public function getTagNames() {
        if (isset($this->relations['tag_names'])) {
            return $this->relations['tag_names'];
        }
        $result = $this->tags()->pluck('name');
        $this->relations['tag_names'] = $result;
        return $result;
    }

}