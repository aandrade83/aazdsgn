<?php
require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php");

$id = param('id');
$id = ctype_digit((string)$id) ? (int)$id : 0;
$proyect = $id > 0 ? get_project($id, $lang) : null;

if (!$proyect) {
    http_response_code(404);
    require($_SERVER['DOCUMENT_ROOT']."/errors/404.php");
    exit;
}

$page_title = $lang === '_en'
  ? $proyect->vars['titulo'].' | Architecture Project | AAZ DSGN'
  : $proyect->vars['titulo'].' | Proyecto de Arquitectura | AAZ DSGN';

$project_description_raw = trim(strip_tags((string) $proyect->vars['description']));
$page_description = $project_description_raw !== ''
  ? text_preview($project_description_raw, 155)
  : ($lang === '_en'
      ? 'Discover this AAZ DSGN architecture project in Costa Rica: design, construction and details of the space.'
      : 'Conozca este proyecto de arquitectura de AAZ DSGN en Costa Rica: diseño, construcción y detalles del espacio.');

$og_type = 'article';
?>
<? include ("./ui/head.php");?>
<? include ("./ui/menu.php");?>
<? include ("./ui/main-detail.php");?>
<? include ("./ui/footer.php"); ?>
