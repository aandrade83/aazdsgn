<?php
require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php");
$page_title = $lang === '_en'
  ? 'Contact | AAZ DSGN Architecture Costa Rica'
  : 'Contacto | AAZ DSGN Arquitectura Costa Rica';
$page_description = $lang === '_en'
  ? 'Contact AAZ DSGN to talk about your next architecture, design, construction or remodeling project in Costa Rica.'
  : 'Contacte a AAZ DSGN para conversar sobre su próximo proyecto de arquitectura, diseño, construcción o remodelación en Costa Rica.';
?>
<? include ("./ui/head.php");?>
<? include ("./ui/menu.php");?>
<? include ("./ui/main-contact.php");?>
<? include ("./ui/footer.php"); ?>
