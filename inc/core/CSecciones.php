<?php

/**
 * class CSecciones
 *
 * @version 28/11/2003
 * @copyright 2003 
 **/

 
class CSecciones extends CErrorHandler {

	var $m_tsecciones;//tabla secciones
	var $m_CTiposSecciones;//miembro de la clase CTipossecciones
	
		
	//buffer
	var $rama; 
	var $m_templatesarbolrama;
	var $m_CSeccion;
	var $m_ArrayCSecciones;
	
	function CSecciones(&$__tsecciones__,&$__m_CTiposSecciones__) {
		$this->Set( $__tsecciones__, $__m_CTiposSecciones__ );		
	}
	
	function Set(&$__tsecciones__,&$__m_CTiposSecciones__) {
		$this->m_CTiposSecciones = &$__m_CTiposSecciones__;
		$this->m_tsecciones = &$__tsecciones__;
		parent::CErrorHandler();
	}

	function SeccionExists( $__nombre__) {
		$this->m_tsecciones->LimpiarSQL();
		$this->m_tsecciones->FiltrarSQL( 'NOMBRE', '', trim($__nombre__) );
		$this->m_tsecciones->Open();
		$this->m_tsecciones->Close();
		if ($this->m_tsecciones->nresultados>0) {
			return true;
		} else return false;
	}
	
	function CrearSeccion( &$__CSeccion__) {
		if (!is_object($__CSeccion__)) {
			ShowError($__CSeccion__." parameter is not an CTipoSeccion object!");
		}		
		if ( !$this->SeccionExists($__CSeccion__->m_nombre) && $__CSeccion__->m_nombre!="" ) {
			
			$_exito_ = $this->m_tsecciones->InsertarRegistro( $__CSeccion__->FullArray() );
					
			if ($_exito_) {
				$__CSeccion__->m_id = $this->m_tsecciones->lastinsertid;
				return true;
			} else {
				ShowError( $this->m_tsecciones->exito );
			}
		} else {
			if ($__CSeccion__->m_nombre!="") ShowError("Seccion: ".$__CSeccion__->m_nombre." already exists!");
		}
		return false;
	}


	
	//------------------------
	// Obtener informacion de secciones
	//------------------------
	
	function Insertar( &$Seccion ) {
		return $this->m_tsecciones->InsertarRegistro( $Seccion->FullArray() );
	}
	
	function NuevaSeccion( $__id_padre__, $__id_tiposeccion__, $__nombre_seccion__, &$__CSeccion__ ) {
		
		$__nombre_seccion__ = trim($__nombre_seccion__);
		
		$SeccionPadre = $this->GetSeccion($__id_padre__);
		
		$CNuevaSeccion = new CSeccion();
		$CNuevaSeccion->m_id_contenido = 1;
		$CNuevaSeccion->m_id_seccion = $__id_padre__;
		$CNuevaSeccion->m_id_tiposeccion = $__id_tiposeccion__;
		$CNuevaSeccion->m_nombre = $__nombre_seccion__;
		$CNuevaSeccion->m_ml_nombre = $__nombre_seccion__;
		$CNuevaSeccion->m_descripcion = $__nombre_seccion__;
		$CNuevaSeccion->m_ml_descripcion = $__nombre_seccion__;
		$CNuevaSeccion->m_palabrasclave = "";
		$CNuevaSeccion->m_ml_palabrasclave = "";		
		$CNuevaSeccion->m_carpeta = strtolower($__nombre_seccion__);
		$CNuevaSeccion->m_categoria = "N";
		$CNuevaSeccion->m_orden = 1;
		$CNuevaSeccion->m_profundidad = $SeccionPadre->m_profundidad + 1;
		
		$CNuevaSeccion->m_id_usuario_creador = $__CSeccion__->m_id_usuario_creador;
		$CNuevaSeccion->m_id_usuario_modificador = $__CSeccion__->m_id_usuario_modificador;
		
		if ( $this->m_tsecciones->InsertarRegistro( array(
					'ID_CONTENIDO'=>$CNuevaSeccion->m_id_contenido,
					'ID_SECCION'=>$CNuevaSeccion->m_id_seccion,
					'ID_TIPOSECCION'=>$CNuevaSeccion->m_id_tiposeccion,
					'NOMBRE'=>$CNuevaSeccion->m_nombre,
					'ML_NOMBRE'=>$CNuevaSeccion->m_ml_nombre,
					'DESCRIPCION'=>$CNuevaSeccion->m_descripcion,
					'ML_DESCRIPCION'=>$CNuevaSeccion->m_descripcion,
					'PALABRASCLAVE'=>$CNuevaSeccion->m_palabrasclave,
					'ML_PALABRASCLAVE'=>$CNuevaSeccion->m_ml_palabrasclave,		
					'CARPETA'=>$CNuevaSeccion->m_carpeta,
					'CATEGORIA'=>$CNuevaSeccion->m_categoria,
					'PROFUNDIDAD'=>$CNuevaSeccion->m_profundidad,
					'ID_USUARIO_CREADOR'=>$CNuevaSeccion->m_id_usuario_creador,
					'ID_USUARIO_MODIFICADOR'=>$CNuevaSeccion->m_id_usuario_modificador,
					'ORDEN'=>$CNuevaSeccion->m_orden ) ) ) {
				$CNuevaSeccion->m_id = $this->m_tsecciones->lastinsertid;
				$__CSeccion__ = $CNuevaSeccion;
				$this->OrdenarArbol($__id_padre__);
				return true;
			} else {
				$CError = new CError("RECORD_CREATION_ERROR","inserting new section >> CSecciones::NewSeccion");
				$this->m_CErrores->PushError( $CError );
				return false;
			}		
	}	
	
