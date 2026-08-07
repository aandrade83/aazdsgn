<?php 
 require_once($_SERVER['DOCUMENT_ROOT'].'/V01/utilities/includes/PHPMailer/class.phpmailer.php');
 require_once($_SERVER['DOCUMENT_ROOT'].'/V01/utilities/includes/PHPMailer/class.smtp.php');
 
function check_licence(){
$mikey = fopen($_SERVER['DOCUMENT_ROOT']."/V01/utilities/process/keymaster.txt", "r") or die("Unable to open file!");
$keys = fgets($mikey);
$licences = explode("__",$keys);
fclose($mikey);
$control = true;
$bases = get_bases();
foreach($bases as $b){
 if (in_array(md5(sha1($b['nombre'])), $licences)) {
    $control = true;
 }
 else {
  $control = false;
  break;
  }
}
return $control;
}


function super_encript($str){
	$result = bin2hex($str);
	$result = str_replace("1","!",$result);
	$result = str_replace("2","@",$result);
	$result = str_replace("3","*",$result);
	$result = str_replace("4","Y",$result);
	$result = str_replace("5","X",$result);
	$result = str_replace("6","S",$result);
	$result = str_replace("7","p",$result);
	$result = str_replace("8","_",$result);
	$result = str_replace("9","-",$result);
	$result = str_replace("0","$",$result);
	$result = str_replace("a","7",$result);
	$result = str_replace("b","1",$result);
	$result = str_replace("c","4",$result);
	$result = str_replace("d","2",$result);
	$result = str_replace("e","9",$result);
	$result = str_replace("f","8",$result);	
	return  md5($result);
}


function date_convert($date){

//$fechaOriginal = "2024-03-02T00:10:00+0";

// Creamos un objeto DateTime para analizar la fecha original
$dateTime = new DateTime($date);

// Formateamos la fecha en el formato deseado
$formated = $dateTime->format('Y-m-d H:i:s');

 return $formated;

}

function get_ip(){
	$ip = $_SERVER["REMOTE_ADDR"];
	if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	return $ip;	
}


////////////////////////////////////////
////////////////////////////////////////

function alerts($bool,$msg){
	if($bool==1){
		echo "<script>Swal.fire({title:'Genial!',text:'".$msg."',type:'success'})</script>";
	}else{
		echo "<script>Swal.fire({title:'UPS...',text:'".$msg."',type:'warning'})</script>";
	}
}


/////////////////////////////////////////
/////////////////////////////////////////


function upload_image($ruta,$dir,$file){
	$parts=pathinfo($file['logo']['name']);
	@mkdir($ruta.$dir, 0777);
	$ruta =$ruta.$dir."/logo-light".'.'.$parts['extension'];
	@move_uploaded_file($file['logo']['tmp_name'], $ruta);
}


/////////////////////////////////////////
/////////////////////////////////////////



function delete_file($ruta){
	try{
		$dir=$ruta;
		$borrado=@unlink($dir);
		return $borrado;
	}
	catch (Exception $e) {
	    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
	}
}


/////////////////////////////////////////
/////////////////////////////////////////



function upload_file($ruta){
	$parts=pathinfo($_FILES['archivo']['name']);
	@mkdir($ruta, 0777);
	$ruta =$ruta.'.'.$parts['extension'];
	@move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta);
}


/////////////////////////////////////////
/////////////////////////////////////////


function check_file($file){
	$peso=$file['archivo']['size'];
	$tipo=strtolower($file['archivo']['type']);
	if($tipo == 'image/jpeg' || $tipo == 'image/jpg'){
		if($peso > 1048576){
			alerts(0,"Archivo muy pesado: $peso, debe ser menor a menor o igual a 1mb");
			return 0;
		}else{
			return 1;
		}
	}else{
		alerts(0,"El archivo debe ser jpg");
		return 0;
	}
}


/////////////////////////////////////////
/////////////////////////////////////////


function ramdon_str($largo = '10'){
 return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $largo);
}


/////////////////////////////////////////
/////////////////////////////////////////


 function remove_acents($cadena){
    //Codificamos la cadena en formato utf8 en caso de que nos de errores
    $cadena = $cadena;
    //Ahora reemplazamos las letras
    $cadena = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $cadena
    );
    $cadena = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $cadena );
    $cadena = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $cadena );
    $cadena = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $cadena );
    $cadena = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $cadena );
    $cadena = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C'),
        $cadena
    );
    return $cadena;
}


/////////////////////////////////////////
/////////////////////////////////////////


function curPageURL() {
	$pageURL = 'http';
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}


/////////////////////////////////////////
/////////////////////////////////////////


function db_connect($db_name){
	global $conn_db;
	$conn_db->connect($db_name);	
}


