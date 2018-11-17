# Favorite films 
An application to demonstrate code.

## Run in development

### Requirements:

* docker
* docker-compose

### How to start:

1. Copy `docker-compose.override.example.yml` to `docker-compose.override.yml`
2. Edit `docker-compose.override.yml` for your preferences.
3. Run `dev_deploy.sh`

#### In case something went wrong

Contents of `dev_deploy.sh`

* Install composer dependencies `composer install`
* Copy `.env.example` to `.env`
* Generate key `php artisan key:generate --ansi`
* Give permissions:
```
chgrp -R www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache
```
* Create oauth keys: `php artisan passport:keys`
* Migrate and seed: `php artisan migrate --seed `
* Create oauth client: `php artisan passport:client --password --no-interaction`


### Testing

#### Auth-app

Run
```
docker-compose exec auth-app vendor/bin/phpunit
```

#### Films-app

Run
```
docker-compose exec films-app vendor/bin/phpunit
```

### Docs

Swagger on [http://127.0.0.1:8003](http://127.0.0.1:8003) by default.

## License

The MIT License
