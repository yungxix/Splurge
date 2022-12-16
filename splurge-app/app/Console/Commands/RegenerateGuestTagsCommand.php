<?php

namespace App\Console\Commands;

use App\Models\CustomerEvent;
use Illuminate\Console\Command;
use App\Models\CustomerEventGuest;

class RegenerateGuestTagsCommand extends Command
{
    private $affected = 0;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guests:tags {--F|force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate tags';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Generating tags for guests...");
        $query = (new CustomerEventGuest())->newQuery();

        if (!$this->hasOption('force')) {
            $query = $query->where('tag', null);
        } else {
            $this->warn("All guests will be affected");
        }

        $query->select("id", "customer_event_id")->orderBy('id')->chunk(200, function ($guests) {
            foreach ($guests as $guest) {
                $tag = CustomerEventGuest::generateTag($guest->customer_event_id);
                CustomerEventGuest::where("id", $guest->id)->update([
                    'tag' => $tag
                ]);
                $this->affected += 1;
            }
        });

        $this->info(sprintf('%s record(s) were affected', $this->affected));

        return Command::SUCCESS;
    }
}
