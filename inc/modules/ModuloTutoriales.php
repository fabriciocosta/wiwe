<?php
global $_seccion_;
global $CLang;

global $texto;

global $_cID_;
?>
<a name="head"></a>
<div class="container">
<?php 
if ($_cID_) {
	?>
	<div class="view-content view-content-<?=$__modulo__?>">
	<?
	$this->InicializarTemplatesCompletos();
	$Contenido = $this->Contenidos->GetContenidoCompleto($_cID_);
	
	if ($Contenido->m_detalles["TUTORIAL_VIDEOEXTERNO"]->m_detalle!="") {
		echo '<style> .view-content div.imagen {display: none !important;} </style>';
	}
	
	$this->TiposContenidos->MostrarCompleto( $Contenido );
	?>
	</div>
	<?  	
}
	{
	
	$TIPOCONTENIDO = FICHA_TUTORIAL;
	
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
</div>


