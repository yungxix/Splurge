<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Gallery;

use App\Models\GalleryItem;
use Illuminate\Http\UploadedFile;

use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;

class GalleryItemRequest extends FormRequest
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
            'heading' => 'required|max:255',
            'content' => 'required|max:1200',
            'media' => 'array',
            'media.*.medium' => 'mimes:png,jpg,jpeg|max:' . (4 * 1024) 
        ];
    }

    public function createItem(Gallery $gallery) {
        return DB::transaction(function () use ($gallery) {
            $item = $gallery->items()->create($this->safe()->only(['heading', 'content']));
            $this->storePictures($item);
            return $item;
        });
        
    }

    public function updateItem(GalleryItem $item) {
        return DB::transaction(function () use ($item) {
            $item->update($this->safe()->only(['heading', 'content']));
            if ($this->input('clear_media', '0') == '1') {
                $item->mediaItems()->delete();
            }
            $this->storePictures($item);
            return $item;
        });
    }

    private function storePictures(GalleryItem $item) {
        $count = count($this->input('media', []));
        foreach (range(0, $count - 1) as $index) {
            $prefix = "media.{$index}.";
            if (!$this->hasFile("{$prefix}medium")) {
                continue;
            }
            static::createMediaOwned($item, $this->input("{$prefix}name"), $this->file("{$prefix}medium"));
        }
    }

    private static function createMediaOwned(
        GalleryItem $galleryItem,
        string $name,
        UploadedFile $file
    ) {
        $path = 'images/gallery';
        $thumbnail_path = "$path/thumbnails";

        $dest = Storage::putFile($path, $file, ['visibility' => 'public', 'access' => 'public']);

        $thumbnail = Image::make($file->getRealPath());

        $image_options = [
            'width' => $thumbnail->width(),
            'height' => $thumbnail->height()
        ];
        $thumbnail->resize(230, null, fn ($cst) => $cst->aspectRatio());

        $image_options = [
            'thumbnail_width' => $thumbnail->width(),
            'thumbnail_height' => $thumbnail->height()
        ];

        $thumbnail_name =  head(explode('.', basename($dest)));

        $full_thumbnail_path = "{$thumbnail_path}/${thumbnail_name}.jpg";

        Storage::put($full_thumbnail_path, $thumbnail->stream('jpg'));

        $galleryItem->mediaItems()->create([
            'url' => Storage::url($dest),
            'thumbnail_url' => Storage::url($full_thumbnail_path),
            'image_options' => $image_options,
            'fs' => config('filesystems.default'),
            'media_type' => $file->getClientMimeType(),
            'name' => empty($name) ? basename($file->path(), $file->extension()) : $name
        ]);

    }
}
