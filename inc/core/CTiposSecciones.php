<?php

/**
 * class CTiposSecciones
 *
 * @version 28/11/2003
 * @copyright 2003 
 **/

 
class CTiposSecciones extends CErrorHandler {

	var $m_ttipossecciones;//tabla tiposcontenidos
	
	var $m_templatesarbolnodo;
	var $m_templatesedicion;
	var $m_templatesconsulta;
	
	var $m_Str2IntArray,$m_Int2StrArray;
	
	function CTiposSecciones(&$__ttipossecciones__) {				
		$this->Set($__ttipossecciones__);
	}
	
	function Set(&$__ttipossecciones__) {

		$this->m_ttipossecciones = &$__ttipossecciones__;

    	$this->m_ttipossecciones->LimpiarSQL();    
    	$this->m_ttipossecciones->Open();		         
		if ( $this->m_ttipossecciones->nresultados>0 ) {
			while($_row_ = $this->m_ttipossecciones->Fetch() ) {
				$tiposeccion = new CTipoSeccion( $_row_ );					
				$this->m_Str2IntArray[$tiposeccion->m_tipo] = $tiposeccion->m_id;											
				$this->m_Int2StrArray[$tiposeccion->m_id] = $tiposeccion->m_tipo;
			}
		}		
		$this->m_ttipossecciones->Close();
		parent::CErrorHandler();
	}
	
	function TipoSeccionExists( $__tipo__) {
		$this->m_ttipossecciones->LimpiarSQL();
		$this->m_ttipossecciones->FiltrarSQL( 'TIPO', '', trim($__tipo__) );
		$this->m_ttipossecciones->Open();
		$this->m_ttipossecciones->Close();
		if ($this->m_ttipossecciones->nresultados>0) {
			return true;
		} else return false;
	}
	
	
	function CrearTipoSeccion( &$__CTipoSeccion__) {
		if (!is_object($__CTipoSeccion__)) {
			ShowError($__CTipoSeccion__->m_tipo." parameter is not an CTipoSeccion object!");
		}
		if ( !$this->TipoSeccionExists($__CTipoSeccion__->m_tipo) && $__CTipoSeccion__->m_tipo!="" ) {
			
			$_exito_ = $this->m_ttipossecciones->InsertarRegistro( $__CTipoSeccion__->FullArray() );
					
			if ($_exito_) {
				$__CTipoSeccion__->m_id = $this->m_ttipossecciones->lastinsertid;
				return true;
			} 
		} else {
			if ($__CTipoSeccion__->m_tipo!="") ShowError("Tipo de seccion: ".$__CTipoSeccion__->m_tipo." already exists!");
		}
		return false;
	}
	
	function GetId( $__tipo__ ) {
		if (isset($this->m_Str2IntArray[$__tipo__])) {
			return $this->m_Str2IntArray[$__tipo__];
		}				
		return -1;
	}
	
	function GetTipo( $__id__ ) {
		if (isset($this->m_Int2StrArray[$__id__])) {
			return $this->m_Int2StrArray[$__id__];
		}
		return "undefined";				
	}	
	
	/**
	 * Devuelve el objeto cuyo identificador de tipo de sección es $__id_tiposeccion__ 
	 *
	 * @param Integer $__id_tiposeccion__  identificador de tipo de sección
	 * @return CTipoSeccion o null si no lo encontró
	 */
	function GetTipoSeccion( $__id_tiposeccion__, $__tipo__="") {
			
		$this->m_ttipossecciones->LimpiarSQL();			
	    if ($__id_tiposeccion__>0) $this->m_ttipossecciones->FiltrarSQL('ID','',$__id_tiposeccion__);
	    if ($__tipo__!='') $this->m_ttipossecciones->FiltrarSQL('TIPO','',trim($__tipo__));
	    $this->m_ttipossecciones->Open();		
		
		if ( $this->m_ttipossecciones->nresultados>0 ) {		
			$_row_ = $this->m_ttipossecciones->Fetch();
			$TipoSeccion = new CTipoSeccion($_row_);
			return $TipoSeccion;			
		}	
		
		return null;
		
	}	
	
	//---------------------------------------
	// TEMPLATES
	//---------------------------------------
	
	function SetTemplateArbolNodo($__id_tiposeccion__,$__item_template__,$__nodo_colapsado__, $__nodo_expandido__, $__nodo_m1_ultimo__, $__nodo_m1_intermedio__, $__nodo_m2__) {
		
		$this->m_templatesarbolnodo[$__id_tiposeccion__] = array("id_tiposeccion"=>$__id_tiposeccion__,
		"item_template"=>$__item_template__,
		"nodo_colapsado"=>$__nodo_colapsado__,
		"nodo_expandido"=>$__nodo_expandido__,
		"nodo_m1_ultimo"=>$__nodo_m1_ultimo__,
		"nodo_m1_intermedio"=>$__nodo_m1_intermedio__,
		"nodo_m2"=>$__nodo_m2__);
				
	}
	
