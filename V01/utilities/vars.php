<?PHP

$__env_path = $_SERVER['DOCUMENT_ROOT'] . '/.env';
if (is_readable($__env_path)) {
    $__env_lines = file($__env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($__env_lines as $__env_line) {
        $__env_line = trim($__env_line);
        if ($__env_line === '' || $__env_line[0] === '#') {
            continue;
        }
        if (strpos($__env_line, '=') === false) {
            continue;
        }
        list($__env_key, $__env_value) = explode('=', $__env_line, 2);
        $__env_key   = trim($__env_key);
        $__env_value = trim($__env_value);
        if (
            strlen($__env_value) >= 2 &&
            (
                ($__env_value[0] === '"' && substr($__env_value, -1) === '"') ||
                ($__env_value[0] === "'" && substr($__env_value, -1) === "'")
            )
        ) {
            $__env_value = substr($__env_value, 1, -1);
        }
        if ($__env_key !== '' && getenv($__env_key) === false) {
            putenv("$__env_key=$__env_value");
            $_ENV[$__env_key]    = $__env_value;
            $_SERVER[$__env_key] = $__env_value;
        }
    }
    unset($__env_path, $__env_lines, $__env_line, $__env_key, $__env_value);
}

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