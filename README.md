# Witrac - Backend Test

La prueba consiste en realizar un refactor completo del código. Aplicando arquitectura hexagonal y DDD.

Requisitos obligatorios:

- Refactor haciendo uso de arquitectura hexagonal y DDD
- Implementación de Test unitarios

Requisitos opcionales:

- Tests de aceptación
- Añadir base de datos para almacenar el estado de la aplicación
- Añadir "obstáculos" al mapa en el momento de su creación
- Modelar el comportamiento de los obstáculos, no debe ser posible avanzar si hay un obstáculo y esta situación se debe de comunicar.
- Añadir el eje "Z" al movimiento de la nave.


# Descripción de la aplicación.

La aplicación implementada con la versión 5.4/6 de Symfony modela el comportamiento de una nave espacial en el 
espacio para su uso en un videojuego retro, como éste es infinito, se ha elegido que el salir por un borde 
implique la entrada por el borde contrario, como si de un mapa se tratara.

Se dispone de un endpoint para generar mapas en 2D. Y una nave espacial que se mueve por el mapa.

Con el primer endpoint creamos un nuevo mapa, dotándolo de nombre y tamaño.

```text
GET http://localhost:8080/create-canvas?
 name=first_canvas&
 width=5&height=5
```

Por ejemplo, hacemos un mapa llamado "first_canvas" de "5x5":

```text
|0,0|0,1|0,2|0,3|0,4|
|1,0|1,1|1,2|1,3|1,4|
|2,0|2,1|2,2|2,3|2,4|
|3,0|3,1|3,2|3,3|3,4|
|4,0|4,1|4,2|4,3|4,4|
```

Se entiende que la posición inicial de la nave, siempre es "0,0":
```text
| X |0,1|0,2|0,3|0,4|
|1,0|1,1|1,2|1,3|1,4|
|2,0|2,1|2,2|2,3|2,4|
|3,0|3,1|3,2|3,3|3,4|
|4,0|4,1|4,2|4,3|4,4|
```

Haciendo uso del endpoint para mover la nave, se puede ir desplazando la nave sobre un mapa específico, con la
peculiaridad que si la nave llega a uno de los límites, ésta ha de aparecer por el margen contrario:

```text
|0,0|0,1|0,2|0,3|0,4|
|1,0|1,1|1,2|1,3| X |
|2,0|2,1|2,2|2,3|2,4|
|3,0|3,1|3,2|3,3|3,4|
|4,0|4,1|4,2|4,3|4,4|
```
Si nos encontramos en la posición "1,4" y nos movemos 1 posición a la derecha, apareceríamos en la "1,0"

## Installation
````shell
$ docker-compose up -d --build
$ docker exec witrac-backend-test-fpm composer install
````

## API Endpoints
````text
Create new canvas:
GET http://localhost:8080/create-canvas?name=first_canvas&width=5&height=5

Movements:
GET http://localhost:8080/move/first_canvas/top
GET http://localhost:8080/move/first_canvas/right
GET http://localhost:8080/move/first_canvas/bottom
GET http://localhost:8080/move/first_canvas/left
````
