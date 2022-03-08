<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\DB;

use App\Support\UploadProcessor;

use App\Models\Post;

class PostRequest extends FormRequest
{
    use TaggableResourceRequest;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('PATCH') || $this->isMethod('PUT')) {
            return [
                'name' => 'required|max:255',
                'description' => 'required|max:3000',
                'image_url' => 'nullable|mimes:png,jpg,jpeg|max:' . (4 * 1024),
                'tags' => 'nullable|array'
            ];    
        }
        return [
            'name' => 'required|max:255',
            'description' => 'required|max:3000',
            'image_url' => 'required|mimes:png,jpg,jpeg|max:' . (4 * 1024),
            'tags' => 'nullable|array'
        ];
    }

    public function createItem() {
        return DB::transaction(function () {
            $attributes = $this->safe()->only(['name', 'description']);

            $uploader = new UploadProcessor($this, 'images/posts', 'image_url', true);

            if (($extra = $uploader->handle())) {
                $attributes = array_merge($attributes, $extra);
            }

            $post = new Post($attributes);

            $post->author_id = $this->user()->id;

            $post->saveOrFail();

            $this->saveTags($post);

            return $post;
        });
        
    }


    public function updateItem(Post $post) {
        return DB::transaction(function () use ($post) {
            $attributes = $this->safe()->only(['name', 'description']);

            $uploader = new UploadProcessor($this, 'posts', 'image_url', true);

            if (($extra = $uploader->handle())) {
                $attributes = array_merge($attributes, $extra);
            }

            $post->update($attributes);

            $this->saveTags($post);

            return $post;
        });
        
    }
}
