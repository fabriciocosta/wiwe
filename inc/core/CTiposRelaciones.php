<?php

 
class CTiposRelaciones extends CErrorHandler {

	var $m_ttiposrelaciones;//tabla tiposcontenidos
	var $m_CTiposDetalles;
	
	var $m_templatesarbolnodo;
	var $m_templatesedicion;
	var $m_templatesconsulta;
	
	var $m_Str2IntArray,$m_Int2StrArray;
	
	function CTiposRelaciones(&$__ttiposrelaciones__, &$__m_CTiposDetalles__) {
				
		$this->Set( $__ttiposrelaciones__, $__m_CTiposDetalles__);
				
	}
	
	function Set(&$__ttiposrelaciones__, &$__m_CTiposDetalles__) {

		$this->m_ttiposrelaciones = &$__ttiposrelaciones__;
		$this->m_CTiposDetalles = $__m_CTiposDetalles__;

    	$this->m_ttiposrelaciones->LimpiarSQL();    
    	$this->m_ttiposrelaciones->Open();		         
		if ( $this->m_ttiposrelaciones->nresultados>0 ) {
			while($_row_ = $this->m_ttiposrelaciones->Fetch($this->m_ttiposrelaciones->resultados) ) {
				$tiporelacion = new CTipoRelacion( $_row_ );					
				$this->m_Str2IntArray[$tiporelacion->m_tipo] = $tiporelacion->m_id;											
				$this->m_Int2StrArray[$tiporelacion->m_id] = $tiporelacion->m_tipo;
			}
		}
		
		$this->m_ttiposrelaciones->Close();
		
		parent::CErrorHandler();		
	}
	
	//---------------------------------------
	// TEMPLATES
	//---------------------------------------
	
	function SetTemplateArbolNodo($__id_tiporelacion__,$__item_template__,$__nodo_colapsado__, $__nodo_expandido__, $__nodo_m1_ultimo__, $__nodo_m1_intermedio__, $__nodo_m2__) {
		
		$this->m_templatesarbolnodo[$__id_tiporelacion__] = array("id_tiporelacion"=>$__id_tiporelacion__,
		"item_template"=>$__item_template__,
		"nodo_colapsado"=>$__nodo_colapsado__,
		"nodo_expandido"=>$__nodo_expandido__,
		"nodo_m1_ultimo"=>$__nodo_m1_ultimo__,
		"nodo_m1_intermedio"=>$__nodo_m1_intermedio__,
		"nodo_m2"=>$__nodo_m2__);
				
	}
	
	function SetTemplateEdicion($__tiporelacion__,$__template__="") {
		global $__lang__;
		if ($__lang__!="") $l = $__lang__."."; else $l = "";
		if ($__template__=="") {
			$fjose = "../../inc/templates/".$this->m_Int2StrArray[$__tiporelacion__].".edicion.".$l."html";
			if (file_exists($fjose)) {
				$__template__ = implode('', file($fjose));
			} else {
				$fjose = "../../inc/templates/RELACION.edicion.".$l."html";
				$__template__ = implode('', file($fjose));
			}		
		}
		$this->m_templatesedicion[$__tiposeccion__] = $__template__;	
	}	

	function SetTemplateConsulta($__tiporelacion__,$__template__="") {
		global $__lang__;
		if ($__lang__!="") $l = $__lang__."."; else $l = "";
		if ($__template__=="") {
			$fjose = "../../inc/templates/".$this->m_Int2StrArray[$__tiporelacion__].".consulta.".$l."html";
			if (file_exists($fjose)) {
				$__template__ = implode('', file($fjose));
			} else {
				$fjose = "../../inc/templates/RELACION.consulta.".$l."html";
				$__template__ = implode('', file($fjose));
			}
		}
		$this->m_templatesconsulta[$__tiporelacion__] = $__template__;	
	}	
	
	
} 
?>