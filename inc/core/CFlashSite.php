<?

$_modulo_ = "contenidosflash";

require "../../inc/include/deftabla.php";//modulo de definicion de las tablas

//AQUI CONTRUIMOS EL OBJETO PRINCIPAL DEL SITIO, AL QUE LE ASIGNAMOS LAS TABLAS YA CREADAS
if (!defined("DNK_SITE") and !defined("Sitio")) {
  	define("DNK_SITE","OK");
  	$Sitio = new CSitioExtended($_tsecciones_,$_ttipossecciones_,$_tcontenidos_,$_ttiposcontenidos_,$_tarchivos_,$_ttiposarchivos_,$_tdetalles_,$_ttiposdetalles_,$_tlogs_);
  	
  	$Sitio->InicializarTemplatesColapsados();
	$Sitio->InicializarTemplatesCompletos();   
}


$Sitio->ModuloContenidosFlash();
?>