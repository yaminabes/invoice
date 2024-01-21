# Install Invoice

## Prerequisites

Make sure you have Docker and Docker Compose installed on your machine.

## Build Docker Image
Build the Docker image using the provided `docker-compose.yml` file.

```bash
docker-compose build
```

## Start Containers
This starts the containers defined in the docker-compose.yml file. Ensure that all necessary services, such as the database, are available.

```bash
docker-compose up -d
```

## Access Symfony Container
This allows you to access the interactive shell of the Symfony container.

```bash
docker exec -it www_docker_symfony bash
cd project
```

## Install Symfony Dependencies
Once inside the Symfony container, navigate to the project directory and run composer install to install the project dependencies.

```bash
cd /var/www/project
composer install
```
## Make migrations
```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```
## Load fake data
```bash
php bin/console doctrine:fixtures:load
```
