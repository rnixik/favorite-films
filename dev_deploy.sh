#!/usr/bin/env bash

set -e -o xtrace

docker-compose exec auth-app cp .env.example .env
docker-compose exec auth-app composer install
docker-compose exec auth-app php artisan key:generate --ansi
docker-compose exec auth-app bash -c "id -u > user_id.txt"
docker-compose exec --user=root auth-app bash -c 'read -r USER_ID<user_id.txt && chgrp -R ${USER_ID} storage bootstrap/cache && echo ${USER_ID}'
docker-compose exec --user=root auth-app chmod -R ug+rwx storage bootstrap/cache
docker-compose exec auth-app php artisan passport:keys
docker-compose exec auth-app php artisan migrate --seed
docker-compose exec auth-app php artisan passport:client --password --no-interaction

docker-compose exec films-app cp .env.example .env
docker-compose exec films-app composer install
docker-compose exec films-app php artisan key:generate --ansi
docker-compose exec films-app bash -c "id -u > user_id.txt"
docker-compose exec --user=root films-app bash -c 'read -r USER_ID<user_id.txt && chgrp -R ${USER_ID} storage bootstrap/cache && echo ${USER_ID}'
docker-compose exec --user=root films-app chmod -R ug+rwx storage bootstrap/cache
docker-compose exec films-app php artisan migrate --seed

echo "Done"
