echo $DB_CERTIFICATE | base64 --decode > /var/www/html/certs/mysql

SLEEP=${SLEEP:-3}
REST=${REST:-3}
TRIES=${TRIES:-3}
TIMEOUT=${TIMEOUT:-3600}

## if NAME is not set, exit with error
if [ -z "$NAME" ]; then
  echo "ERROR: NAME is not set"
  exit 1
fi

## if QUEUE is not set, exit with error
if [ -z "$QUEUE" ]; then
  echo "ERROR: QUEUE is not set"
  exit 1
fi

echo "Starting worker $NAME with queue $QUEUE"

php /var/www/html/artisan queue:work --sleep=$SLEEP --rest=$REST --tries=$TRIES --timeout=$TIMEOUT --name=$NAME --queue=$QUEUE -v