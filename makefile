up:
	./vendor/bin/sail up -d

down:
	./vendor/bin/sail down


shell:
	./vendor/bin/sail shell


migrate:
	./vendor/bin/sail artisan migrate


reset-db:
	./vendor/bin/sail artisan migrate:fresh --seed
	./vendor/bin/sail artisan passport:install

code-style-check:
	./vendor/bin/phpcs --standard=PSR12 app/ --colors

code-style-fix:
	./vendor/bin/phpcbf --standard=PSR12 app/ --colors

docs:
	php artisan l5-swagger:generate
