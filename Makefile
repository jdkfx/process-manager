up:
	docker-compose up -d --build

bash:
	docker-compose exec process-manager bash

run:
	docker-compose exec process-manager php bin/run.php