	function SeccionUtilizada( $__id_padre__, $__id_tiposeccion__, $__nombre_seccion__ ) {

		$this->m_tsecciones->LimpiarSQL();
		$this->m_tsecciones->FiltrarSQL('ID_SECCION','',$__id_padre__);
		$this->m_tsecciones->FiltrarSQL('ID_TIPOSECCION',"/*SPECIAL*/ (secciones.NOMBRE LIKE '".trim($__nombre_seccion__)."')",$__id_tiposeccion__);		
		$this->m_tsecciones->Open();		
		
		if ( $this->m_tsecciones->nresultados>0 ) {
			return true;
		} else return false;
		
	}	
	
	function Actualizar( &$Seccion ) {
		return $this->m_tsecciones->ModificarRegistro( 
						$Seccion->m_id, $Seccion->FullArray() );
	}
	
	function GetSeccion($__idseccion__='',$__filtro__='') {
		
			$tsecciones2 = $this->m_tsecciones;
		
			$tsecciones2->LimpiarSQL();
			//$GLOBALS['_f_ID'] = $__idseccion__;			
			if ($__idseccion__!='')  {
				$tsecciones2->FiltrarSQL('ID',$__filtro__,$__idseccion__);
			} else {
				$tsecciones2->FiltrarSQL('ID',$__filtro__,'0','_superior_ID');
			} 
			$tsecciones2->Open();		
				
			if ( $tsecciones2->nresultados==1 ) {			
				$_row_ = $tsecciones2->Fetch($tsecciones2->resultados);
				$this->m_CSeccion = new CSeccion($_row_);
				return $this->m_CSeccion;									
			}	else {
				return null;
			}				
	}

	function GetSeccionByType($__tiposeccion__='',$__filtro__='') {
		$tsecciones2 = $this->m_tsecciones;
	
		$tsecciones2->LimpiarSQL();
		//$GLOBALS['_f_ID'] = $__idseccion__;			
		if ($__tiposeccion__!='')  {
			$tsecciones2->FiltrarSQL('ID','/*SPECIAL*/ ( secciones.ID_TIPOSECCION = '.trim($__tiposeccion__).') ','0','_superior_ID');
		} else {
			$tsecciones2->FiltrarSQL('ID',$__filtro__,'0','_superior_ID');
		} 
		$tsecciones2->Open();
				
			
		if ( $tsecciones2->nresultados>=1 ) {			
			$_row_ = $tsecciones2->Fetch($tsecciones2->resultados);
			$this->m_CSeccion = new CSeccion($_row_);
			return $this->m_CSeccion;									
		}	else {
			return null;
		}				
	}
	
	function GetSeccionByName($__seccion__='',$__filtro__='') {
		
			$tsecciones2 = $this->m_tsecciones;
		
			$tsecciones2->LimpiarSQL();
			//$GLOBALS['_f_ID'] = $__idseccion__;			
			if ($__seccion__!='')  {
				$tsecciones2->FiltrarSQL('ID','/*SPECIAL*/ ( secciones.NOMBRE = \''.trim($__seccion__).'\') ','0','_superior_ID');
			} else {
				$tsecciones2->FiltrarSQL('ID',$__filtro__,'0','_superior_ID');
			} 
			$tsecciones2->Open();
					
				
			if ( $tsecciones2->nresultados>=1 ) {			
				$_row_ = $tsecciones2->Fetch($tsecciones2->resultados);
				$this->m_CSeccion = new CSeccion($_row_);
				return $this->m_CSeccion;									
			}	else {
				return null;
			}				
	}	
	
	//devuelve la seccion padre de idseccion
	function GetSeccionPadre($__idseccion__) {
		
			$this->m_tsecciones->LimpiarSQL();
			//$GLOBALS['_f_ID'] = $__idseccion__;			
			$this->m_tsecciones->FiltrarSQL('ID','',$__idseccion__);
			$this->m_tsecciones->Open();		
				
			if ( $this->m_tsecciones->nresultados==1 ) {			
				$_row_ = $this->m_tsecciones->Fetch($this->m_tsecciones->resultados);
				$this->m_CSeccion = $this->GetSeccion($_row_['secciones.ID_SECCION']);
				return $this->m_CSeccion;									
			}	else {
				return null;
			}				
	}
	
