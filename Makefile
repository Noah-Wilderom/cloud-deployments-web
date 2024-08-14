
websocket:
	@php artisan reverb:start --host="0.0.0.0" --port=8080 --debug

lang:
	@php artisan zora:generate

docker-push: build-fpm build-nginx
	@docker push noahdev123/cloud-deployments:latest
	@docker push noahdev123/cloud-deployments-nginx:latest

build-fpm:
	@docker image build --no-cache -f production/docker/fpm/Dockerfile -t noahdev123/cloud-deployments:latest --target fpm .

build-nginx:
	@docker image build --no-cache -f production/docker/nginx/Dockerfile -t noahdev123/cloud-deployments-nginx:latest --target nginx .

kubernetes:
	@kubectl apply -f ./production/kubernetes/namespace.yaml
	@kubectl apply -f ./production/kubernetes/mariadb.yaml
	@kubectl apply -f ./production/kubernetes/redis-deployment.yaml
	@kubectl apply -f ./production/kubernetes/redis-service.yaml
	@kubectl apply -f ./production/kubernetes/storageclass.yaml
	@kubectl apply -f ./production/kubernetes/configmap.yaml
	@kubectl apply -f ./production/kubernetes/secret.yaml
	@kubectl apply -f ./production/kubernetes/pv.yaml
	@kubectl apply -f ./production/kubernetes/pvc.yaml
	@kubectl apply -f ./production/kubernetes/deployment.yaml
	@kubectl apply -f ./production/kubernetes/migrations.yaml
	@kubectl apply -f ./production/kubernetes/service.yaml
	@kubectl apply -f ./production/kubernetes/ingress.yaml
	@kubectl apply -f ./production/kubernetes/hpa.yaml

deploy: build-fpm build-nginx kubernetes

.PHONY: lang
