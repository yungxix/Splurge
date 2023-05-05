<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\CustomerEventGuest as Guest;

use Illuminate\Support\Facades\DB;

class GenerateGuestBarcodesCommand extends Command
{
    private $affected = 0;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guests:barcodes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate barcodes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Generating missing barcodes...");
        DB::transaction(function () {
            Guest::where('barcode_image_url', null)->orderBy('id')->chunk(200, function ($guests) {
                foreach ($guests as $guest) {
                    $guest->generateBarcode(true);
                    $this->affected += 1;
                }
            });
        });

        $this->info(sprintf("%s record(s) were affected", $this->affected));
        return Command::SUCCESS;
    }
}
