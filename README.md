# Site reviews

Интерфейс для создания отзывов.

Страница с отзывами сделана на VueJs. В настройках можно изменить адрес страницы, количество на страницу, адрес для оповещения и нужна ли модерация.

## Установка

    php artisan migrate
    
    php artisan vendor:publish --provider="PortedCheese\SiteReviews\ServiceProvider" --tag=public --force
    
    php artisan make:reviews
                            {--all : Run all}
                            {--menu : Config menu}
                            {--models : Export models}
                            {--controllers : Export controllers}
                            {--policies : Export and create rules}
                            {--only-default : Create default rules}
                            {--vue : Export vue}
                            {--config : Make config}

