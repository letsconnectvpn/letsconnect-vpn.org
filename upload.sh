#!/bin/sh
rm -rf output
php bin/generate.php
rsync -avzuh -e ssh output/ ${HOST}:/var/www/html/web/letsconnect-vpn --progress --exclude '.git'
