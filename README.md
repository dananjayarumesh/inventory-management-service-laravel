
# Inventory Management REST API

A REST API application designed to provide data for a web-based inventory management system. It handles operations such as retrieving inventory details, tracking stock levels, managing item dispatch and receipt, and generating alerts for low stock thresholds.

[![Unit/Feature Tests](https://github.com/dananjayarumesh/inventory-management-service-laravel/actions/workflows/tests.yml/badge.svg)](https://github.com/dananjayarumesh/inventory-management-service-laravel/actions/workflows/tests.yml)


### Overview
* [Development environment setup](#development-environment-setup)
* [Environment variables](#environment-variables)

## Development environment setup

This repository is designed to use a development container.

### Prerequisites

The prerequisites for working on this repo are:

-   Docker Desktop (MacOS, Windows) or docker engine (Linux)
-   GIT
-   VS Code
-   VS Code Extension "Remote Development"

All other prerequisites are provided by the dev container and its dependencies.

VSCode and other IDEA-based IDEs can use devcontainers to set up the development environment. Simply open the project in the IDE and choose the 'Open in Container' option to get started.

### Install dependencies

```bash
composer install
```

### Copy env sample file

```bash
cp .env.example .env
```

### Generate an application key

```sh
php artisan key:generate
```

### Generate a JWT secret key

```sh
php artisan jwt:secret
```

### Run Unit/Feature Tests

```sh
php artisan test
```

### Analyze and automatically fix coding style violations

```sh
./vendor/bin/phpcs --standard=PSR12 --fix
```

## Environment variables

* JWT_SECRET : JWT secret key