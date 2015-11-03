<?php
header('Content-Type: text/html; charset=iso-8859-1');

$__modulo__ = "getcontent";
		
require "../../inc/include/deftabla.php";//modulo de definicion de las tablas

if (!defined("DNK_SITE") and !defined("Sitio")) {

		define("DNK_SITE","OK");
  	
		$Sitio = new CSitioExtended($_tsecciones_,$_ttipossecciones_,$_tcontenidos_,$_ttiposcontenidos_,$_tarchivos_,$_ttiposarchivos_,$_tdetalles_,$_ttiposdetalles_,$_tlogs_,$_tusuarios_);
  	
		$Sitio->Inicializar();    	
  	
}



global $_cID_;

$Content = $Sitio->Contenidos->GetContenidoCompleto( $_cID_ );

echo  $Sitio->TiposContenidos->TextoCompleto( $Content );

?>