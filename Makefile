# Makefile

current_dir := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))

ifeq ($(shell uname), Darwin)
	SED_INPLACE_FLAG=-i ''
	XDEBUG_CLIENT_HOST := host.docker.internal
else
	SED_INPLACE_FLAG=-i
	XDEBUG_CLIENT_HOST := $(shell hostname -I | cut -d" " -f1)
endif

run: install start

install: \
	env-prepare \
	install-docker-compose-override \
	install-php-ini-files \
	docker-build \
	docker-initialize-mysql \
	docker-up \
	php-install-composer-packages \
	vue-build

env-prepare:
	@test -f .env || cp -n .env.example .env

install-docker-compose-override:
	@test -f compose.override.yml || cp -f compose.override.yml.example compose.override.yml

install-php-ini-files:
	@test -f ./docker/php.ini || cp -n ./docker/php.ini.example ./docker/php.ini
	@test -f ./docker/xdebug.ini || cp -n ./docker/xdebug.ini.example ./docker/xdebug.ini
	sed $(SED_INPLACE_FLAG) "s/XDEBUG_CLIENT_HOST/${XDEBUG_CLIENT_HOST}/" ./docker/xdebug.ini

docker-build:
	docker-compose build

docker-initialize-mysql:
	docker-compose up -d mysql
	docker run --rm --network url_shortener_net jwilder/dockerize -wait tcp://mysql:3306 -timeout 30s

docker-up:
	docker-compose up -d

php-install-composer-packages:
	docker exec url_shortener_php sh -c "composer update && composer install"

#php-env-prepare:
#	docker exec url_shortener_php cp -n .env.example .env
#	docker exec url_shortener_php php artisan key:generate --ansi

#php-run-migrations:
#	docker exec url_shortener_php php artisan migrate

vue-build:
	docker run --rm --workdir=/var/www/src -v ${current_dir}/src:/var/www/src node:alpine sh -c "yarn install && yarn dev"

start:
	docker-compose up -d
	@echo "Your application is available at: http://localhost:8081"

stop:
	docker-compose stop

clean:
	docker-compose down -v
	rm -rf .env ./src/.env ./src/vendor ./src/node_modules
