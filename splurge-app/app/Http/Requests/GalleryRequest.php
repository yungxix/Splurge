<?php

namespace App\Http\Requests;

use App\Models\Gallery;
use App\Models\Taggable;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;

class GalleryRequest extends FormRequest
{
    use TaggableResourceRequest;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin');
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
                'caption' => 'nullable|max:255',
                'image_url' => 'nullable|mimes:jpg,jpeg,png|max:' . (4 * 1024),
                'tags' => 'nullable|array',
                'description' => 'nullable|max:1200',
                'tags' => 'nullable|array',
            ];    
        }
        return [
            'caption' => 'required|max:255',
            'image_url' => 'required|mimes:jpg,jpeg,png|max:' . (4 * 1024),
            'tags' => 'nullable|array',
            'description' => 'required|max:1200',
            'tags' => 'nullable|array',
        ];
    }

    public function newGallery() {
        $gallery = new Gallery([
            'caption' => $this->old('caption'),
            'description' => $this->old('description')
        ]);


        return $gallery;
    }


    public function createGallery() {
        $validated = $this->safe()->only(['caption', 'description']);
        $gallery = new Gallery(array_merge($validated, $this->storeMedia()));
        $gallery->saveOrFail();
        
        $this->saveTags($gallery);
        return $gallery;
    }

    public function updateGallery(Gallery $gallery) {
        $validated = $this->safe()->only(['caption', 'description']);
        $gallery->fill(array_merge($validated, $this->storeMedia()));
        $gallery->saveOrFail();
        $this->saveTags($gallery);
        return $gallery;
    }

    /**
     * Stores 
     * @return array array of attributes to merge
     */
    private function storeMedia() {
        $key = 'image_url';
        if ($this->hasFile($key)) {
            $path = 'images/gallery';
            $file = $this->file($key);
            $name = 'gallery_' . Str::random() . '.' . $file->getClientOriginalExtension();
            Storage::putFileAs($path,
             $file,
              $name, ['visibility' => 'public']);
            return [
                $key => Storage::url("{$path}/{$name}")
            ];
        }
        return [];
    }
}
