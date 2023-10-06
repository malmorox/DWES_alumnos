# Ejercicio 9 - Persistencia en ficheros
Crea una web con un listado de cosas por hacer, se añadirán al final del fichero. Tendrá un formulario y una tabla.

Explicación de serialización de información
```
// Dentro
$s = serialize($a);
file_put_contents('store.dat', $s);  

// Fuera
$s = file_get_contents('store.dat');
$a = unserialize($s);
```

## Soluciones

Listado:
- [Jorge](./todo_jorge/)
