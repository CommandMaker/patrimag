.PHONY: analyse
analyse:
	@./vendor/bin/phpstan analyse --memory-limit=2G

.PHONY: lint
lint:
	@./vendor/bin/pint

.PHONY: lint-test
lint-test:
	@./vendor/bin/pint --test