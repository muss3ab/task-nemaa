# User Transactions API

This Laravel application provides an API to fetch and filter user transactions from multiple data providers.

## Table of Contents
- [Requirements](#requirements)
- [Installation](#installation)
  - [Local Setup](#local-setup)
  - [Docker Setup](#docker-setup)
- [Configuration](#configuration)
- [API Endpoints](#api-endpoints)
- [Testing](#testing)
- [Adding New Providers](#adding-new-providers)

## Requirements

### Local Setup
- PHP >= 8.2
- Composer
- MySQL >= 5.7
- Laravel 11.x

### Docker Setup
- Docker
- Docker Compose

## Installation

### Local Setup

1. Clone the repository
```bash
git clone https://github.com/muss3ab/task-nemaa.git
cd <project-directory>
```

2. Install dependencies
```bash
composer install
```

3. Set up environment file
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your database in `.env` 

5. copy dataProviders from root project path to storage/app  && set permission 
```bash
cp DataProvider{X,Y}.json storage/app
chmod -R 775 storage 
chmod -R 775 bootstrap/cache
```
6. start server 
```bash
php artisan serve
```

--------------------------------------------------------------------------------

## Run with Docker -- without Sail package 


1. Clone the repository
```bash
git clone https://github.com/muss3ab/task-nemaa.git
cd <project-directory>
```

2. set env file
```bash
cp .env.example .env
```
3. build up and start containers
```bash
docker-compose up -d
```

4. install dependencies and set app 
```bash
docker-compose exec app composer install 
docker-compose exec app php artisan key:generate 
docker-compose exec app php artisan migarte
```

5. Provider files 
```bash
docker-compose exec app mkdir -p storage/app
docker-compose exec app cp DataProvider{X,Y}.json storage/app
docker-compose exec app chmod -R 775 storage 
docker-compose exec app chmod -R 775 bootstrap/cache
```
==================================================================================

The API will be available at `http://localhost:8000`

## Configuration

### Status Codes
- DataProviderX:
  - authorised: 1
  - decline: 2
  - refunded: 3

- DataProviderY:
  - authorised: 100
  - decline: 200
  - refunded: 300

## API Endpoints

### Get Users

GET /api/v1/users

#### Query Parameters
- `provider`: Filter by provider (DataProviderX or DataProviderY)
- `statusCode`: Filter by status (authorised, decline, refunded)
- `balanceMin`: Minimum balance amount
- `balanceMax`: Maximum balance amount
- `currency`: Filter by currency (USD, EUR, AED)

#### Example Requests

# Get all users
curl http://localhost:8000/api/v1/users

# Filter by provider
curl http://localhost:8000/api/v1/users?provider=DataProviderX

# Filter by status
curl http://localhost:8000/api/v1/users?statusCode=authorised

# Filter by amount range
curl http://localhost:8000/api/v1/users?balanceMin=100&balanceMax=300

# Filter by currency
curl http://localhost:8000/api/v1/users?currency=USD

# Combined filters
curl http://localhost:8000/api/v1/users?provider=DataProviderX&statusCode=authorised&currency=USD


========================================================================================================

## Testing

### Run Tests

# local 
```bash
php artisan test 
```

# Docker
```bash
docker-compose exec app php artisan test
```
=================================================

## Adding New Providers

To add a new data provider:

1. Create a new provider class implementing `DataProviderInterface`
2. Register the provider in `DataProviderFactory`
3. Add the provider's status codes to `TransactionStatus` enum