	function SetTemplateEdicion($__tiposeccion__,$__template__="") {
		global $__lang__;
		if ($__lang__!="") $l = $__lang__."."; else $l = "";
		if ($__template__=="") {
			$fjose = "../../inc/templates/".$this->m_Int2StrArray[$__tiposeccion__].".edicion.".$l."html";
			if (file_exists($fjose)) {
				$__template__ = implode('', file($fjose));
			} else {
				$fjose = "../../inc/templates/SECCION.edicion.".$l."html";
				$__template__ = implode('', file($fjose));
			}		
		}
		$this->m_templatesedicion[$__tiposeccion__] = $__template__;	
	}	

	function SetTemplateConsulta($__tiposeccion__,$__template__="") {
		global $__lang__;
		if ($__lang__!="") $l = $__lang__."."; else $l = "";
		if ($__template__=="") {
			$fjose = "../../inc/templates/".$this->m_Int2StrArray[$__tiposeccion__].".consulta.".$l."html";
			if (file_exists($fjose)) {
				$__template__ = implode('', file($fjose));
			} else {
				$fjose = "../../inc/templates/SECCION.consulta.".$l."html";
				$__template__ = implode('', file($fjose));
			}
		}
		$this->m_templatesconsulta[$__tiposeccion__] = $__template__;	
	}	
	
	
	function MostrarArbolNodo($__CSeccion__,$__template__="", $mostrarrama="" ) {
		$branchs = ""; 
		
		//if ($mostrarrama!="") ShowMessage("mostrarrama:".$mostrarrama);
		//if ( is_object($__CSeccion__) ) ShowMessage("Seccion id_tiposeccion:".$__CSeccion__->m_id_tiposeccion );
		
		switch($__CSeccion__->m_id_tiposeccion) {
			default:
				if ($__template__=='' 
				&& isset($this->m_templatesarbolnodo[$__CSeccion__->m_id_tiposeccion])) {
					
					//ShowMessage("MostrarArbolNodo w/m_templatesarbolnodo: ".$__CSeccion__->m_nombre);
					//ShowMessage("mostrarrama:".$mostrarrama);
					
					$tarbnodo = $this->m_templatesarbolnodo[$__CSeccion__->m_id_tiposeccion];
					
					$__template__ = $tarbnodo['item_template'];		
								
					for($i=$__CSeccion__->m_profundidad,$j=0;$i>=0;$j++,$i--) {
						
						if ($j==0) {//nodo
							
							$branchs = $tarbnodo['nodo_colapsado'].$branchs;
							
						} else if ($j==1) {//codo
							
							if ($__CSeccion__->m_nhijos == ($__CSeccion__->m_nitem+1)) {
								$branchs = $tarbnodo['nodo_m1_ultimo'].$branchs;							
							} else $branchs = $tarbnodo['nodo_m1_intermedio'].$branchs;
							
						} else if ($j>=2) {//rama
							
							$branchs = $tarbnodo['nodo_m2'].$branchs;
														
						}
					}
					
					$__template__ = str_replace("{RAMA}",$branchs,$__template__);
					$__template__ = str_replace("*ID*",$__CSeccion__->m_id,$__template__);
					$__template__ = str_replace("*IDSECCION*",$__CSeccion__->m_id_seccion,$__template__);
					$__template__ = str_replace("*IDTIPOSECCION*",$__CSeccion__->m_id_tiposeccion,$__template__);
					$__template__ = str_replace("*NOMBRE*",$__CSeccion__->Nombre(),$__template__);
					$__template__ = str_replace("*DESCRIPCION*",$__CSeccion__->Descripcion(),$__template__);
					$__template__ = str_replace("{MOSTRARRAMA}",$mostrarrama,$__template__);
					echo $__template__;
					return;
				} else {
					//ShowMessage("MostrarArbolNodo: ".$__CSeccion__->m_nombre);
					for($i=0;$i<=$__CSeccion__->m_profundidad;$i++,$branchs.="-");
					echo $branchs.$__CSeccion__->Nombre()."<br>";
				}
				break;
		}		
		
		
		
	}
	
