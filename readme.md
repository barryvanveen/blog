Source code for [barryvanveen.nl](https://barryvanveen.nl)

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

## Supported by
I couldn't run my website without these awesome services providing free plans for Open Source projects:

- [Bugsnag](https://www.bugsnag.com/open-source) gives me insight into exceptions.