	//devuelve las secciones hijas de idseccion
	function GetSeccionHijos($__idseccion__, $__orden__="") {
		
			$this->m_ArrayCSeccciones = array();
		
			$this->m_tsecciones->LimpiarSQL();					
			$this->m_tsecciones->FiltrarSQL('ID_SECCION','/*SPECIAL*/ secciones.PROFUNDIDAD>0',$__idseccion__);
			
			if ($__orden__=="") $this->m_tsecciones->OrdenSQL( 'ORDEN ASC' );
			else  $this->m_tsecciones->OrdenSQL( $__orden__ );
			
			$this->m_tsecciones->Open();		
				
			if ( $this->m_tsecciones->nresultados>0 ) {			
				while($_row_ = $this->m_tsecciones->Fetch()) {
					$this->m_CSeccion = new CSeccion($_row_);
					$this->m_ArrayCSeccciones[$_row_['secciones.ID']] = $this->m_CSeccion;	
				}
				return $this->m_ArrayCSeccciones;
			}	else {
				return null;
			}				
	}	
	
	function GetRoot() {
		$this->m_tsecciones->LimpiarSQL();
		$this->m_tsecciones->FiltrarSQL('ID_TIPOSECCION', '', ROOT );
		$this->m_tsecciones->Open();
		if ( $this->m_tsecciones->nresultados>0 ) {
			$_row_ = $this->m_tsecciones->Fetch($this->m_tsecciones->resultados);
			$this->m_CSeccion = new CSeccion($_row_);
			return $this->m_CSeccion;
		} else {
			return null;
		}						
	}
	
	
	function GetPathSeccion($__idseccion__,$__pathseccion__="",$__separador__="") {
		
		$sec = $this->GetSeccion($__idseccion__);
		
		if (is_object($sec)) {
			if ($__pathseccion__) $__pathseccion__ = $sec->m_nombre.$__separador__.$__pathseccion__;
			else $__pathseccion__ = $sec->m_nombre;
			
			if ($sec->m_profundidad>0) {
				return $this->GetPathSeccion($sec->m_id_seccion,$__pathseccion__,$__separador__);
			}
		}
		return $__pathseccion__;
	}
	
	
	//-----------------------
	// Impresion de secciones
	//-----------------------
	
	function AsignarGruposSecciones($__idseccionpadre__,$__idseccion__) {	
		global $_tgrupossecciones_;
				//>>tomar de la seccion padre de los grupossecciones los id de grupos que la contienen  (conjunto de ID's)
				//>>(la linea anterior ya implica al grupo_usuario que lo creo) y listo				
				$_tgrupossecciones_->LimpiarSQL();				
				$_tgrupossecciones_->SQL = 'SELECT DISTINCT grupossecciones.ID_GRUPO FROM grupossecciones WHERE grupossecciones.ID_SECCION='.$__idseccionpadre__;
				$_tgrupossecciones_->Open();
				$_gruposseccionesid_ = '';
				if ($_tgrupossecciones_->nresultados>0) {
					while ($row = $_tgrupossecciones_->Fetch($_tgrupossecciones_->resultados)) {
						$_gruposseccionesid_[$row['grupossecciones.ID_GRUPO']]='si';
					}
				}
				
				//>>generar registros nuevos en grupossecciones (por cada idgrupopadre) -> id_seccion = idnuevaseccion y id_grupo = idgrupopadre
				foreach($_gruposseccionesid_ as $_idgrupopadre_ => $_vale_) {
					if ($_vale_=='si') {
						//$_tgrupossecciones_->debug = 'si';
						$_tgrupossecciones_->LimpiarSQL();
						$_tgrupossecciones_->SQL = 'INSERT INTO grupossecciones (ID_GRUPO,ID_SECCION) ';
						$_tgrupossecciones_->SQL.= 'VALUES('.$_idgrupopadre_.','.$__idseccion__.')';
						$_tgrupossecciones_->EjecutaSQL();
					}
				}	
	}
	
	function DesasignarGruposSecciones($__idseccion__) {
		global $_tgrupossecciones_;
		$_tgrupossecciones_->LimpiarSQL();
		$_tgrupossecciones_->SQL = 'DELETE FROM grupossecciones WHERE ID_SECCION='.$__idseccion__;
		return $_tgrupossecciones_->EjecutaSQL();		
	}
	
	//-----------------------
	// Impresion de secciones
	//-----------------------
	
	function SetTemplateArbolRama($__id_tiposeccion__,$__header__,$__footer__) {
		
		$this->m_templatesarbolrama[$__id_tiposeccion__] = array('id_tiposeccion'=>$__id_tiposeccion__,
		'headertpl'=>$__header__,
		'footertpl'=>$__footer__);
		
		
		
		
	}
	
	//-------------------------
	// Funciones de asignacion de orden
	//-------------------------
	
	function OrdenarArbol($__raiz__="") {
			if ($__raiz__=="") {				
				$this->m_tsecciones->LimpiarSQL();
				//$GLOBALS['_f_PROFUNDIDAD'] = 0;
				$this->m_tsecciones->FiltrarSQL('PROFUNDIDAD','',0);
				$this->m_tsecciones->OrdenSQL('ORDEN');				
				$this->m_tsecciones->Open();		
				
				if ( $this->m_tsecciones->nresultados>0 ) {
					$i = 0;
					while($_row_ = $this->m_tsecciones->Fetch($this->m_tsecciones->resultados) ) {													
						$this->rama["root"][$i] = new CSeccion($_row_);			
						$i++;
					}
					if ($i>0) {							
						foreach($this->rama[$__raiz__] as $rama) {																									
							$this->m_tsecciones->LimpiarSQL();
							$this->m_tsecciones->SQL = 'UPDATE secciones SET secciones.ORDEN='.$rama->m_nitem.' WHERE secciones.ID='.$rama->m_id;
							$this->m_tsecciones->EjecutaSQL();
							$this->OrdenarRama($rama->m_id);											
						}							
					}
				}								
			}	else {
				$this->OrdenarRama($__raiz__);			
			}	
	}

