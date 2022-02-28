<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory, HasTags, HasImageOptions;

    protected $fillable = ['image_url', 'thumbnail_image_url', 'description', 'name', 'image_options'];

    protected $casts = ['image_options' => 'array'];

    public function scopeSearch($builder, $term) {
        if (empty($term)) {
            return $builder;
        }
        return $builder->where('name', 'like', "%$term%");
    }

}
