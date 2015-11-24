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

$__modulo__= "descargas";
$__submodulo__ = "lastversion";
$request_lastversion = "ok";

require "../../inc/include/deftabla.php";//modulo de definicion de las tablas

//AQUI CONTRUIMOS EL OBJETO PRINCIPAL DEL SITIO, AL QUE LE ASIGNAMOS LAS TABLAS YA CREADAS
if (!defined("DNK_SITE") and !defined("Sitio")) {
  	define("DNK_SITE","OK");
  	$Sitio = new CSitioExtended($_tsecciones_,$_ttipossecciones_,$_tcontenidos_,$_ttiposcontenidos_,$_tarchivos_,$_ttiposarchivos_,$_tdetalles_,$_ttiposdetalles_,$_tlogs_);
  	$Sitio->Inicializar();
}

?>
<?
$Sitio->ModuloDescargas();
?>