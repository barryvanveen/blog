Source code for [barryvanveen.nl](https://barryvanveen.nl)

## Getting started
- Copy `.env.example` to `.env` and customize where necessary
- Run `make start` to start all Docker containers
- Run `make install` to install composer and npm dependencies
- (optional) Run `install_cert` to trust the provided certificate for blog.test. This allows you to run it on https without any annoying warnings.

When you are done, run `make stop` to shut down all containers.

Use `make php|nginx|mysql|selenium` you can quickly start a bash terminal in the given container. For example, you can run `make php` to "enter" the php container and then you can run `php artisan migrate:fresh --seed` or any other command.

## Acceptance tests
Tests are run using Laravel Dusk. To get started, copy `.env` to `.env.dusk.local` and make sure that at least `APP_URL`, `DB_DATABASE` AND `DUSK_DRIVER_URL` are correctly set. Hints can be found at the end of `.env.example`.

Then, run `make test-browser`. 

## Mailing
Open up [http://localhost:1080/](http://localhost:1080/) to view any mails in Maildev

## Tooling
The composer.json file contains some shortcuts that can help with checking/fixing code style, running tests and running static analysis.

## Security Vulnerabilities
If you discover a security vulnerability within this code, please send an e-mail to Barry van Veen via barryvanveen[at]gmail.com

## Supported by
I couldn't run my website without these awesome services providing free plans for Open Source projects:

- [Bugsnag](https://www.bugsnag.com/open-source) gives me insight into exceptions.
