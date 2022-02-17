<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaOwner extends Model
{
    use HasFactory, HasImageOptions;
    protected $fillable = ['name', 'media_type', 'url', 'thumbnail_url', 'fs'];

    protected $casts = ['image_options' => 'array'];

    public function owner() {
        return $this->morphTo();
    }

    public function scopeForGalleryItems($builder) {
        return $builder->where('owner_type', GalleryItem::class);
    }
}
