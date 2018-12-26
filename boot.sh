cd /app
composer install
npm install
php vendor/bin/phinx migrate -c app/config/config-phinix.php
cd public
npm install -g yarn
yarn install && yarn run build
cd /app && chown -R www-data:www-data .
exec /usr/bin/supervisord -n "$@"
