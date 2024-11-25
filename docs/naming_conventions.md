## Naming Conventions
### GameApps
Una instancia de `GameApp` representa una aplicación de juego. Se define en un seeder (carpeta `database/seeders`) con la siguiente estructura:
- `image`: Es el nombre de la imagen que se usará para la tarjeta de la aplicación de juego. Esta imagen debe estar ubicada en la carpeta `database/seeders/resources`.
- `prefix`: Es el prefijo que se usa para identificar la aplicación de juego en la estructura de archivos y formará parte de los namespaces.
- `name`: Es el nombre de la aplicación de juego.
- `description`: Es la descripción que se muestra al usuario.
- `prefab_name`: Nombre del prefab que creará el GameObject raíz de la aplicación de juego.

```php
GameApp::factory()->image('guess-the-number.jpeg')->create([
    'prefix' => 'gtn',
    'name' => 'Adivina el número',
    'description' => 'Debes divinar un número entre 1 y 1024.',
    'prefab_name' => 'guess-the-number'
]);
```
### Prefabs
Los prefabs representan estructuras abstractas de GameObjects, sus GameObjects hijos; y los componentes de cada uno. Su finalidad es la de poder instanciar estructuras de GameObjects de cualquier grado de complejidad en el backend. Se definen en forma de archivos `.php` de configuración a partir de la carpeta `app/Models/Prefabs` con la siguiente estructura:

```php
// app/Models/Prefabs/gtn/root.php
[
    'state' => 'asking-to-play',
    'components' => [
        'gtn.game-data' => [
            'min_number' => 1,
            'max_number' => 1024,
            'attempts' => 0,
            'max_attempts' => 10,
            'score' => 0,
        ],
        'gtn.initial-state' => [],
        'gtn.asking-to-play-state' => [],
        'gtn.game-over-state' => [],
        'gtn.playing-state' =>[],
        'gtn.preparing-state' => [],
        'gtn.showing-clue-state' => [],
        'gtn.success-state' => [],
    ]
];
```
En ese ejemplo se está definiendo la estructura de un prefab raíz para la aplicación de juego que lleva el prefijo `gtn`. Este prefab tiene un estado inicial `asking-to-play` y una serie de componentes que se instanciarán en el GameObject raíz, los cuales, por ser específicos de aplicación de juego, llevan el prefijo `gtn`.
Posteriormente, estos prefabs serán registrados en la tabla `prefabs` y su nombre será el mismo que el archivo que los define, sin la extensión `.php` y con el prefijo de la aplicación de juego.
Por ejemplo, en el caso del prefab anterior, el nombre será: `gtn.root`.

### GameObjects
Los GameObjects son elementos que se pueden instanciar en el backend a través de un Prefab.

### Components
**Ruta**: `app/Models/Components`
