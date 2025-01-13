DOCKER_COMPOSE_CMD=docker compose
COMPOSER_CMD=composer
PHP_CS_FIXER_CMD=vendor/bin/php-cs-fixer
PHPUNIT_CMD=vendor/bin/phpunit
PHPSTAN_CMD=vendor/bin/phpstan
SHELL=sh
PHP_CLI=$(DOCKER_COMPOSE_CMD) exec -e XDEBUG_MODE=off php

help:
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

dev-build: ## build container
	$(DOCKER_COMPOSE_CMD) build --no-cache

dev-up: ## start container
	$(DOCKER_COMPOSE_CMD) up -d --wait --remove-orphans
	# See local api: http://localhost:8980/api/doc

dev-init: composer-install setup-db-dev ## initializes the container and sets the database up
	# See local api: http://localhost:8980/api/doc

setup-db-test: create-db-test run-migrations-test fixtures-load-test ## sets the test database up

setup-db-dev: create-db-dev run-migrations-dev fixtures-load-dev ## sets the dev db up and fills it

composer-install: ## install php dependencies
	$(PHP_CLI) composer install

php-cli: ## open a bash on the php container
	$(PHP_CLI) $(SHELL)

dev-check: phpcs phpstan validate-apidoc  ## run qa tools

phpstan: ## run phpstan
	$(PHP_CLI) $(PHPSTAN_CMD) analyse

phpcs: ## run cs fixer (dry-run)
	$(DOCKER_COMPOSE_CMD) exec -e PHP_CS_FIXER_FUTURE_MODE=1 php $(PHP_CS_FIXER_CMD) fix --verbose --diff --dry-run

phpcs-fix: ## run cs fixer
	$(DOCKER_COMPOSE_CMD) exec -e PHP_CS_FIXER_FUTURE_MODE=1 php $(PHP_CS_FIXER_CMD) fix

phpunit: ## run phpunit
	$(DOCKER_COMPOSE_CMD) exec -e CLEAR_CACHE=yes -e REFRESH_DATABASE=yes php $(PHPUNIT_CMD) --display-deprecations

cache: ## clear cache
	$(PHP_CLI) bin/console cache:clear

create-db-%: ## setup ENV database
	$(PHP_CLI) bin/console doctrine:database:drop --env=$* --force
	$(PHP_CLI) bin/console doctrine:database:create --env=$*

run-migrations-%: ## setup ENV database
	$(PHP_CLI) bin/console doctrine:migrations:migrate -n --env=$*

fixtures-load-%: ## load alice fixtures for ENV
	$(PHP_CLI) bin/console hautelook:fixtures:load -n --env=$*

validate-apidoc: dump-apidoc vacuum-report vacuum-check ## dumps and validates the api doc and creates an html report
	# See report: http://localhost:63342/showcase-workflow-api/app/var/log/vacuum/report.html?_ij_reload=RELOAD_ON_SAVE

dump-apidoc: ## dumps the api doc into app/apidoc.yaml
	$(PHP_CLI) bin/console nelmio:apidoc:dump --format=yaml > app/apidoc.yaml

vacuum-check: ## validates the api doc
	$(DOCKER_COMPOSE_CMD) run --rm openapi /bin/bash -c "touch /temp/apidoc.yaml && vacuum lint -r /temp/ruleset.yaml /temp/apidoc.yaml -d --fail-severity warn"

vacuum-report: ## create api doc report
	$(DOCKER_COMPOSE_CMD) run --rm openapi /bin/bash -c "touch /temp/apidoc.yaml && vacuum html-report -r /temp/ruleset.yaml /temp/apidoc.yaml"
