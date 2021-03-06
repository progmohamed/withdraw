# Contentbird Task
[![Content Bird](https://de.contentbird.io/wp-content/uploads/sites/2/2018/01/contentbird_Logo.png)](https://de.contentbird.io/)
### This script to scraping urls Which you can add it from Back-End .
### Features include:
  - Get title, external links count.
  - Check if url contain's any snippet for Google Analytics like analytics.js(new snippet) or  ga.js(old snippet) or googletagmanager (tags manager snippet for google analytics)
  - Recording all logs in system like adding, modifying and deleting any thing in Back-End also you can send logs to admin email.
  - You can control scraping process like provide crawler with user agent and if we want Consider sub-domains in page as internal or external link.
  - You can also config max idle time for user, change admin email and if you want to send the logs to admin email or no.
  - You can control localization such as add or delete languages (RTL languages support ), or dialect or countries.
  - Full control on users permissions.
  - Display results instantly without reload the page.

### Installation and usage:
  - To Install this script you should download it or run this command in you terminal:
```sh
$ git@github.com:progmohamed/withdraw.git
```
  - After download it run these commands respectively but firstly you must have knowledge in symfony framework to know how you can setup new project:
```sh
$ composer install
```
```sh
$ php bin/console doctrine:schema:update --force
```
```sh
$ php bin/console doctrine:fixtures:load --no-interaction --multiple-transactions
```
```sh
$ php bin/console assets:install --symlink
```
  - After this you should set cron job to run next command every 5 seconds.
```sh
$ php bin/console taskmanager:run
```
 - Go to `http://localhost/withdraw/web/admin` and use these credentials `username:admin, password:admin` and enjoy :)
 - If you are in dev environment you can run tests via this command
```sh
$ ./vendor/bin/phpunit
```
### TO-DO:
 - Enhance Test Cases.
 - Display Internal and External links in show page.
 