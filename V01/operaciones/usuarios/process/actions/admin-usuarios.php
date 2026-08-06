<?php
require_once("../../includes.php");
require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/ui/head.php"); 


// Esta linea se necesita para que se puedan visualizar las Sweet Alerts en el sistema.
echo "<p style='color: white;'>.</p>";


$action = param('action');

if($action != ""){
	
	switch($action){
		case "insert":
		if(contAgregarUsuario($_POST)>=1){
			alerts(1,"Usuario Agregado");
			header("Refresh:1; url =../../../../operaciones/usuarios");
		}else {
			alerts(0,"Problema al Agregar Usuario");
			header("Refresh:1; url=../../../../operaciones/usuarios");

		}
		break;

		case "delete":
		$deleted=contEliminarUsuario($_POST);
		if($deleted==true){
			alerts(1,"Usuario Eliminado");
			header("Refresh:1; url =../../../../operaciones/usuarios");
		}else {
			alerts(0,"Problema al Eliminar Usuario");
			header("Refresh:1; url=../../../../operaciones/usuarios");

		}
		break;

		case "update":
		if(contEditarUsuario($_POST)>=1){
			alerts(1,"Usuario Actualizado");
			header("Refresh:1; url =../../../../operaciones/usuarios");
		} else {
			alerts(0,"Problema al Actualizar Usuario");
			header("Refresh:1; url=../../../../operaciones/usuarios");

		}
		break;
	}

}

?>