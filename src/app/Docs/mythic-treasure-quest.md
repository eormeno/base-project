# Mythic Treasure Quest GDD

## Índice
- [Resumen del Juego](#resumen-del-juego)
- [Género](#género)
- [Plataforma](#plataforma)
- [Público Objetivo](#público-objetivo)
- [Historia](#historia)
- [Mecánicas de Juego](#mecánicas-de-juego)
  - [Exploración de Mazmorras](#exploración-de-mazmorras)
  - [Desbloqueo de Celdas](#desbloqueo-de-celdas)
  - [Uso de Pistas](#uso-de-pistas)
  - [Inventario y Objetos](#inventario-y-objetos)
  - [Progresión y Niveles](#progresión-y-niveles)
- [Controles](#controles)
- [Estilo Visual](#estilo-visual)
- [Sonido](#sonido)
- [Niveles y Progresión](#niveles-y-progresión)
- [Diseño de niveles](#diseño-de-niveles)
  - [Nivel 1: La Cueva de los Antiguos](#nivel-1-la-cueva-de-los-antiguos)
    - [Descripción](#descripción)
    - [Mapa del Nivel](#mapa-del-nivel)
    - [Detalles del Nivel](#detalles-del-nivel)
      - [Celdas Reveladas](#celdas-reveladas)
      - [Tesoros](#tesoros)
      - [Trampas](#trampas)
      - [Monstruos](#monstruos)
      - [Celdas Vacías](#celdas-vacías)
    - [Pistas del Nivel](#pistas-del-nivel)
    - [Objetos del Inventario](#objetos-del-inventario)
- [Aspectos Técnicos](#aspectos-técnicos)

## Resumen del Juego
**Mythic Treasure Quest** es un juego de puzzle y aventura en el que los jugadores exploran mazmorras llenas de trampas, monstruios y tesoros. El objetivo es descubrir todos los tesoros mientras se evitan trampas y monstruos. Los jugadores deben utilizar pistas para identificar las celdas seguras y las que contienen peligros.

## Género
Puzzle, Aventura

## Plataforma
Web

## Público Objetivo
Jugadores de todas las edades interesados en juegos de lógica y aventura.

## Historia
El jugador asume el rol de un cazador de tesoros en busca de reliquias míticas escondidas en antiguas mazmorras. Cada mazmorra está repleta de peligros, incluyendo trampas ocultas y monstruos guardianes. Con la ayuda de un mapa y pistas encontradas en la mazmorra, el jugador debe navegar con cuidado para encontrar todos los tesoros y salir con vida.

## Mecánicas de Juego

### Exploración de Mazmorras
- El jugador explora una cuadrícula que representa una mazmorra.
- Cada celda puede contener un tesoro, una trampa, un monstruo o estar vacía.
- Las celdas no descubiertas muestran un símbolo genérico hasta que se revelan.

### Desbloqueo de Celdas
- El jugador puede revelar una celda a la vez.
- Algunas celdas proporcionan pistas sobre el contenido de las celdas adyacentes (número de trampas, monstruos o tesoros cercanos).

### Uso de Pistas
- Las pistas son números que indican cuántas trampas, monstruos o tesoros están en las celdas adyacentes.
- El jugador debe usar la lógica para deducir la ubicación de las trampas y evitar revelarlas.

### Inventario y Objetos
- El jugador puede recolectar objetos que le ayudan en su aventura, como mapas adicionales, herramientas para desactivar trampas, y pociones para curar heridas de monstruos.
- Los objetos recolectados se almacenan en el inventario y pueden ser usados en cualquier momento.

### Progresión y Niveles
- Cada mazmorra completada desbloquea nuevas mazmorras con mayor dificultad.
- La dificultad aumenta con más trampas, monstruos más poderosos y configuraciones de mazmorra más complejas.

## Controles
- **PC/Mac**: Clic izquierdo para revelar celdas, clic derecho para marcar posibles trampas/monstruos, teclado para usar objetos del inventario.
- **Móviles**: Toque para revelar celdas, toque largo para marcar posibles trampas/monstruos, deslizamiento para usar objetos del inventario.

## Estilo Visual
- **Gráficos**: Estilo de arte 2D detallado con temática de fantasía medieval.
- **Interfaz**: Interfaz clara y fácil de usar con íconos intuitivos para el inventario y las pistas.

## Sonido
- **Música**: Banda sonora épica y ambiental que cambia según la situación en la mazmorra.
- **Efectos de sonido**: Sonidos distintivos para revelar celdas, encontrar tesoros, activar trampas y encuentros con monstruos.

## Niveles y Progresión
- **Niveles**: Cada mazmorra es un nivel único con su propio diseño y desafíos.
- **Progresión**: Los jugadores desbloquean nuevas mazmorras al completar las anteriores, con mayor dificultad y recompensas.

## Diseño de niveles
### Nivel 1: La Cueva de los Antiguos
#### Descripción
La "Cueva de los Antiguos" es el primer nivel de Mythic Treasure Quest. Es una mazmorra introductoria que enseña a los jugadores las mecánicas básicas del juego, como revelar celdas, usar pistas y manejar el inventario. La cueva está llena de trampas, tesoros y algunos monstruos básicos.
#### Mapa del Nivel
El mapa es una cuadrícula de 8x8 celdas.
#### Detalles del Nivel
##### Celdas Reveladas
Celda (3,3): Muestra un número "2", indicando que hay dos trampas en las celdas adyacentes.
##### Tesoros
Cantidad: 5
Ubicación de los Tesoros:
- (1,1)
- (4,4)
- (5,2)
- (6,7)
- (8,3)
##### Trampas
Cantidad: 3
Ubicación de las Trampas:
- (2,3)
- (3,2)
- (4,3)
##### Monstruos
Cantidad: 2
Ubicación de los Monstruos:
- (6,4)
- (7,5)
##### Celdas Vacías
Todas las demás celdas están vacías o contienen pistas adicionales.
#### Pistas del Nivel
- La pista numérica "2" en la celda (3,3) indica que hay dos trampas en las celdas adyacentes: (2,3) y (4,3).
- El jugador debe usar esta información para deducir que las celdas (2,2), (3,2) y (4,4) son seguras para revelar.
#### Objetos del Inventario
- Mapa Antiguo: Revela aleatoriamente una celda segura en la mazmorra.
- Desactivador de Trampas: Permite desactivar una trampa descubierta.
- Poción de Salud: Restaura la salud del jugador si es atacado por un monstruo.

## Aspectos Técnicos
Implementación en HTML, CSS y JavaScript utilizanzo Laravel como backend.
### Controlador
`App\Http\Controllers\MythicTreasureQuestController`
### Vistas
**Carpeta de vistas**: `resources/views/mythic-treasure-quest`
- **Vista principal**: `index.blade.php`
### Campos para migración
|Campo|Tipo|Descripción|
|-------------|-----|-----------------|
|id|int|Identificador de la partida.|
|user_id|int|Identificador del jugador.|
|state|string|Estado actual del juego.|
|level|int|Nivel actual del jugador.|
|map|json|Mapa de la mazmorra.|
|inventory|json|Inventario del jugador.|
|health|int|Salud actual del jugador.|
|is_finished|bool|Indica si la partida finalizó.|
#### Modelo
#### Rutas
**Rutas**: `routes/web.php`
- **Ruta principal**: `/mythic-treasure-quest`
- **Ruta para guardar el progreso**: `/save`
- **Ruta para cargar el progreso**: `/load`
- **Ruta para reiniciar el juego**: `/reset`

