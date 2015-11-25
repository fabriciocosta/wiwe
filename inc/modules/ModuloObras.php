<?php
global $_seccion_;
global $CLang;

global $texto;

global $_cID_;
global $_titulo_contenido_;
global $__modulo__;

global $_mod_;
global $_valor_;

$_valor_ = stripcslashes(strip_tags(trim(urldecode($_valor_))));

$TIPOCONTENIDO = FICHA_OBRAS;
?>
<a name="head"></a>
<div class="container">
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

<div class="content content-<?=$__modulo__?>">
<!-- 
	<div class="header header-<?=$__modulo__?>">    
	    <h1><?=$texto_firstline?></h1>
	    <h2><?=$texto_body?></h2>
	</div>-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?=$texto_firstline?>
				<small>Crea, clona, comparte...</small>
			</h1>
		</div>
	</div>
<?

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
} else {
	?>
	<div class="view-list view-list-<?=$__modulo__?>">
	<?
	$this->InicializarTemplatesResumenes();
	$this->TiposContenidos->SetTemplateResumen( $TIPOCONTENIDO );

	
	/*FILTROS*/
	
	$ids = array();
	
	if ($_mod_=="categorias" && $_valor_!="") {		
		
		$tc = $this->Contenidos->m_tcontenidos;
		$tc->AgregarReferencias(
				array(	"RL.ID_SECCION_REL","CATS.NOMBRE"),
				array(	"relaciones RL","secciones CATS"),
				array(	"RL.ID_TIPORELACION=".OBRAS_CATEGORIAS_ESCENICAS,
								"RL.ID_CONTENIDO=contenidos.ID",
								"RL.ID_SECCION_REL=CATS.ID",
								"CATS.ID_SECCION=22",
								"CATS.ID_TIPOSECCION=".SECCION_CATEGORIA,
								"CATS.NOMBRE LIKE '%".str_replace(" ","%",$_valor_)."%'" )
		);
		
		$ids = $this->Contenidos->GetContenidos(FICHA_OBRAS);
	
		$tc->QuitarReferencias();
		
	} else if ($_mod_=="espacios" && $_valor_!="") {		
		
		$tc = $this->Contenidos->m_tcontenidos;
		$tc->AgregarReferencias(
				array(	"RL.ID_CONTENIDO_REL","ESPS.TITULO"),
				array(	"relaciones RL","contenidos ESPS"),
				array(	"RL.ID_TIPORELACION=".OBRAS_ESPACIOS,
								"RL.ID_CONTENIDO=contenidos.ID",
								"RL.ID_CONTENIDO_REL=ESPS.ID",
								"ESPS.ID_TIPOCONTENIDO=".FICHA_ESPACIOS,
								"ESPS.TITULO LIKE '%".str_replace(" ","%",$_valor_)."%'" )
		);
		
		$ids = $this->Contenidos->GetContenidos(FICHA_OBRAS);
	
		$tc->QuitarReferencias();
		
	} else if ($_mod_=="festivales" && $_valor_!="") {		
		
		$tc = $this->Contenidos->m_tcontenidos;
		$tc->AgregarReferencias(
				array(	"RL.ID_CONTENIDO_REL","FESTS.TITULO"),
				array(	"relaciones RL","contenidos FESTS"),
				array(	"RL.ID_TIPORELACION=".OBRAS_FESTIVALES,
								"RL.ID_CONTENIDO=contenidos.ID",
								"RL.ID_CONTENIDO_REL=FESTS.ID",
								"FESTS.ID_TIPOCONTENIDO=".FICHA_FESTIVALES,
								"FESTS.TITULO LIKE '%".str_replace(" ","%",$_valor_)."%'" )
		);
		
		$ids = $this->Contenidos->GetContenidos(FICHA_OBRAS);
	
		$tc->QuitarReferencias();
		
	}
	
	
	
	/*IMPRESION DE RESULTADOS*/
	
	if (count($ids)>0) {
		$repes = array();
		ShowMessage("Obras filtradas por &quot;<strong>".$_valor_."</strong>&quot;<br><a href=/obras>ver todas las obras</a>");
		echo "<br><br>";
		foreach($ids as $key=>$val) {
			if ($repes[$val]!="ok") {
				$Obra = $this->Contenidos->GetContenidoCompleto($val);
				echo $this->TiposContenidos->TextoResumen( $Obra );
				$repes[$val] = "ok";
			}
		}
		
	} else if($_mod_!="") {
		
		ShowMessage("No hay obras asociadas a la categoría <strong>".$_valor_."</strong>");
		
	} else {
		$this->Contenidos->MostrarPorTipo( "resumen", $Seccion->m_id, $TIPOCONTENIDO, 100 );
	}
	
	
	?>
	</div>
	<?  
}

?>
</div>


</div>