/////////////////////////////////////////
/////////////////////////////////////////


function send_email($email, $sub, $content, $html, $from, $from_name){	
	$headers = 'From: '. $from_name . "<".$from."> \r\n" .
	'Reply-To: '. $from . "\r\n";
	if($html){$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";}
	$headers .= 'X-Mailer: PHP/' . @phpversion();
	if (@mail($email, $sub, $content, $headers)){}else{}
}


/////////////////////////////////////////
/////////////////////////////////////////

function two_way($str, $desc = false){
	if(!$desc){
		$result = bin2hex($str);
		$result = str_replace("d","y!",$result);
		$result = str_replace("1","X-",$result);
		$result = str_replace("7","p?",$result);
		$result = str_replace("3","S*",$result);
	}
	else{
		$str = str_replace("y!","d",$str);
		$str = str_replace("X-","1",$str);
		$str = str_replace("p?","7",$str);
		$str = str_replace("S*","3",$str);
		$result = hextostr($str);
	}
	return $result;
}



/////////////////////////////////////////
/////////////////////////////////////////

function hextostr($hex){
	$str='';
	for ($i=0; $i < strlen($hex)-1; $i+=2){
		$str .= chr(hexdec($hex[$i].$hex[$i+1]));
	}
	return $str;
}


/////////////////////////////////////////
/////////////////////////////////////////


function secure_input($str) {		
	$str = preg_replace("/[^-A-Za-zñÑáéíóúÁÉÍÓÚ_0-9,.@ ]/", "", $str);
	$str = str_replace("--","",$str);
	return $str;  
}



/////////////////////////////////////////
/////////////////////////////////////////


function contains($full,$search){
	$found = false;
	if(strlen(strstr($full,$search))>0){
		$found = true;	
	}
	return $found;	
}


/////////////////////////////////////////
/////////////////////////////////////////


 
 function get_qs_symbol($url){
	if(contains($url,"?")){
		$sym = "&";
	}else{
		$sym = "?";
	}
	return $sym;
}


/////////////////////////////////////////
/////////////////////////////////////////



/*
function create_objects_list($name, $id, $data, $idvar, $labvar, $default_name = "", $selected = NULL, $onchange = "", $class = "", $nodisplay = false){
	?>
	<select name="<? echo $name ?>" id="<? echo $id ?>" onchange="<? echo $onchange ?>" class="<? echo $class ?>" <? if($nodisplay){ ?> style="display:none;"<? } ?>>
    	<? if($default_name != ""){ ?><option value=""><? echo $default_name ?></option><? } ?>
    	<? foreach($data as $item){ ?>
        	<option value="<? echo $item->vars[$idvar] ?>" <? if($item->vars[$idvar] == $selected){echo 'selected="selected"';} ?>><? echo $item->vars[$labvar]?></option>
        <? } ?>
    </select>
    <?
}


/////////////////////////////////////////
/////////////////////////////////////////

function create_list($name, $id, $data, $default_name = "", $selected = NULL, $onchange = "", $class = "", $nodisplay = false){
	?>
	<select name="<? echo $name ?>" id="<? echo $id ?>" onchange="<? echo $onchange ?>" class="<? echo $class ?>" <? if($nodisplay){ ?> style="display:none;"<? } ?>>
    	<? if($default_name != ""){ ?><option value=""><? echo $default_name ?></option><? } ?>
    	<? foreach($data as $item){ ?>
        	<option value="<? echo $item["id"] ?>" <? if($item["id"] == $selected){echo 'selected="selected"';} ?>><? echo $item["label"] ?></option>
        <? } ?>
    </select>
    <?
}
*/

/////////////////////////////////////////
/////////////////////////////////////////

function print_error($id){
	$errors = array();
	$errors[0] = "Usuario o Contraseña Incorrecta";
	return $errors[$id];
}


/////////////////////////////////////////
/////////////////////////////////////////


function str_boolean($bool){
	if($bool){$str = "True";}
	else{$str = "False";}
	return $str;	
}


/////////////////////////////////////////
/////////////////////////////////////////


function do_post_request($url, $data){
	$postdata = http_build_query($data);
	$opts = array('http'=>array('method'=>'POST',
	'user_agent '  => "Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2) Gecko/20100301 Ubuntu/9.10 (karmic) Firefox/3.6",
		'header' => array('Accept: text/html,application/xhtml+xml,application/x-www-form-urlencoded,application/xml;q=0.9,*\/*;q=0.8'),'content' => $postdata));
	$context  = stream_context_create($opts);
	return @file_get_contents($url, false, $context);
}


/////////////////////////////////////////
/////////////////////////////////////////



function file_get_contents_curl($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}


/////////////////////////////////////////
/////////////////////////////////////////

function timediff($from, $to){
	$creted_at = strtotime($from);
	$now = strtotime($to);
	$time_diff = ($now - $creted_at);
	$hours = gmdate("H", $time_diff);
	$mins = gmdate("i", $time_diff);
	$secs = gmdate("s", $time_diff);
	$str = "";
	if($hours > 0){$str .= $hours . " hrs, ";}
	if($mins > 0){$str .= $mins . " mins, ";}
	$str .= $secs . " secs";
	return $str;
}


/////////////////////////////////////////
/////////////////////////////////////////



function param($name, $secured = true){
	$val = "";
	if(isset($_GET[$name])){$val = $_GET[$name];}
	else if(isset($_POST[$name])){$val = $_POST[$name];}
	if($secured){$val = secure_input($val);}
	return $val;
}


/////////////////////////////////////////
/////////////////////////////////////////


function text_preview($text, $num, $tags = ''){
	$text = strip_tags($text,$tags);
	if(strlen($text) > $num){
		$text = substr($text,0,$num) . "...";
	}
	return $text;
}


/////////////////////////////////////////
/////////////////////////////////////////


function str_center($first, $second, $string){
	$exnum = strlen($first);
	$pos = strpos($string,$first);
	$pos2 = strpos($string,$second);
	$extra = substr($string,$pos2);	
	return str_replace($extra,"",substr($string,$pos+$exnum));
}


/////////////////////////////////////////
/////////////////////////////////////////


function utf8_for_xml($string){
	$clean =	preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u',
                          ' ', $string);
	 $clean = str_replace('"','',$clean);					  
      return str_replace("'","",$clean);
}


