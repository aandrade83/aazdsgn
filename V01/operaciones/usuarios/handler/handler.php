<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'].'/V01/utilities/db/connection.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/V01/utilities/db/manager.php');

//////////////////////////////////////////////
//											
//////////////////////////////////////////////

function getUsuarios(){

	db_connect("master");

	$sql="SELECT * FROM usuarios ORDER BY id DESC";
	return get_str($sql, false,'user'); 
}

//////////////////////////////////////////////
//											
//////////////////////////////////////////////

function getUsuarioPorId($id){

	db_connect("master");
	$sql= "SELECT * FROM usuarios WHERE id=$id";
	return get($sql,"Usuarios"); 
 
}
?>