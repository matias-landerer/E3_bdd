<?php
function datos_no_estandar($nombre_archivo){
    $archivo = fopen($nombre_archivo, 'r');
    $columnas = fgetcsv($archivo, 0, ';', '"', '\\');
    $indices_columnas = [];
    foreach ($columnas as $col){
        $col_actual = trim($col);
        $col_actual = preg_replace('/^\xEF\xBB\xBF/', '', $col_actual);
        
        if ($col_actual == "institucion previsional de salud"){
            $indice_actual = array_search($col, $columnas);
            $indices_columnas[] = $indice_actual;
        }
    }

    while (($fila = fgetcsv($archivo, 0, ';', '"', '\\')) !== false){
        $len_fila = count($fila);
        for ($i = 0; $i < $len_fila; $i++){
            if (in_array($i, $indices_columnas)){
                $col_actual = $columnas[$i];
                $col_actual = trim($col_actual);
                $col_actual = preg_replace('/^\xEF\xBB\xBF/', '', $col_actual);
                
                if ($col_actual == "institucion previsional de salud"){
                    $valor_actual = $fila[$i];
                    $valor_actual = trim($valor_actual);
                    $len_valor = strlen($valor_actual);
                    if ($valor_actual == "FONASA"){
                        echo $valor_actual."\n";
                    }
                }
                
            }
        }
    }
    fclose($archivo);
}
?>