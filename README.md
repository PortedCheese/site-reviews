# Site reviews

Интерфейс для создания отзывов.

Страница с отзывами сделана на VueJs. В настройках можно изменить адрес страницы, количество на страницу, адрес для оповещения и нужна ли модерация.

## Установка

`php artisan make:reviews`

`{--all : Run all}`

`{--menu : Config menu}`

`{--models : Export models}`

`{--controllers : Export controllers}`

`{--vue : Export vue}`

`{--config : Make config}`

`php artisan migrate` - Создать таблицы.

`php artisan vendor:publish --provider="PortedCheese\SiteReviews\ServiceProvider" --tag=public --force` - Копирует компоненты.
