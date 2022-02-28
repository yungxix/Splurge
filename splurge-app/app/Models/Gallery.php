<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory, HasAuthor, HasTags, HasImageOptions;

    protected $fillable = ['caption', 'image_url', 'description'];

    protected $casts = ['image_options' => 'array'];
    
     public function items() {
         return $this->hasMany(GalleryItem::class);
     }

     public function mediaItems() {
         return $this->hasManyThrough(MediaOwner::class, GalleryItem::class, 'id', 'owner_id')
         ->where('owner_type', GalleryItem::class);
     }

     public function scopeSearch($builder, $term) {
        if (empty($term)) {
            return $builder;
        }
        return $builder->where('caption', 'like', "%$term%");
    }
}
