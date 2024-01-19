echo $DB_CERTIFICATE | base64 --decode > /var/www/html/certs/mysql

echo "Running Default Worker ..."

SLEEP=${SLEEP:-3}
REST=${REST:-3}
TRIES=${TRIES:-3}
TIMEOUT=${TIMEOUT:-3600}

php /var/www/html/artisan queue:work --sleep=$SLEEP --rest=$REST --tries=$TRIES --timeout=$TIMEOUT --name=Default -v 