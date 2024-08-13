#!/bin/bash
DIR="/projects/{{USER_NAME}}/public_html"

chown -R {{USER_NAME}}:{{USER_NAME}} $DIR

find $DIR -type f -exec chmod 644 {} \;
find $DIR -type d -exec chmod 755 {} \;

chmod -R 775 $DIR/storage
chmod -R 775 $DIR/bootstrap/cache

chmod -R ug+rwx $DIR/storage $DIR/bootstrap/cache
find $DIR/storage -type d -exec chmod g+s {} \;
find $DIR/bootstrap/cache -type d -exec chmod g+s {} \;

systemctl restart nginx
systemctl restart php{{PHP_VERSION}}-fpm