/////////////////////////////////////////
/////////////////////////////////////////


function condition_boostrap($ccondicion){
	switch($ccondicion){
		case 01: $color="badge badge-success";
		break;
		case 02: $color="badge badge-danger";
		break;
		case 03: $color="badge badge-warning";
		break;
		case 04: $color="badge badge-primary";
		break;
		case 05: $color="badge badge-info";
		break;
		case 06: $color="badge badge-info";
		break;
		case 99: $color="badge badge-warning";
		break;
	}
	return $color;
}



/////////////////////////////////////////
/////////////////////////////////////////






function begin_end_month($date,$type){
	
	$query_date = $date;
	// First day of the month.
	if ($type == "first"){
		return date('Y-m-01', strtotime($query_date));
	}
	// Last day of the month.
	if ($type == "last"){
		return date('Y-m-t', strtotime($query_date));	
	}
}


/////////////////////////////////////////
/////////////////////////////////////////


function xml2array ( $xmlObject, $out = array () ){
    foreach ( (array) $xmlObject as $index => $node )
        $out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;
    return $out;
}


/////////////////////////////////////////
/////////////////////////////////////////


function insert_error_log($type, $error){
	//echo $type . $error;
}


/////////////////////////////////////////
/////////////////////////////////////////


function getRealIP(){
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];
       
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
   
    return $_SERVER['REMOTE_ADDR'];
} 

/////////////////////////////////////////
/////////////////////////////////////////


function doToolTip($type,$title){

	switch($type){
		case 1:return 'style="cursor:pointer;" data-plugin="tippy" title="'.$title.'" data-tippy-interactive="true" data-tippy-animation="perspective" data-tippy-arrow="true"';
		break;

		case 2:return 'style="cursor:pointer;" data-plugin="tippy" title="'.$title.'" data-tippy-animation="perspective" data-tippy-arrow="true"';
		break;
	}
	
}


/////////////////////////////////////////
/////////////////////////////////////////




function DebugLogTxt($texto, $array = null) {
    // Obtiene la fecha y hora actual
    $fechaHora = date('Y-m-d H:i:s');
    
    // Abre el archivo LogVRB.txt en modo de escritura/lectura; si no existe, lo crea
    $archivo = fopen( $_SERVER['DOCUMENT_ROOT'].'/V01/LogFile.txt', 'a');
    
    // Escribe la fecha y hora en el archivo
    fwrite($archivo, $fechaHora . "\n");
    
    // Escribe el texto recibido en el archivo
    fwrite($archivo, $texto . "\n");
    
    // Si se proporciona un array, se formatea y se escribe en el archivo
    if ($array !== null && is_array($array)) {
        fwrite($archivo, "<pre>" . print_r($array, true) . "</pre>\n");
    }
    
    // Cierra el archivo
    fclose($archivo);
}


function site_url($url) {
    global $base_url;

    if (empty($url)) {
        return $url;
    }

    $productionUrl = 'https://aazdsgn.com';

    if (strpos($url, $productionUrl) === 0) {
        return $base_url . substr($url, strlen($productionUrl));
    }

    return $url;
}
?>