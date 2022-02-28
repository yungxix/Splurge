<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use Intervention\Image\Facades\Image;

use Illuminate\Support\Str;

class UploadProcessor {
    private $path;
    private $request;
    private $fileAttribute;
    private $storeThumbnail;
    private $urlAttribute;
    private $thumbnailUrlAttribute;


    public function __construct(Request $request,
     string $path,
      string $attribute,
       $storeThumbnail = FALSE,
       $urlAttribute = 'image_url',
       $thumbnailUrlAttribute = 'thumbnail_image_url'
       )
    {
        $this->request = $request;
        $this->path = $path;
        $this->fileAttribute = $attribute;
        $this->storeThumbnail = $storeThumbnail;
        $this->urlAttribute = $urlAttribute;
        $this->thumbnailUrlAttribute = $thumbnailUrlAttribute;
    }

    public function handle(): ?array {
        if (!$this->request->hasFile($this->fileAttribute)) {
            return null;
        }
        $file = $this->request->file($this->fileAttribute);

        return $this->handleFileImpl($file);
        
    }

    public function handleFile(UploadedFile $file) {
        return $this->handleFileImpl($file);
    }

    private function handleFileImpl(UploadedFile $file): array {
        $filePath = Storage::putFile($this->path, $file, ['visibility' => 'public']);

        $img = Image::make($file->getRealPath());

        $image_options = [
            'width' => $img->width(),
            'height' => $img->height()
        ];

        $result = [];

        $result[$this->urlAttribute] = Storage::url($filePath);



        if ($this->storeThumbnail) {
            $img->resize(230, null, fn ($cs) => $cs->aspectRatio());
            $image_options['thumbnail_width'] = $img->width();
            $image_options['thumbnail_height'] = $img->height();
            $thumbnailPath = $this->path . '/thumbnails/' . Str::random() . '.jpg';
            Storage::put($thumbnailPath, $img->stream('jpg'));
            $result[$this->thumbnailUrlAttribute] = Storage::url($thumbnailPath);
            
        }

        $img->destroy();

        $result['image_options'] = $image_options;

        return $result;
    }



}
