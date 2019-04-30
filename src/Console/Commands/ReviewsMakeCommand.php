<?php

namespace PortedCheese\SiteReviews\Console\Commands;

use Illuminate\Console\Command;

class ReviewsMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:reviews';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make reviews settings';

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
     * @return mixed
     */
    public function handle()
    {
        $this->makeConfig();
    }

    public function makeConfig()
    {
        $config = siteconf()->get('reviews');
        if (!empty($config)) {
            if (! $this->confirm("Reviews config already exists. Replace it?")) {
                return;
            }
        }

        siteconf()->save("reviews", [
            'pager' => 10,
            'path' => 'reviews',
            'email' => '',
            'customTheme' => null,
        ]);

        $this->info("Config reviews added to siteconfig");
    }
}
