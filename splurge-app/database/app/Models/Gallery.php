<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory, HasAuthor, HasTags, HasImageOptions;

    protected $fillable = ['caption', 'image_url', 'tags', 'description'];

    protected $casts = ['image_options' => 'array'];
    
     public function items() {
         return $this->hasMany(GalleryItem::class);
     }
}
