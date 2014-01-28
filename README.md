LaravelControllerGenerator
===============

This library is intended to provide a method to generate matching routes and controllers for Laravel 4.


Requirements
============

This library requires `PHP >= 5.3`

Installation
============

To install, simply `require` the `LaravelControllerGenerator.php` file under `lib`.

You can also install it via `Composer` by using the [Packagist archive](http://packagist.org/packages/jsanc623/LaravelControllerGenerator).

Usage
=====

**From CLI**

    (sample output)
    sh $ ./vendor/jsanc623/LaravelControllerGenerator/CLI.php /app/controllers /app/routes.php /app/LCG.php
         > LEXING LCG.php
         > PARSING LEXED DATA
         > GENERATING CONTROLLERS
         > WRITING CONTROLLERS
         > GENERATING ROUTES
         > WRITING ROUTES
         > DONE.

**Custom Initialization**

    $LaravelControllerGenerator = new LaravelControllerGenerator\Builder(
                                          new LaravelControllerGenerator\Lexer,
                                          new LaravelControllerGenerator\Parser,
                                      );
