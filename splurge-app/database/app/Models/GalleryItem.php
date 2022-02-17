<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasFactory, HostsMedia;

    protected $fillable = ['content', 'heading', 'gallery_id'];

    public function gallery() {
        return $this->belongsTo(Gallery::class);
    }
}
