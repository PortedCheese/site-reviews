<?php

namespace PortedCheese\SiteReviews\Console\Commands;

use App\Menu;
use App\MenuItem;
use Illuminate\Console\Command;
use PortedCheese\BaseSettings\Console\Commands\BaseConfigModelCommand;

class ReviewsMakeCommand extends BaseConfigModelCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:reviews
                                {--menu : Only config menu}';

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

    protected $configName = "reviews";

    protected $configValues = [
        'pager' => 10,
        'path' => 'reviews',
        'email' => '',
        'customTheme' => null,
        'needModerate' => true,
        'useOwnAdminRoutes' => false,
        'useOwnSiteRoutes' => false,
    ];

    protected $vueFolder = "site-reviews";

    protected $vueIncludes = [
        'app' => [
            "site-reviews" => "ReviewsComponent",
        ],
    ];

    protected $dir = __DIR__;

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
        if (! $this->option('menu')) {
            $this->exportModels();
            $this->makeVueIncludes('app');
            $this->makeConfig();
        }
        $this->makeMenu();
    }

    protected function makeMenu()
    {
        try {
            $menu = Menu::where('key', 'admin')->firstOrFail();
        }
        catch (\Exception $e) {
            return;
        }

        $title = "Отзывы";
        $itemData = [
            'title' => $title,
            'template' => "site-reviews::admin.menu",
            'url' => "#",
            'ico' => 'far fa-comments',
            'menu_id' => $menu->id,
        ];

        try {
            $menuItem = MenuItem::query()
                ->where('title', $title)
                ->where("menu_id", $menu->id)
                ->firstOrFail();
            $menuItem->update($itemData);
            $this->info("Элемент меню '$title' обновлен");
        }
        catch (\Exception $e) {
            $menuItem = MenuItem::create($itemData);
            $this->info("Элемент меню '$title' создан");
        }
    }
}
