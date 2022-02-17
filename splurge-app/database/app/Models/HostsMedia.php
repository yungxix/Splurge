<?php
namespace App\Models;

use App\Models\MediaOwner;

trait HostsMedia {
    public function mediaItems() {
        return $this->morphMany(MediaOwner::class, 'owner');
    }
}