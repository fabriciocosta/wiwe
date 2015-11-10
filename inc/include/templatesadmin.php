<?php
	
	global $_TIPOS_;
	if($idtipocontenido==0)
		foreach($_TIPOS_['tiposcontenidos'] as $tipo=>$id) {
			if ($id>2) $this->TiposContenidos->SetTemplateConsulta($id);
			if ($id>2) $this->TiposContenidos->SetTemplateEdicion($id);
		}
	else $this->TiposContenidos->SetTemplateEdicion($idtipocontenido);
	
//	$this->TiposDetalles->SetParameters( PRODUIT_FLOOR, 10, 0 );

	
//	$this->TiposDetalles->SetParameters( PRODUIT_TARIF1NIGHT, 10, 0 );

	
	$this->TiposContenidos->m_templatesedicion[FICHA_NOTICIA]["htmlcopete"] = "html";
	$this->TiposContenidos->m_templatesedicion[FICHA_NOTICIA]["htmlcuerpo"] = "html";
	
	$this->TiposContenidos->m_templatesedicion[FICHA_ENUNCIADO]["htmlcopete"] = "html";
	$this->TiposContenidos->m_templatesedicion[FICHA_ENUNCIADO]["htmlcuerpo"] = "html";
	
	$this->TiposContenidos->m_templatesedicion[FICHA_VIDEO]["htmlcopete"] = "html";
	$this->TiposContenidos->m_templatesedicion[FICHA_VIDEO]["htmlcuerpo"] = "html";
	
	$this->TiposContenidos->m_templatesedicion[FICHA_DESCARGA]["htmlcopete"] = "html";
	$this->TiposContenidos->m_templatesedicion[FICHA_DESCARGA]["htmlcuerpo"] = "html";

	$this->TiposContenidos->m_templatesedicion[FICHA_OBRAS]["htmlcopete"] = "html";
	$this->TiposContenidos->m_templatesedicion[FICHA_OBRAS]["htmlcuerpo"] = "html";	

	$this->TiposContenidos->m_templatesedicion[FICHA_EVENTOS]["htmlcopete"] = "html";
	$this->TiposContenidos->m_templatesedicion[FICHA_EVENTOS]["htmlcuerpo"] = "html";	

	$this->TiposContenidos->m_templatesedicion[FICHA_DOCUMENTACION]["htmlcopete"] = "html";
	$this->TiposContenidos->m_templatesedicion[FICHA_DOCUMENTACION]["htmlcuerpo"] = "html";
?>