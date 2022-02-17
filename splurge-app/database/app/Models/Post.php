<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, HasAuthor, HasTags, HasImageOptions;

    protected $fillable = ['image_url', 'name', 'description', 'thumbnail_image_url', 'author_id'];

    protected $casts = ['image_options' => 'array'];

    public function items() {
        return $this->hasMany(PostItem::class);
    }
}
