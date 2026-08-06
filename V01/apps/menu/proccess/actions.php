<?
require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php");


$action  = param("ac");


switch ($action){

 case "save":

 $id      = param("id");
 $descripcion = param('menu',false);
 $descripcion_en = param('eng',false);
 $link = param('link',false);
 $pos = param('pos');

 $menu_esp = get_content_by_id($id);
 $menu_esp->vars['descripcion'] = $descripcion;  
 $menu_esp->vars['link'] = $link;
 $menu_esp->vars['pos'] = $pos;
 $control = $menu_esp->update(array('descripcion','link','pos'));

 $menu_en = get_content_by_id($id,"_en");
 $menu_en->vars['descripcion'] = $descripcion_en;  
  $menu_en->vars['link'] = $link;
 $menu_en->vars['pos'] = $pos;
 $control = $menu_en->update(array('descripcion','link','pos'));



 if($control){
   $data['control'] =1;
 } else {
   $data['control'] =0;
 }

 // Imprime el JSON de vuelta para confirmar los datos recibidos
 echo json_encode($data);


 break;


 default: break;

}




?>