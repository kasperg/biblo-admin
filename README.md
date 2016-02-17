# biblo.dk Administration

This is the administration interface for biblo.dk based on Drupal 8.

The project structure is based on [`drupal-composer/drupal-project`](https://github.com/drupal-composer/drupal-project).

## Development dependencies

* Docker and Docker Compose
* Composer

## Installation instructions

1. Clone this repository
2. From the root of the cloned repository:
  1. Run `composer install`
  2. Run `docker-compose build`
  3. Run `docker-compose up`
3. Profit!

### Optional installation instructions

* If you wish to provide "real data" instead of using "dummy fallbacks", then copy `example.docker-compose.override.yml` to `docker-compose.override.yml` and fill out the required enviroment variables.

## Webservice client generation

To generate a PHP webservice client for accessing [the community service](https://github.com/DBCDK/dbc-community-service) run the following command:

`docker-compose run swagger generate -i http://service:3000/explorer/swagger.json -l php -o /var/usr/client -c /var/usr/swagger/config.json`

Afterwards remember to fix file permissions.

Note that this generates quite a lot of noise in the log. The process completes
anyhow.

## Continuous Integration

* We use Scrutinizer to analyze the code for code standards, debugging code and general mistakes.
  * Codesniffer runs with Drupal standards.
  * The ESLint file (`.eslintrc`) in the root of the project is a duplicate of Drupal 8's `.eslintrc` file. This is needed because Scrutinizer assumes that the ESLint config file is placed in the root.
