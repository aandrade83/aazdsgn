<?php 

function contAgregarUsuario($data){
  
   try{
	   $user = new Usuarios();
	   $user->vars['nombre'] = $data["nombre"];
	   $user->vars['usuario'] = $data["usuario"];
	   $user->vars['clave'] = md5(sha1($data["clave"]));
	   $user->vars['activo'] = $data["activo"];
	   $user->vars['privilegio'] = $data["privilegio"];
	   $user->vars['detalle'] = $data["detalle"];
	   $user->vars['fecha_acceso'] = date("Y-m-d");
	   $user->insert();	  
		return $user->vars['id'];

   }catch(Exception $e){
     die($e->getMessage());
   }

}

//////////////////////////////////////////////////////////

function contEliminarUsuario($data){

  global $_usuario;
  $usuario =  getUsuarioPorId($data["id"]);
  $check = $usuario->delete();
  return $usuario->vars['id'];
}  

//////////////////////////////////////////////////////////

function contEditarUsuario($data){

  global $_usuario;
  $usuario=getUsuarioPorId($data["id"]);

  $usuario->vars["nombre"]=$data["nombre"];
  $usuario->vars["usuario"]=$data["usuario"];
  $usuario->vars["clave"]=$data["clave"];
  $usuario->vars["activo"]=$data["activo"];
  $usuario->vars["privilegio"]=$data["privilegio"];
  $usuario->vars["detalle"]=$data["detalle"];

  $check = $usuario->update();
  return $usuario->vars['id'];
} 
?>