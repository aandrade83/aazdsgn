<?php
require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php");
$page_title = $lang === '_en'
  ? 'About AAZ DSGN | Architecture Studio in Costa Rica'
  : 'Sobre AAZ DSGN | Estudio de Arquitectura en Costa Rica';
$page_description = $lang === '_en'
  ? 'Meet AAZ DSGN, an architecture studio in Costa Rica focused on creative design, functional solutions and professional guidance throughout every project.'
  : 'Conozca AAZ DSGN, estudio de arquitectura en Costa Rica enfocado en diseño creativo, soluciones funcionales y acompañamiento profesional en cada etapa del proyecto.';
?>
<? include ("./ui/head.php");?>
<? include ("./ui/menu.php");?>
<? include ("./ui/main-about.php");?>
<? include ("./ui/footer.php"); ?>


