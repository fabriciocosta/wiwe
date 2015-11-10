<?php
global $_seccion_;
global $CLang;

global $package;
global $moldeoversion;
global $osversion;
global $request_lastversion;

//echo "Last Version: package: ".$package." osversion:".$osversion." moldeoversion:".$moldeoversion;

if ( $package!="" && $moldeoversion!="" && $osversion!="" ) {
	
	$Descarga = null;
	
	if ( $osversion=="win32" ) {
		$Descarga = $this->Contenidos->GetContenidoPorTitulo( "windows%", FICHA_DESCARGA );		
	}
	if ( $osversion=="linux" ) {
		$Descarga = $this->Contenidos->GetContenidoPorTitulo( "linux%", FICHA_DESCARGA );
	}
	if ( $osversion=="mac" || $osversion=="macos" || $osversion=="osx" ) {
		$Descarga = $this->Contenidos->GetContenidoPorTitulo( "mac%", FICHA_DESCARGA );
	}

	$Descarga = $this->Contenidos->GetContenidoCompleto( $Descarga->m_id);
	
	header ("Content-Type:text/xml");
	$result = '<?xml version="1.0" encoding="utf-8"?>';	
	if ($Descarga) {
		$major_version = $Descarga->m_detalles["DESCARGA_MAJOR_VERSION"]->m_detalle;
		$minor_version = $Descarga->m_detalles["DESCARGA_MINOR_VERSION"]->m_detalle;
		$release_version = $Descarga->m_detalles["DESCARGA_RELEASE_VERSION"]->m_detalle;
		$build_version = $Descarga->m_detalles["DESCARGA_BUILD_VERSION"]->m_detalle;
		$os_version = $Descarga->m_detalles["DESCARGA_OS_VERSION"]->m_detalle;
		$comments = $Descarga->m_detalles["DESCARGA_MEJORAS"]->m_txtdata;
			
		$full_text_version = $major_version.".".$minor_version." ".ucfirst($release_version)." (build ".$build_version.")";
		$result.= "\n".'<moldeo org_api_version="1.0" date="'.date("Y-m-d,H:m:s").'">';
		$result.= "\n".'<version os="'.$os_version.'" major="'.$major_version.'" minor="'.$minor_version.'" release="'.$release_version.'" build="'.$build_version.'" >'.$full_text_version.'</version>';		
		$result.= "\n".'<comments type="html">'.utf8_encode( utf8_decode( html_entity_decode( $comments ))).'</comments>';	
		$result.= "\n"."</moldeo>";
	}
	echo $result;
	return;
}

if ($request_lastversion) {
	header ("Content-Type:text/xml");
	$result = '<?xml version="1.0" encoding="utf-8"?>';	
	$result.= "\n".'<moldeo org_api_version="1.0" date="'.date("Y-m-d,H:m:s").'">';
	$result.= "\n".'<error>package, moldeoversion and osversion parameters missing from url.</error>';
	$result.= "\n"."</moldeo>";
	echo $result;
	return;
}


global $texto;

$this->Sistema('SISTEMA_DESCARGAS', $texto);
?>
<!-- 
<table cellpadding="0" cellspacing="0" border="0" width="95%">
	<tr>
		<td style="text-align:justify;"><br>
		<span class="text_white" ><?=$texto?></span>
		</td>
	</tr>		
</table>
-->
<?Php
/**
*ModuloDescargas
*
**/
global $_cID_;
global $_titulo_contenido_;
global $__modulo__;




$TIPOCONTENIDO = FICHA_DESCARGA;
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
<a name="head2"></a>
<div class="container">

<div class="content content-<?=$__modulo__?>">
	<div class="header header-<?=$__modulo__?>">    
	    <!--<h1><?=$texto_firstline?></h1>
	    <h2><?=$texto_body?></h2>-->
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
	$this->Contenidos->MostrarPorTipo( "resumen", $Seccion->m_id, $TIPOCONTENIDO, 100 );
	?>
	</div>
	<?  
}

?>
</div>

</div>
