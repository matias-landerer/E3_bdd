<?php
include 'datos_fuera_formato.php';

$archivos_originales = [
    'Persona.csv',
    'Orden.csv',
    'Medicamento.csv',
    'Instituciones previsionales de salud.csv',
    'Farmacia.csv',
    'Atencion.csv',
    'Arancel fonasa.csv',
    'Arancel DCColita de rana.csv'
];

foreach ($archivos_originales as $archivo_original){
    $len_nombre_archivo = strlen($archivo_original);
    $XXXLOG = fopen(substr($archivo_original, 0, $len_nombre_archivo - 4)."LOG.txt", 'w');
    fclose($XXXLOG);
    $XXXERR = fopen(substr($archivo_original, 0, $len_nombre_archivo - 4)."ERR.csv", 'w');
    fclose($XXXERR);
    datos_fuera_formato($archivo_original);
}
?>