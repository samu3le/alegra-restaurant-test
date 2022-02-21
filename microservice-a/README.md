## Entorno de desarrollo


Ubuntu 20.04.03


Composer version 2.2.3 2021-12-31 12:18:53


Laravel 9


PostgreSQL 14.2



### docker --version

    Docker version 20.10.11, build dea9396

### docker-compose --version

    docker-compose version 1.27.4, build 40524192

## Cómo instalar y usar Docker en Ubuntu 20.04

https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-20-04-es

https://docs.docker.com/engine/install/linux-postinstall/#configure-docker-to-start-on-boot

## Comandos para arrancar el contenedor:

    sudo docker-compose build --no-cache
    sudo docker-compose up -d --build --remove-orphans

## Nota, comandos docker:

### Comandos para ver el log de un contenedor:

    sudo docker-compose logs -t -f web

### Comandos para bajar todos los contenedores:

    sudo docker-compose down

### Comandos para bajar todos los contenedores:

    sudo docker rm -f $(docker ps -a -q)

### Comandos para bajar todas las imagenes:

    sudo docker rmi -f $(docker images -aq)

### Comandos para bajar todos los volumenes:

    sudo docker volume rm $(docker volume ls -q)

## Si el puerto esta ocupado, se puede usar el comando para liberarlo:

    lsof -t -i tcp:8000 | xargs kill -9

## Postman Collection

[Archivo .json](/public/postman/test-restaurant.postman_collection.json)

## Database structure

![Diagram ERD](/public/diagrams/erd.png)
