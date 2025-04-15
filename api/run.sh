#!/bin/sh

args=$@

if [ "$args" = "migrate" ]; then
    composer install --no-interaction --optimize-autoloader
    php artisan key:generate
    php artisan migrate:fresh --seed
fi

php artisan serve --host=0.0.0.0 --port=8000