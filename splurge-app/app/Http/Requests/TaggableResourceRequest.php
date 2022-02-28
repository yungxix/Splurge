<?php

namespace App\Http\Requests;

use App\Models\Taggable;


trait TaggableResourceRequest {
    protected function saveTags($model, $key = null) {
        $tag_key = $key ?: 'tags';
        if ($this->has($tag_key)) {
            $model->taggables()->delete();
            $model->taggables()->saveMany(array_map(function ($tag) {
                return new Taggable([
                    'tag_id' => is_array($tag) ? $tag['id'] : $tag
                ]);
            }, $this->input($tag_key)));

        }
    }
}