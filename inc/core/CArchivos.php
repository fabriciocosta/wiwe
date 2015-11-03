<?php

/**
 * class CArchivos
 *
 * @version 28/11/2003
 * @copyright 2003 
 **/

 
class CArchivos {

	var $m_tarchivos;//tabla archivos
	var $m_CTiposArchivos;//miembro de la clase CTiposArchivos
	
	function CArchivos(&$__tarchivos__,&$__m_CTiposArchivos__) {
		
		$this->m_CTiposArchivos = &$__m_CTiposArchivos__;
		$this->m_tarchivos = &$__tarchivos__;
		
	}
	
	function Set(&$__tarchivos__,&$__m_CTiposArchivos__) {
		$this->m_CTiposArchivos = &$__m_CTiposArchivos__;
		$this->m_tarchivos = &$__tarchivos__;
	}
	
	

	function MostrarColapsados($__idseccion__,$__excluyeaid__=-1) {
					
			echo '<tr><td height="2"><img src="../../inc/images/spacer.gif" height="10"></td></tr>';	
					
			$this->m_tarchivos->LimpiarSQL();
			//$GLOBALS['_f_ID_SECCION'] = $__idseccion__;			
			if ($__excluyeaid__>=1) $this->m_tarchivos->FiltrarSQL('ID_SECCION','/*SPECIAL*/archivos.ID<>'.$__excluyeaid__,$__idseccion__);
			else $this->m_tarchivos->FiltrarSQL('ID_SECCION','',$__idseccion__);
			$this->m_tarchivos->Open();		
				
			if ( $this->m_tarchivos->nresultados>0 ) {
			
				while($_row_ = $this->m_tarchivos->Fetch($this->m_tarchivos->resultados) ) {
					//$_CArchivo_ = new CArchivo($_row_);
					//$this->m_CTiposArchivos->Mostrar($_CArchivo_);
					$this->m_CTiposArchivos->MostrarColapsado((new CArchivo($_row_)) );
				}
			}						
	}

	function MostrarColapsadosPorTipo($__idseccion__,$__tipo__,$__excluyeaid__=-1) {
					
			echo '<tr><td height="2"><img src="../../inc/images/spacer.gif" height="10"></td></tr>';	
					
			$this->m_tarchivos->LimpiarSQL();
			//$GLOBALS['_f_ID_TIPOARCHIVO'] = $__tipo__;			
			$this->m_tarchivos->FiltrarSQL('ID_TIPOARCHIVO','',$__tipo__);			
			//$GLOBALS['_f_ID_SECCION'] = $__idseccion__;			
			if ($__excluyeaid__>=1) {				
				$this->m_tarchivos->FiltrarSQL('ID_SECCION','/*SPECIAL*/archivos.ID<>'.$__excluyeaid__,$__idseccion__);
			} else $this->m_tarchivos->FiltrarSQL('ID_SECCION','',$__idseccion__);
			//echo $this->m_tarchivos->SQL;
			$this->m_tarchivos->Open();		
							
			if ( $this->m_tarchivos->nresultados>0 ) {
			
				while($_row_ = $this->m_tarchivos->Fetch($this->m_tarchivos->resultados) ) {
					//$_CArchivo_ = new CArchivo($_row_);
					//$this->m_CTiposArchivos->Mostrar($_CArchivo_);
					$this->m_CTiposArchivos->MostrarColapsado((new CArchivo($_row_)) );
				}
			}						
	}


	function MostrarArchivoColapsado($__idarchivo__) {
					
			echo '<tr><td height="2"><img src="../../inc/images/spacer.gif" height="10"></td></tr>';	
					
			$this->m_tarchivos->LimpiarSQL();
			//$GLOBALS['_f_ID'] = $__idarchivo__;			
			$this->m_tarchivos->FiltrarSQL('ID','',$__idarchivo__);
			$this->m_tarchivos->Open();		
				
			if ( $this->m_tarchivos->nresultados>0 ) {
				while($_row_ = $this->m_tarchivos->Fetch($this->m_tarchivos->resultados) ) {
					//$_CArchivo_ = new CArchivo($_row_);
					//$this->m_CTiposArchivos->Mostrar($_CArchivo_);
					$this->m_CTiposArchivos->MostrarColapsado((new CArchivo($_row_)) );
				}
				
			}						
			
	}	
	
	function MostrarArchivoResumen($__idarchivo__) {
					
			echo '<tr><td height="2"><img src="../../inc/images/spacer.gif" height="10"></td></tr>';	
					
			$this->m_tarchivos->LimpiarSQL();
			//$GLOBALS['_f_ID'] = $__idarchivo__;			
			$this->m_tarchivos->FiltrarSQL('ID','',$__idarchivo__);
			$this->m_tarchivos->Open();		
				
			if ( $this->m_tarchivos->nresultados>0 ) {
				while($_row_ = $this->m_tarchivos->Fetch($this->m_tarchivos->resultados) ) {
					//$_CArchivo_ = new CArchivo($_row_);
					//$this->m_CTiposArchivos->Mostrar($_CArchivo_);
					$this->m_CTiposArchivos->MostrarResumen((new CArchivo($_row_)) );
				}
				
			}						
			
	}	
	
	
	function MostrarArchivoCompleto($__idarchivo__) {
					
			echo '<tr><td height="2"><img src="../../inc/images/spacer.gif" height="10"></td></tr>';	
					
			$this->m_tarchivos->LimpiarSQL();
			if ($__idarchivo__) {
				//$GLOBALS['_f_ID'] = $__idarchivo__;
				$this->m_tarchivos->FiltrarSQL('ID','',$__idarchivo__);
				$this->m_tarchivos->Open();		
					
				if ( $this->m_tarchivos->nresultados>0 ) {
					while($_row_ = $this->m_tarchivos->Fetch($this->m_tarchivos->resultados) ) {
						//$_CArchivo_ = new CArchivo($_row_);
						//$this->m_CTiposArchivos->Mostrar($_CArchivo_);
						$this->m_CTiposArchivos->MostrarCompleto((new CArchivo($_row_)) );
					}
					
				}						
			}	
	}	
	
	
	
}
?>