install:
	docker-compose exec php composer install
	docker-compose exec php php artisan key:generate
	docker-compose exec php php artisan migrate
	docker-compose exec php yarn install
	docker-compose exec php yarn run dev

start:
	docker-compose up -d --build --remove-orphans

start-xdebug:
	docker-compose build --build-arg xdebug=true
	docker-compose up -d --no-build --remove-orphans

stop:
	docker-compose down

php:
	docker-compose exec php /bin/bash

nginx:
	docker-compose exec nginx /bin/bash

mysql:
	docker-compose exec mysql /bin/bash

selenium:
	docker-compose exec selenium /bin/bash

test: test-unit test-feature test-browser
	docker-compose exec php composer test

test-unit:
	docker-compose exec php composer test-unit

test-feature:
	docker-compose exec php composer test-feature

test-browser:
	docker-compose exec php composer test-browser

install_cert:
	brew install mkcert
	mkcert -cert-file docker/nginx/certs/blog.test.crt -key-file docker/nginx/certs/blog.test.key blog.test
