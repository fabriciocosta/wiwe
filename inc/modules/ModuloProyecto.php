<?Php
/**
*ModuloProyecto
*
**/

global $CLang;
global $_cID_;
global $_titulo_contenido_;

$TIPOCONTENIDO = FICHA_PROYECTO;

?>
<?
	{
		$Seccion = $this->Secciones->GetSeccionByName( $__modulo__ );	
		if (is_object($Seccion)) {
			$texto = $Seccion->m_descripcion;			
		}
	} 
	
	if (trim($texto)!="") {
		$textos = explode( "\n", $texto);
		$texto_firstline = $textos[0];
		$texto_body = substr( $texto, strlen($texto_firstline) );			
	}
?>
<a name="head"></a>
<div class="container"><!-- content content-<?=$__modulo__?>">-->

	<!-- Page Header -->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?=$texto_firstline?>
				<small>Crea, clona, comparte...</small>
			</h1>
		</div>
	</div>
	
        <!-- /.row -->
<!--
	<div class="header header-<?=$__modulo__?>">    
	    <h1><?=$texto_firstline?></h1>
	    <h2><?=$texto_body?></h2>
	</div>
-->
<?

if ($_cID_) {
	?>
	<!--<div class="view-content view-content-<?=$__modulo__?>">-->
	<div class="row">
	<?
	$this->InicializarTemplatesCompletos();
	$Contenido = $this->Contenidos->GetContenidoCompleto($_cID_);	
	$this->TiposContenidos->MostrarCompleto( $Contenido );
	?>
	</div>
	<?  	
} else {
	?>
	<!--<div class="view-list view-list-<?=$__modulo__?>">-->
	<div class="row">
	<?
	$this->InicializarTemplatesResumenes();
	$this->TiposContenidos->SetTemplateResumen( $TIPOCONTENIDO );
	$this->Contenidos->MostrarPorTipo( "resumen", "", $TIPOCONTENIDO, 100 );
	?>
	</div>
	<?  
}

?>
</div>
