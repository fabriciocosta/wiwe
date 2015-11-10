<?php
		if ($GLOBALS['_ADMIN_TYPE']=='TREE') {
			$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_SECCION','','escondido');
			
			
			if (!isset($_f_ID_TIPOSECCION)) $_f_ID_TIPOSECCION = 0;
			
			
			
			switch($_f_ID_TIPOSECCION) {

				//**ADDCASE**//
	
		//ADDED BY CONFIG MANAGER
						case SECCION_PERSONAS:
							$_f_ID_TIPOCONTENIDO = FICHA_PERSONAS;
							$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
							break;
					
					
	
		//ADDED BY CONFIG MANAGER
						case SECCION_MEJORAS:
							$_f_ID_TIPOCONTENIDO = FICHA_MEJORAS;
							$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
							break;
					
					
	
		//ADDED BY CONFIG MANAGER
						case SECCION_GALERIA:
						case SECCION_GALERIA_CATEGORIA:
							$_f_ID_TIPOCONTENIDO = FICHA_GALERIA;
							$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
							break;
					
					
	
		//ADDED BY CONFIG MANAGER
						case SECCION_NOTAS:
							$_f_ID_TIPOCONTENIDO = FICHA_NOTAS;
							$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
							break;
					
					
	
		//ADDED BY CONFIG MANAGER
						case SECCION_EVENTOS:
							$_f_ID_TIPOCONTENIDO = FICHA_EVENTOS;
							$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
							break;
					
					
	
		//ADDED BY CONFIG MANAGER
						case SECCION_CIUDADES:
							$_f_ID_TIPOCONTENIDO = FICHA_CIUDADES;
							$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
							break;
					
					
	
		//ADDED BY CONFIG MANAGER
						case SECCION_PAISES:
							$_f_ID_TIPOCONTENIDO = FICHA_PAISES;
							$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
							break;
					
					
	
		//ADDED BY CONFIG MANAGER
						case SECCION_FESTIVALES:
							$_f_ID_TIPOCONTENIDO = FICHA_FESTIVALES;
							$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
							break;
					
					
	
		//ADDED BY CONFIG MANAGER
						case SECCION_INSTITUCIONES:
							$_f_ID_TIPOCONTENIDO = FICHA_INSTITUCIONES;
							$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
							break;
					
					
	
		//ADDED BY CONFIG MANAGER
						case SECCION_ESPACIOS:
							$_f_ID_TIPOCONTENIDO = FICHA_ESPACIOS;
							$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
							break;
					
					
	
		//ADDED BY CONFIG MANAGER
						case SECCION_ROLESOBRA:
							$_f_ID_TIPOCONTENIDO = FICHA_ROLESOBRA;
							$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
							break;
					
					
	
		//ADDED BY CONFIG MANAGER
						case SECCION_COMENTARIOS:
							$_f_ID_TIPOCONTENIDO = FICHA_COMENTARIOS;
							$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
							break;
					
					
	
		//ADDED BY CONFIG MANAGER
						case SECCION_OBRAS:
							$_f_ID_TIPOCONTENIDO = FICHA_OBRAS;
							$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
							break;
					
					
				
				case SECCION_NOVEDADES:
						$_f_ID_TIPOCONTENIDO = FICHA_NOVEDAD;
						break;				
						
				case SECCION_NOTICIAS:
						$_f_ID_TIPOCONTENIDO = FICHA_NOTICIA;
						break;				

				case SECCION_ENUNCIADOS:
						$_f_ID_TIPOCONTENIDO = FICHA_ENUNCIADO;
						break;				
						

				case SECCION_DESCARGAS:
						$_f_ID_TIPOCONTENIDO = FICHA_DESCARGA;
						break;				
						

				case SECCION_DOCUMENTACION:
				case SECCION_DOCUMENTACION_CATEGORIA:
						$_f_ID_TIPOCONTENIDO = FICHA_DOCUMENTACION;
						break;				

				case SECCION_VIDEOS:
						$_f_ID_TIPOCONTENIDO = FICHA_VIDEO;
						break;				
						
				case SECCION_ENLACES:
						$_f_ID_TIPOCONTENIDO = FICHA_ENLACE;
						break;				
						
				case SECCION_TUTORIALES:
						$_f_ID_TIPOCONTENIDO = FICHA_TUTORIAL;
						break;				
			
				case SECCION_TUTORIALES:
						$_f_ID_TIPOCONTENIDO = FICHA_TUTORIAL;
						break;				
					
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