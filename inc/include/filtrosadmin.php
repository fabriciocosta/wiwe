<?php
		if ($GLOBALS['_ADMIN_TYPE']=='TREE') {
			$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_SECCION','','escondido');
			
			if (!isset($_f_ID_TIPOSECCION)) $_f_ID_TIPOSECCION = 0;
			
			switch($_f_ID_TIPOSECCION) {
				
				case SECCION_SERVICIOS:
						$_f_ID_TIPOCONTENIDO = FICHA_SERVICIOS;
						$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
						break;

				//**ADDCASE**//
	
		//ADDED BY CON
						
				default:
					$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','/*SPECIAL*/ (tiposcontenidos.ID>2)','');
					break;
			}
			
			if (isset($_f_ID_TIPOCONTENIDO) && is_numeric($_f_ID_TIPOCONTENIDO)) {
				$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
			}			
							
		} else if ($GLOBALS['_ADMIN_TYPE']=='LAPEL SECTION') {
		} else if ($GLOBALS['_ADMIN_TYPE']=='LAPEL CONTENTTYPE') {
		}
?>