# ESG Maturity

# The onboarding

When you visit the central domain, you can either register or login as a tenant. This doesn't use Laravel authentication — tenants aren't authenticable users in our setup. Instead, we use a more appropriate & simple implementation.

# Registration

The user enters his details:

- company name
- domain
- full name
- email
- password

We create a Tenant instance and persist the data on the tenant. Next, we generate a route that takes the tenant to his home page and impersonates the first user in the database.

The user visits this route in the browser and is shown a "We're building your application" message.

Meanwhile, a queued job runs in the background. It does the following:

- create the tenant's database
- migrate the database
- create a superuser, with the credentials from the tenant (full name, email, password)
- seeds the database with the default data

The job will run pretty much instantly.

The user clicks on the refresh button and if the database is set up already, he'll be taken to the tenant's (sub)domain and logged in as the first user (= the owner).

Note: The "We're building your application" screen is displayed using the exception handler. In some rare instances, it could make local debugging somewhat more difficult, so be aware of how this works.

Also <ins>note that if the queued job runs fast, the user won't even see the "we're building your application" screen</ins>.

And, conversely, if the queued job runs super slow, the user might get a 403. The TTL for the impersonation token is 60 seconds.

# Login

A user enters his email address. Based on the email, we find the correct tenant and redirect the user to the tenant's application's login screen with the email filled in.

Note that **this only works with the owner's email address**. Not any user's email address. We're not using resource syncing here — we're only keeping track of the organization owners.

# Project structure

## LivewireControllers

- App\Http\Controllers\Central
- App\Http\Controllers\Tenant

## Routes

Central route names are prefixed with central. and tenant routes with `tenant.`.

## Views

- central
-- central.tenants
--- central.tenants.login
--- central.tenants.register
-- central.landing
- errors (technically part of the tenant app)
- errors.building
- layouts
-- layouts.central
-- layouts.tenant
- livewire
-- livewire.tenant.*
-- livewire.central.*
- partials
- tenant
-- tenant.auth.* - laravel auth
-- tenant.settings
--- tenant.settings.application
--- tenant.settings.user

## Models

- App\Models\Central\*
- App\Models\Tenant\*

## Middleware

We're using the `InitializeTenancyByDomainOrSubdomain` middleware. To make life less of a repetitive pain, we created a `tenant` middleware group in `app/Http/Kernel`.php. It includes this tenancy middleware, `'web'` and `PreventAccessFromCentralDomains`.

# User Interface

The frontend is made with server-rendered Blade and Tailwind CSS. In some places, we're using Alpine JS for interactivity.

# Livewire

Just like we use Alpine for frontend interactivity, we use Livewire for interactive forms that require a round-trip to the server.

I decided to go with Livewire because:

- it's literally Blade and nothing else — everyone who can make a Laravel app can understand Livewire
using any specific frontend framework - would impose a heavy technical decision and changing it would be a pain
- there's no need to write an API, which is also heavy. If we decide we don't want dynamic forms like these and want regular POST forms, we can just copy the code from the Livewire component, copy the view, and make a controller-route-form thing.

# Nova Admin Panel

We can manage tenants, their domains, and administrators in a Nova admin panel.

Nova can be visited on `/bo`.

# Creating an admin

You can create an admin user in the central database using the `php artisan nova:user` command.

# Domains

The package provides us with a lightweight layer for identifying tenants using domains.

## Primary domains
Each tenant has one primary domain. This domain is used for generating tenant routes. We can use the following helper to generate tenant routes, with beautiful syntax:

- `$tenant->route('tenant.home');`
- `tenant()->route('tenant.settings.user'); // Don't forget that we can use tenant() to get the current tenant`

## Fallback domains

Each tenant can add any number of custom third-party domains. He can also make them primary. But in case something goes wrong, we need a fallback domain on which the application will be guaranteed to be accessible.

The fallback domain should be a subdomain on our central domain.

# Domain management UI

In the domain management part of the Application Settings page, user can add custom domains, mark domains as primary, remove domains, and change fallback domains.

