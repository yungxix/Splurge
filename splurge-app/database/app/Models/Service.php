<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory, HasTags, HasImageOptions;

    protected $fillable = ['image_url', 'thumbnail_image_url', 'description', 'name'];

    protected $casts = ['image_options' => 'array'];

}
