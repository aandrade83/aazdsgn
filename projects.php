<?php
require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php");
$page_title = $lang === '_en'
  ? 'Architecture Projects in Costa Rica | AAZ DSGN'
  : 'Proyectos de Arquitectura en Costa Rica | AAZ DSGN';
$page_description = $lang === '_en'
  ? 'Explore AAZ DSGN architecture projects in Costa Rica, including residential design, remodeling, construction and custom proposals.'
  : 'Explore proyectos arquitectónicos de AAZ DSGN en Costa Rica, incluyendo diseño residencial, remodelaciones, construcción y propuestas personalizadas.';
?>
<? include ("./ui/head.php");?>
<? include ("./ui/menu.php");?>
<? include ("./ui/main-projects.php");?>
<? include ("./ui/footer.php"); ?>

