up: docker-up
init: docker-down-clear docker-pull docker-build docker-up project-init

project-init: project-composer-install

project-composer-install:
	docker-compose run --rm php-cli composer install

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build