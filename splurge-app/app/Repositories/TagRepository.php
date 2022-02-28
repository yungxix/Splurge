<?php

namespace App\Repositories;

use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\Post;
use App\Models\Service;
use App\Models\Tag;
use App\Models\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TagRepository {
    public function findAll(Request $request) {
        $query = (new Tag())->newQuery();
        if ($request->has('type')) {
            $query = $query->whereHas(['taggables' => fn ($q) => 
            $q->where('taggable_type',
             static::translateTaggableType($request->input('type')))]);
        }

        return $query->orderBy('created_at', 'desc')->get();
        
    }

    public function findAllWithAttachments(Request $request) {
        return $this->findAllWithAttachmentsFor(
            ['id' => $request->input('type_id'),
             'type' => $request->input('type_name')]);
    }

    public function findAllWithAttachmentsFor(array $taggable) {
        $tag_table = (new Tag())->getTable();
        $taggable_table = (new Taggable())->getTable();
        $taggable_type = static::translateTaggableType($taggable['type']);
        $taggable_id = $taggable['id'];

        return Tag::leftJoin($taggable_table, function ($join) use ($tag_table,
         $taggable_table, $taggable_type,
          $taggable_id) {
            $join->on("{$tag_table}.id", "{$taggable_table}.tag_id")
                ->where("{$taggable_table}.taggeable_type", '=', $taggable_type)
                ->where("{$taggable_table}.taggeable_id", '=', $taggable_id);
        })->select("{$tag_table}.*", "{$taggable_table}.taggeable_type", "{$taggable_table}.taggeable_id")
        ->orderBy("{$tag_table}.created_at", "desc")->get();
    }

    private static function translateTaggableType($type) {
        switch ($type) {
            case 'service':
                return Service::class;
            case 'event':
            case 'post':
                return Post::class;
            case 'gallery':
                return Gallery::class;
            case 'gallery_item':
                return GalleryItem::class;
            default:
                return $type;
        }
    }

    public function attachTo(Tag $tag, $taggable) {
        if ($taggable instanceof Model) {
            $tag->taggables()->create([
                'taggeable_id' => $taggable->id,
                'taggeable_type' => get_class($taggable)
            ]);
        } else {
            $tag->taggables()->create(['taggeable_id' => $taggable['id'],
            'taggeable_type' => static::translateTaggableType($taggable['type'])]);
        }
    }

    public function detachFrom(Tag $tag, $taggable) {
        return Taggable::where([
            'tag_id' => $tag->id,
             'taggeable_id' => $taggable['id'],
              'taggeable_type' => static::translateTaggableType($taggable['type']) ])->delete();
    }
}