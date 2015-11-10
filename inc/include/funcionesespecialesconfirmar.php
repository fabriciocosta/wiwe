<?php

	global $_accion_;
	global $_seleccion_;
	

/*
	
	global $_fcomboe_vincular_categoria;
	 
	if ($_accion_=="vincular_categoria") {
		
			ShowMessage("Procesando ".$_accion_." cat:".$_fcomboe_vincular_categoria);
			
			$Categoria = $this->Secciones->GetSeccion($_fcomboe_vincular_categoria);
		
			if ( is_object($Categoria) ) {
				
				$SELS = explode(",",$_seleccion_);
				
				foreach($SELS as $id) {
		
					$Marca = $this->Contenidos->GetContenidoCompleto($id);
		
					ShowMessage("Asignando categoria: ".$Categoria->Nombre()." a marca:".$Marca->Titulo() );
					
					if ( $this->Relaciones->RelacionExists( MARCA_RUBROS, $Marca->m_id, 0, 0, $Categoria->m_id ) ) {
						ShowError("Ya asignado");
					} else {
						ShowMessage("Asignando");
						if ($this->Relaciones->ConnectC2S( MARCA_RUBROS, $Marca, $Categoria )) {
							ShowMessage("Asignado ok");
						}
					}
				
					
				} //foreach
				
			} //is categoria
			else {
				ShowError("Debe seleccionar una categoría válida");
			}
			
	} else if ($_accion_=="desvincular_categoria") {
		
			ShowMessage("Procesando ".$_accion_." cat:".$_fcomboe_vincular_categoria);
			
			$Categoria = $this->Secciones->GetSeccion($_fcomboe_vincular_categoria);		
			if ( is_object($Categoria) ) {
						
					$SELS = explode(",",$_seleccion_);
					
					foreach($SELS as $id) {
			
						$Marca = $this->Contenidos->GetContenidoCompleto($id);
			
						ShowMessage("Desvinculando categoria: ".$Categoria->Nombre()." de marca:".$Marca->Titulo() );
						
						if ( $this->Relaciones->RelacionExists( MARCA_RUBROS, $Marca->m_id, 0, 0, $Categoria->m_id ) ) {
							ShowMessage("Desvinculando");
							$Vinculo = $this->Relaciones->GetRelacionPorDatos( MARCA_RUBROS, $Marca->m_id, 0, 0, $Categoria->m_id );
							if (is_object($Vinculo)) {
								if ($this->Relaciones->EliminarRelacion($Vinculo)) {
									ShowMessage("Desvinculado ok");
								}
							}
							
						} else {
							ShowError("No se puede desvincular porque no está vinculada esta categoría con esta marca");
						}
						
					} //foreach
			} //is categoria
			else {
				ShowError("Debe seleccionar una categoría válida");
			}
		
	}
	*/
	
	global $_fcomboe_cambiar_seccion;
	
	
	if ($_accion_=="cambiar_seccion") {
		
			ShowMessage("Procesando ".$_accion_." cat:".$_fcomboe_cambiar_seccion);
			
			$Categoria = $this->Secciones->GetSeccion($_fcomboe_cambiar_seccion);		
			if ( is_object($Categoria) ) {
						
					$SELS = explode(",",$_seleccion_);
					
					foreach($SELS as $id) {
			
						$Galeria = $this->Contenidos->GetContenidoCompleto($id);
			
						if ($Galeria->m_id_tipocontenido==FICHA_GALERIA) {
						
							ShowMessage("Moviendo a : ".$Categoria->Nombre()." :".$Galeria->Titulo() );
							
							$Galeria->m_id_seccion = $Categoria->m_id;
							
							if ($this->Contenidos->Actualizar($Galeria,false) ) {
								ShowMessage("OK");
							} else {
								ShowMessage("no se pudo acutalizar el contenido: ".$Galeria->Titulo());
							}
						} else {
							ShowError("Atención: el contenido ".$Galeria->Titulo()." no es una ficha de galeria");
						}							
						
						
					} //foreach
			} //is categoria
			else {
				ShowError("Debe seleccionar una categoría válida");
			}
		
	}	
	
	
?>