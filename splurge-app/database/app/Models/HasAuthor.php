<?php
namespace App\Models;


trait HasAuthor {
    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }
}
