### Últimas ideas
- Hacer que los estados de los GameObjects se implementen como estructuras de hijos. Es decir, que existan GameObjects que se activen automáticamente ante un estado.
- Hacer que todos los posibles nombres de estados de los GameObjects estén definidos en un campo de la clase GameObject. Ventajas:
    - Ahí mismo se puede definir cuál de los estados es el estado por defecto.
    - Se puede validar que cada estado tenga un único componente que lo defina.

- Se podrían definir los recursos con un path al estilo Godot. Por ejemplo: `res://images/guess-the-number.jpeg`. Esto permitiría diferenciar una simple cadena de texto de un recurso, además de poder incluir subcarpetas. Obviamente estos recursos físicamente estarían a partir de la carpeta `resources`, de la GameApp.

- Crear un tipo de componente que se encargue de manejar la instanciaciación de prefabs.