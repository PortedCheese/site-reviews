<?php

namespace PortedCheese\SiteReviews\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;

class ReviewsMakeCommand extends Command
{
    use DetectsApplicationNamespace;

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
     * The models that need to be exported.
     * @var array
     */
    protected $models = [
        'Review.stub' => 'Review.php',
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $namespace = $this->getAppNamespace();
        $this->namespace = str_replace("\\", '', $namespace);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->makeConfig();
        $this->exportModels();
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
            'needModerate' => true,
            'useOwnAdminRoutes' => false,
            'useOwnSiteRoutes' => false,
        ]);

        $this->info("Config reviews added to siteconfig");
    }

    /**
     * Create models files.
     */
    protected function exportModels()
    {
        foreach ($this->models as $key => $model) {
            if (file_exists(app_path($model))) {
                if (!$this->confirm("The [{$model}] model already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            file_put_contents(
                app_path($model),
                $this->compileModetStub($key)
            );

            $this->info("Model [{$model}] generated successfully.");
        }
    }

    /**
     * Replace namespace in model.
     *
     * @param $model
     * @return mixed
     */
    protected function compileModetStub($model)
    {
        return str_replace(
            '{{namespace}}',
            $this->namespace,
            file_get_contents(__DIR__ . "/stubs/make/models/$model")
        );
    }
}
