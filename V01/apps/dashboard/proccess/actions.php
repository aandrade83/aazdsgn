<?
require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php");

$id      = param("id");
$action  = param("ac");
$value  = param("value");
$company  = param("c");


switch ($action){


  case "main":

  $bdBooks = get_all_books($_company,$value);
  $html = "";
  $checked = "";
  

  foreach($bdBooks AS $book){
  
    $html .= '<tr>';
    $html .= '<td>'.$book->vars["id_book"].'</td>';
    $html .= '<td>'.$book->vars["name"].'</td>';
    $html .= '<td>'.$book->vars["short"].'</td>';
    if($book->vars["available"]) {  $checked = 'checked="checked"'; } 
    $change = 'onchange="saveChanges('.$book->vars["id_book"].','.$book->vars["id_company"].')"';
    $html .= '<td><input type="checkbox" class="Check" id="check_'.$book->vars["id"].'" '.$checked.'  '.$change.' data-plugin="switchery" data-color="#1bb99a" data-switchery="true" >';
  
    $html .= '</td>';
    $html .= '</tr>';
  
     }

     $data['html'] = $html; 
     echo json_encode($data);
     break;


 case "update":

        $book = get_book($id."_".$company);
        $book->vars['available'] = $value;
        $book->update(array("available"));       
        break;


default: break;

}
?>