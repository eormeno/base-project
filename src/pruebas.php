<?php
function busquedaBinariaIteraciones($n, $x) {
    $inicio = 1;
    $fin = $n;
    $iteraciones = 0;
    while ($inicio <= $fin) {
        $iteraciones++;
        $medio = intdiv($inicio + $fin, 2);
        if ($x == $medio) {
            return $iteraciones;
        } elseif ($x < $medio) {
            $fin = $medio - 1;
        } else {
            $inicio = $medio + 1;
        }
    }
    return $iteraciones;
}

function clasificarNumerosPorIteraciones($n) {
    $clasificacion = [];
    for ($i = 1; $i < $n; $i++) {
        $iteraciones = busquedaBinariaIteraciones($n, $i);
        if (!isset($clasificacion[$iteraciones])) {
            $clasificacion[$iteraciones] = [];
        }
        $clasificacion[$iteraciones][] = $i;
    }
    return $clasificacion;
}

// Ejecución
$n = 512;
$clasificacion = clasificarNumerosPorIteraciones($n);

// Mostrar resultados
foreach ($clasificacion as $iteraciones => $numeros) {
    echo "Iteraciones: $iteraciones\n";
    echo "Números: " . implode(", ", $numeros) . "\n";
    echo "\n";
}
