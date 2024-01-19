### Issue
ISSUE: #%{source_branch}

%{title}

## Description


### Checklist
> **Note:** Mark all items that apply with an `x`.

## Installs

* [ ] Composer
```
docker compose exec php-apache composer install
```
* [ ] NPM
```
docker compose exec php-apache npm install
```

## Migrations

* [ ] Central
```
docker compose exec php-apache php artisan migrate
```
* [ ] Tenant
```
docker compose exec php-apache php artisan tenants:migrate
```

## Seeders

* [ ] Central ( change **SeederName** to the name of the seeder )
```
docker compose exec php-apache php artisan db:seed --class=SeederName
```
* [ ] Tenant ( change **SeederName** to the name of the seeder )
```
docker compose exec php-apache php artisan tenants:seed --class=\\Database\\Seeds\\Tenants\\SeederName
```

## Browser Tests

* [ ] Chrome
* [ ] Firefox
* [ ] Edge
* [ ] Other ( Specify: )

## Aditional notes


## Screenshots
> **Note:** Screenshots are required for help the QA team to test the feature.

## Video
> **Note:** Video is required for help the QA team to test the feature.
