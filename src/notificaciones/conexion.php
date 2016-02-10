<?php
    require_once('spyc/spyc.php');     
    $data = Spyc::YAMLLoad('/var/www/html/brasa/app/config/parameters.yml'); 
    $arrParametros = $data['parameters'];
    $servidor = $arrParametros['database_host'];
    $usuario = $arrParametros['database_user'];
    $clave = $arrParametros['database_password'];
    $baseDatos = $arrParametros['database_name'];
    $servidor = new mysqli($servidor, $usuario, $clave, $baseDatos);
    if ($servidor->connect_error) {
        die("Connection failed: " . $servidor->connect_error);
    }
    

