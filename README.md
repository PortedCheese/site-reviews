# Site reviews

Интерфейс для создания отзывов.

Страница с отзывами сделана на VueJs. В настройках можно изменить адрес страницы, количество на страницу, адрес для оповещения и нужна ли модерация.

## Установка

`php artisan make:reviews` - Конфиг для отзывов.

`php artisan migrate` - Создать таблицы.

`php artisan vendor:publish --provider="PortedCheese\SiteReviews\ServiceProvider" --tag=public` - Копирует компоненты.

`php artisan vendor:publish --provider="PortedCheese\SiteReviews\ServiceProvider" --tag=views` - Если нужно поменять стандартный вывод на сайт.

`@includeIf("site-reviews::admin.menu")` - меню для админки
