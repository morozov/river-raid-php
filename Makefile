.PHONY: build
build:
	bin/main.php
test:
	vendor/bin/phpcs
	vendor/bin/phpstan
	vendor/bin/psalm
