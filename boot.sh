cd /app
composer install
php vendor/bin/phinx migrate -c app/config/config-phinix.php
cd public
npm install -g yarn
yarn run build
exec /usr/bin/supervisord -n "$@"
