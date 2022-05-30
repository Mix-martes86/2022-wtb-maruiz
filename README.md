# Witrac - Backend Test - Maruiz, 05/2022

Presente en versión actual:

- Refactor haciendo uso de arquitectura hexagonal y DDD
- Implementación de Test unitarios

Por problemas varios de tiempo no he podido apenas hacer nada con los puntos opcionales (me habría gustado por lo
menos poder tocar el persistir los cuadros a la BDD), pero debido al considerable retraso que llevaba ya en la entrega
he preferido entregarlo ya, tal cual está, para no alargarlo innecesariamente.


# Estado de la aplicación.

He tenido que cambiar la versión utilizada de PHP por problemas de requisitos a la hora de la instalación inicial.
Aparte, he hecho cambios que me han parecido oportunos relativos a los métodos de los endpoints y el cuerpo
de los mensajes.

Por algún motivo que no he conseguido vislumbrar, tras portar el código a la nueva estructura, no se encuentran en
la caché los paneles ya existentes (método FilesystemAdapter::hasItem($canvasName):bool), por lo que la aplicación
no funciona al 100% tal como se espera.

Por lo demás, todo el testing unitario que he podido añadir da OK.

He añadido también un par de archivos .bat (Windows) en la carpeta cmd para agilizar ciertas acciones en vez
de tener que copiar constantemente comandos largos o peor, tener que memorizarlos.

- cmd/composer [comando]
- cmd/php [bin/console comando]
- cmd/phpunit
- cmd/phpstan [--level X]


## Installation
````shell
$ docker-compose up -d --build
$ cmd/composer install
````

## API Endpoints
````text
Create new canvas:
POST http://localhost:8080/create-canvas
- Body en JSON: {'name':'first_canvas','width':5,'height':5}

Movements:
PATCH http://localhost:8080/move/first_canvas/top
PATCH http://localhost:8080/move/first_canvas/right
PATCH http://localhost:8080/move/first_canvas/bottom
PATCH http://localhost:8080/move/first_canvas/left
````
