###
# Indiana Coronavirus Tracker
###

###
## Intention
###
To Keep track of daily data from the Indiana coronavirus website, and create
more detailed reports with the data. This is also to fuel my own desire to
keep people more informed in this crazy Pandemic.

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