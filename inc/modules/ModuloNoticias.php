<?php
global $_seccion_;
global $CLang;

global $_cID_;

if ($_cID_) {
	?>
	<div class="view-content view-content-<?=$__modulo__?>">
	<?
	$this->InicializarTemplatesCompletos();
	$Contenido = $this->Contenidos->GetContenidoCompleto($_cID_);
	$this->TiposContenidos->MostrarCompleto( $Contenido );
	?>
	</div>
	<?  	
}?>

	<?{
	
	$TIPOCONTENIDO = FICHA_NOTICIA;
	
	?>
	<div class="view-list view-list-<?=$__modulo__?>">
	<br>
	<?
	$this->InicializarTemplatesResumenes();
	$this->TiposContenidos->SetTemplateResumen( $TIPOCONTENIDO );
	$this->Contenidos->MostrarPorTipo( "resumen", $Seccion->m_id, $TIPOCONTENIDO, 100 );
	?>
	</div>
	<?  
}

?>
