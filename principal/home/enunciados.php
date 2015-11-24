<?
//======================================================================
//
//			home.php
//			pagina modelo del site
//			version: 1.0 (18/04/2003)
//			descripcion: toma como parametros el id de seccion y el 
//			id de contenido, para mostrar el contenido se fija primero 
//			si se especifico una seccion sino muestra como contenido de la pagina
//			homepage.php que es la encargada de visualizar el contenido de la home
//======================================================================

require "../../inc/include/deftabla.php";//modulo de definicion de las tablas

$__modulo__= "enunciados";

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
<? require "../../inc/include/siteheader.php";//el encabezado comun al sitio (marco del sitio-toc-cartelera-etc)?>
<? require "../../inc/include/pageheader.php";//el encabezado comun a la pagina (marco de la pagina-por ahora nada en particular)?>
<!-- PAGINA --><?
$Sitio->ModuloEnunciados();
?><!-- FIN PAGINA -->
<? require "../../inc/include/pagefooter.php";?>
<? require "../../inc/include/sitefooter.php";?>
</html>