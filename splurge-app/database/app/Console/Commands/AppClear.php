<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Service;
use App\Models\Post;
use App\Models\MediaOwner;

class AppClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear {target}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear app target data';

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
        if ('thumbnails' === $this->argument('target')) {
            $this->clearThumbails();
        } else {
            $this->error('Unknown target');
            return 1;
        }
        return 0;
    }

    private function clearThumbails() {
        $affected = Service::hasImageOptions()->update(['thumbnail_image_url' => null, 'image_options' => null]);
        $affected += Post::hasImageOptions()->update(['thumbnail_image_url' => null, 'image_options' => null]);
        $affected += MediaOwner::hasImageOptions()->update(['thumbnail_url' => null, 'image_options' => null]);
        $this->info("${affected} record(s) affected");
    }
}
