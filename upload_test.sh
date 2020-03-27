#!/bin/sh
rm -rf output
php bin/generate.php
rsync -avzuh -e ssh output/ argon.tuxed.net:/var/www/html/fkooman/letsconnect-vpn.org --progress --exclude '.git'
