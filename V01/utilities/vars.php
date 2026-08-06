<?PHP
if(session_status() == PHP_SESSION_NONE) { session_start(); }
date_default_timezone_set("America/Chicago");

$base_url     = 'http://localhost:8083';
$base_img_url = 'http://localhost:8083';

error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ); 
ini_set('display_errors', '1');

if(isset($_GET['l'])){
 $lang = $_GET['l'];
 $_SESSION['lang'] = $lang;
}
else{
 $lang = '_esp';
} 

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = $lang;
}else{
  $lang = $_SESSION['lang'];
}



?>