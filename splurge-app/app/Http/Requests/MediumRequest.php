<?php

namespace App\Http\Requests;

use App\Models\GalleryItem;
use App\Models\MediaOwner;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Arr;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;

use Intervention\Image\Facades\Image;

class MediumRequest extends FormRequest
{
    static $typeTranslations = [
        'post' => Post::class,
        'event' => Post::class,
        'service' => Service::class,
        'gallery' => GalleryItem::class,
        'gallery_item' => GalleryItem::class
    ];


    static $paths = [
        Post::class => 'posts',
        Service::class => 'services',
        GalleryItem::class => 'gallery',
        Gallery::class => 'gallery'
    ];
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin') ;
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
            'medium_file' => 'required|file|mimes:png,jpg,jpeg|max:' . (4 * 1024),
            'owner_id' => 'required|integer',
            'owner_type' => 'required'
        ];
    }


    public function store() {
        $validated = $this->safe()->only(['name', 'owner_id']);
        
        $attrs = array_merge($validated, [
            'owner_type' => static::translateType($this->input('owner_type'))
        ], $this->storeFile());

        return MediaOwner::create($attrs);
    }

    private function storeFile() {
        $key = 'medium_file';
        $file = $this->file($key);
        $path = static::getPrefferedPath($this->input('owner_type'));
        $name = Str::random(18) . '.' . $file->getClientOriginalExtension();
        Storage::putFileAs($path, $file, $name);

        if (preg_match('/image\/.*/i', $file->getClientMimeType())) {
            $thumbnail = Image::make($file->getRealPath());
            $image_options = [
                'width' => $thumbnail->width(),
                'height' => $thumbnail->height()
            ];
            $thumbnail->resize(230, null, function ($cst) {
                $cst->aspectRatio();
            });


            $image_options['thumbnail_width'] = $thumbnail->width();
            $image_options['thumbnail_height'] = $thumbnail->height();
            
            $thumbnail_path = $path . '/thumbnails/' . $name;

            Storage::put($thumbnail_path, $thumbnail->stream(), 'public');




            $thumbnail->destroy();

            return [
                'url' => Storage::url($path . '/' . $name),
                'thumbnail_url' => $thumbnail_path,
                'media_type' => 'image',
                'fs' => config('filesystems.default'),
                'image_options' => $image_options
            ];
        }

        return [
            'url' => Storage::url($path . '/' . $name),
            'media_type' => $file->getClientMimeType(),
            'fs' => config('filesystems.default')
        ];
    }

    private static function translateType($type) {
        return Arr::get(static::$typeTranslations, $type, $type);
    }

    private static function getPrefferedPath($type) {
        $paths = last(explode('/', $type), $type);
        return Arr::get(static::$paths, static::translateType($type),  Str::plural($paths));
    }
}
