<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostItem extends Model
{
    use HasFactory, HostsMedia;

    protected $fillable = ['name', 'content', 'author_id'];

    public function post() {
        return $this->belongsTo(Post::class);
    }
}
