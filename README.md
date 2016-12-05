# simple-php-framework
Simple php framework to play with classes and MVC concepts

## General
This is a simple PHP framework made to understand the approach of MVC pattern and OOP with PHP.

**THIS IS IN NO CASES MEANT TO BE USED ON PRODUCTION OR FOR REAL PROJECTS** (at least, for now)

### Requirements

  - PHP >= 7.0
  - Composer (for autoloading, PHPUnit and Codesniffer)
  - A MySQL server

### Getting started
  - Clone/fork this repository,
  - Copy/paste the `config.default.php` and configure it with your own values.
  - Run `composer install`

**Model generation**
When you have your database set up, run `php console/generateModels` to generate the models for the tables in your db.

When you need to re-write the models, delete the files you want to overwrite and re-reun the command above.

if you want to overwrite all your models, run `php console/generateModels force`.


## Code testing
We use PHPUnit to run the tests and CodeSniffer to define some standards. Pull requests **won't be accepted** if they don't pass the tests.

### PHPUnit
To run PHPUnit before your commits, run `vendor/bin/phpunit ./src/test ./app/test`

### CodeSniffer
To run CodeSniffer before your commits, run `vendor/bin/phpcs -p --extensions=php --standard=./src/codesniffer.xml ./src ./app ./console`.
