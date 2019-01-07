docker-up:
	docker-compose up -d

docker-down:
	docker-compose down

docker-build:
	sudo chown ${USER}:${USER} storage -R
	docker-compose up --build -d --force-recreate

test:
	docker-compose exec php-cli vendor/bin/phpunit
