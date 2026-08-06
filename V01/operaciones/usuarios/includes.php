<?php 
ob_start();require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php");


spl_autoload_register(function($nombre_clase){
    include "clases/".$nombre_clase.".php";
});

require_once("funciones/funciones.php");
require_once("handler/handler.php");
require_once("controler/controler.php");


////////////////////////////////////////////////////
//				Archivos de proceso
////////////////////////////////////////////////////
//require_once("process/actions/admin-usuarios.php");
?>