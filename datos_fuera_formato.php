<?php
function datos_fuera_formato ($nombre_archivo){
    $len_nombre_archivo = strlen($nombre_archivo);
    $archivo = fopen($nombre_archivo, 'r');
    $columnas = fgetcsv($archivo, 0, ';', '"', '\\');

    $XXXOK = fopen(substr($nombre_archivo, 0, $len_nombre_archivo - 4)."OK.csv", 'w');

    fputcsv($XXXOK, $columnas, ';', '"', '\\');

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

    while (($fila = fgetcsv($archivo, 0, ';', '"', '\\')) !== false){
        $enERR = false;
        $fila_corregida = $fila;
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
                        $XXXERR = fopen(substr($nombre_archivo, 0, $len_nombre_archivo - 4)."ERR.csv", 'a');
                        for ($j = 0; $j < $len_fila; $j++){
                            fwrite($XXXERR, $fila[$j].";");
                        }
                        fwrite($XXXERR, "\n");
                        fclose($XXXERR);
                        $enERR = true;
                    }
                }
                
                elseif ($col_actual == "rut") {
                    $valor_actual = $fila[$i];
                    $valor_actual = trim($valor_actual);
                    $len_valor = strlen($valor_actual);
                    if ($len_valor > 12){
                        $valor_actual_mejorado = $valor_actual;
                        $valor_actual_mejorado[$len_valor - 4] = "-";
                        $valor_actual_mejorado[$len_valor - 3] = $valor_actual_mejorado[$len_valor - 1];
                        $valor_actual_mejorado = substr($valor_actual_mejorado, 0, $len_valor - 2);
                        $len_valor = strlen($valor_actual_mejorado);
                        $XXXLOG = fopen(substr($nombre_archivo, 0, $len_nombre_archivo - 4)."LOG.csv", 'a');
                        fwrite($XXXLOG, "Se cambió ".$valor_actual." por ".$valor_actual_mejorado." debido a su '-' era más largo, por lo que los tamaños no encajaban.\n");
                        fclose($XXXLOG);
                        $fila_corregida[$i] = $valor_actual_mejorado;
                    }

                    if ($len_valor !== 12){
                        $XXXERR = fopen(substr($nombre_archivo, 0, $len_nombre_archivo - 4)."ERR.csv", 'a');
                        for ($j = 0; $j < $len_fila; $j++){
                            fwrite($XXXERR, $fila[$j].";");
                        }
                        fwrite($XXXERR, "\n");
                        fclose($XXXERR);
                        $enERR = true;
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
                        $valor_actual_mejorado = $valor_sin_tildes;
                        $XXXLOG = fopen(substr($nombre_archivo, 0, $len_nombre_archivo - 4)."LOG.csv", 'a');
                        fwrite($XXXLOG, "Se cambió ".$valor_actual." por ".$valor_actual_mejorado." debido a que habían tildes en el correo.\n");
                        fclose($XXXLOG);
                        $fila_corregida[$i] = $valor_actual_mejorado;
                    }

                    if (strpos($valor_actual, '@@')){
                        $valor_actual_mejorado = str_replace('@@', '@', $valor_actual);
                        $XXXLOG = fopen(substr($nombre_archivo, 0, $len_nombre_archivo - 4)."LOG.csv", 'a');
                        fwrite($XXXLOG, "Se cambió ".$valor_actual." por ".$valor_actual_mejorado." debido a que tenía un @ extra.\n");
                        fclose($XXXLOG);
                        $fila_corregida[$i] = $valor_actual_mejorado;
                    }

                    if (strpos($valor_actual, '..')){
                        $valor_actual_mejorado = str_replace('..', '.', $valor_actual);
                        $XXXLOG = fopen(substr($nombre_archivo, 0, $len_nombre_archivo - 4)."LOG.csv", 'a');
                        fwrite($XXXLOG, "Se cambió ".$valor_actual." por ".$valor_actual_mejorado." debido a que tenía un . extra.\n");
                        fclose($XXXLOG);
                        $fila_corregida[$i] = $valor_actual_mejorado;
                    }
                }
                
                elseif ($col_actual == "titular") {
                    $valor_actual = $fila[$i];
                    $valor_actual = trim($valor_actual);
                    $len_valor = strlen($valor_actual);
                    if ($len_valor !== 0 and $len_valor !== 8){
                        //Para ERR
                        echo "Error\n";
                        echo $valor_actual."\n";
                    }
                }
                
                elseif ($col_actual == "Teléfono") {
                    $valor_actual = $fila[$i];
                    $valor_actual = trim($valor_actual);
                    $len_valor = strlen($valor_actual);
                    if ($len_valor !== 9){
                        $valor_actual_mejorado = "100000000";
                        $XXXLOG = fopen(substr($nombre_archivo, 0, $len_nombre_archivo - 4)."LOG.csv", 'a');
                        fwrite($XXXLOG, "Se cambió ".$valor_actual." por ".$valor_actual_mejorado." debido a que, por su número de dígitos, no era un número telefónico válido.\n");
                        fclose($XXXLOG);
                        $fila_corregida[$i] = $valor_actual_mejorado;
                    }
                    if ($valor_actual[0] == "0"){
                        $valor_actual_mejorado = "100000000";
                        $XXXLOG = fopen(substr($nombre_archivo, 0, $len_nombre_archivo - 4)."LOG.csv", 'a');
                        fwrite($XXXLOG, "Se cambió ".$valor_actual." por ".$valor_actual_mejorado." debido a que, como su primer dígito es 0, no era un número telefónico válido.\n");
                        fclose($XXXLOG);
                        $fila_corregida[$i] = $valor_actual_mejorado;
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

        if ($enERR == false){
            fputcsv($XXXOK, $fila_corregida, ';', '"', '\\');
        }
    }
    fclose($archivo);
    fclose($XXXOK);
}
?>