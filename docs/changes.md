## Indice

### Últimos cambios
- Ahora cuando se instancia un Prefab, se le puede indicar el nombre y si se quiere que esté activo.
Por ejemplo:
```php
'children' => [
        'initial_view_2' => [
            'prefab' => 'ui_container',
            'active' => false,
        ],
    ],
```
En el ejemplo, se está diciendo que se quiere instanciar un prefab llamado `gtn.ui_container` con el nombre `initial_view_2`, y que no esté activo.

- Se creó el folder "GameApps\Common" para contener a Prefabs y Components que son comunes a todas las aplicaciones de juego.

- Ahora los Prefabs dejaron de ser simples estructuras definidas mediante arreglos PHP, para convertirse en clases que extienden de la clase `Prefab`. Esto permite que los Prefabs tengan métodos y propiedades que los definen, y que su instanciación se pueda personalizar.

- Se modificó la forma en la que se cargan los elementos de GameApps. El comando para cargar todos los GameApps y sus elementos es `php artisan games`.
    - Se refactorizó el código para separar creación o actualización de GameApps respecto de los Prefabs.
    - [ReloadGameAppsCommand](../src/app/Console/Commands/ReloadGameAppsCommand.php) se encarga de cargar los GameApps.
    - Se creó la clase [GameAppLoader](../src/app/Console/Commands/GameAppsLoader.php) para cargar los GameApps.
    - Se creó la clase [PrefabLoader](../src/app/Console/Commands/PrefabsLoader.php) para cargar los Prefabs.

- Ahora los prebabs pueden recibir atributos de configuración al momento de ser instanciados. Por ejemplo:
```php
'children' => [
        'initial_view_2' => [
            'prefab' => 'ui_container',
            'active' => false,
            'attributes' => [
                'value_1' => 100,
                'value_2' => false,
            ],
        ],
    ],
```
En el ejemplo, se está diciendo que se quiere instanciar un prefab llamado `gtn.ui_container` con el nombre `initial_view_2`, que no esté activo, y que reciba los atributos `value_1` y `value_2` con los valores `100` y `false` respectivamente.
Estos atributos se envían al método `afterInstantiate($gameObject, $attributes)` para que el prefab pueda hacer algo con ellos, una vez que se ha instanciado el GameObject.

- Para el caso del prefab raíz, en config.php de la GameApp, se pueden definir atributos de configuración que serán enviados al prefab raíz al momento de instanciarse. Por ejemplo:
```php
return [
    'image' => 'guess-the-number.jpeg',
    'name' => 'Adivina el número',
    'description' => 'Un simple juego donde adivinas un número entre 1 y 1024.',
    'prefab_name' => 'gtn.root',
    'prefab_attributes' => ['value' => 1],
    'client' => 'blade',
];
```
25 de diciembre de 2024
Se crearon los prefabs label y button y sus componentes.