	/**
	 * Función recursiva que ordenar las secciones por ramas....
	 *
	 * @param Integer $__raiz__ el id de la sección a partir de la cual se recorre y ordena el arbol
	 */
	function OrdenarRama($__raiz__) {
									
				$this->m_tsecciones->LimpiarSQL();
				//$GLOBALS['_f_ID_SECCION'] = $__raiz__;
				//$GLOBALS['_f_PROFUNDIDAD'] = 1;
				//$GLOBALS['_tf_PROFUNDIDAD'] = "_superior_PROFUNDIDAD";
				$this->m_tsecciones->FiltrarSQL('ID_SECCION','',$__raiz__);
				$this->m_tsecciones->FiltrarSQL('PROFUNDIDAD','',1,"_superior_PROFUNDIDAD");
				$this->m_tsecciones->OrdenSQL('ORDEN');				
				$this->m_tsecciones->Open();
				
				if ( $this->m_tsecciones->nresultados>0 ) {							
							$i = 0;
							while($_row_ = $this->m_tsecciones->Fetch() ) {														
								//aqui guarda los indices
								$this->rama[$__raiz__][$i] = new CSeccion($_row_,$this->m_tsecciones->nresultados,$i+1);
								$i++;
							}		
							
							if ($i>0) {
								foreach($this->rama[$__raiz__] as $rama) {
									
									$Padre = $this->GetSeccion($rama->m_id_seccion);
																																		
									$this->m_tsecciones->LimpiarSQL();
									$this->m_tsecciones->SQL = 'UPDATE secciones SET secciones.ORDEN='.$rama->m_nitem.' WHERE secciones.ID='.$rama->m_id;
									$this->m_tsecciones->EjecutaSQL();	
									
									$rama_str = sprintf( $Padre->m_rama.".%03d", $rama->m_nitem);
									
									$this->m_tsecciones->LimpiarSQL();
									$this->m_tsecciones->SQL = "UPDATE secciones SET secciones.RAMA='".$rama_str."' WHERE secciones.ID=".$rama->m_id;
									$this->m_tsecciones->EjecutaSQL();									
									
									$this->OrdenarRama($rama->m_id);										
								}
							}
				}
			
	}	

	
	function OrdenarSeccion($__raiz__,$__idseccion__,$__ordenar__) {
									
				$this->m_tsecciones->LimpiarSQL();
				$this->m_tsecciones->FiltrarSQL('ID_SECCION','',$__raiz__);
				$this->m_tsecciones->FiltrarSQL('PROFUNDIDAD','',1,"_superior_PROFUNDIDAD");
				$this->m_tsecciones->OrdenSQL('secciones.ORDEN');				
				$this->m_tsecciones->Open();
				
				if ( $this->m_tsecciones->nresultados>0 ) {							
							$i = 0;							
							//levantamos todas las secciones en el array de $this->rama[$__raiz__]
							while($_row_ = $this->m_tsecciones->Fetch($this->m_tsecciones->resultados) ) {														
								//aqui guarda los indices
								$this->rama[$__raiz__][$i] = new CSeccion($_row_,$this->m_tsecciones->nresultados,$i+1);
								if ($this->rama[$__raiz__][$i]->m_id==$__idseccion__) $PosSeccion = $i;
								$i++;								
							}
							
							if ( $__ordenar__=="up" ) {
								if ( $PosSeccion > 0 ) {
									$this->rama[$__raiz__][$PosSeccion]->m_nitem--;
									$this->rama[$__raiz__][$PosSeccion-1]->m_nitem++;										
								}
							}
							if ( $__ordenar__=="down" ) {
								if ( ($PosSeccion+1) < $i ) {
									$this->rama[$__raiz__][$PosSeccion]->m_nitem++;
									$this->rama[$__raiz__][$PosSeccion+1]->m_nitem--;										
								}
							}							
							
							//luego los grabamos
							if ($i>0) {
								foreach($this->rama[$__raiz__] as $rama) {																									
									$this->m_tsecciones->LimpiarSQL();
									$this->m_tsecciones->SQL = 'UPDATE secciones SET secciones.ORDEN='.$rama->m_nitem.' WHERE secciones.ID='.$rama->m_id;
									$this->m_tsecciones->EjecutaSQL();	
									$this->OrdenarRama($rama->m_id);										
								}
							}
				}
			
	}	
	
