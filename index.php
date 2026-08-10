<?php
require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php");
$page_title = $lang === '_en'
  ? 'AAZ DSGN | Architecture, Design and Construction in Costa Rica'
  : 'AAZ DSGN | Arquitectura, Diseño y Construcción en Costa Rica';
$page_description = $lang === '_en'
  ? 'AAZ DSGN develops architecture, design and construction projects in Costa Rica, with a creative, functional approach for residential and commercial spaces.'
  : 'AAZ DSGN desarrolla proyectos de arquitectura, diseño y construcción en Costa Rica, con enfoque creativo, funcional y personalizado para espacios residenciales y comerciales.';
?>
<? include ("./ui/head.php");?>
<? include ("./ui/home-video.php");?>
<? include ("./ui/menu.php");?>
<?  include ("./ui/main-page.php");?>
<? include ("./ui/footer.php"); ?>

