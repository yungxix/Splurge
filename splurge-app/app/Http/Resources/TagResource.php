<?php

namespace App\Http\Resources;

use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class TagResource extends JsonResource
{
    static $translations = [
        Post::class => 'post',
        Service::class => 'service',
        Gallery::class => 'gallery',
        GalleryItem::class => 'gallery_item'
    ];
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($request->has('attachments')) {
            return [
                'id' => $this->resource->id,
                'name' => $this->resource->name,
                'category' => $this->resource->category,
                'attached' => !is_null($this->resource->taggeable_id),
                'taggable' => [
                    'id' => $this->resource->taggeable_id,
                    'type' => static::translateType($this->resource->taggeable_type),
                ]
            ];    
        }
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'category' => $this->resource->category
        ];
    }

    static function translateType($type) {
        if (is_null($type)) {
            return $type;
        }
        return Arr::get(static::$translations, $type, $type);
    } 
}
