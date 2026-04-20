# Запуск
```
git clone https://github.com/IlyaVafin/WordleCake.git
```
### Создаем .env
``` 
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:c7Ri9cyqoRHR8OMQ1zqUvOsQzUdCkuJtS9ZlxPYt1Nw=
APP_DEBUG=true
APP_URL=http://localhost:8000

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=game_app
DB_USERNAME=root
DB_PASSWORD=root

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MEMCACHED_HOST=127.0.0.1

VITE_APP_NAME="${APP_NAME}"

JWT_SECRET=QLycpKnDhufk6wKdPhUOyqvH4SnkynZHw0NpOVfwOZEuDPr4EJvoy8TesGtHaDlG

```
### Запускаем докер, пишем в корне проекта команду для билда
``` 
docker-compose up -d --build
```
### Переходим в контейнер
``` 
docker-compose exec -it app bash
```
### Внутри контейнера
``` 
composer install 
npm i
php artisan migrate 
php artisan db:seed
composer run dev
```