	//----------------------------
	// Funciones para recorrer el arbol y mostrarlo
	// (usa los templates: m_templatesarbolrama)
	//----------------------------
	//ATENCION: funcion recursiva
	//dada la raiz ejecuta MostrarRama por cada hijo
	function MostrarRama($__raiz__, $__header__='', $__footer__='') {			
				$str = "";			
				$this->m_tsecciones->LimpiarSQL();
				$this->m_tsecciones->FiltrarSQL('ID_SECCION','',$__raiz__);
				$this->m_tsecciones->FiltrarSQL('PROFUNDIDAD','',1,"_superior_PROFUNDIDAD");
				$this->m_tsecciones->OrdenSQL('secciones.ORDEN ASC');				
				$this->m_tsecciones->Open();
				
				if ( $this->m_tsecciones->nresultados>0 ) {							
							$i = 0;
							while($_row_ = $this->m_tsecciones->Fetch() ) {														
								//aqui guarda los indices
								$this->rama[$__raiz__][$i] = new CSeccion($_row_,$this->m_tsecciones->nresultados,$i);
								$i++;
							}
							
							//Luego recorremos la rama llamando en forma recursiva...
							if ($i>0) {
								
								$str.= $__header__;								
								foreach($this->rama[$__raiz__] as $CRama) {
									
									if (isset($this->m_templatesarbolrama[ $CRama->m_id_tiposeccion])) $__template__=$this->m_templatesarbolrama[ $CRama->m_id_tiposeccion];
									if ($__template__['headertpl']!="")	{
										$__template__['headertpl'] = str_replace("*ID*", $CRama->m_id,	$__template__['headertpl']);																			
									}
									if ($__template__['footertpl']!="")	{
										$__template__['footertpl'] = str_replace("*ID*", $CRama->m_id,	$__template__['footertpl']);
									}
									
									$mostrar_rama = $this->MostrarRama( $CRama->m_id, $__template__['headertpl'], $__template__['footertpl'] );
									//ShowMessage("mostrar_rama de: ".$CRama->m_nombre." > ".$mostrar_rama);
									$str.= $this->m_CTiposSecciones->GetArbolNodo( $CRama, '', $mostrar_rama);
									
								}
								$str.= $__footer__;
							}
							
				}						
			return $str;
	}
	
	function RecorrerRama($__raiz__) {			
				$str = "";		
				$this->m_tsecciones->LimpiarSQL();
				$this->m_tsecciones->FiltrarSQL('ID_SECCION','',$__raiz__);
				$this->m_tsecciones->FiltrarSQL('PROFUNDIDAD','',1,"_superior_PROFUNDIDAD");
				$this->m_tsecciones->OrdenSQL('secciones.ORDEN');				
				$this->m_tsecciones->Open();
				
				if ( $this->m_tsecciones->nresultados>0 ) {							
							$i = 0;
							while($_row_ = $this->m_tsecciones->Fetch($this->m_tsecciones->resultados) ) {														
								//aqui guarda los indices
								$this->rama[$__raiz__][$i] = new CSeccion($_row_,$this->m_tsecciones->nresultados,$i);
								$i++;
							}
							
							//Luego recorremos la rama llamando en forma recursiva...
							if ($i>0) {
								foreach($this->rama[$__raiz__] as $rama) {
									$str.=  $this->m_CTiposSecciones->GetArbolNodo($rama);
									if (isset($this->m_templatesarbolrama[$rama->m_id_tiposeccion])) $__template__=$this->m_templatesarbolrama[$rama->m_id_tiposeccion];
									if ($__template__['headertpl']!="")	{
										$__template__['headertpl'] = str_replace("*ID*",$rama->m_id,	$__template__['headertpl']);									
										$str.=  $__template__['headertpl'];
									}
									$str.= $this->MostrarRama($rama->m_id);
									if ($__template__['footertpl']!="")	{
										$__template__['footertpl'] = str_replace("*ID*",$rama->m_id,	$__template__['footertpl']);
										$str.=  $__template__['footertpl'];
									}
								}
							}
							
				}

				return $str;
			
	}
	
	function RecorrerArbol($__raiz__="") {
			$str = "";
			if ($__raiz__=="") {				
				$this->m_tsecciones->LimpiarSQL();
				$this->m_tsecciones->FiltrarSQL('PROFUNDIDAD','','0','_inferior_PROFUNDIDAD');
				$this->m_tsecciones->OrdenSQL('secciones.ORDEN');
				$this->m_tsecciones->Open();		
				
				if ( $this->m_tsecciones->nresultados>0 ) {
					$i = 0;
					while($_row_ = $this->m_tsecciones->Fetch($this->m_tsecciones->resultados) ) {													
						$this->rama["root"][$i] = new CSeccion($_row_);			
						$i++;
					}
					if ($i>0) {
						foreach($this->rama["root"] as $rama) {																						
							$str.=  $this->m_CTiposSecciones->GetArbolNodo($rama);
							if (isset($this->m_templatesarbolrama[$rama->m_id_tiposeccion])) $__template__=$this->m_templatesarbolrama[$rama->m_id_tiposeccion];
							if ($__template__['headertpl']!="")	{
								$__template__['headertpl'] = str_replace("*ID*",$rama->m_id,	$__template__['headertpl']);																	
								$str.= $__template__['headertpl'];
							}
							$str.= $this->RecorrerRama($rama->m_id);
							if ($__template__['footertpl']!="")	{
								$__template__['footertpl'] = str_replace("*ID*",$rama->m_id,	$__template__['footertpl']);
								$str.= $__template__['footertpl'];
							}
						}
					}
				}								
			}	else {
				$str.= $this->RecorrerRama($__raiz__,$__template__);			
			}
			return $str;
	
	}
	
