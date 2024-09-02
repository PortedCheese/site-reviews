### Versions:
    v2.0.0-2.0.1:
        - base-settings 5.0
        - обновлены компоненты ReviewsFormComponent, ReviewsListComponent
        - обновлен  admin.menu view
        Обновление:
        - php artisan vendor:publish --provider="PortedCheese\SiteReviews\ServiceProvider" --tag=public --force 
    v1.6.5: 
        - change view
        Обновление:
        - php artisan vendor:publish --provider="PortedCheese\SiteReviews\ServiceProvider" --tag=public --force
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