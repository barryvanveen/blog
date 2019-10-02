[![Build Status](https://travis-ci.org/barryvanveen/blog.svg?branch=master)](https://travis-ci.org/barryvanveen/blog)
[![Code Coverage](https://scrutinizer-ci.com/g/barryvanveen/blog/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/barryvanveen/blog/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/barryvanveen/blog/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/barryvanveen/blog/?branch=master)

## Getting started
- Copy `.env.example` to `.env` and customize where necessary
- Run `composer install`
- Run `yarn install`
- Run `php artisan key:generate`
- Run `php artisan migrate --seed`
- Copy `.env` to `.env.dusk.local` and change the database name. This will prevent Laravel Dusk from emptying your database on every run.

## Getting started with Laravel Homestead
- Check the [Homestead docs](https://laravel.com/docs/master/homestead) for necessary software (Vagrant, Virtualbox)
- Run `vendor/bin/homestead make` to generate a clean `Homestead.yaml`
- Customize `Homestead.yaml` file
- Update hosts file with link to Homestead ip
- Run `vagrant up`
- Visit the hostname in your browser

## Tooling
The composer.json file contains some shortcuts that can help with checking/fixing code style, running tests and running static analysis.

## Security Vulnerabilities
If you discover a security vulnerability within this code, please send an e-mail to Barry van Veen via barryvanveen[at]gmail.com

## License
The Laravel framework is open-source software licensed under the MIT license.
