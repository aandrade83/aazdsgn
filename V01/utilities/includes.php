<?php

header('X-Frame-Options: DENY');

header("X-XSS-Protection: 1; mode=block");

header('Content-Type: text/html; charset=utf-8');

//date_default_timezone_set("America/Costa_Rica");



require_once($_SERVER['DOCUMENT_ROOT'].'/V01/utilities/vars.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/V01/utilities/functions.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/V01/utilities/classes.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/V01/utilities/db/handler.php');

//include($_SERVER['DOCUMENT_ROOT'].'/V01/utilities/db/handler_sql.php');



?>