	//this->rama contiene el arbol construido a partir de raiz
	//es un array de CSeccion's
	//si se definieron los templates: $this->m_templatesarbolrama, por tiposeccion
	//imprime segun el template m_templatesarbolrama[tiposeccion][headertpl][footertpl]
	function MostrarArbol($__raiz__="") {
			
			if ($__raiz__=="") {
				/*STARTING IN LEVEL 0: NIVEL 0 = SECCIONES PRINCIPALES = CATEGORIAS*/
				$ts = $this->m_tsecciones;								
				$ts->LimpiarSQL();
				$ts->FiltrarSQL('PROFUNDIDAD','','0','_inferior_PROFUNDIDAD');
				$ts->OrdenSQL('secciones.ORDEN');
				$ts->Open();		
				
				if ( $ts->nresultados>0 ) {
					
					$i = 0;
					while($_row_ = $ts->Fetch() ) {													
						$this->rama["root"][$i] = new CSeccion($_row_);			
						$i++;
					}
					
					
					if ($i>0) {
						foreach($this->rama["root"] as $CRama) {																						
							
							if (isset($this->m_templatesarbolrama[$CRama->m_id_tiposeccion]))
								$__template__=$this->m_templatesarbolrama[$CRama->m_id_tiposeccion];
							
							if ($__template__['headertpl']!="")	{
								$__template__['headertpl'] = str_replace("*ID*",$CRama->m_id,	$__template__['headertpl']);																	
							}
							
							if ($__template__['footertpl']!="")	{
								$__template__['footertpl'] = str_replace("*ID*",$CRama->m_id,	$__template__['footertpl']);
							}
							
							$mostrar_rama = $this->MostrarRama($CRama->m_id, $__template__['headertpl'], $__template__['footertpl']);
							
							//ShowMessage("mostrar_rama de: ".$CRama->m_nombre." > ".$mostrar_rama);
							
							$this->m_CTiposSecciones->MostrarArbolNodo( $CRama, "",  $mostrar_rama );
							
						}
					}
				}								
			}	else {
				echo $this->MostrarRama($__raiz__);			
			}
	
	}
	
	//--------------------------------------------------
	// Funcion de impresion del arbol como combo, con filtros
	//--------------------------------------------------
//template 'headertpl' 'rowtpl' 	'footertpl'
	function MostrarComboArbol( $__variable__,$__raiz__='',$__niveles__=1, $__id_tiposeccion__='',$__filtro__='',$__template__='',$__iteration__=1,$__size__=8,$__todos__="todos...") {
					
			$rama = '';
		
			if ($__iteration__==1) {				
				echo '<select name="'.$__variable__.'" onchange="javascript:filtrarcomboarbol();">';				
				for($i=0,$todos=$__todos__;$i<($__size__-8);$i++) $todos.=".";
				echo '<option value="">'.$todos.'</option>';
			}
		
			if ($__raiz__=='') $__raiz__="root";			
			if ($__filtro__!='') $__filtro__ = '/*SPECIAL*/'.$__filtro__;			
		
			$this->m_tsecciones->LimpiarSQL();			
			if ($__raiz__=="root") {						
				//empezamos por la raiz del arbol (profundidad 0)										
				//$GLOBALS['_f_PROFUNDIDAD'] = 0;
				//$GLOBALS['_tf_PROFUNDIDAD'] = "";
				$this->m_tsecciones->FiltrarSQL('PROFUNDIDAD',$__filtro__,0,'');		
			} else {			
				//$GLOBALS['_f_ID_SECCION'] = $__raiz__;
				//$GLOBALS['_f_PROFUNDIDAD'] = 1;
				//$GLOBALS['_tf_PROFUNDIDAD'] = "_superior_PROFUNDIDAD";
				$this->m_tsecciones->FiltrarSQL('ID_SECCION','',$__raiz__);
				$this->m_tsecciones->FiltrarSQL('PROFUNDIDAD',$__filtro__,1,"_superior_PROFUNDIDAD");
			}	

			$this->m_tsecciones->OrdenSQL('secciones.NOMBRE');				
			$this->m_tsecciones->Open();		

			if ( $this->m_tsecciones->nresultados>0 ) {
				$i = 0;
				while($_row_ = $this->m_tsecciones->Fetch($this->m_tsecciones->resultados) ) {													
					$rama[$__raiz__][$i] = new CSeccion($_row_);			
					$i++;
				}
				if ($i>0) {
					foreach($rama[$__raiz__] as $CRama) {
						$GLOBALS[$__variable__]==$CRama->m_id ? $selected = 'selected': $selected = '';
						for( $o=0,$tab=""; $o<$CRama->m_profundidad; $o++,$tab.="----");	
						if ($__template__!='') $__optiontemplate__ = '<option value=*ID* '.$selected.'>'.$tab.$__template__.'</option>';
						if (($__id_tiposeccion__=='') || ($CRama->m_id_tiposeccion==$__id_tiposeccion__)) {
							$this->m_CTiposSecciones->MostrarComboArbolNodo( $CRama, $__optiontemplate__);																					
						}
						if ($__niveles__>1) {//iteramos por la cantidad de niveles que necesitamos
							$this->MostrarComboRama($__variable__,$CRama->m_id,($__niveles__-1),$__id_tiposeccion__,$__filtro__,$__template__,($__iteration__+1));
						}
					}
				}
			}								

			if ($__iteration__==1) { echo '</select>';	}
			
					
	}

	
	function MostrarComboRama($__variable__,$__raiz__,$__niveles__=1, $__id_tiposeccion__='',$__filtro__='',$__template__='',$__iteration__=1) {	
		
		$this->MostrarComboArbol($__variable__,$__raiz__,$__niveles__, $__id_tiposeccion__,$__filtro__,$__template__,$__iteration__);
				
	}
	
