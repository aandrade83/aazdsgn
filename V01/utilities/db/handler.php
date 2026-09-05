<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/V01/utilities/db/connection.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/V01/utilities/db/manager.php');



function get_master_login($user,$pass){

	db_connect("master");
	$sql = "SELECT * FROM users WHERE user = '".$user."' AND pass = '".$pass."'";
	
	return get($sql,'_Users',true); 
}


function get_user($user){

	db_connect("master");
	$sql = "SELECT * FROM users WHERE id= $user";
	
	return get($sql,'_Users',true); 
}



function get_page_data($pagina,$lang = "_esp",$index= "NO_FIELD"){

	db_connect("master");
    $table = ' content'.$lang	;	
	$sql = "SELECT * FROM $table WHERE id_page = $pagina order by pos ASC";
    return get($sql,'_Content'.$lang,false,$index); 
}


function get_projects($lang = "_esp",$active = 1){

	db_connect("master");
    $table = ' projects'.$lang	;	
    $str_active = ""; 
    if(!$active){
    	$str_active = " AND id > 0 ";
    }
	$sql = "SELECT * FROM $table WHERE 1 $str_active order by pos ASC";
    return get($sql,'_Project'.$lang,false); 
}

function get_project($id,$lang = "_esp"){

	db_connect("master");
    $table = ' projects'.$lang	;	
	$sql = "SELECT * FROM $table WHERE id = $id order by year ASC";
    
    return get($sql,'_Project'.$lang,true); 
}





function get_projects_images($id){

	db_connect("master");
   
	$sql = "SELECT * FROM projects_img WHERE project_id = $id order by id ASC";
    return get($sql,'_Project_img',false,'key_index'); 
}

function get_projects_images_array($id){

	db_connect("master");
   
	$sql = "SELECT * FROM projects_img WHERE project_id = $id order by id ASC";
   
    return get_str($sql); 
}


function get_projects_images_custom($project,$key){

	db_connect("master");
   
	$sql = "SELECT * FROM projects_img WHERE project_id = $project AND 	key_index = '".$key."'";
   //echo $sql; 
   //exit;    

    return get($sql,'_Project_img',true); 
}



function get_content_by_id($id,$lang = "_esp"){
	db_connect("master");
	 $table = ' content'.$lang	;	
	$sql = "SELECT * FROM $table WHERE id = $id ";
    return get($sql,'_Content'.$lang,true); 
}




function delete_project($id){
db_connect("master");
	$sql1 = "DELETE FROM projects_en WHERE id = $id";
	$a = execute($sql1);
	$sql2 = "DELETE FROM projects_esp WHERE id = $id";
	$a= execute($sql2);
	$sql3 = "DELETE FROM projects_img WHERE project_id = $id";
	$a = execute($sql3);


	return $a; 

    

}






////////////////////////////


function get_all_books($companie,$available = "-1"){
	db_connect("master");
	$str_available = "";
	if($available != "-1"){
	 $str_available = " AND available = $available";
    }
	$sql = "SELECT * FROM books WHERE id_company = $companie $str_available		";
	return get($sql,'_Books',false,'id_book'); 
}

function get_book($id){
	db_connect("master");
	$sql = "SELECT * FROM books WHERE id = '".$id."'";
	return get($sql,'_Books',true); 
   
}


function get_active_schedule(){
	db_connect("master");
	$yesterday	= date("Y-m-d", strtotime("-1 day", strtotime(date("Y-m-d"))));
	$sql = "SELECT * FROM schedule WHERE added_date >= '".$yesterday."' ";
	
	return get($sql,'_Schedule',false,'id_event'); 
}



function get_all_sports($companie,$available = "-1"){
	db_connect("master");
	$str_available = "";
	if($available != "-1"){
	 $str_available = " AND available = $available";
    }
	$sql = "SELECT * FROM sports WHERE id_company = $companie $str_available";
	return get($sql,'_Sports',false,'id_sport'); 
}

function get_sport($id){
	db_connect("master");
	$sql = "SELECT * FROM sports WHERE id = '".$id."'";
	return get($sql,'_Sports',true); 
   
}

function get_all_leagues($companie,$available = "-1"){
	db_connect("master");
	$str_available = "";
	if($available != "-1"){
	 $str_available = " AND available = $available";
    }
	$sql = "SELECT * FROM leagues WHERE id_company = $companie $str_available";
	return get($sql,'_Leagues',false,'id_league'); 
}

function get_league($id){
	db_connect("master");
	$sql = "SELECT * FROM leagues WHERE id = '".$id."'";
	return get($sql,'_Leagues',true);

}




function get_google_reviews_by_review_id_index(){

	db_connect("master");
	$sql = "SELECT * FROM google_reviews";
	return get($sql,'_Google_reviews',false,'review_id');
}

function get_google_review_by_review_id($reviewId){

	db_connect("master");
	$sql = "SELECT * FROM google_reviews WHERE review_id = '".$reviewId."'";
	return get($sql,'_Google_reviews',true);
}

?>