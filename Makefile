
websocket:
	@php artisan reverb:start --host="0.0.0.0" --port=8080 --debug

lang:
	@php artisan zora:generate

.PHONY: lang
