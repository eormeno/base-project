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
