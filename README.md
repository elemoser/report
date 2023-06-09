# Report

This project is created within the framework of the course *Objektorienterade webbteknologier* (a.k.a [mvc](https://dbwebb.se/kurser/mvc-v2)) at Blekinge Tekniska Högskolan.

Below are the badges from the (latest) Scrutinizer analysis results:
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/elemoser/report/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/elemoser/report/?branch=main) [![Code Coverage](https://scrutinizer-ci.com/g/elemoser/report/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/elemoser/report/?branch=main) [![Build Status](https://scrutinizer-ci.com/g/elemoser/report/badges/build.png?b=main)](https://scrutinizer-ci.com/g/elemoser/report/build-status/main) [![Code Intelligence Status](https://scrutinizer-ci.com/g/elemoser/report/badges/code-intelligence.svg?b=main)](https://scrutinizer-ci.com/code-intelligence)

## Application

```
# run application
php -S localhost:8888 -t public
```

### Debug

```
# Show the routes
php bin/console debug:router

# Match a specific route
php bin/console router:match /lucky/number

# Clear the cache
php bin/console cache:clear

# Show available commands
php bin/console

```

### Database

I chose SQLite for my database backend and the ORM framework Doctrine for managing database interactions.
I followed this guide to set up Doctrine and integrate it with the database **var/data.db**.
As part of the set up, I created an Entity class for each table in the database, which can be viewed in the folder **src/Entity/**. In the folder **src/Repository/**, you can also find a Repository for each Entity class, which is created automatically by Doctrine.

For my project (kmom10), I further created a PHP script that handles the transfer of all the data from a CSV file into the database. I followed this tutorial to set up the script. The code for the script can be viewed at **src/Command/CsvImportCommand.php**. The script can be run with the following command. Make sure to delete all rows in the tables adventure_room and adventure_items before running the command.

```
# to run php script (src/Command/CsvImportCommand.php)
php bin/console csv:import
```

## PHP Linter and Mess Detection

TL;DR: To validate code use the following commands.

```
# validate code with all linters
composer lint

# PHP Coding Standards Fixer
composer csfix

# PHPMD
composer phpmd

#PHPstan
composer phpstan

```

or 

For more details on the tools, see below.

### PHP Coding Standards Fixer

The Symfony community uses the tool "PHP Coding Standards Fixer" to format the code so it looks the same throughout all Symfony modules.
The tool does not provide any warnings or hints (as per default), it just processes your code to fix its code style.

```
composer csfix:dry
composer csfix
```

### PHPMD - PHP Mess Detector

A mess detector warning you when you write "messy" code with potential problems.

```
# without config file
tools/phpmd/vendor/bin/phpmd src text cleancode,codesize,controversial,design,naming,unusedcode

# with config file
tools/phpmd/vendor/bin/phpmd . text phpmd.xml
```

### PHPStan

A linter tool that tries to "find bugs before they reach production".

```
# execute tool
tools/phpstan/vendor/bin/phpstan analyse src

# validate code on strictest level
tools/phpstan/vendor/bin/phpstan analyse -l 9 src

# with config file
tools/phpstan/vendor/bin/phpstan
```

### Phpmetrics

A tool that reads the source code and creates an HTML report containing quality metrics on the code. This type of information can indicate the level of code quality or code complexity.

```
# to run phpmetrics
tools/phpmetrics/vendor/bin/phpmetrics --config=phpmetrics.json

# or with shortcut
composer phpmetrics
```

### Scrutinizer

A continuous integration (CI) service that checks out the code on every update of your GitHub/GitLab repo, executes the test suite and to analyzes the code. As a result, a few badges show the status of the code.



## Database

```
# Debug the dotenv (database connection)
php bin/console debug:dotenv

# create database
php bin/console doctrine:database:create

# create new entity class
php bin/console make:entity

# migration
php bin/console make:migration
php bin/console doctrine:migrations:migrate

# create Controller
php bin/console make:controller ProductController

# run SQL towards the database
php bin/console dbal:run-sql 'SELECT * FROM product'
```