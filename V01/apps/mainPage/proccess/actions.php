<?
require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php");


$action  = param("ac");

switch ($action){

 case "mainPage":

 $id      = param("id");
 $text1 = param('titulo',false);
 $text1_en = param('titulo_en',false);
 $text2 = param('detalle',false);
 $text2_en = param('detalle_en',false);
 $text3 = param('texto_link',false);
 $text3_en = param('texto_link_en',false);
 $link = param('link',false);
 $nombre_imagen = param('nombre_imagen');
 $nombre_imagen = str_replace(" ","-",$nombre_imagen  );
 $nombre_imagen2 = $nombre_imagen; 
 $alt = $text1." ".param('nombre_imagen');
 $img_control = false;
 $pag_esp = get_content_by_id($id);
 $pag_en = get_content_by_id($id,"_en");

  // Procesar las imágenes
  if (isset($_FILES["image1"]) && isset($_FILES["image2"])) {
  $uploadedImages = [];
    for ($i = 1; $i <= 2; $i++) {
        if (isset($_FILES["image$i"]) && $_FILES["image$i"]['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES["image$i"]['tmp_name'];
            $fileName = $_FILES["image$i"]['name'];
            $fileSize = $_FILES["image$i"]['size'];
            $fileType = $_FILES["image$i"]['type'];
            $fileNameCmps = pathinfo($fileName);
            $fileExtension = $fileNameCmps['extension']; 
     
            if($i==1){
             $nombre_imagen = $nombre_imagen."-".$id."-".$pag_esp->vars['text4']; 
             $nombre_imagen = $nombre_imagen.".".$fileExtension;
             $nombre_imagen = str_replace("_","-",$nombre_imagen);
             $url1 = $nombre_imagen;

             }
            else {
             $nombre_imagen = $nombre_imagen2."-".$id."-".$pag_esp->vars['text5']; 
             $nombre_imagen = $nombre_imagen.".".$fileExtension;
             $nombre_imagen = str_replace("_","-",$nombre_imagen);
             $url2 = $nombre_imagen;
             }
               
        

           // $imageDestination = $_SERVER['DOCUMENT_ROOT']."/V01/apps/mainPage/uploads/". $nombre_imagen;
            $imageDestination = "/var/www/vhosts/aazdsgn.com/images.aazdsgn.com/mainPage/uploads/" . $nombre_imagen;

            if (move_uploaded_file($fileTmpPath, $imageDestination)) {
            // La imagen se ha subido correctamente
            $imageUploaded = true;
        } else {
            $imageUploaded = false;
        }
         
            // Agregar información de la imagen a la respuesta
            $uploadedImages[] = [
                'name' => $nombre_imagen  ,
                'size' => $fileSize,
                'type' => $fileType,
                'extension' => $fileExtension
            ];
        
           $img_control = true; 
        }
  }
}

  
 $pag_esp->vars['text1'] = $text1; 
 $pag_esp->vars['text2'] = $text2;
 $pag_esp->vars['text3'] = $text3;
 $pag_esp->vars['link'] = $link;

 if ($img_control) {
  $pag_esp->vars['nombre_imagen'] = $nombre_imagen2;
  $pag_esp->vars['url'] = 'https://images.aazdsgn.com/mainPage/uploads/'.$url1;
  $pag_esp->vars['url2'] = 'https://images.aazdsgn.com/mainPage/uploads/'.$url2;
  $pag_esp->vars['alt'] = $alt;
 }

 $control = $pag_esp->update(array('text1','text2','text3','link','nombre_imagen','alt','url','url2'));

 $pag_en->vars['text1'] = $text1_en; 
 $pag_en->vars['text2'] = $text2_en;
 $pag_en->vars['text3'] = $text3_en;
 $pag_en->vars['link'] = $link;
 if ($img_control) {
  $pag_en->vars['nombre_imagen'] = $nombre_imagen2;
  $pag_en->vars['url'] = 'https://images.aazdsgn.com/mainPage/uploads/'.$url1;
  $pag_en->vars['url2'] = 'https://images.aazdsgn.com/mainPage/uploads/'.$url2;
  $pag_en->vars['alt'] = $alt;
 }

 $control = $pag_en->update(array('text1','text2','text3','link','nombre_imagen','alt','url','url2'));


if (isset($_FILES["image1"]) && isset($_FILES["image2"])) {
 //if($control){
   $data['control'] =1;
 } else {
   $data['control'] =0;
 }




 // Imprime el JSON de vuelta para confirmar los datos recibidos
// $data = $uploadedImages;
  //  $data['control'] =1;
 echo json_encode($data);


 break;

 default: break;

}




?>