echo $DB_CERTIFICATE | base64 --decode > /var/www/html/certs/mysql
# php /var/www/html/artisan queue:work --sleep=3 --tries=3 --timeout=600

echo "Starting workers ..." > /var/www/html/worker.log
nohup php /var/www/html/artisan queue:work --sleep=3 --rest=3 --tries=3 --timeout=3600 --name=Default -v >> /var/www/html/worker.log 2>&1 &
nohup php /var/www/html/artisan queue:work --sleep=3 --rest=3 --tries=3 --timeout=3600 --name=Questionnaires --queue=questionnaires -v >> /var/www/html/worker.log 2>&1 &
nohup php /var/www/html/artisan queue:work --sleep=3 --rest=3 --tries=3 --timeout=3600 --name=CreateTenant --queue=tenant_create  -v >> /var/www/html/worker.log 2>&1 &
nohup php /var/www/html/artisan queue:work --sleep=3 --rest=3 --tries=3 --timeout=3600 --name=TenantSeeder --queue=tenant_seeders -v  >> /var/www/html/worker.log 2>&1 &

tail -f /var/www/html/worker.log
