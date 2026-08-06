<?
require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php");

header('Content-Type: application/json');
// Opcional pero útil para depurar:
error_reporting(0); // O usar error_reporting(E_ERROR) en producción
ini_set('display_errors', 0);

//error_reporting(E_ALL);
//i_set('display_errors', 1);

$action  = param("ac");



$img = get_projects_images_custom(1,'imagen5');

switch ($action){


 case "Projects_edit":


 $id      = param("id");
 $titulo = param('titulo',false);
 $titulo_en = param('titulo_en',false);
 $ubicacion = param('ubicacion',false);
 $year = param('year',false);
 $services = param('services',false);
 $detalle = param('detalle',false);
 $detalle_en = param('detalle_en',false);
 $area = param('area',false);
 $categoria = param('categoria',false);
 $video = param('video',false);
 $pos = param('pos',false);

 if($id>0){ 
  $project = get_project($id);
  $new = false; 
} else {
 $new = true;  
 $project  = new _Project_esp();
}
$project->vars['titulo'] = $titulo;
$project->vars['cat'] = $categoria;
$project->vars['services'] = $services;
$project->vars['description'] = $detalle;
$project->vars['area'] = $area;
$project->vars['ubicacion'] = $ubicacion;
$project->vars['year'] = $year;
$project->vars['video'] = $video;
$project->vars['pos'] = $pos;

if($new){
  $new_id =  $project->insert(); 
} else {
  $project->update();
}

if($new){
 $project_en =  new _Project_en();
} else {
 $project_en = get_project($id,'_en');   
}
$project_en->vars['titulo'] = $titulo_en;
$project_en->vars['cat'] = $categoria;
$project_en->vars['services'] = $services;
$project_en->vars['description'] = $detalle_en;
$project_en->vars['area'] = $area;
$project->vars['ubicacion'] = $ubicacion;
$project_en->vars['year'] = $year;
$project_en->vars['video'] = $video;
$project_en->vars['pos'] = $pos;

if($new){
  $project_en->insert();
} else {
  $project_en->update();  
}

if($new){
 $images = get_projects_images(0);     
 foreach($images as $img){
  $new_img = new _Project_img();
  $new_img->vars['project_id'] = $new_id;
  $new_img->vars['nombre'] = $img->vars['nombre'];
  $new_img->vars['key_index'] = $img->vars['key_index'];
  $new_img->vars['size1'] = $img->vars['size1'];
  $new_img->vars['size2'] = $img->vars['size2'];
  $new_img->insert();   
}   

$images = get_projects_images($new_id);          



} else {
  $images = get_projects_images($id);          
}

$imgnames = explode(',',param("img_name"));


$img_control = true; 

        //IMAGE 1
if (isset($_FILES["image1"]) &&  isset($_FILES["image2"]) && $_FILES["image1"]['error'] === UPLOAD_ERR_OK &&  $_FILES["image2"]['error'] === UPLOAD_ERR_OK) {


  $fileTmpPath1 = $_FILES["image1"]['tmp_name'];
  $fileTmpPath2 = $_FILES["image2"]['tmp_name'];
  $fileName1 = $_FILES["image1"]['name'];
  $fileName2 = $_FILES["image2"]['name'];
  $fileSize1 = $_FILES["image1"]['size'];
  $fileSize2 = $_FILES["image2"]['size'];
  $fileType1 = $_FILES["image1"]['type'];
  $fileType2 = $_FILES["image2"]['type'];
  $fileNameCmps1 = pathinfo($fileName1);
  $fileNameCmps2 = pathinfo($fileName2);
  $fileExtension1 = $fileNameCmps1['extension']; 
  $fileExtension2 = $fileNameCmps2['extension']; 


  $nombre_imagen1 = $imgnames[0]."-".$id."-".str_replace(" ","",$images["image1"]->vars['size1']); 
  $nombre_imagen1 = $nombre_imagen1.".".$fileExtension1;
  $nombre_imagen1 = str_replace("_","-",$nombre_imagen1);
  $url1 = $nombre_imagen1;
  $nombre_imagen2 = $imgnames[0]."-".$id."-".str_replace(" ","",$images["image1"]->vars['size2']); 
  $nombre_imagen2 = $nombre_imagen2.".".$fileExtension2;
  $nombre_imagen2 = str_replace("_","-",$nombre_imagen2);
  $url2 = $nombre_imagen2;

            //$imageDestination1 = $_SERVER['DOCUMENT_ROOT']."/V01/apps/projects/uploads/". $nombre_imagen1;
           // $imageDestination2 = $_SERVER['DOCUMENT_ROOT']."/V01/apps/projects/uploads/". $nombre_imagen2;
  $imageDestination1 = "/var/www/vhosts/aazdsgn.com/images.aazdsgn.com/projects/uploads/" . $nombre_imagen1;
  $imageDestination2 = "/var/www/vhosts/aazdsgn.com/images.aazdsgn.com/projects/uploads/" . $nombre_imagen2;



  if (move_uploaded_file($fileTmpPath1, $imageDestination1) && move_uploaded_file($fileTmpPath2, $imageDestination2)) {
              // La imagen se ha subido correctamente
              //DebugLogTxt(" La imagen se ha subido correctamente");
    $imageUploaded = true;
  } else {
              //DebugLogTxt(" La imagen NO se ha subido correctamente");
    $imageUploaded = false;
    $img_control = false; 
  }


  if ($img_control) {
    $ImgName = explode("-",$nombre_imagen1);
    $images["image1"]->vars['nombre'] = $ImgName[0];
    $images["image1"]->vars['url'] = 'https://images.aazdsgn.com/projects/uploads/'.$url1;
    $images["image1"]->vars['url2'] = 'https://images.aazdsgn.com/projects/uploads/'.$url2;
    $images["image1"]->update(array('nombre','url','url2'));
  }


        } //image 1



      //IMAGE 2
        if (isset($_FILES["image3"]) &&  isset($_FILES["image4"]) && $_FILES["image3"]['error'] === UPLOAD_ERR_OK &&  $_FILES["image4"]['error'] === UPLOAD_ERR_OK) {


          $fileTmpPath1 = $_FILES["image3"]['tmp_name'];
          $fileTmpPath2 = $_FILES["image4"]['tmp_name'];
          $fileName1 = $_FILES["image3"]['name'];
          $fileName2 = $_FILES["image4"]['name'];
          $fileSize1 = $_FILES["image3"]['size'];
          $fileSize2 = $_FILES["image4"]['size'];
          $fileType1 = $_FILES["image3"]['type'];
          $fileType2 = $_FILES["image4"]['type'];
          $fileNameCmps1 = pathinfo($fileName1);
          $fileNameCmps2 = pathinfo($fileName2);
          $fileExtension1 = $fileNameCmps1['extension']; 
          $fileExtension2 = $fileNameCmps2['extension']; 


          $nombre_imagen1 = $imgnames[1]."-".$id."-".str_replace(" ","",$images["image3"]->vars['size1']); 
          $nombre_imagen1 = $nombre_imagen1.".".$fileExtension1;
          $nombre_imagen1 = str_replace("_","-",$nombre_imagen1);
          $url1 = $nombre_imagen1;
          $nombre_imagen2 = $imgnames[1]."-".$id."-".str_replace(" ","",$images["image3"]->vars['size2']); 
          $nombre_imagen2 = $nombre_imagen2.".".$fileExtension2;
          $nombre_imagen2 = str_replace("_","-",$nombre_imagen2);
          $url2 = $nombre_imagen2;

            //$imageDestination1 = $_SERVER['DOCUMENT_ROOT']."/V01/apps/projects/uploads/". $nombre_imagen1;
            //$imageDestination2 = $_SERVER['DOCUMENT_ROOT']."/V01/apps/projects/uploads/". $nombre_imagen2;
          $imageDestination1 = "/var/www/vhosts/aazdsgn.com/images.aazdsgn.com/projects/uploads/" . $nombre_imagen1;
          $imageDestination2 = "/var/www/vhosts/aazdsgn.com/images.aazdsgn.com/projects/uploads/" . $nombre_imagen2;


          if (move_uploaded_file($fileTmpPath1, $imageDestination1) && move_uploaded_file($fileTmpPath2, $imageDestination2)) {
              // La imagen se ha subido correctamente
              //DebugLogTxt(" La imagen se ha subido correctamente");
            $imageUploaded = true;
          } else {
              //DebugLogTxt(" La imagen NO se ha subido correctamente");
            $imageUploaded = false;
            $img_control = false; 
          }


          if ($img_control) {
            $ImgName = explode("-",$nombre_imagen1);
            $images["image2"]->vars['nombre'] = $ImgName[0];
            $images["image2"]->vars['url'] = 'https://images.aazdsgn.com/projects/uploads/'.$url1;
            $images["image2"]->vars['url2'] = 'https://images.aazdsgn.com/projects/uploads/'.$url2;
            $images["image2"]->update(array('nombre','url','url2'));
          }


        } //image 2


        //IMAGE 3
        if (isset($_FILES["image5"]) &&  isset($_FILES["image6"]) && $_FILES["image5"]['error'] === UPLOAD_ERR_OK &&  $_FILES["image6"]['error'] === UPLOAD_ERR_OK) {


          $fileTmpPath1 = $_FILES["image5"]['tmp_name'];
          $fileTmpPath2 = $_FILES["image6"]['tmp_name'];
          $fileName1 = $_FILES["image5"]['name'];
          $fileName2 = $_FILES["image6"]['name'];
          $fileSize1 = $_FILES["image5"]['size'];
          $fileSize2 = $_FILES["image6"]['size'];
          $fileType1 = $_FILES["image5"]['type'];
          $fileType2 = $_FILES["image6"]['type'];
          $fileNameCmps1 = pathinfo($fileName1);
          $fileNameCmps2 = pathinfo($fileName2);
          $fileExtension1 = $fileNameCmps1['extension']; 
          $fileExtension2 = $fileNameCmps2['extension']; 


          $nombre_imagen1 = $imgnames[2]."-".$id."-".str_replace(" ","",$images["image5"]->vars['size1']); 
          $nombre_imagen1 = $nombre_imagen1.".".$fileExtension1;
          $nombre_imagen1 = str_replace("_","-",$nombre_imagen1);
          $url1 = $nombre_imagen1;
          $nombre_imagen2 = $imgnames[2]."-".$id."-".str_replace(" ","",$images["image5"]->vars['size2']); 
          $nombre_imagen2 = $nombre_imagen2.".".$fileExtension2;
          $nombre_imagen2 = str_replace("_","-",$nombre_imagen2);
          $url2 = $nombre_imagen2;

            //$imageDestination1 = $_SERVER['DOCUMENT_ROOT']."/V01/apps/projects/uploads/". $nombre_imagen1;
            //$imageDestination2 = $_SERVER['DOCUMENT_ROOT']."/V01/apps/projects/uploads/". $nombre_imagen2;
          $imageDestination1 = "/var/www/vhosts/aazdsgn.com/images.aazdsgn.com/projects/uploads/" . $nombre_imagen1;
          $imageDestination2 = "/var/www/vhosts/aazdsgn.com/images.aazdsgn.com/projects/uploads/" . $nombre_imagen2;


          if (move_uploaded_file($fileTmpPath1, $imageDestination1) && move_uploaded_file($fileTmpPath2, $imageDestination2)) {
              // La imagen se ha subido correctamente
             // DebugLogTxt(" La imagen se ha subido correctamente");
            $imageUploaded = true;
          } else {
              //DebugLogTxt(" La imagen NO se ha subido correctamente");
            $imageUploaded = false;
            $img_control = false; 
          }


          if ($img_control) {
            $ImgName = explode("-",$nombre_imagen1);
            $images["image3"]->vars['nombre'] = $ImgName[0];
            $images["image3"]->vars['url'] = 'https://images.aazdsgn.com/projects/uploads/'.$url1;
            $images["image3"]->vars['url2'] = 'https://images.aazdsgn.com/projects/uploads/'.$url2;
            $images["image3"]->update(array('nombre','url','url2'));
          }


        } //image 3


        //IMAGE 4
        if (isset($_FILES["image7"]) &&  isset($_FILES["image8"]) && $_FILES["image7"]['error'] === UPLOAD_ERR_OK &&  $_FILES["image8"]['error'] === UPLOAD_ERR_OK) {


          $fileTmpPath1 = $_FILES["image7"]['tmp_name'];
          $fileTmpPath2 = $_FILES["image8"]['tmp_name'];
          $fileName1 = $_FILES["image7"]['name'];
          $fileName2 = $_FILES["image8"]['name'];
          $fileSize1 = $_FILES["image7"]['size'];
          $fileSize2 = $_FILES["image8"]['size'];
          $fileType1 = $_FILES["image7"]['type'];
          $fileType2 = $_FILES["image8"]['type'];
          $fileNameCmps1 = pathinfo($fileName1);
          $fileNameCmps2 = pathinfo($fileName2);
          $fileExtension1 = $fileNameCmps1['extension']; 
          $fileExtension2 = $fileNameCmps2['extension']; 


          $nombre_imagen1 = $imgnames[3]."-".$id."-".str_replace(" ","",$images["image7"]->vars['size1']); 
          $nombre_imagen1 = $nombre_imagen1.".".$fileExtension1;
          $nombre_imagen1 = str_replace("_","-",$nombre_imagen1);
          $url1 = $nombre_imagen1;
          $nombre_imagen2 = $imgnames[3]."-".$id."-".str_replace(" ","",$images["image7"]->vars['size2']); 
          $nombre_imagen2 = $nombre_imagen2.".".$fileExtension2;
          $nombre_imagen2 = str_replace("_","-",$nombre_imagen2);
          $url2 = $nombre_imagen2;


            //$imageDestination1 = $_SERVER['DOCUMENT_ROOT']."/V01/apps/projects/uploads/". $nombre_imagen1;
            //$imageDestination2 = $_SERVER['DOCUMENT_ROOT']."/V01/apps/projects/uploads/". $nombre_imagen2;
          $imageDestination1 = "/var/www/vhosts/aazdsgn.com/images.aazdsgn.com/projects/uploads/" . $nombre_imagen1;
          $imageDestination2 = "/var/www/vhosts/aazdsgn.com/images.aazdsgn.com/projects/uploads/" . $nombre_imagen2;


          if (move_uploaded_file($fileTmpPath1, $imageDestination1) && move_uploaded_file($fileTmpPath2, $imageDestination2)) {
              // La imagen se ha subido correctamente
             // DebugLogTxt(" La imagen se ha subido correctamente");
            $imageUploaded = true;
          } else {
             // DebugLogTxt(" La imagen NO se ha subido correctamente");
            $imageUploaded = false;
            $img_control = false; 
          }


          if ($img_control) {
            $ImgName = explode("-",$nombre_imagen1);
            $images["image4"]->vars['nombre'] = $ImgName[0];
            $images["image4"]->vars['url'] = 'https://images.aazdsgn.com/projects/uploads/'.$url1;
            $images["image4"]->vars['url2'] = 'https://images.aazdsgn.com/projects/uploads/'.$url2;
            $images["image4"]->update(array('nombre','url','url2'));
          }


        } //image 4


        //IMAGE 5
        if (isset($_FILES["image9"]) &&  isset($_FILES["image10"]) && $_FILES["image9"]['error'] === UPLOAD_ERR_OK &&  $_FILES["image10"]['error'] === UPLOAD_ERR_OK) {


          $fileTmpPath1 = $_FILES["image9"]['tmp_name'];
          $fileTmpPath2 = $_FILES["image10"]['tmp_name'];
          $fileName1 = $_FILES["image9"]['name'];
          $fileName2 = $_FILES["image10"]['name'];
          $fileSize1 = $_FILES["image9"]['size'];
          $fileSize2 = $_FILES["image10"]['size'];
          $fileType1 = $_FILES["image9"]['type'];
          $fileType2 = $_FILES["image10"]['type'];
          $fileNameCmps1 = pathinfo($fileName1);
          $fileNameCmps2 = pathinfo($fileName2);
          $fileExtension1 = $fileNameCmps1['extension']; 
          $fileExtension2 = $fileNameCmps2['extension']; 


          $nombre_imagen1 = $imgnames[4]."-".$id."-".str_replace(" ","",$images["image9"]->vars['size1']); 
          $nombre_imagen1 = $nombre_imagen1.".".$fileExtension1;
          $nombre_imagen1 = str_replace("_","-",$nombre_imagen1);
          $url1 = $nombre_imagen1;
          $nombre_imagen2 = $imgnames[4]."-".$id."-".str_replace(" ","",$images["image9"]->vars['size2']); 
          $nombre_imagen2 = $nombre_imagen2.".".$fileExtension2;
          $nombre_imagen2 = str_replace("_","-",$nombre_imagen2);
          $url2 = $nombre_imagen2;

           // $imageDestination1 = $_SERVER['DOCUMENT_ROOT']."/V01/apps/projects/uploads/". $nombre_imagen1;
            //$imageDestination2 = $_SERVER['DOCUMENT_ROOT']."/V01/apps/projects/uploads/". $nombre_imagen2;
          $imageDestination1 = "/var/www/vhosts/aazdsgn.com/images.aazdsgn.com/projects/uploads/" . $nombre_imagen1;
          $imageDestination2 = "/var/www/vhosts/aazdsgn.com/images.aazdsgn.com/projects/uploads/" . $nombre_imagen2;


          if (move_uploaded_file($fileTmpPath1, $imageDestination1) && move_uploaded_file($fileTmpPath2, $imageDestination2)) {
              // La imagen se ha subido correctamente
             // DebugLogTxt(" La imagen se ha subido correctamente");
            $imageUploaded = true;
          } else {
             // DebugLogTxt(" La imagen NO se ha subido correctamente");
            $imageUploaded = false;
            $img_control = false; 
          }


          if ($img_control) {
            $ImgName = explode("-",$nombre_imagen1);
            $images["image5"]->vars['nombre'] = $ImgName[0];
            $images["image5"]->vars['url'] = 'https://images.aazdsgn.com/projects/uploads/'.$url1;
            $images["image5"]->vars['url2'] = 'https://images.aazdsgn.com/projects/uploads/'.$url2;
            $images["image5"]->update(array('nombre','url','url2'));
          }


        } //image 5



        if ($img_control) {
 //if($control){
         $data['control'] =1;
       } else {
         $data['control'] =0;
       }

       DebugLogTxt(json_encode($data));


 // Imprime el JSON de vuelta para confirmar los datos recibidos
// $data = $uploadedImages;
  //  $data['control'] =1;
       echo json_encode($data);     



       //$data = $_FILES + $_POST;
       
       break;


       case "mainPage":

       $id      = param("id");
       $titulo = param('titulo',false);
       $titulo_en = param('titulo_en',false);
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



            //$imageDestination = $_SERVER['DOCUMENT_ROOT']."/V01/apps/mainPage/uploads/". $nombre_imagen;
           $imageDestination = "/var/www/vhosts/aazdsgn.com/images.aazdsgn.com/projects/uploads/" . $nombre_imagen;


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

  case "Project_deletes":
   $id = param("id");
   echo $id;
    break;

   case "Project_delete":

   $id = param("id");

   $data = [];
   $control = 1;


   $data['control'] = 0;
   if ($id > 0) {

        // ===========================
        // 1. OBTENER IMÁGENES
        // ===========================

        // ACA SE CARGA EL ARRAY CON LAS IMAGENES

        // ARRAY[0]['url'], ARRAY[0]['url2'], ARRAY[1]['url'], ARRAY[1]['url2']

        $imagesArray = get_projects_images_array($id); 
        //print_r($imagesArray);

        // ===========================
        // 2. ELIMINAR REGISTROS DB
        // ===========================

       $delete = delete_project($id);
       //var_dump($delete) ;

        // ===========================
        // 3. ELIMINAR IMÁGENES DEL SERVER
        // ===========================

    if($delete && !empty($imagesArray)) {

      //echo "ELIMINAMOS";

      foreach ($imagesArray as $img) {

    if (!empty($img['url'])) {

        $relativePath = parse_url($img['url'], PHP_URL_PATH);
        $path = "/var/www/vhosts/aazdsgn.com/images.aazdsgn.com" . $relativePath;

        //echo $path . "<br>";

        if (file_exists($path)) {
            unlink($path);
        }
    }

    if (!empty($img['url2'])) {

        $relativePath2 = parse_url($img['url2'], PHP_URL_PATH);
        $path2 = "/var/www/vhosts/aazdsgn.com/images.aazdsgn.com" . $relativePath2;

        if (file_exists($path2)) {
            unlink($path2);
        }
    }
}

      $data['control'] = 1;
    }

  } else {
    $data['control'] = 0;
  }

  echo json_encode($data);

  break;

  default: break;

}



?>