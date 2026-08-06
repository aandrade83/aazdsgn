<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/V01/utilities/db/connection.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/V01/utilities/db/manager.php');


function get_test(){
	db_connect("dgs_reports");
	/*$sql = "select * GAME ORDER BY IdGame DESC";
	return get_str($sql);*/

	echo "TEST";
}



?>