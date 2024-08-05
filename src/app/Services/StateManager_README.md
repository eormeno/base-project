# StateManager
StateManager is a service that manages the state of the application. It is responsible for managing the state of the application and providing a way to access and update all the states of the application.
El servidor mantiene una estructura de objetos que representan elementos visuales de la interfaz de usuario. Esta estructura parte de un objeto raíz cuyos posibles estados representan los estados globales de la aplicación. A su vez, cada uno de esos estados puede relacionarse con otros objetos, también visuales, que se corresponden con elementos hijos de la interfaz de usuario.

El cliente se comunica con el servidor a través de un único punto de entrada que espera un evento con la siguiente estructura:
 - **event**: Nombre del evento
 - **source**: Nombre del objeto que genera el evento
 - **is_signal**: Indica si el evento es una señal o no
 - **destination**: Nombre del objeto que debe procesar el evento, o el valor 'all' si todos los objetos deben procesar el evento
 - **data**: Un objeto con los datos necesarios para procesar el evento

## Gestión de datos cacheados en cliente y servidor
El cliente mantiene una copia de los datos en caché para evitar la necesidad de realizar múltiples solicitudes al servidor. El servidor también mantiene una copia de los datos en caché para evitar la necesidad de realizar múltiples consultas a la base de datos.
