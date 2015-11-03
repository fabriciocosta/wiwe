<?php
header('Content-Type: text/html; charset=iso-8859-1');

$__modulo__ = "getrandomcontent";
		
require "../../inc/include/deftabla.php";//modulo de definicion de las tablas

if (!defined("DNK_SITE") and !defined("Sitio")) {

		define("DNK_SITE","OK");
  	
		$Sitio = new CSitioExtended($_tsecciones_,$_ttipossecciones_,$_tcontenidos_,$_ttiposcontenidos_,$_tarchivos_,$_ttiposarchivos_,$_tdetalles_,$_ttiposdetalles_,$_tlogs_,$_tusuarios_);
  	
		$Sitio->Inicializar();    	
  	
}



global $_cID_;
global $_tipocontenido_;
global $_titulo_;
global $_search_;

$tc = $Sitio->Contenidos->m_tcontenidos;

$tc->LimpiarSQL();

if (is_numeric( $_tipocontenido_ ) ) 
	$tc->FiltrarSQL('ID_TIPOCONTENIDO','',$_tipocontenido_);

$tc->OrdenSQL('RAND() ASC');

$tc->Open();

if ($tc->nresultados>0) {

	while( $rf = $tc->Fetch()) {
		
		if ($rf["contenidos.ID"]!=$_cID_) {
			
			$Content = $Sitio->Contenidos->GetContenidoCompleto( $rf["contenidos.ID"] );
			
			echo  $Sitio->TiposContenidos->TextoCompleto( $Content );
			
			break;
			
		}
		
	}

}


?>