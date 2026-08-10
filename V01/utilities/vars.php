<?PHP
if(session_status() == PHP_SESSION_NONE) { session_start(); }
date_default_timezone_set("America/Chicago");

$is_https     = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
|| (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
$base_url     = ($is_https ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
$base_img_url = $base_url;
$v            = '1';

error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED );
ini_set('display_errors', '0');
ini_set('log_errors', '1');

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