	function GetArbolNodo($__CSeccion__,$__template__="", $mostrarrama='' ) {
		$branchs = ""; 
		switch($__CSeccion__->m_id_tiposeccion) {
			default:
				if ($__template__=='' 
				&& isset($this->m_templatesarbolnodo[$__CSeccion__->m_id_tiposeccion])) {					
					
					$tarbnodo = $this->m_templatesarbolnodo[$__CSeccion__->m_id_tiposeccion];
					
					$__template__ = $tarbnodo['item_template'];					
					
					for($i=$__CSeccion__->m_profundidad,$j=0;$i>=0;$j++,$i--) {
						
						if ($j==0) {//nodo
							
							$branchs = $tarbnodo['nodo_colapsado'].$branchs;
							
						} else if ($j==1) {//codo
							
							if ($__CSeccion__->m_nhijos == ($__CSeccion__->m_nitem+1)) {
								$branchs = $tarbnodo['nodo_m1_ultimo'].$branchs;							
							} else $branchs = $tarbnodo['nodo_m1_intermedio'].$branchs;
							
						} else if ($j>=2) {//rama
							$branchs = $tarbnodo['nodo_m2'].$branchs;							
						}
					}
					$__template__ = str_replace("{RAMA}",$branchs,$__template__);
					$__template__ = str_replace("*ID*",$__CSeccion__->m_id,$__template__);
					$__template__ = str_replace("*IDSECCION*",$__CSeccion__->m_id_seccion,$__template__);
					$__template__ = str_replace("*IDTIPOSECCION*",$__CSeccion__->m_id_tiposeccion,$__template__);
					$__template__ = str_replace("*NOMBRE*",$__CSeccion__->Nombre(),$__template__);
					$__template__ = str_replace("*DESCRIPCION*",$__CSeccion__->Descripcion(),$__template__);
					$__template__ = str_replace("{MOSTRARRAMA}",$mostrarrama,$__template__);
					return $__template__;
				} else {
					for($i=0;$i<=$__CSeccion__->m_profundidad;$i++,$branchs.="-");
					return $branchs.$__CSeccion__->Nombre()."<br>";
				}
				break;
		}		
	}
	
	
	function MostrarComboArbolNodo($__CSeccion__,$__template__="") {		
		if ($__template__!='') {
			$__template__ = str_replace("*ID*",$__CSeccion__->m_id,$__template__);
			$__template__ = str_replace("*IDSECCION*",$__CSeccion__->m_id_seccion,$__template__);
			$__template__ = str_replace("*IDTIPOSECCION*",$__CSeccion__->m_id_tiposeccion,$__template__);
			$__template__ = str_replace("*NOMBRE*",$__CSeccion__->Nombre(),$__template__);
			$__template__ = str_replace("*DESCRIPCION*",$__CSeccion__->Descripcion(),$__template__);					
			echo $__template__;
		} else {
			for($i=0;$i<=$__CSeccion__->m_profundidad;$i++,$branchs.="-");
			echo $branchs.$__CSeccion__->Nombre()."<br>";
		}						
	}
	
	function GetComboArbolNodo($__CSeccion__,$__template__="") {
		if ($__template__!='') {
			$__template__ = str_replace("*ID*",$__CSeccion__->m_id,$__template__);
			$__template__ = str_replace("*IDSECCION*",$__CSeccion__->m_id_seccion,$__template__);
			$__template__ = str_replace("*IDTIPOSECCION*",$__CSeccion__->m_id_tiposeccion,$__template__);
			$__template__ = str_replace("*NOMBRE*",$__CSeccion__->Nombre(),$__template__);
			$__template__ = str_replace("*DESCRIPCION*",$__CSeccion__->Descripcion(),$__template__);					
			return $__template__;
		} else {
			for($i=0;$i<=$__CSeccion__->m_profundidad;$i++,$branchs.="-");
			return $branchs.$__CSeccion__->Nombre()."<br>";
		}		
	}
	

	function MostrarColapsado($__CSeccion__, $__template__="" ) {
		
		global $CFun;
		
		if ($__template__!="") {
			$__template__ = str_replace("*ID*",$__CSeccion__->m_id,$__template__);
			$__template__ = str_replace("*IDSECCION*",$__CSeccion__->m_id_seccion,$__template__);
			$__template__ = str_replace("*IDTIPOSECCION*",$__CSeccion__->m_id_tiposeccion,$__template__);
			//if ($__lang__!='' && $__CSeccion__->Nombre() ) $__template__ = str_replace("*NOMBRE*",$this->m_ttipossecciones->TextoML( $__CSeccion__->m_ml_nombre,$__lang__),$__template__);
			//else
			$__template__ = str_replace("*NOMBRE*",$__CSeccion__->Nombre(),$__template__); 	
			$__template__ = str_replace("*NOMBRE:URL*",$__CSeccion__->NombreURL(),$__template__);	 				
			$__template__ = str_replace("*DESCRIPCION*",$__CSeccion__->Descripcion(),$__template__);				

			if (is_object($CFun)) {
				$CFun->Process($__template__);
			}			
			
			return $__template__;
			
		} else {
			return $__CSeccion__->Nombre();
		}
		
	}


	function MostrarResumen($__CSeccion__,  $__template__="" ) {
		global $__lang__;
		if ($__template__!="") {
			$__template__ = str_replace("*ID*",$__CSeccion__->m_id,$__template__);
			$__template__ = str_replace("*IDSECCION*",$__CSeccion__->m_id_seccion,$__template__);
			$__template__ = str_replace("*IDTIPOSECCION*",$__CSeccion__->m_id_tiposeccion,$__template__);
			
			 
			$__template__ = str_replace("*NOMBRE*",$__CSeccion__->Nombre(),$__template__); 	
			$__template__ = str_replace("*NOMBRE:URL*",$__CSeccion__->NombreURL(),$__template__);			
			$__template__ = str_replace("*DESCRIPCION*",$__CSeccion__->Descripcion(),$__template__);				
				
								
			return $__template__;
		} else {
			return $__CSeccion__->Nombre();
		}
		
	}
	
} 
?>