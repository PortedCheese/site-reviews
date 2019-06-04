<?php

namespace PortedCheese\SiteReviews\Console\Commands;

use Illuminate\Console\Command;
use PortedCheese\BaseSettings\Console\Commands\BaseOverrideCommand;

class ReviewsOverrideCommand extends BaseOverrideCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'override:reviews
                    {--admin : Scaffold admin}
                    {--site : Scaffold site}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change reviews default logic';

    protected $controllers = [
        "Admin" => ["ReviewsController"],
        "Site" => ["ReviewsController"],
    ];

    protected $packageName = "SiteReviews";

    protected $dir = __DIR__;

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
        if ($this->option('admin')) {
            $this->createControllers("Admin");
            $this->expandSiteRoutes('admin');
        }

        if ($this->option('site')) {
            $this->createControllers("Site");
            $this->expandSiteRoutes('web');
        }
    }
}
