<?
require "../../inc/include/deftabla.php";//modulo de definicion de las tablas

$__modulo__ = "contacto";

//AQUI CONTRUIMOS EL OBJETO PRINCIPAL DEL SITIO, AL QUE LE ASIGNAMOS LAS TABLAS YA CREADAS
if (!defined("DNK_SITE") and !defined("Sitio")) {
  	define("DNK_SITE","OK");
  	$Sitio = new CSitioExtended($_tsecciones_,$_ttipossecciones_,$_tcontenidos_,$_ttiposcontenidos_,$_tarchivos_,$_ttiposarchivos_,$_tdetalles_,$_ttiposdetalles_,$_tlogs_);
  	$Sitio->Inicializar();  
}

?>
<html>
<head>
<title><?=$_TITLE_?></title>
<? require "../../inc/include/scripts.php";//los scripts mas comunes?>
<? require "../../inc/include/style.php";//los estilos?>
</head>
<? require "../../inc/include/siteheader.php";?>
<? require "../../inc/include/pageheader.php";?>
<!-- PAGINA --><?
$Sitio->ModuloContacto();
?><!-- FIN PAGINA -->
<? require "../../inc/include/pagefooter.php";?>
<? require "../../inc/include/sitefooter.php";?>
</html>