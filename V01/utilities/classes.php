<?php
ob_start();

class Debug{

	var $vars = array();

	function initial(){}

//////////////////////////////////////////////////
//
//////////////////////////////////////////////////

	function checkErrorCode($code,$error =""){

	    //echo $code;
	   if(isset($_SERVER['HTTP_REFERER'])) {
	     $url = $_SERVER['HTTP_REFERER'];
	   }
	   //switch to Alerts
		switch($code){

			//MYSQL ERRORS
			case '1062': 
		    	$alert = "<script>window.alert('Error # 1452:  Problema de llave Primaria');</script>"; 
		    	break;
			case '1452': 
		    	$alert = "<script>window.alert('Error # 1452:  Problema de llave Secundaria');</script>"; 
		    	break;

			case '1054': 
				$alert = "<script>window.alert('Error # 1054: Columna no encontrada');</script>";
				 break;

			case '1146': 
				$alert = "<script>window.alert('Error # 1146: Tabla no encontrada');</script>";
				 break;

			// WARNINGS ERRORS
			case 'W02': 
				$alert ="<script>window.alert('Error # w02: Faltan Parametros obligatorios');</script>";
				break;
			case 'W03': 
				$alert ="<script>window.alert('Error # w03: Ciclo Foreach vacio');</script>";
				break;	
			case 'W04': 
				$alert ="<script>window.alert('Error # w04: Objeto no encontrado');</script>";
				break;		
			case 'W05': 
				$alert ="<script>window.alert('Error # w05: Falta parametro a Funcion');</script>";
				break;				

            case '6000': 
				$alert ="<script>window.alert('Error # 6000: La clase no existe');</script>";
				break;

			default:
				// $alert ="<script>window.alert('Error # $code: NO AGREGADO $error');</script>";
			      break;


		}
        //header("Refresh:0; url=$url");
		echo $alert;


	}
}
/*
class Vars{

	var $vars = array();

	function initial(){}

}
*/

class _Users{
    var $vars = array();
    function initial(){}
    function update($specific = NULL){
         db_connect("master");
       return update($this, "users", $specific);
    }
    function insert(){
          db_connect("master");
        $this->vars["id"] = insert($this, "users");
    }
    function delete(){
          db_connect("master");
       delete("users", $this->vars["id"]);
    }
}



class _Logs{
    var $vars = array();
    function initial(){}
    function update($specific = NULL){
         db_connect("master");
       return update($this, "logs", $specific);
    }
    function insert(){
          db_connect("master");
        $this->vars["id"] = insert($this, "logs");
    }
    function delete(){
          db_connect("master");
       delete("logs", $this->vars["id"]);
    }
}



class _Content_esp{
    var $vars = array();
    function initial(){}
    function update($specific = NULL){
         db_connect("master");
       return update($this, "content_esp", $specific);
    }
    function insert(){
          db_connect("master");
        $this->vars["id"] = insert($this, "content_esp");
    }
    function delete(){
          db_connect("master");
       delete("content_esp", $this->vars["id"]);
    }
}

class _Content_en{
    var $vars = array();
    function initial(){}
    function update($specific = NULL){
         db_connect("master");
       return update($this, "content_en", $specific);
    }
    function insert(){
          db_connect("master");
        $this->vars["id"] = insert($this, "content_en");
    }
    function delete(){
          db_connect("master");
       delete("content_en", $this->vars["id"]);
    }
}




class _Project_esp{
    var $vars = array();
    function initial(){}
    function update($specific = NULL){
         db_connect("master");
       return update($this, "projects_esp", $specific);
    }
    function insert(){
          db_connect("master");
         return  $this->vars["id"] = insert($this, "projects_esp");
    }
    function delete(){
          db_connect("master");
       delete("projects_esp", $this->vars["id"]);
    }
}

class _Project_en{
    var $vars = array();
    function initial(){}
    function update($specific = NULL){
         db_connect("master");
       return update($this, "projects_en", $specific);
    }
    function insert(){
          db_connect("master");
        $this->vars["id"] = insert($this, "projects_en");
    }
    function delete(){
          db_connect("master");
       delete("projects_en", $this->vars["id"]);
    }
}




class _Project_img{
    var $vars = array();
    function initial(){}
    function update($specific = NULL){
         db_connect("master");
       return update($this, "projects_img", $specific);
    }
    function insert(){
          db_connect("master");
        $this->vars["id"] = insert($this, "projects_img");
    }
    function delete(){
          db_connect("master");
       delete("projects_img", $this->vars["id"]);
    }
}





class _Visitas{
    var $vars = array();
    function initial(){}
    function update($specific = NULL){
         db_connect("master");
       return update($this, "visitas", $specific);
    }
    function insert(){
          db_connect("master");
        $this->vars["id"] = insert($this, "visitas");
        //  return insert_test($this, "visitas");
    }
    function delete(){
          db_connect("master");
       delete("visitas", $this->vars["id"]);
    }
}





?>