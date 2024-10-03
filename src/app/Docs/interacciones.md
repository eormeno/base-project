# Interacciones
## Conceptos
### Componente
Un objeto persistente que puede ser renderizado por el cliente. En este framework, un componente debe heredar de la clase [`AStateModel`](../Models/AStateModel.php).
Cada componente tiene:
- **alias**, un nombre que identifica a la instancia. Se implementa como un string formado por la clase del componente y el identificador asignado por el motor de base de datos, separado por un carácter `'_'`. Por ejemplo, el alias de una instancia de la clase `MyComponent` con identificador 1 sería `MyComponent_1`. Este identificador es utilizado para referenciar al componente en las interacciones con el servidor.
- **states**, un arreglo con las clases de estados que el componente puede tener.

## Interacciones de la aplicación
[MythicTreasureQuestController](../Http/Controllers/MythicTreasureQuestController.php) es un controlador encargado de procesar en su método `event()` los eventos enviados desde un cliente web. La información del método se obtiene a partir de un `FormRequest` llamado [`EventRequestFilter`](../Http/Requests/EventRequestFilter.php), cuyo único fin es el de facilitar el acceso a los datos del evento en sí. El evento recibido tiene la siguiente estructura:
| Campo | Tipo | Descripción |
| --- | --- | --- |
| event | string | Nombre del evento |
| source | string | Origen del evento |
| data | string | Datos del evento |
| destination | string | Destino del evento |
| rendered | array | Un arreglo con los identificadores de los componentes que están activos en el cliente |
