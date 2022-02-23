# Proyecto desplegado en Heroku
https://test-restaurant-db.herokuapp.com/


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

### 1- Crear archivo .env en cada uno de los contenedores de servicios y en carpeta apps
    Puede usar de referencia el archivo .env.example

### 2- Ingresar a carpeta apps 
    cd apps/

### 3- Ejecutar comandos para crear un contenedor:
    sudo docker-compose build --no-cache
    sudo docker-compose up -d --build --remove-orphans

### 4- Ejecutar comando para ver direccion IP de un contenedor:
    docker inspect -f '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}' test_restaurant_db

### 5- Ingresar a carpeta de servicio api-gateway (apps/api-gateway)
    cd api-gateway/
### 6- Renombrar {DB_HOST} en archivo .env 

    Agregar direccion dada en el paso 4

### 7- Ejecutar migraciones, (carga tabla en base de datos y datos de prueba)   
    php artisan migrate

### 8- Renombrar {DB_HOST} en archivo .env 

    Agregar nombre de host dado a base de datos (Use por defecto 'test_restaurant_db')   

### Ver ruta principal para iniciar el proyecto

    docker inspect -f '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}' apps_api_gateway_1

    Usar agregando prefijo '/api/v1/'

## Nota, comandos docker:

### Comando para ver contenedores activos
    docker ps
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
