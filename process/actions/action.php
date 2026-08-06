<? require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php"); ?>

<?php
// action.php

    $ip = param('ip',false);
    $pais = param('pais');
    $dispositivo = param('dispositivo',true);
    $navegador = param('navegador',true);
    $url = param('url',false);
       
    $visita = new _Visitas();
    $visita->vars['ip'] = $ip;
    $visita->vars['pais'] = $pais;
    $visita->vars['dispositivo'] = $dispositivo;
    $visita->vars['navegador'] = $navegador;
    $visita->vars['url'] = $url;
    $visita->vars['fecha'] = time();
    $visita->vars['date'] = date('Y-m-d H:s:i');
    $visita->insert();
    
    if($visita->vars['id'] > 0){
    	$data['control'] = 1;
    } else { $data['control'] = 0 ;}
  
    /*
    $data['aa'] = $visita;
    $data['control'] = $control;
    $data['POST'] = $_POST;
*/
 

 echo json_encode($data);


?>