	function MostrarCombo( $__variable__, $__combo__,$__size__,$__todos__="todos...") {
	
		echo '<select name="'.$__variable__.'" onchange="javascript:filtrarcomboarbol();">';				
		for($i=0,$todos=$__todos__;$i<($__size__-8);$i++,$todos.=".");
		echo '<option value="">'.$todos.'</option>';
		
		foreach( $__combo__ as $__entry__ ) {
			$dato = split( "\|", $__entry__);
			if ($dato!='') {
				$GLOBALS[$__variable__] == $dato[1] ? $selected = 'selected': $selected = '';
				if ($dato[0]!='')
					echo '<option value='.$dato[1].' '.$selected.'>'.$dato[0].'</option>';			
			}
		}
		
		
		echo '</select>';
	}
	
	function GetComboArbol( $__variable__,$__raiz__='',$__niveles__=1, $__id_tiposeccion__='',$__filtro__='',$__template__='',$__iteration__=1,$__size__=8) {
		
			global $_comboout_;
			$rama = '';
		
			if ($__iteration__==1) 	$_comboout_="";
			
			if ($__raiz__=='') $__raiz__="root";			
			if ($__filtro__!='') $__filtro__ = '/*SPECIAL*/'.$__filtro__;			
		
			$this->m_tsecciones->LimpiarSQL();			
			if ($__raiz__=="root") {
				$_comboout_ = "";						
				//empezamos por la raiz del arbol (profundidad 0)										
				//$GLOBALS['_f_PROFUNDIDAD'] = 0;
				//$GLOBALS['_tf_PROFUNDIDAD'] = "";
				$this->m_tsecciones->FiltrarSQL('PROFUNDIDAD',$__filtro__,0,'');		
			} else {			
				//$GLOBALS['_f_ID_SECCION'] = $__raiz__;
				//$GLOBALS['_f_PROFUNDIDAD'] = 1;
				//$GLOBALS['_tf_PROFUNDIDAD'] = "_superior_PROFUNDIDAD";
				$this->m_tsecciones->FiltrarSQL('ID_SECCION','',$__raiz__);
				$this->m_tsecciones->FiltrarSQL('PROFUNDIDAD',$__filtro__,1,"_superior_PROFUNDIDAD");
			}	

			$this->m_tsecciones->OrdenSQL('secciones.NOMBRE');				
			$this->m_tsecciones->Open();		

			if ( $this->m_tsecciones->nresultados>0 ) {
				$i = 0;
				while($_row_ = $this->m_tsecciones->Fetch($this->m_tsecciones->resultados) ) {													
					$rama[$__raiz__][$i] = new CSeccion($_row_);			
					$i++;
				}
				if ($i>0) {
					foreach($rama[$__raiz__] as $CRama) {													
						if (($__id_tiposeccion__=='') || ($CRama->m_id_tiposeccion==$__id_tiposeccion__)) {																							
							$_comboout_.= $this->m_CTiposSecciones->GetComboArbolNodo( $CRama, $__template__)."|".$CRama->m_id."\n";
						}
						if ($__niveles__>1) {//iteramos por la cantidad de niveles que necesitamos
							$this->GetComboRama($__variable__,$CRama->m_id,($__niveles__-1),$__id_tiposeccion__,$__filtro__,$__template__,($__iteration__+1));
						}
					}
				}
			}								

			return $_comboout_;
			
	}
	
	function GetComboRama($__variable__,$__raiz__,$__niveles__=1, $__id_tiposeccion__='',$__filtro__='',$__template__='',$__iteration__=1) {	
		
		$this->GetComboArbol($__variable__,$__raiz__,$__niveles__, $__id_tiposeccion__,$__filtro__,$__template__,$__iteration__);
				
	}	
	//--------------------------
	// Mostrar Secciones
	//--------------------------
	
