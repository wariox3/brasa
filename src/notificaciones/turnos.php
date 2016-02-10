<?php
ini_set('display_errors', 1);
include('mail.php');
include('conexion.php');
$dateFecha = new DateTime('now');
$strAnio = $dateFecha->format('Y');
$strMes = $dateFecha->format('m');

$arrRecursosSinProgramacion = array();
$strSql = "SELECT codigo_recurso_pk, nombre_corto "
        . "FROM tur_recurso WHERE estado_activo = 1";
$arRecursos = $servidor->query($strSql);
if ($arRecursos->num_rows > 0) {
    while ($arRecurso = $arRecursos->fetch_assoc()) {
        $strSql = "SELECT codigo_programacion_detalle_pk "
                . "FROM tur_programacion_detalle WHERE anio = $strAnio AND mes = $strMes AND codigo_recurso_fk = " . $arRecurso['codigo_recurso_pk'];
        $arProgramacionDetalles = $servidor->query($strSql);
        if ($arProgramacionDetalles->num_rows <= 0) {
            $arrRecursosSinProgramacion[] = array(
                'codigo' => $arRecurso['codigo_recurso_pk'],
                'nombre' => $arRecurso['nombre_corto']
            );
        }
    }
}
// Armar el cuerpo del mensaje
if ($arrRecursosSinProgramacion) {
    $strMensaje = "<br />Este es un correo enviado automaticamente desde sogaapp con el fin de informarle circunstancias precisas de su interes <br /><br />";   
    $strMensaje .= "Modulo: [Turnos] <br />";    
    $strMensaje .= "Destinatario: [Jefe operaciones]<br /><br />";    
    $strMensaje .= "Los siguientes recursos no tienen programacion para el mes $strMes año $strAnio<br /><br />";    
    $strMensaje .= "<table border='2'>"; 
    $strMensaje .= "<tr>";
    $strMensaje .= "<th>CODIGO</th>";
    $strMensaje .= "<th>NOMBRE</th>";
    $strMensaje .= "</tr>";
    foreach ($arrRecursosSinProgramacion as $recurso) {
        $strMensaje .= "<tr>";
        $strMensaje .= "<td>" . $recurso['codigo'] . "</td>";
        $strMensaje .= "<td>" . $recurso['nombre'] . "</td>";
        $strMensaje .= "</tr>";
    }
    $strMensaje .= "</table><br /><br />";    
    $strMensaje .= "Por favor no conteste este mensaje, para comunicarse con servicio al cliente marque 4448120 ext 131<br /><br />";    
    $strMensaje = utf8_decode($strMensaje);
    $arrDirecciones = array();
    $arrDirecciones[] = array('direccion' => 'maestradaz3@gmail.com', 'nombre' => 'Turnos');   
    //echo $strMensaje;
    enviarCorreo($strMensaje, $arrDirecciones,"Inconsistencias recursos [SogaApp-turnos]");
}