Primary domains or fallback domains cannot be removed. If user is visiting a fallback domain (we're using a secret trick to check that) and change it, he will be redirected to the new fallback domain.

# SaaS config file

The config/saas.php file stores some config keys related to the SaaS functionality. Currently, it stores the trial length, Stripe keys (loaded from env), and your subscription plans.

# Settings

On the user settings screen, any user can:
- update their name, email and password.

On the application settings screen, the **owner** can:

- change the application name
- change the application's domains
- change billing settings

# Authentication

We have two separate authenticable models. One for the central app and one for the tenant app.

In the central app, the Admin model is the authenticable user. In tenant app, it's User.

This brings the issue of "what model will `auth()->user()` return?".

We use separate auth guards. If you go to `config/auth.php`, you'll see this:

```
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
 
    'admin' => [
        'driver' => 'session',
        'provider' => 'admins',
    ],
```

We have a `web` (default name) auth guard, for the tenant app. And an `admin` auth guard for the central app.

Now, we don't have any controllers in the central app that would use auth. So in our case, we only told **Nova** to use the `admin auth guard and we're done.

Note: The authentication routes are registered in a route group with a `tenant`. name prefix, so you should use `tenant.register`, not `register`.

# Testing
An important part of this boilerplate is the test suite.

There are two types of tests:

* Central tests. These happen in your central app. They also may create tenants, but not necessarily.
* Tenant tests. These happen PURELY in your tenant app. They don't make requests to the central app.

For central tests, it's simple. We write tests like you're used to. If we need to create a tenant in the tests, we may use `$this->createTenant()` or the factory or create the model directly. Then we can `tenancy()->initialize($tenant)` if we need to. Though keep in mind that we should probably `tenancy()->end()` too so that the test cleanup happens properly.

For tenant tests, we do some nice magic.

- We create a tenant initialize tenancy
- We force the app URL to that tenant's URL
- We set the SERVER_NAME and HTTP_HOST server variables to that tenant's domain (tenant.localhost)
- We log in as the superadmin (feel free to remove this part, it's in TenantTestCase)
The magical thing about forcing the URLs and hostnames is that you can use:

`$this->get(companies)->assertSee('foo');`

**as if tenancy didn't exist at all!** You don't have to think about it for one second.

Normally, we'd have to make a request to `http://tenant.test/companies`. While that's fine once in a while, having to think of that constantly would be a pain and our tests would be a mess.

Also, tests use `localhost` as the central domain. This is set in `phpunit.xml's` env. The reason for this is that changing the central domains config in `setUp()` doesn't work — the config is read before `setUp()` is executed to register the central routes.

Finally, one thing to keep in mind: The tests use our central connection with the saas_test database. This is in `phpunit.xml`. **Be sure to create that database.**

Tenants' test databases are created as MySQL databases (and users).

# Billing

Cashier Stripe is used for billing.

A user may configure billing on the Application Settings screen.

The user first needs to add a payment card, then he can select a subscription, and then he can download invoices. He will be shown the upcoming payment along with the current state of his subscription.

**0 days** of trial are used.

The code related to billing is located in Livewire components included in the `tenant/settings/application` view. Another part is in the `CreateTenantAction` where we create the tenant as a Stripe customer.

One thing to keep in mind is that we made some changes to Cashier migrations (so that we can use the Tenant model) and disabled them in AppServiceProvider.

# Development environment

1. Install docker ([documentation](https://docs.docker.com/engine/install/) | [documentation for windows](https://docs.docker.com/desktop/install/windows-install/))
2. Install docker-compose [documentation](https://docs.docker.com/compose/install//)
3. Add main domain and some sub-domains to hosts file
3. Clone the project `git clone git@gitlab.com:c-more/app.git`
4. Copy .env.development to .env:  `cp .env.development .env`
5. Copy AWS and Portal credentials from our slack channel to .env file
6. Run `docker login registry.gitlab.com` and use your `gitlab credentials` to login in the docker registry
7. Start the project `docker compose up -d --force-recreate --remove-orphans`
8. Final steps:

```
echo "Installing NODE dependencies..."
docker compose exec -it php-apache npm install

echo "Compiling assets..."
docker compose exec -it php-apache npm run dev

echo "Running composer install..."
docker compose exec -it php-apache composer install

echo "Clearing cache..."
docker compose exec -it php-apache php artisan optimize:clear

echo "Installing NODE dependencies..."
docker compose exec -T php-apache npm install

echo "Compiling assets..."
docker compose exec -T php-apache npm run dev

echo "Running composer install..."
docker compose exec -T php-apache composer install

echo "Clearing cache..."
docker compose exec -T php-apache php artisan optimize:clear

echo "Running central migrations..."
docker compose exec -T php-apache php artisan migrate

echo "Running tenant migrations..."
docker compose exec -T php-apache php artisan tenants:migrate
```

## Add domain and sub-domains to hosts file

### Linux

```
sudo nano /etc/hosts
saas.test test.saas.test # Add this line to the one that starts with "127.0.0.1"
```

### Windows

On Windows, launch Notepad (or another text editor) as Administrator. Open the hosts file, which is located under C:\Windows\System32\drivers\etc.

```
saas.test test.saas.test # Add this line to the one that starts with "127.0.0.1"
```

## Back offices

[Central Back Office](http://saas.test/bo)
[Tenant Back Office - Test Tenant](http://test.saas.test/bo)

**User:** <dev@cmore.pt>
**Password:** dev

## Front offices

[Central](http://saas.test)

[Tenant Front Office - Test Tenant](http://test.saas.test)

## Some tips

### GIT

- After a new pull or switch to a new branch , a few tips:
  - docker compose pull + docker compose up -d  ### to update the images and restart the containers
  - migrations and tenants migrations
  - composer install and npm install
  - container running the job , its good restart.

### Gitlab

- Search for the issues that are assigned to you;
- inside the issue , click to the *Create Merge Request* button to create a new branch and merge request;
- Add the label : dev::in progress;
- At your local machine ,  run
  - git fetch origin
  - git checkout <branch_name>
- Keep a good commit history , commiting small changes , with a good description of the changes;
- After finish the issue ,  remove the "Draft" from the merge request and add the label : dev::ready for review;
- If you are working on a big issue  , update your main branch frequently and do rebase to keep your branch updated;
  - git checkout main
  - git pull origin main
  - git checkout <branch_name>
  - git rebase main
  - git push origin <branch_name> --force

### Frontend - Livewire

- The frontend its with livewire , so , if you change anything at blade/config files and didnt change at the screen , run the npm run dev ( or watch )
  - docker compose exec -it php-apache npm run dev

### Folders

- The folders are organized by modules , so , if you create a new module , create a folder with the name of the module and inside of it , create the folders ( controllers , models , etc ) .
- The vendor folder only exists inside of the container. So , you can execute the command to copy the vendro folder to your local machine , to have the autocomplete, linters, etc
- The node modules, contains some packages only inside of the container , so , if you need to use some package
  - sudo docker compose cp php-apache:/var/www/html/vendor .
  - sudo docker compose cp php-apache:/var/www/html/node_modules .
- After change/add anything inside the *public* folder , run the command to copy the public to the container
  - sudo docker compose cp public php-apache:/var/www/html/

### Database

- Avoid to create data on the tables that are used in ours seeders, like BusinessSector,  questionnaireTypes , etc.  These tables need to have only the data from our seeders
- If you dont have any data at your tables  , check your .env ( aws and portal credentials ) and if is missing , copy from the "general channel" . If you create data manually  , truncate the table to drop and reset the AI .

### Tenants

- To update the tenant data:
  - Go to saas.test/bo
  - Find your tenant
  - Click on the checkbox or click on the tenant name
  - Click on the action button and select **Seed: All portal data**
- If you create a new tenant , dont forget to go to your **hosts** file and add the domain of your tenant

## Questions or Missing informations?

Please ask for help in #help slack channel.
