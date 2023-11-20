#!/bin/sh
build:
	docker build -t cornatul/paytruh --progress=plain .
build-fresh:
	docker image rm -f cornatul/paytruh && docker build -t cornatul/paytruh --no-cache --progress=plain . --build-arg CACHEBUST=$(date +%s)
up-dev:
	docker-compose -f docker-compose.yml up  --remove-orphans
stop:
	docker-compose down
ssh:
	docker exec -it paytruh /bin/bash
publish:
	docker push cornatul/paytruh