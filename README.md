Recommerce Solution
=====

Recommerce Solution using Symfony 3

Version
----
Current Version 0.1

Bundle / Libraries used
----

* friendsofsymfony/rest-bundle

Server requirements
----
* Docker
*<p>Note : You can still deploy this app with a classic PHP5+ / Nginx / MySQL stack. If so, you'll need to set your parameters in app/config/parameters.yml, be sure to change 'sf3' alias in command by 'php bin/console' and don't mind the 'Enter Docker bash commands' steps.</p>*

Installation
----

#### Clone Github repository

```sh
git clone https://github.com/dim4k/Recommerce.git
```

#### Run the server

*Build/run Docker containers*
```sh
cd docker-symfony
docker-compose build
docker-compose up -d
```

*Composer install and create database*
```sh
# Enter Docker bash commands
docker-compose exec php bash

# Install Composer dependencies, if you're usinge Docker just use default database settings
composer install

# Create database
sf3 doctrine:database:create
sf3 doctrine:schema:update --force

# Load fixtures
sf3 doctrine:fixtures:load

# Exit Docker bash commands
exit
```

*Get containers IP address*
```sh
docker network inspect bridge | grep Gateway
```

Access the API
----

Use a Rest client or simply use Curl to access the API

* GET : /brands
* POST : /brands
* GET : /brands/&lt;id&gt;
* DELETE : /brands/&lt;id&gt;
* GET : /products
* POST : /products
* GET : /products/&lt;id&gt;
* DELETE : /products/&lt;id&gt;
* GET : /orders
* POST : /orders
* GET : /orders/&lt;id&gt;
* DELETE : /orders/&lt;id&gt;

Test the app
----

```sh
# Go to Docker folder
cd docker-symfony

# Enter Docker bash commands
docker-compose exec php bash

# Launch tests
./vendor/bin/phpunit
```

TODO
----

* Add PUT methods
* Add JSON web token to secure the API
