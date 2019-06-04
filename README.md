# Site reviews

Интерфейс для создания отзывов.

Страница с отзывами сделана на VueJs. В настройках можно изменить адрес страницы, количество на страницу, адрес для оповещения и нужна ли модерация.

## Установка

`php artisan make:reviews` - Конфиг и модели для отзывов.

`php artisan override:reviews --admin --site` - контроллеры и роуты.

`php artisan migrate` - Создать таблицы.

`php artisan vendor:publish --provider="PortedCheese\SiteReviews\ServiceProvider" --tag=public --force` - Копирует компоненты.

`Vue.component(
     'site-reviews',
     require('./components/vendor/site-reviews/ReviewsComponent')
 );` - Подключить компонент.

`php artisan vendor:publish --provider="PortedCheese\SiteReviews\ServiceProvider" --tag=views` - Если нужно поменять стандартный вывод на сайт.

`@includeIf("site-reviews::admin.menu")` - меню для админки
