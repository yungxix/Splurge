<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SingleRelationResouceKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resource:single {key : Resource key}
                             {--rel= : Relation name}
                            {--class= : Resource class for the key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a resource key that points to a single relation using when wrapper';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $relation = $this->option('rel');
        $getter = Str::snake($relation);
        $key = $this->argument('key');
        $resourceClass = $this->option('class');
        $ctx = "$" . 'this->';
        $this->info(sprintf("'%s' => %swhen(%sresource->relationLoaded('%s'), fn () => new %s(%sresource->%s)),", $key, $ctx, $ctx, $relation, $resourceClass, $ctx, $getter));
        return Command::SUCCESS;
    }
}
