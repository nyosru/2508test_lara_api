start:
	docker-compose -f docker/docker-compose.yml up -d
	docker exec 2508test php -r "file_exists('.env') || copy('.env.example', '.env');"

restart:
	docker-compose -f docker/docker-compose.yml up -d --build
