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
global $_content_template_;
//echo "_content_template_:".$_content_template_;
//echo '<br>';
$tc = $Sitio->Contenidos->m_tcontenidos;

$tc->LimpiarSQL();
$tc->FiltrarSQL('ID_TIPOCONTENIDO','',$_tipocontenido_);
$tc->OrdenSQL('RAND() ASC');
$tc->Open();
if ($tc->nresultados>0) {
	while( $rf = $tc->Fetch()) {
		if ($rf["contenidos.ID"]!=$_cID_) {
			$Content = $Sitio->Contenidos->GetContenidoCompleto( $rf["contenidos.ID"] );
			if ($_content_template_=='resumen') {
				echo  $Sitio->TiposContenidos->TextoResumen( $Content  ); 
			} else if ($_content_template_=='colapsado') {
				echo  $Sitio->TiposContenidos->TextoColapsado( $Content ); 
			} else { 
				echo  $Sitio->TiposContenidos->TextoCompleto( $Content, $_content_template_ );
			}
			break;
		}
	}
}


?>