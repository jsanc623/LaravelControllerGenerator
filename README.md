password_compat
===============

This library is intended to provide forward compatibility with the password_* functions being worked on for PHP 5.5.


Requirements
============

This library requires `PHP >= 5.3.7`

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
