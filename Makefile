start:
	docker-compose -f docker/docker-compose.yml up -d --build
	docker exec 2508test cp .env.example .env
	docker exec 2508test php artisan key:generate
	docker exec 2508test composer i
	docker exec 2508test php artisan migrate --seed

restart:
	rm -rf /mysql_db
	cd docker && docker-compose down
	docker-compose -f docker/docker-compose.yml up -d --build
	docker exec 2508test cp .env.example .env
	docker exec 2508test php artisan key:generate
	docker exec 2508test php artisan migrate --seed

