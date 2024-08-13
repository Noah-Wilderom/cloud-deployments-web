
websocket:
	@php artisan reverb:start --host="0.0.0.0" --port=8080 --debug

lang:
	@php artisan zora:generate

build-fpm:
	docker image build --no-cache -f production/docker/fpm/Dockerfile -t cloud-deployments:latest --target fpm .

kubernetes:
	@kubectl apply -f ./production/kubernetes/mariadb.yaml
	@kubectl apply -f ./production/kubernetes/redis-deployment.yaml
	@kubectl apply -f ./production/kubernetes/redis-service.yaml
	@kubectl apply -f ./production/kubernetes/storageclass.yaml
	@kubectl apply -f ./production/kubernetes/pvc.yaml
	@kubectl apply -f ./production/kubernetes/deployment.yaml
	@kubectl apply -f ./production/kubernetes/service.yaml
	@kubectl apply -f ./production/kubernetes/ingress.yaml
	@kubectl apply -f ./production/kubernetes/hpa.yaml

deploy: build-fpm kubernetes

.PHONY: lang
