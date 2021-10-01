DC_EXEC = docker-compose exec
PHP_EXEC = $(DC_EXEC) -T php

NGINX = $$(docker-compose ps -q nginx)
GREEN = $$(tput setaf 2)
YELLOW = $$(tput setaf 3)
RESET = $$(tput sgr0)

UNAME_S := $(shell uname -s)

all: help

help:                  #~ Show this help
	@fgrep -h "#~"  $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e "s/^\([^:]*\):/${GREEN}\1${RESET}/;s/#~r/${RESET}/;s/#~y/${YELLOW}/;s/#~ //"

#~y
#~ Project Setup
#~ ________________________________________________________________
#~r

install:               #~ Install and start the project
install: build start composer-install migrations

build:                 #~ Build docker containers
	docker-compose build

start:                 #~ Start the docker containers
	docker-compose up -d

stop:                  #~ Stop the docker containers
	docker-compose stop

clean:                 #~ Clean the docker containers
clean: stop
	docker-compose rm

destroy:               #~ Remove all docker images
destroy: stop
	docker-compose down --rmi all

.PHONY: install build composer-install start traefik stop clean destroy

#~y
#~ PHP
#~ ________________________________________________________________
#~r

composer-install:      #~ Run composer install.
	$(DC_EXEC) php sh -c "php -d memory_limit=-1 /usr/local/bin/composer install"

migrations:            #~ Run db migrations
	$(PHP_EXEC) bin/console doctrine:database:create --if-not-exists
	$(PHP_EXEC) bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

.PHONY: composer-install migrations

#~y
#~ Tests and code quality
#~ ________________________________________________________________
#~r

test:                  #~ Run security-check + all tests + code quality
test: unit

unit:                  #~ Run PHP Unit tests
	$(DC_EXEC) php sh -c "./bin/phpunit -c ./phpunit.xml.dist"

.PHONY: test unit


code-quality:          #~ Run PhpStan, Php-cs-fixer and PhpMd
code-quality: php-cs-fixer-diff

php-cs-fixer-diff:     #~ Run Php-cs-fixer with diff (mode dry-run)
	$(DC_EXEC) php sh -c "./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php -v --dry-run"

php-cs-fixer:          #~ Run Php-cs-fixer
	$(DC_EXEC) php sh -c "./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php -v"

.PHONY: code-quality php-cs-fixer-diff php-cs-fixer
