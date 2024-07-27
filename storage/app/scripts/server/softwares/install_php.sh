#!/bin/bash

VERSION="{{VERSION}}"

apt update && apt upgrade -y

apt-get install ca-certificates apt-transport-https software-properties-common openssl -y

add-apt-repository ppa:ondrej/php -y

apt-get update

apt-get install php$VERSION php$VERSION-fpm php$VERSION-bcmath php$VERSION-curl php$VERSION-mbstring php$VERSION-mysql php$VERSION-tokenizer php$VERSION-xml php$VERSION-zip -y
