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

DebugLogTxt("LOGIN_DEBUG ac=$action user=$user");

$pass_enc = super_encript($pass);
DebugLogTxt("LOGIN_DEBUG pass_enc=$pass_enc");

switch ($action){


  case "login":

   $login = get_master_login($user,$pass_enc);
   DebugLogTxt("LOGIN_DEBUG login result: " . ($login ? "objeto id=".$login->vars["id"] : "NULL"));
  
    $log_ip = get_ip();
    $log = new _logs();
    if(isset($login->vars["id"])){ $id = $login->vars["id"];} else { $id = 0;}
    $log->vars["user"] = $id;
    $log->vars["ip"] = $log_ip;
    $log->vars["date"] = date("Y-m-d H:i:s");

  if(!is_null($login)){

    $_SESSION['loged'] = "1";
    $_SESSION['user'] = $login->vars["id"];
    DebugLogTxt("LOGIN_DEBUG active=".$login->vars["active"]);

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

    DebugLogTxt("LOGIN_DEBUG antes del insert log");
    $log->insert();
    DebugLogTxt("LOGIN_DEBUG despues del insert log");

    // Capturar cualquier output basura (warnings, etc) que rompa el JSON
    $basura = ob_get_clean();
    if($basura) { DebugLogTxt("LOGIN_DEBUG BASURA EN BUFFER: ".$basura); }

   echo json_encode($data);
   break;
}

?>