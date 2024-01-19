echo $DB_CERTIFICATE | base64 --decode > /var/www/html/certs/mysql

/usr/bin/supervisord -n -c /etc/supervisord.conf
