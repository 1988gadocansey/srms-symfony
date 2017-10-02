# SymfoNyx • Symfony 3.3.5

## Status

[![Build Status](https://travis-ci.org/maxperei/SymfoNyx.svg?branch=master)](https://travis-ci.org/maxperei/SymfoNyx)

### Installed bundles and other stuff

- FriendsOfSymfony/FOSUserBundle
- A specific UserBundle to extend from FosUserBundle (with `createdAt` and `updatedAt` extra fields)
- KnpLabs/KnpPaginatorBundle
- Form templates
- Symfony Reverse Proxy configured to allow esi parts
- phpunit/phpunit (with a symbolic link under your bin folder. Simply run `phpunit` or `./bin/phpunit`)
- Atlantic18/DoctrineExtensions (Slug generation)
- twig/extensions (time_diff)
- translation enabled in config
- symfony/assetic-bundle installed and configured
- doctrine/doctrine-fixtures-bundle
- Sass
- Gulp
- LiveReload
- Bootstrap 3 with horizontal login
- jQuery

## Installation

- PHP >= 5.5.9 required
- NodeJS >= 6.9.4
- npm >= 5.3.0
- Bower >= 1.8.0

#### • Installing Composer

Composer is the dependency manager used by modern PHP applications and it can also be used to create new applications.

Download the installer from [getcomposer.org/download](https://getcomposer.org/download/), execute it and follow the instructions.

Above all run at your project root : 
`composer install`

#### • Installing NodeJs and npm

Go to : [nodeJS.org](https://nodejs.org/fr/), and follow the instructions.

Then, run (inside the project) : 
`npm install`

#### • Installing Bower

Go to : [bower.io](https://bower.io/), and follow the instructions.

Then, run (inside the project) : 
`bower install`

#### • Installing LiveReload

Go to : [LiveReload](https://chrome.google.com/webstore/detail/livereload/jnihajbhpnppcggbcgedagnkighmdlei), and follow the instructions.

#### • ORM mapping

In order to map entities with a MySQL database, run :

First, create db : 
`php bin/console doctrine:database:create`

Then, show commands : 
`php bin/console doctrine:schema:update --dump-sql`

Finally, create tables : 
`php bin/console doctrine:schema:update --force`

#### • Loading user fixture

Just before launching the app, we probably want an admin user, so : 
`php bin/console doctrine:fixtures:load`

#### • Launching server (dev mode)

Run : `php bin/console server:start [address:port]`

If port is not specified, go to [http://127.0.0.1:8000](http://127.0.0.1:8000) => refers to `./.web-server-pid`

Then, run : `gassetic build --env=[env]` and it will compile assets to destination folders => refers to `./gassetic.yml`

To make gassetic watch the files and by this way to use the livereload feature, just run : `gassetic`

---

### Credentials

Username : `admin`

Email : `admin@email.com`

Password : `root`