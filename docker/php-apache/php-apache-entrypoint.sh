echo $DB_CERTIFICATE | base64 --decode  > /var/www/html/certs/mysql
php artisan nova:publish
php artisan telescope:publish
php artisan l5-swagger:generate
php artisan migrate --force
php artisan tenants:migrate
php artisan cache:clear

php artisan storage:link

apache2-foreground
