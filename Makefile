#Запустить проект
init: down rebuild create-env  composer-install migrate-db

up:
	docker-compose -f docker-compose.yml up -d
down:
	docker-compose -f docker-compose.yml down
rebuild:
	docker-compose -f docker-compose.yml up -d --build
create-env:
	docker exec -t M1HT-php cp .env.example .env
migrate-db:
	docker exec -t M1HT-php php bin/console doctrine:migrations:migrate --no-interaction
composer-install:
	docker exec -t M1HT-php composer install
