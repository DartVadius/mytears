docker-up:
	docker-compose up -d

docker-down:
	docker-compose down

docker-build:
	sudo chown ${USER}:${USER} storage -R
	docker-compose up --build -d --force-recreate

test:
	docker-compose exec php-cli vendor/bin/phpunit

make-migration:
	docker-compose exec php-cli php artisan doctrine:migrations:diff

migrate:
	docker-compose exec php-cli php artisan doctrine:migrations:migrate
	make doc

perm:
	sudo chgrp -R www-data storage bootstrap/cache
	sudo chmod -R ug+rwx storage bootstrap/cache

doc:
	php artisan ide-helper:generate

fix-yarn:
	curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -
