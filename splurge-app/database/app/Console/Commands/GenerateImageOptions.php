<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use App\Models\MediaOwner;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;


class GenerateImageOptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appgen:imageoptions {--thumbnail=on} --verbose';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate image options for posts, galleries,services etc';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::transaction(function () {
            $this->handleMediaItems();
            $this->handleGalleries();
            $this->handlePosts();
            $this->handleServices();
        });
        return 0;
    }
    
    private function handleMediaItems() {
        foreach ( MediaOwner::noImageOptions()->where('media_type', 'image')->where('fs', 'local')->cursor() as $model) {
            $this->handleModel($model, 'url', 'thumbnail_url');
        }
    }
    private function handlePosts() {
        foreach ( Post::noImageOptions()->whereNotNull('image_url')->cursor() as $model) {
            $this->handleModel($model, 'image_url', 'thumbnail_image_url');
        }
    }

    private function handleServices() {
        foreach ( Service::noImageOptions()->whereNotNull('image_url')->cursor() as $model) {
            $this->handleModel($model, 'image_url', 'thumbnail_image_url');
        }
    }

    private function handleGalleries() {
        foreach ( Gallery::noImageOptions()->whereNotNull('image_url')->cursor() as $model) {
            $this->handleModel($model, 'image_url', null);
        }
    }

    private function handleModel($model, $image_url_attribute, $thumbnail_url_attribute) {
        $url = $model->$image_url_attribute;


        $verbose = $this->hasOption('verbose');

        $image = Str::startsWith($url, 'http') ? Image::make($url) : Image::make(public_path($url));
       
       
        try {
            

            if ($verbose) {
                $this->info("Handling image at ${url}...");
            }

            $options = [
                'width' => $image->width(),
                'height' => $image->height()
            ];

            if ($verbose) {
                $this->info("Found width and height");
            }



            if ($this->option('thumbnail') !== 'off' && !is_null($thumbnail_url_attribute) && empty($image->$thumbnail_url_attribute)) {
                if ($verbose) {
                    $this->info("Thumbnail will be generated...");
                }



                
                $image->heighten(config('app.default_thumbnail_height', 228), function ($cs) {
                    $cs->upsize();
                });

                if ($verbose) {
                    $this->info("Created thumbnail size");
                }
               

                $path = 'images' . DIRECTORY_SEPARATOR . 'thumbnails' . DIRECTORY_SEPARATOR . Str::random() . '.png';

                $full_path = public_path($path);

                $dir = dirname($full_path);

                if (!file_exists($dir)) {
                    mkdir($dir);
                }

                $image = $image->save($full_path);


                if ($verbose) {
                    $this->info("Thumbnail has been saved to ${path}");
                }
               



                $options['thumbnail_width'] = $image->width();
                
                $options['thumbnail_height'] = $image->height();

                $model->$thumbnail_url_attribute = str_replace(DIRECTORY_SEPARATOR, '/',  $path);

            }
            $model->image_options = array_merge($model->image_options ?: [], $options);

            $model->update();

            if ($verbose) {
                $class_name = get_class($model);
                $this->info("Updated ${class_name}");
            }
           
        } catch (\Throwable $th) {
            $this->error('An error occurred while processing image');
            $this->error($th->getMessage());
        } finally {
            if ($image) {
                $image->destroy();
            }
        }

    }

}
