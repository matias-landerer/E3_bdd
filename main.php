<?php
include 'datos_fuera_formato.php';
include 'conversion_datos.php';
include 'datos_no_estandar.php';

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
    datos_fuera_formato($archivo_original);
}
?>