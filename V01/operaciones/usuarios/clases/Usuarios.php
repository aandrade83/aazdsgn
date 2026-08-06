
<?php

class Usuarios{

	var $vars = array();

	function initial(){
	}

/////////////////////////////

	function update($specific = NULL){
	   if(!is_array($specific) && !is_null($specific)){$specific = explode(",",$specific);}
	   db_connect("master");
	   return update($this, "usuarios", $specific);
	}

/////////////////////////////

	function insert(){
		try{
			db_connect("master");
			$this->vars["id"] = insert($this, "usuarios");
		}catch(Exception $e){
		  die($e->getMessage());
		}
		
	}

/////////////////////////////

	function delete(){
	   db_connect("master");	
	   delete("usuarios", $this->vars["id"]);
	}
}

?>