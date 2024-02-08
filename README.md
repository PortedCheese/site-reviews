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

### Versions:
    
    v1.6.4:
        - email для уведомлений можно задать через запятую
        - заменена иконка неавторизованного пользователя
        - в шаблоне вывода для авторизованного пользователя используется @pic
    v1.5.0:
        - Пересобрана таблица отзывов
        - Добавлена редактируемая дата отзыва
    Обновление:
        - php artisan migrate
        - php artisan cache:clear
    
    v1.3.0:
        - Шаблоны меню изменены под sb-admin
        
    v1.2.4:
        - Изменен внешний вид отзывов
        - Для тизера запоминаются только данные, а не шаблон
        - Формы разделены, ошибки выводятся в разные места
    Обновление:
        - php artisan vendor:publish --provider="PortedCheese\SiteReviews\ServiceProvider" --tag=public --force
        - php artisan cache:clear

    v1.2.3:
        - Добавлен параметр --only-default в команду
        
    v1.2.2:
        - Добавлены права доступа
    Обновление:
        - php artisan make:reviews --policies