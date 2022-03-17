<?php

namespace PortedCheese\SiteReviews\Console\Commands;

use App\Menu;
use App\MenuItem;
use PortedCheese\BaseSettings\Console\Commands\BaseConfigModelCommand;

class ReviewsMakeCommand extends BaseConfigModelCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:reviews
                                {--all : Run all}
                                {--menu : Config menu}
                                {--models : Export models}
                                {--controllers : Export controllers}
                                {--policies : Export and create rules}
                                {--only-default : Create default rules}
                                {--vue : Export vue}
                                {--config : Make config}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make reviews settings';

    protected $vendorName = 'PortedCheese';

    protected $packageName = "SiteReviews";

    /**
     * The models that need to be exported.
     * @var array
     */
    protected $models = ['Review'];

    protected $controllers = [
        "Admin" => ["ReviewsController"],
        "Site" => ["ReviewsController"],
    ];

    protected $configName = "reviews";
    protected $configTitle = "Отзывы";
    protected $configTemplate = "site-reviews::admin.settings";
    protected $configValues = [
        'pager' => 10,
        'path' => 'reviews',
        'email' => '',
        'needModerate' => true,
    ];

    protected $vueFolder = "site-reviews";
    protected $vueIncludes = [
        'app' => [
            "site-reviews" => "ReviewsComponent",
        ],
    ];

    protected $ruleRules = [
        [
            "title" => "Отзывы",
            "slug" => "review",
            "policy" => "ReviewPolicy",
        ],
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
     */
    public function handle()
    {
        $all = $this->option("all");

        if ($this->option('menu') || $all) {
            $this->makeMenu();
        }

        if ($this->option("models") || $all) {
            $this->exportModels();
        }

        if ($this->option("controllers") || $all) {
            $this->exportControllers("Admin");
            $this->exportControllers("Site");
        }

        if ($this->option("vue") || $all) {
            $this->makeVueIncludes("app");
        }

        if ($this->option("config") || $all) {
            $this->makeConfig();
        }

        if ($this->option("policies") || $all) {
            $this->makeRules();
        }
    }

    protected function makeMenu()
    {
        try {
            $menu = Menu::query()->where('key', 'admin')->firstOrFail();
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
            MenuItem::create($itemData);
            $this->info("Элемент меню '$title' создан");
        }
    }
}
