# Report

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