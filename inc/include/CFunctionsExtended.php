<?php

if (is_object($CFun)) {
	
	global $sitemenu;

	$sitemenu = '';
	
	function SITEMENU() {
		
		global $sitemenu;

		return $sitemenu;
		
	}
	
	function EVENTOSOBRA( $idcontenido ) {
		
		global $Sitio;
		
		$eventosstr = "";
		
		if ($idcontenido!="") {
			
			//Debug($idcontenido);
			
			$Obra = $Sitio->Contenidos->GetContenidoCompleto( $idcontenido );
			
			//Debug( $Obra->Titulo() );
			
			$tr = $Sitio->Relaciones->m_trelaciones;
			
			$tr->QuitarReferencias();
			$tr->LimpiarSQL();
			$tr->FiltrarSQL('ID_CONTENIDO_REL','',$idcontenido);
			$tr->FiltrarSQL('ID_TIPORELACION','',EVENTOS_OBRAS);		
			$tr->OrdenSQL('FECHAEVENTO DESC');
			$tr->Open();
			//Debug($tr->SQL);
			if($tr->nresultados>0) {
				
				$eventosstr = '<div class="eventos">';
				
				while($rr = $tr->Fetch()) {
					$CRelacion = new CRelacion($rr);
					$Evento = $Sitio->Contenidos->GetContenidoCompleto( $CRelacion->m_id_contenido );
					//Debug($Galeria->Titulo());
					//$galstr.= '<div class="galeria"><a href="" rel="lightbox"><img src="'.$Galeria->m_detalles['GALERIA_IMAGEN']->m_detalle.'"/></div>';
					$eventosstr.= $Sitio->TiposContenidos->TextoCompleto( $Evento, 
							'<div class="*ID_TIPOCONTENIDO:FICHA_TIPO*" id="_*IDCONTENIDO*_">
	<div class="resumen">
		<a href="/eventos/*TITULO:URL*" name="*IDCONTENIDO*">
		<div class="fechaevento">*FECHAEVENTO*</div>		
		<div class="titulo">*TITULO*</div>				
		</a>		
	</div>
</div>' );
				}
				$eventosstr.= '</div>';
			} else {
				Debug("sin resultados");
			}
			
		}
		return $eventosstr;		
		
	}
	
	function GALERIAOBRAS( $idcontenido ) {
		
		global $Sitio;
		
		$galstr = "";
		
		if ($idcontenido!="") {
			
			//Debug($idcontenido);
			
			$Obra = $Sitio->Contenidos->GetContenidoCompleto( $idcontenido );
			
			//Debug( $Obra->Titulo() );
			
			$tr = $Sitio->Relaciones->m_trelaciones;
			
			$tr->QuitarReferencias();
			$tr->LimpiarSQL();
			$tr->FiltrarSQL('ID_CONTENIDO_REL','',$idcontenido);
			$tr->FiltrarSQL('ID_TIPORELACION','',GALERIA_OBRAS);		
			$tr->Open();
			//Debug($tr->SQL);
			if($tr->nresultados>0) {
				
				$galstr = '<div class="galeria">';
				
				while($rr = $tr->Fetch()) {
					$CRelacion = new CRelacion($rr);
					$Galeria = $Sitio->Contenidos->GetContenidoCompleto( $CRelacion->m_id_contenido );
					//Debug($Galeria->Titulo());
					//$galstr.= '<div class="galeria"><a href="" rel="lightbox"><img src="'.$Galeria->m_detalles['GALERIA_IMAGEN']->m_detalle.'"/></div>';
					$galstr.= $Sitio->TiposContenidos->TextoCompleto( $Galeria, 
							'<div class="foto">
									<a name="*TITULO*" href="*#GALERIA_IMAGEN#*" rel="lightbox">
										<img alt="*TITULO*" title="*TITULO*" src="*#GALERIA_IMAGEN_THUMB#*" border="0"/>
									</a>
									<div class="copete">*COPETE*</div>
									</div>' );
				}
				$galstr.= '</div>';
			} else {
				Debug("sin resultados");
			}
			
		}
		return $galstr;
	}
	
	function GALERIAEVENTOS( $idcontenido ) {
		
		global $Sitio;
		
		$galstr = "";
		
		if ($idcontenido!="") {
			
			//Debug($idcontenido);
			
			$Evento = $Sitio->Contenidos->GetContenidoCompleto( $idcontenido );
			
			//Debug( $Obra->Titulo() );
			
			$tr = $Sitio->Relaciones->m_trelaciones;
			
			$tr->QuitarReferencias();
			$tr->LimpiarSQL();
			$tr->FiltrarSQL('ID_CONTENIDO_REL','',$idcontenido);
			$tr->FiltrarSQL('ID_TIPORELACION','',GALERIA_EVENTOS);		
			$tr->Open();
			//Debug($tr->SQL);
			if($tr->nresultados>0) {
				
				$galstr = '<div class="galeria">';
				
				while($rr = $tr->Fetch()) {
					$CRelacion = new CRelacion($rr);
					$Galeria = $Sitio->Contenidos->GetContenidoCompleto( $CRelacion->m_id_contenido );
					//Debug($Galeria->Titulo());
					//$galstr.= '<div class="galeria"><a href="" rel="lightbox"><img src="'.$Galeria->m_detalles['GALERIA_IMAGEN']->m_detalle.'"/></div>';
					$galstr.= $Sitio->TiposContenidos->TextoCompleto( $Galeria, 
							'<div class="foto">
									<a name="*TITULO*" href="*#GALERIA_IMAGEN#*" rel="lightbox">
										<img alt="*TITULO*" title="*TITULO*" src="*#GALERIA_IMAGEN_THUMB#*" border="0"/>
									</a>
									<div class="copete">*COPETE*</div>
									</div>' );
				}
				$galstr.= '</div>';
			} else {
				Debug("sin resultados");
			}
			
		}
		return $galstr;
	}	
	
	function ADDTHIS() {
		
		return '
		
				<div class="compartir">
					<div class="addthis_toolbox addthis_default_style ">
					<a href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4d648b1e28bfa0e0" class="addthis_button_compact">Compartir</a>
					<span class="addthis_separator">|</span>
					<a class="addthis_button_preferred_1"></a>
					<a class="addthis_button_preferred_2"></a>
					<a class="addthis_button_preferred_3"></a>
					<a class="addthis_button_preferred_4"></a>
					</div>
					<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4d648b1e28bfa0e0"></script>
					<!-- AddThis Button END -->	    
				</div>
						
		';
		
	}
	
	$CFun->AddFunction( "SITEMENU" );
	$CFun->AddFunction( "ADDTHIS" );
	$CFun->AddFunction( "GALERIAOBRAS" );	
	$CFun->AddFunction( "GALERIAEVENTOS" );
	$CFun->AddFunction( "EVENTOSOBRA" );		
	
}


?>