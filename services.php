<?php
require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php");
$page_title = $lang === '_en'
  ? 'Architecture, Design and Construction Services | AAZ DSGN'
  : 'Servicios de Arquitectura, Diseño y Construcción | AAZ DSGN';
$page_description = $lang === '_en'
  ? 'Architecture, design, construction, remodeling and construction inspection services in Costa Rica for clients seeking integral, custom solutions.'
  : 'Servicios de arquitectura, diseño, construcción, remodelación e inspección de obra en Costa Rica para clientes que buscan soluciones integrales y personalizadas.';
?>
<? include ("./ui/head.php");?>
<? include ("./ui/menu.php");?>
<? include ("./ui/main-services.php");?>
<? include ("./ui/footer.php"); ?>

