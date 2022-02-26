<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaOwner extends Model
{
    use HasFactory, HasImageOptions;
    protected $fillable = ['name', 'media_type', 'owner_id', 'image_options', 'owner_type', 'url', 'thumbnail_url', 'fs'];

    protected $casts = ['image_options' => 'array'];

    public function owner() {
        return $this->morphTo();
    }

    public function scopeForGalleryItems($builder, $ids = NULL) {
        $builder = $builder->where('owner_type', GalleryItem::class);
        if (is_null($ids)) {
            return $builder;
        }
        if (is_array($ids)) {
            return $builder->whereIn('owner_id', $ids);
        }
        return $builder->where('owner_id', $ids);
    }
}
