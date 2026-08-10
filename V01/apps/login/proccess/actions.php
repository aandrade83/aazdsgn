<?php
ini_set('session.gc_maxlifetime', 1800);

// Configurar el tiempo de vida de la cookie de sesión a 1 hora
session_set_cookie_params(1800);
session_start();
$_SESSION = array();

require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php");
// Configurar el tiempo de vida de la sesión a 1 hora (3600 segundos)


$user      = param("user");
$pass      = param("pass");
$action  = param("ac");

$pass_enc = super_encript($pass);

switch ($action){


  case "login":

   $login = get_master_login($user,$pass_enc);

    $log_ip = get_ip();
    $log = new _logs();
    if(isset($login->vars["id"])){ $id = $login->vars["id"];} else { $id = 0;}
    $log->vars["user"] = $id;
    $log->vars["ip"] = $log_ip;
    $log->vars["date"] = date("Y-m-d H:i:s");

  if(!is_null($login)){

    $_SESSION['loged'] = "1";
    $_SESSION['user'] = $login->vars["id"];

    if($login->vars["active"] == 0){
      $log->vars["data"] = 0;
      $data['login'] = "3";

    } else {
      $log->vars["data"] = 1;
      $data['login'] = "1";
    }

  }else{
      $data['login'] = "2";
      $log->vars["data"] = 0;
   }

    $log->insert();

    // Capturar cualquier output basura (warnings, etc) que rompa el JSON
    $basura = ob_get_clean();

   echo json_encode($data);
   break;
}

?>