###
# Indiana Coronavirus Tracker
###

###
## Intention
###
To Keep track of daily data from the Indiana coronavirus website, and create
more detailed reports with the data. This is also to fuel my own desire to
keep people more informed in this crazy Pandemic.

<p align="center">
  <img height="300" title="Dashboard" src="/Resources/dashboard-ss.png">
</p>

###
## Prerequisites
###
You will need the following to develop this website - 
- MYSQL
- PHP
- composer
- Symfony CLI
- Node.JS (Yarn)
 
###
## Installation
###
Please note stylesheets are not included since they are from a purchased
theme.
1) Download and install [PHP](https://www.php.net/downloads.php), [Composer](https://getcomposer.org), and [Symfony](https://symfony.com/download)
2) Run `composer install`
3) Run `yarn install`
4) Run `symfony server:start`
5) Run `yarn watch`

###
## Pulling Data
###
Running `./bin/console app:pull-data` will do the following:
1) Fetch the latest data from the coronavirus.in.gov website
2) Backup the data to its own file under Resources/coronavirus.in.gov/
3) Delete data from the MYSQL database
4) Put the latest data into the MYSQL database

###
## Using the project with Docker
##  docker-compose run encore yarn add axios --dev
###
1) `docker-compose up` - `docker-compose build` to rebuild configurations
2) yarn install - copy over assets `docker-compose run encore yarn run watch`
3) composer install `docker-compose run composer`
4) (You can run the pull command by typing in `docker-compose exec php bin/console app:pull-data`)

###
## Debugging APPS
###
1) Configure docker/php/xdebug.ini with the proper IP of your local computer for debugging PHP routes from the server
2) Configure docker-compose.yaml's environment variable of `PHP_IDE_CONFIG` for debugging console commands
3) Configure PHPStorm to PHP -> Servers:
    - Name of server should be the value of ServerName from `PHP_IDE_CONFIG`
    - Host should be _
    - Port should be 80 for NGINX
    - Configure Path Mappings Project files to `/var/www/html`

Removing and stopping all docker containers:
docker stop $(docker ps -a -q)
docker rm $(docker ps -a -q)

babel-polyfill babel-preset-env --dev allow us to use the arrow for shorthand REACT functions
docker-compose run encore yarn add dayjs --dev
docker run --volume $PWD:/app composer require symfony/serializer
