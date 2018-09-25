#!/bin/bash

docker-compose down

composer install

cp .env.example .env

docker-compose up -d --force-recreate

>&2 echo "Waiting for MySql to run. Please wait....."
sleep 10
>&2 echo "MySql started :)"
>&2 echo "Running all phpunit tests now...."


docker container exec -it home24store-backend-app php artisan migrate --seed
>&2 echo "Database migrations and seeders done..."


docker container exec -it home24store-backend-app composer test
>&2 echo "All tests done..."


>&2 echo "Home24 Store backend api v1 is now ready "
sleep 3
exit 0
