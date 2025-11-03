<?php
function datos_fuera_formato ($nombre_archivo){
    $len_nombre_archivo = strlen($nombre_archivo);
    $archivo = fopen($nombre_archivo, 'r');
    $columnas = fgetcsv($archivo, 0, ';', '"', '\\');
    $indices_columnas = [];
    foreach ($columnas as $col){
        $col_actual = trim($col);
        $col_actual = preg_replace('/^\xEF\xBB\xBF/', '', $col_actual);
        
        if ($col_actual == "RUN"){
            $indice_actual = array_search($col, $columnas);
            $indices_columnas[] = $indice_actual;
        }
        
        elseif ($col_actual == "rut") {
            $indice_actual = array_search($col, $columnas);
            $indices_columnas[] = $indice_actual;
        }
        
        elseif ($col_actual == "Correo Electrónico") {
            $indice_actual = array_search($col, $columnas);
            $indices_columnas[] = $indice_actual;
        }
        
        elseif ($col_actual == "titular") {
            $indice_actual = array_search($col, $columnas);
            $indices_columnas[] = $indice_actual;
        }
        
        elseif ($col_actual == "RUT") {
            $indice_actual = array_search($col, $columnas);
            $indices_columnas[] = $indice_actual;
        }
        
        elseif ($col_actual == "Teléfono") {
            $indice_actual = array_search($col, $columnas);
            $indices_columnas[] = $indice_actual;
        }
        
        elseif ($col_actual == "fecha") {
            $indice_actual = array_search($col, $columnas);
            $indices_columnas[] = $indice_actual;
        }
    }
    print_r($indices_columnas);
    while (($fila = fgetcsv($archivo, 0, ';', '"', '\\')) !== false){
        $len_fila = count($fila);
        for ($i = 0; $i < $len_fila; $i++){
            if (in_array($i, $indices_columnas)){
                $col_actual = $columnas[$i];
                $col_actual = trim($col_actual);
                $col_actual = preg_replace('/^\xEF\xBB\xBF/', '', $col_actual);
                
                if ($col_actual == "RUN"){
                    $valor_actual = $fila[$i];
                    $valor_actual = trim($valor_actual);
                    $len_valor = strlen($valor_actual);
                    if ($len_valor !== 8 or $valor_actual[0] == "0"){
                        echo $valor_actual."\n"; //Para ERR
                        $XXXERR = fopen(substr($nombre_archivo, 0, $len_nombre_archivo - 4)."ERR.csv", 'w');
                        for ($j = 0; $j < $len_fila; $j++){
                            fwrite($XXXERR, $fila[$j].";");
                        }
                        fclose($XXXERR);
                    }
                }
                
                elseif ($col_actual == "rut") {
                    $valor_actual = $fila[$i];
                    $valor_actual = trim($valor_actual);
                    $len_valor = strlen($valor_actual);
                    if ($len_valor > 12){
                        $valor_actual[$len_valor - 4] = "-";
                        $valor_actual[$len_valor - 3] = $valor_actual[$len_valor - 1];
                        $valor_actual = substr($valor_actual, 0, $len_valor - 2);
                        $len_valor = strlen($valor_actual);
                    }

                    if ($len_valor !== 12){
                        echo $valor_actual."\n"; //Para ERR
                        $XXXERR = fopen(substr($nombre_archivo, 0, $len_nombre_archivo - 4)."ERR.csv", 'w');
                        for ($j = 0; $j < $len_fila; $j++){
                            fwrite($XXXERR, $fila[$j].";");
                        }
                        fclose($XXXERR);
                    }
                }
                
                elseif ($col_actual == "Correo Electrónico") {
                    $valor_actual = $fila[$i];
                    $valor_actual = trim($valor_actual);
                    $valor_sin_tildes = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ'], 
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N'],
                        $valor_actual
                    );
                    $len_valor = strlen($valor_actual);
                    
                    if ($valor_actual !== $valor_sin_tildes){
                        //echo $valor_actual."\n";
                        $valor_actual = $valor_sin_tildes; //Para LOG
                        //echo $valor_actual."\n";
                    }

                    if (strpos($valor_actual, '@@')){
                        //echo $valor_actual."\n";
                        $valor_actual = str_replace('@@', '@', $valor_actual); //Para LOG
                        //echo $valor_actual."\n";
                    }
                }
                
                elseif ($col_actual == "titular") {
                    $valor_actual = $fila[$i];
                    $valor_actual = trim($valor_actual);
                    $len_valor = strlen($valor_actual);
                    if ($len_valor !== 0 and $len_valor !== 8){
                        echo "Error\n";
                        echo $valor_actual."\n";
                    }
                }
                
                elseif ($col_actual == "Teléfono") {
                    $valor_actual = $fila[$i];
                    $valor_actual = trim($valor_actual);
                    $len_valor = strlen($valor_actual);
                    if ($len_valor !== 9){
                        //echo $valor_actual."\n";
                        $valor_actual = "100000000"; //Para LOG
                        //echo $valor_actual."\n";
                    }
                    if ($valor_actual[0] == "0"){
                        //echo $valor_actual."\n";
                        $valor_actual = "100000000"; //Para LOG
                        //echo $valor_actual."\n";
                    }
                }
                
                elseif ($col_actual == "fecha") {
                    $valor_actual = $fila[$i];
                    $valor_actual = trim($valor_actual);
                    $len_valor = strlen($valor_actual);
                    if ($len_valor !== 8){
                        echo $valor_actual."\n";
                    }
                }
            }
        }
    }
    fclose($archivo);
}
?>