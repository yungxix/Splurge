<?php

namespace App\Repositories;

use App\Models\GalleryItem;
use App\Models\MediaOwner;
use App\Models\PostItem;
use Illuminate\Http\Request;

class MediaOwnerRepository {
    
    public function findAll(Request $request) {
        $query = (new MediaOwner())->newQuery();
        if ($request->has('type')) {
            $query = $query->where('owner_type', static::translateType($request->input('type')));
        }
        return $query->orderBy('created_at', 'desc')->paginage()->appends($request->only(['type']));
    }


    private static function translateType($type) {
        switch ($type) {
            case 'gallery':
            case 'gallery_item':
                return GalleryItem::class;
            case 'post':
            case 'post_item':
                return PostItem::class;
            default:
                return $type;
        }
    }
}