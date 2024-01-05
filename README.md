# Password generator API

## Prerequisites
Before you begin, ensure that you have the following:

- Docker installed on your machine.

- Docker Compose installed on your machine. 

## Getting Started

1. Clone your project repository to your local machine.

```
    git clone https://github.com/kukuruzvelt/Test_task_junior_php](https://github.com/kukuruzvelt/passwordApi
   
    cd passwordApi
```

2. Build images with the following command:

```
    docker-compose build
```

3. Launch application with the following command:

```
    docker-compose up -d
```
4. Run migrations with the following command:

```
    docker exec passwordapi-php-1 bin/console d:m:m
```

## Stopping the Application

To stop the application and shut down the containers, run the following command from the same directory where your
docker-compose.yml file is located:

```
docker-compose down
```