	function MostrarSecciones($__idpadre__) {
					
			echo '<tr><td height="2"><img src="../../inc/images/spacer.gif" height="10"></td></tr>';	
					
			$this->m_tsecciones->LimpiarSQL();
			//$GLOBALS['_f_ID_SECCION'] = $__idpadre__;			
			$this->m_tsecciones->FiltrarSQL('ID_SECCION','',$__idpadre__);
			$this->m_tsecciones->Open();		
				
			if ( $this->m_tsecciones->nresultados>0 ) {
			
				while($_row_ = $this->m_tsecciones->Fetch($this->m_tsecciones->resultados) ) {
				
					$this->m_CTiposSecciones->MostrarColapsado(new CSeccion($_row_));
					
				}
			}						
	}
	
	
	function MostrarSeccion($__idseccion__) {
					
			echo '<tr><td height="2"><img src="../../inc/images/spacer.gif" height="10">__</td></tr>';	
					
			$this->m_tsecciones->LimpiarSQL();			
			$this->m_tsecciones->FiltrarSQL('ID','',$__idseccion__);
			$this->m_tsecciones->Open();		
				
			if ( $this->m_tsecciones->nresultados>0 ) {
			
				while($_row_ = $this->m_tsecciones->Fetch($this->m_tsecciones->resultados) ) {
				
					$this->m_CTiposSecciones->MostrarColapsado(new CSeccion($_row_));
					
				}				
				
			}						
			
	}

	function MostrarResultados($__template__="") {		
				
			if ( $this->m_tsecciones->nresultados>0 ) {
			
				while($_row_ = $this->m_tsecciones->Fetch($this->m_tsecciones->resultados) ) {
				
					echo $this->m_CTiposSecciones->MostrarColapsado(new CSeccion($_row_),$__template__);
					
				}				
				
			}						
			
	}	
	
	
	function MostrarColapsadosPorTipo($__idseccion__,$__tipo__,$_maxitems_=0,$__excluyeaid__=-1) {
			
			$this->m_tsecciones->LimpiarSQL();
			//$GLOBALS['_f_ID_TIPOSECCION'] = $__tipo__;			
			$this->m_tsecciones->FiltrarSQL('ID_TIPOSECCION','',$__tipo__);
			
			if ($__idseccion__!='') {
				//$GLOBALS['_f_ID_SECCION'] = $__idseccion__;
				if ($__excluyeaid__>=1) {				
					$this->m_tsecciones->FiltrarSQL('ID_SECCION','/*SPECIAL*/secciones.ID<>'.$__excluyeaid__,$__idseccion__);
				} else $this->m_tsecciones->FiltrarSQL('ID_SECCION','',$__idseccion__);
			}

			$this->m_tsecciones->Open();		
							
			if ( $this->m_tsecciones->nresultados>0 ) {				
				while($_row_ = $this->m_tsecciones->Fetch($this->m_tsecciones->resultados) ) {
					//$_CContenido_ = new CContenido($_row_);
					//$this->m_CTiposContenidos->Mostrar($_CContenido_);
					$this->m_CTiposSecciones->MostrarColapsados((new CSeccion($_row_)) );
				}
			} else {
				echo '<tr><td><span class="seccion_colapsado_sinresultados">sin resultados del tipo '.$__tipo__.'</span></td></tr>';
			}						
			
	}
	
	function Eliminar( $__idseccion__, $__eliminar_relaciones__=true ) {		
		$CS = $this->GetSeccion($__idseccion__);		
		if ($CS!=null) {			
			if ( $_SESSION['nivel']==0 || $CS->m_id_usuario_creador==$_SESSION['idusuario'] ) {				
				$exito = $this->m_tsecciones->Borrari($__idseccion__);
				if ( $exito && $__eliminar_relaciones__ ) {
					$exito = $this->Relaciones->EliminarRelaciones( "", $__idseccion__);
				}
				return $exito;
			}				
		}
	}
	
	function Edit( $__tiposeccion__ , $CLang, $CMultiLang ) {
	
		$tpl = "";
		$tpl = $this->m_CTiposSecciones->m_templatesedicion[$__tiposeccion__];
		//translate
		foreach( $CLang->m_Words as $field=>$value ) {
			$tpl = str_replace( "{".$field."}",  $value, $tpl );
		}
		$nn = 0;
		$cola = "";
		foreach($this->m_tsecciones->campos as $nombre=>$campo) {
						
			if ( strpos( $tpl, $nombre ) === false ) {
				//$cola.= '<input type="hidden" value="'.$GLOBALS['_e_'.$nombre].'" name="_e_'.$nombre.'">';
			} else {			
			
				//editar campo
				$cceedit = $this->m_tsecciones->EditarCampoStr($nombre);
				if ($campo['tipo']=='BLOBTEXT') $cceedit.= '<script> textareaEdit( \'_e_'.$nombre.'\',\'\' ); </script>';
	
				//multidioma		
				if ( ($campo['tipo']=='TEXTOML' || $campo['tipo']=='BLOBTEXTOML') && $CMultiLang->Activo()) {			
					foreach( $CMultiLang->m_arraylangs as $idioma=>$codigo ) {
						$cceedit.= '<div id="did'.$codigo.'_'.$nombre.'" class="did'.$codigo.'"><img src="../../inc/images/flags/'.$codigo.'.jpg" width="21" height="11" border="0">';
						$cceedit.= $this->m_tsecciones->EditarCampoStr($nombre,'','',$codigo);
						$cceedit.= '</div>';
						if ($campo['tipo']=='BLOBTEXTOML') $cceedit.= '<script> textareaEdit( \'_e_'.$nombre.'\',\''.$codigo.'\' ); </script>';
					}
				}			
				
				//replace
				$tpl = str_replace( "*".$nombre."*", $cceedit, $tpl );
			}
		}
		return $tpl.$cola;		
		
	}	
	
	
}
?>