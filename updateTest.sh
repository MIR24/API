#!/bin/bash

git pull

#cp .env.dist .env

composer install

php artisan migrate

#php artisan db:seed

##php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"

##npm install

php artisan optimize:clear

echo "DONE"
