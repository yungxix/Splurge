<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\PostItem;

use App\Models\MediaOwner;
use App\Models\Post;
use App\Support\UploadProcessor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class PostItemRequest extends FormRequest
{
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
        return [
            'name' => 'required|max:255',
            'content' => 'required|max:5000',
            'medium_file' => 'nullable|mimes:png,jpg,jpeg',
            'medium_name' => 'nullable|max:255',
            'medium_id' => 'nullable|integer',
            'remove_picture' => 'nullable|integer'
        ];
    }

    public function createItem(Post $post) {
        return DB::transaction(function () use ($post) {
            $item = $post->items()->save(new PostItem($this->safe()->only(['name', 'content'])));
            $this->handleImage($item);
            return $item;
        });
    }

    public function updateItem(PostItem $postItem) {
        return DB::transaction(function () use ($postItem) {
            $postItem->update($this->safe()->only(['name', 'content']));
            $this->handleImage($postItem);
            return $postItem;
        });
    }

    private function handleImage(PostItem $item) {
        if ($this->input('remove_picture', '0') == '1') {
            $item->mediaItems()->delete();
            return;
        }
        $file_key  = 'medium_file';
        if ($this->hasFile($file_key)) {
            $file = $this->file($file_key);
            $uploader = new UploadProcessor($this, 'posts', $file_key, true, 'url', 'thumbnail_url');
            $image_attrs = $uploader->handleFile($file);
            $name = $this->input('medium_name');
            if (empty($name)) {
            
                $name = head(explode('.',  basename($file->path())));
            }
            $image_attrs['name'] = $name;
            
            $image_attrs['fs'] = config('filesystems.default');

            $image_attrs['media_type'] = $file->getClientMimeType();

            $item->mediaItems()->delete();

            $item->mediaItems()->save(new MediaOwner($image_attrs));
        } else if($this->has('medium_id')) {
            $medium = MediaOwner::find($this->input('medium_id'));

            

            if (!is_null($medium)) {
                $item->mediaItems()->delete();

                $item->mediaItems()->save(
                    new MediaOwner(
                        Arr::except($medium->toArray(),
                         ['id', 'created_at', 'updated_at', 'owner_type', 'owner_id'])));
            }
        }
    }
}
