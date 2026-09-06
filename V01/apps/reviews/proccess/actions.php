<?
require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php");


$action = param("ac");


switch ($action){

 case "save":

 $id = param("id");
 $show_review_raw = param("show_review");

 $data = array();

 if($id === '' || ($show_review_raw !== '0' && $show_review_raw !== '1')){
   $data['control'] = 0;
   echo json_encode($data);
   break;
 }

 $review = get_google_review_by_review_id($id);

 if(empty($review)){
   $data['control'] = 0;
   echo json_encode($data);
   break;
 }

 $review->vars['show_review'] = (int) $show_review_raw;
 $control = $review->update(array('show_review'));

 if($control){
   $data['control'] = 1;
 } else {
   $data['control'] = 0;
 }

 echo json_encode($data);

 break;


 default: break;

}

?>
