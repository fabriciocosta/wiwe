<?php
global $_seccion_;
global $CLang;

global $texto;

$this->Sistema('SISTEMA_COMUNIDAD', $texto);


global $_cID_;
?>
<a name="head"></a>
<div class="container">

<?php 
if ($_cID_) {
	?>
	<div class="table-striped view-content view-content-<?=$__modulo__?>">
	<?
	$this->InicializarTemplatesCompletos();
	$Contenido = $this->Contenidos->GetContenidoCompleto($_cID_);
	$this->TiposContenidos->MostrarCompleto( $Contenido );
	?>
	</div>
	<?  	
}?>

<table cellpadding="0" cellspacing="0" border="0" width="95%">
	<tr>
		<td style="text-align:justify;"><br>
		<span class="text_white" ><?=$texto?></span>
		</td>
	</tr>		
</table>

	<?{
	
	$Seccion = $this->Secciones->GetSeccionByName( "tutoriales");
	$TIPOCONTENIDO = FICHA_TUTORIAL;
	
	?>
	<div class="view-list-seccion">
		<div class="nombre">
		<?=$Seccion->Nombre() ?>
		</div>
	</div>
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

</div>


