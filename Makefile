default: dev

dev: prerequisitos
	- cp api/docker/dev/env.dev/.env.dev api/.env

	- docker exec suficienciaWEB2 sh -c 'composer update'

	- docker exec suficienciaWEB2 sh -c  'php artisan key:generate'

	- docker exec suficienciaWEB2 sh -c  'php artisan l5-swagger:generate'

	- chmod o+w api/storage/ -R 

prerequisitos:
	- docker-compose -f docker-compose.yml up -d