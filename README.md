# Favorite films 
An application to demonstrate code.

## Run in development

### Requirements:

* docker
* docker-compose

### How to start:

1. Copy `docker-compose.override.example.yml` to `docker-compose.override.yml`
2. Edit `docker-compose.override.yml` for your preferences.
3. Run `docker-compose up -d --build`
4. Wait services and run `dev_deploy.sh`

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

Auth-app

```
docker-compose exec auth-app vendor/bin/phpunit
```

Films-app

```
docker-compose exec films-app vendor/bin/phpunit
```

### Docs

Swagger on [http://127.0.0.1:8003](http://127.0.0.1:8003) by default.

#### Auth

A user gets _access token_ from __auth-app__ 
by OAuth2 endpoint: `/oauth/token` with username and password.

You need `client_id` and `client_secret`. It can be found:

* As output of command `docker-compose exec auth-app php artisan passport:client --password --no-interaction`
* In table `oauth_clients` of auth-db

Then user uses __access token__ in header `Authorization: Bearer Token` for __films-app__.

__films-app__ requests __auth-app__ by API with this token to get info about user.
This info can be cached, for example in redis.

#### User's info API in auth-app

`GET /api/user` with access token.

Returns:

```
{
    "id": 1,
    "name": "John"
    "email": "some@example.com"
}
```

#### Variables in docker-compose.override.yml:

* `WEB_USER_ID` - id of local user to preserve permissions.
* `XDEBUG_CONFIG: remote_host` - ip of host for remote xdebug.
* `8001` - port for __auth-app__. Used in swagger for OAuth2 endpoint.
* `8002` - port for __films-app__. Used in swagger for API endpoints.
* `8003` - port for swagger.
* `35432` - port for postgres of __auth-db__.
* `45432` - port for postgres of __films-db__.


## License

The MIT License
