<?

/**
 * class CContenidos
 * 
 * * @version 5.1 09/06/2007 -- corregido MostrarEncabezados
 * @version 5.0 07/06/2007
 * @version 28/11/2003
 * @copyright 2003 
 **/

 
class CContenidos extends CErrorHandler {

	var $m_tcontenidos;//tabla contenidos
	var $m_CRelaciones;
	var $m_CTiposContenidos;//miembro de la clase CTiposContenidos
	
	//buffer
	var $m_CContenido;
	var $m_CUsuarios;
	
	//display properties
	var $m_columns;	
	var $m_rows;	
	var $m_separador;
	var $m_principiolinea;
	var $m_finlinea;
	
	function PopErrorsCascade() {				
		$fullcascade = $this->m_CTiposContenidos->PopErrorsCascade();
		$fullcascade.= $this->PopAllErrorsFullStr();
		return $fullcascade;
		
	}	
	
	function CContenidos(&$__tcontenidos__,&$__m_CTiposContenidos__, &$__m_CRelaciones__, &$__m_CUsuarios__) {
		$this->m_class = "CContenidos";
		$this->Set( $__tcontenidos__, $__m_CTiposContenidos__, $__m_CRelaciones__, $__m_CUsuarios__ );		
	}
	
	function Set(&$__tcontenidos__,&$__m_CTiposContenidos__, &$__m_CRelaciones__, &$__m_CUsuarios__) {
		$this->m_CTiposContenidos = &$__m_CTiposContenidos__;
		$this->m_CRelaciones = &$__m_CRelaciones__;
		$this->m_CUsuarios = &$__m_CUsuarios__;
		$this->m_tcontenidos = &$__tcontenidos__;
		$this->m_columns = 1;
		$this->m_separador = "";		
		parent::CErrorHandler();
	}

	function SetColumns($cols,$separador,$principiolinea='<tr>',$finlinea='</tr>') {
		$this->m_columns = $cols;
		$this->m_separador = $separador;
		$this->m_principiolinea = $principiolinea;
		$this->m_finlinea = $finlinea;			
	}
	
	function SetRows($rows) {
		$this->m_rows = $rows;
	}	
	
	//------------------------
	// Obtener informacion de contenidos
	//------------------------	
	
	function GetSeccionId($__idcontenido__) {
			$this->GetContenido($__idcontenido__);
			if ($this->m_CContenido!=null) return $this->m_CContenido->m_id_seccion; else return (-1);
	}
	
	function GetContenido($__idcontenido__) {
			$tc = $this->m_tcontenidos;
			$this->m_CContenido = null;
			if (is_numeric($__idcontenido__)) {
				
				$tc->LimpiarSQL();	
				$tc->FiltrarSQL('ID','',$__idcontenido__);
				$tc->Open();						
				if ( $tc->nresultados==1 ) {
					$_row_ = $tc->Fetch();
					$this->m_CContenido = new CContenido($_row_);
					return $this->m_CContenido;
				} elseif ( $tc && $tc->nresultados>1) {
					DebugError('Hay demasiadas fichas con este id. ['.$tc->nresultados.']');
				} elseif ($tc->nresultados==0) {
					DebugError('No hay fichas con este id.');
				}
			} else $debugerror = "CContenidos::GetContenido __idcontenido__ INDEFINIDO: [".$__idcontenido__."]";			
			DebugError($debugerror);
			$this->PushError( new CError( "UNDEFINED_PARAMETER", $debugerror) );
			return null;
	}
	
	function GetContenidoPorTitulo( $__titulo__, $__id_tipocontenido__='',$__id_seccion__='' ) {
			$__titulo__ = trim($__titulo__);
			if ($__titulo__!="") {
				$this->m_tcontenidos->LimpiarSQL();
				if ($__id_tipocontenido__!='')
						$this->m_tcontenidos->FiltrarSQL( 'ID_TIPOCONTENIDO', '', $__id_tipocontenido__ );
				if ($__id_seccion__)
						$this->m_tcontenidos->FiltrarSQL( 'ID_SECCION', '', $__id_seccion__ );
				$this->m_tcontenidos->FiltrarSQL('ID','/*SPECIAL*/ contenidos.TITULO LIKE \''.$__titulo__.'\' ', '0','_superior_ID');
				$this->m_tcontenidos->Open();		
				if ( $this->m_tcontenidos->nresultados>=1 ) {
					$_row_ = $this->m_tcontenidos->Fetch();
					$this->m_CContenido = new CContenido($_row_);
					return $this->m_CContenido;
				}
			}			
			return null;
	}
	
	function GetContenidos( $__id_tipocontenido__="", $__id_seccion__="", $__filtro__="", $__orden__="", $__start__="", $__max_items__="") {
		$ids = array();
		$TC = $this->m_tcontenidos;
		if ($__id_seccion__!='' || $__id_tipocontenido__!="" ) {
			
			$TC->LimpiarSQL();
			
			if ($__filtro__!="") {
				$__filtro__ = '/*SPECIAL*/'.$__filtro__;
			}
			
			if ( $__id_seccion__!="" )
				$TC->FiltrarSQL( 'ID_SECCION', $__filtro__, $__id_seccion__ );
								
			if ( $__id_tipocontenido__!="" )
				$TC->FiltrarSQL( 'ID_TIPOCONTENIDO', '', $__id_tipocontenido__ );
			
			$TC->FiltrarSQL( 'BAJA', $__filtro__, 'S');
			if ( is_numeric($__start__) && is_numeric($__max_items__) ) {
				$TC->LimiteSQL( $__start__, $__max_items__ );
			}
			
			if (trim($__orden__)=="") $__orden__ = "ORDEN ASC";
			$TC->OrdenSQL($__orden__);
			
			$TC->Open();		

			$cn = 0;	
			if ( $TC->nresultados>0 ) {			
				while($_row_ = $TC->Fetch() ) {					
					$ids[$cn] = $_row_["contenidos.ID"];
					$cn++; 
				}
			}
		}
		return $ids;		
	}
	
	function GetContenidoCompleto( $__idcontenido__ ) {
		$this->GetContenido($__idcontenido__);
		if ($this->m_CContenido!=null)  {
			$this->m_CTiposContenidos->GetCompleto( $this->m_CContenido );
			return $this->m_CContenido;
		} else return null;
		
	}
	
	
	function CountContenidos( $__idseccion__, $__filtro__='', $_filtrar_baja_=true ) {
		if ($__idseccion__!='') {
			$this->m_tcontenidos->LimpiarSQL();
			if ($_filtrar_baja_) $this->m_tcontenidos->FiltrarSQL('BAJA','','S');
			if ($__filtro__!='') 
				$this->m_tcontenidos->FiltrarSQL('ID_SECCION','/*SPECIAL*/'.$__filtro__,$__idseccion__);
			else $this->m_tcontenidos->FiltrarSQL('ID_SECCION','',$__idseccion__);			
			//$this->m_tcontenidos->OrdenSQL($__orden__);			
			$this->m_tcontenidos->Open();		
			$this->m_tcontenidos->Close();
			return $this->m_tcontenidos->nresultados;
		}
		return 0;
	}
	//devuelve los ids de los contenidos dentro de la seccion en el formato id1|id2|id3
	function GetIdsContenidos($__idseccion__,$__filtro__='',$__orden__='') {
		$ids = '';
		$sep = '';
		if ($__idseccion__!='') {
			$this->m_tcontenidos->LimpiarSQL();

			if ($__filtro__!='') 
				$this->m_tcontenidos->FiltrarSQL('ID_SECCION','/*SPECIAL*/'.$__filtro__,$__idseccion__);
			else $this->m_tcontenidos->FiltrarSQL('ID_SECCION','',$__idseccion__);			
			$this->m_tcontenidos->OrdenSQL($__orden__);
			$this->m_tcontenidos->Open();		
			//echo $this->m_tcontenidos->SQL;
				
			if ( $this->m_tcontenidos->nresultados>0 ) {
			
				while($_row_ = $this->m_tcontenidos->Fetch() ) {
					$this->m_CContenido = new CContenido($_row_);					
					$ids.= $sep.$this->m_CContenido->m_id;
					if ($sep == '') $sep = '|';		
					
				}
			}
			$this->m_tcontenidos->Close();
		}
		return $ids;
	}
	
	function NuevoContenido( $__id_tipocontenido__ ) {		
		$ContenidoNuevo = new CContenido( 0 ); ///set empty
		$ContenidoNuevo->m_id_tipocontenido = $__id_tipocontenido__;
		$this->m_CTiposContenidos->NuevoContenido( $__id_tipocontenido__, $ContenidoNuevo );
		return $ContenidoNuevo;
	}
	
	/**
	 * Crea una ficha completa con sus detalles
	 *
	 * A continuación se detalla como se tratarán las variables globales para detalles: [parametro crear_detalles en false] 
	 *  name = _adetalle_DETALLE    significa: accion a realizar sobre ese detalle, OBLIGATORIO [ nuevo, modificar, borrar ]
	 *  name = _idetalle_DETALLE    significa: id del detalle, OBLIGATORIO para las acciones [ modificar, borrar ]
	 *  name = _edetalle_DETALLE    significa: valor editado del detalle, OBLIGATORIO para las acciones [ nuevo, modificar ]
	 * 
	 * @param Integer $__id_tipocontenido__  el id del tipo de contenido
	 * @param CContenido $ContenidoReferencia el objeto CContenido a crear
	 * @param Bool $__crear_detalles__ si es verdadero generará valores genéricos para los detalles, si no los tomará de las variables globales existentes
	 * @param Bool $__ordenar_contenidos__  si verdadero reordena la seccion de ese contenido con el nuevo contenido, si falso no lo hace...
	 * @return CContenido en caso de exito devuelve el objeto creado... en caso de error devuelve nil (valor nulo) (que no es un objeto)
	 */
	function CrearContenidoCompleto( $__id_tipocontenido__, $ContenidoReferencia=null, $__crear_detalles__=true, $__ordenar_contenidos__=true ) {
		
		//$__id_tipocontenido__ = ;
		if ($ContenidoReferencia==null || !is_object($ContenidoReferencia)) {
			$ContenidoNuevo = new CContenido();
			$ContenidoNuevo->m_id_tipocontenido = $__id_tipocontenido__;
			$ContenidoNuevo->m_id_seccion = 1; //EN SISTEMA
			$ContenidoNuevo->m_id_contenido = 2; //VOID o SYSTEM REFE
			$ContenidoNuevo->m_titulo = "";
			$ContenidoNuevo->m_ml_titulo = "";
			$ContenidoNuevo->m_copete = "";
			$ContenidoNuevo->m_ml_copete = "";
			$ContenidoNuevo->m_palabrasclave = "";
			$ContenidoNuevo->m_ml_palabrasclave = "";			
			$ContenidoNuevo->m_cuerpo = "";
			$ContenidoNuevo->m_ml_cuerpo = "";
			$ContenidoNuevo->m_id_usuario_creador = 1; //CG_ADMIN
			$ContenidoNuevo->m_id_usuario_modificador = 1; //CG_ADMIN
			$ContenidoNuevo->m_baja = "N";
		} else {
			$ContenidoNuevo = $ContenidoReferencia;
		}	
		///id usuario no valido?
		if ( !is_numeric( $ContenidoNuevo->m_id_usuario_creador ) || $ContenidoNuevo->m_id_usuario_creador < 1 ) {
			DebugError("PERMISSION_NOT_GRANTED user id not valid");
			$this->PushError( new CError("PERMISSION_NOT_GRANTED","user id not valid ".$ContenidoNuevo->m_id_usuario_creador ) );
			
			return null;
		}
		
		if ( !is_numeric( $ContenidoNuevo->m_id_tipocontenido ) || $ContenidoNuevo->m_id_tipocontenido < 1 ||
			in_array( $ContenidoNuevo->m_id_tipocontenido, $this->m_CTiposContenidos->m_Int2StrArray) ) {
			DebugError("PERMISSION_NOT_GRANTED id tipocontenido not valid");
			$this->PushError( new CError("PERMISSION_NOT_GRANTED","user id tipocontenido not valid ".$ContenidoNuevo->m_id_tipocontenido ) );
			
			return null;
		}		
		
		$ContenidoNuevo->m_id_usuario_modificador = $ContenidoNuevo->m_id_usuario_creador;
		
		$ContenidoNuevo->ToGlobals();
		
		///habilitamos los campos de registro de usuarios
		$this->m_tcontenidos->campos["ID_USUARIO_CREADOR"]["editable"] = 'si';
		$this->m_tcontenidos->campos["ID_USUARIO_MODIFICADOR"]["editable"] = 'si';
						
		$_exito_ = $this->m_tcontenidos->Insertar();
		
		if ($_exito_) {
			
			$ContenidoNuevo->m_id = $this->m_tcontenidos->lastinsertid;			
			
			//generar detalles...por default vacios
			if ( $__crear_detalles__ ) $this->m_CTiposContenidos->CrearCompleto( $ContenidoNuevo );
			else $this->m_CTiposContenidos->ConfirmarDetalles(  "nuevo", $ContenidoNuevo->m_id_tipocontenido, $ContenidoNuevo->m_id );
			

			//ordenar contenidos por default
			if ($__ordenar_contenidos__ && 
				is_numeric($ContenidoNuevo->m_id_seccion) && 
				$ContenidoNuevo->m_id_seccion>0)  
					$this->OrdenarContenido( $ContenidoNuevo->m_id_seccion, 0, "", $ContenidoNuevo->m_id_tipocontenido );
			
			$this->ActualizarAutoria( $ContenidoNuevo->m_id, $ContenidoNuevo->m_id_usuario_creador );
		} else DebugError("CrearContenidoCompleto no se pudo insertar");
		
		if ($_exito_) return $ContenidoNuevo;
		else return null;
		
	}
	
	//------------------------
	// Mostrar contenidos
	//------------------------	
	
	function MostrarResultadoColapsado($__template__="") {
			if ( $this->m_tcontenidos->nresultados>0 ) {
			
				while($_row_ = $this->m_tcontenidos->Fetch() ) {
					$this->m_CContenido = new CContenido($_row_);
					$this->m_CTiposContenidos->MostrarColapsado( $this->m_CContenido,$__template__ );
					
				}
			}		
	}

	function MostrarResultadoResumen($__template__="") {
			if ( $this->m_tcontenidos->nresultados>0 ) {
			
				while($_row_ = $this->m_tcontenidos->Fetch() ) {
					$this->m_CContenido = new CContenido($_row_);
					$this->m_CTiposContenidos->MostrarResumen( $this->m_CContenido, $__template__);
			
				}
			}		
	}

	function MostrarResultadoCompleto($__template__="") {
			if ( $this->m_tcontenidos->nresultados>0 ) {
			
				while($_row_ = $this->m_tcontenidos->Fetch() ) {
					$this->m_CContenido = new CContenido($_row_);
					
					if(is_array($this->m_tcontenidos->camposalias))
					foreach($this->m_tcontenidos->camposalias as $field=>$yeah ) {
						if ($yeah=='si')
						$this->m_CContenido->m_specials[$field] = $_row_[$field];						
					}			
					$this->m_CTiposContenidos->MostrarCompleto( $this->m_CContenido, $__template__ );
			
				}
			}		
	}
	
	function MostrarResultadoConsulta($__template__="") {
			if ( $this->m_tcontenidos->nresultados>0 ) {
			
				while($_row_ = $this->m_tcontenidos->Fetch() ) {
					$this->m_CContenido = new CContenido($_row_);
					
					if(is_array($this->m_tcontenidos->camposalias))
					foreach($this->m_tcontenidos->camposalias as $field=>$yeah ) {
						if ($yeah=='si')
						$this->m_CContenido->m_specials[$field] = $_row_[$field];						
					}
					$this->m_CTiposContenidos->MostrarConsulta( $this->m_CContenido, $__template__ );
			
				}
			}		
	}	
	
	function MostrarColapsados($__idseccion__,$__excluyeaid__=-1, $__template__='') {

		
			$this->m_tcontenidos->LimpiarSQL();
			//$GLOBALS['_f_ID_SECCION'] = $__idseccion__;			
			if ($__excluyeaid__>=1) $this->m_tcontenidos->FiltrarSQL('ID_SECCION','/*SPECIAL*/contenidos.ID<>'.$__excluyeaid__,$__idseccion__);
			else $this->m_tcontenidos->FiltrarSQL('ID_SECCION','',$__idseccion__);
			$this->m_tcontenidos->Open();		
				
			if ( $this->m_tcontenidos->nresultados>0 ) {
			
				while($_row_ = $this->m_tcontenidos->Fetch() ) {
					$this->m_CContenido = new CContenido($_row_);
					$this->m_CTiposContenidos->MostrarColapsado( $this->m_CContenido, $__template__);
					
				}
			}						
	}

	/**
	 * Muestra los registros filtrados por los parametros siguientes. Se utilizará el template especificado o bien el definido predeterminadamente
	 * por /inc/templates/CONTENIDO.colapsado.html
	 * 
	 * Atención: no muestra registros no publicados.
	 *
	 * @param Integer $__idseccion__  id de la seccion
	 * @param Integer $__tipo__ id del tipo de contenido
	 * @param Integer $_maxitems_ 0 si no hay máximo, N si se quiere un máximo
	 * @param Integer $__excluyeaid__  el id del contenido que se quiere excluir
	 * @param String $__template__ texto del template, "" si se usa uno predeterminado
	 * @param String $__orden__ SQL, RAND() si se quiere registros aleatorios, ORDEN ASC, si se quiere que dependa del orden especificado, o FECHAALTA DESC para orden decreciente segune el alta del registro
	 * @param String $__filtro__ texto del template, "" si se usa uno predeterminado
	 * @param Integer $__limite_offset__ indice del primero registro a recibir (junto con maxitems fija el rango de los registros a recibir)
	 */
	function MostrarColapsadosPorTipo($__idseccion__,$__tipo__,$_maxitems_='',$__excluyeaid__=-1, $__template__='', $__orden__='', $__filtro__='', $__limite_offset__='') {
			$this->MostrarPorTipo( "colapsado", $__idseccion__, $__tipo__, $_maxitems_, $__excluyeaid__, $__template__, $__orden__, $__filtro__, $__limite_offset__ );
	}
	
	function MostrarResumenesPorTipo($__idseccion__,$__tipo__,$_maxitems_='',$__excluyeaid__=-1, $__template__='', $__orden__='', $__filtro__='', $__limite_offset__='') {
		$this->MostrarPorTipo( "resumen", $__idseccion__, $__tipo__, $_maxitems_, $__excluyeaid__, $__template__, $__orden__, $__filtro__, $__limite_offset__='' );
	}	
	
	function MostrarCompletosPorTipo($__idseccion__,$__tipo__,$_maxitems_='',$__excluyeaid__=-1, $__template__='', $__orden__='', $__filtro__='', $__limite_offset__='') {
		$this->MostrarPorTipo( "completo", $__idseccion__, $__tipo__, $_maxitems_, $__excluyeaid__, $__template__, $__orden__, $__filtro__, $__limite_offset__='' );
	}
		
	
	
	function MostrarContenidoColapsado($__idcontenido__, $__template__='',$__show_all__=false) {				
			
		 if ($this->m_CContenido!=null) {
		 	 if ($this->m_CContenido->m_id!=$__idcontenido__) $this->GetContenido($__idcontenido__);		   		 	 
		 }
		 
		 if (!$__show_all__ && !$this->EsValido($this->m_CContenido)) {
		 	$this->m_CContenido = null;
			ShowError("Contenido no disponible. Intente en otro momento." );			
		 }
		 
		 
		 if ($this->m_CContenido!=null) {
		 	$this->m_CTiposContenidos->GetCompleto($this->m_CContenido);
		 	$this->m_CTiposContenidos->MostrarColapsado($this->m_CContenido , $__template__);
		 }
			
	}

	
	function MostrarContenidoResumen($__idcontenido__, $__template__='',$__show_all__=false) {
		 if ($this->m_CContenido!=null) {
		 	 if ($this->m_CContenido->m_id!=$__idcontenido__) $this->GetContenido($__idcontenido__);		   		 	 
		 }
		 
		 if (!$__show_all__ && !$this->EsValido($this->m_CContenido)) {
		 	$this->m_CContenido = null;
			ShowError("Contenido no disponible. Intente en otro momento." );			
		 }
		 
		 
		 if ($this->m_CContenido!=null) {
		 	$this->m_CTiposContenidos->GetCompleto($this->m_CContenido);
		 	$this->m_CTiposContenidos->MostrarResumen($this->m_CContenido , $__template__);
		 }
		
	}	
	 
	function EsValido(&$__m_CContenido__) {
			return ( 
			$this->m_CContenido && 
						( 
							( $this->m_CContenido->m_baja=='S' )
				 			|| 
				 			( 
				 				$this->m_CUsuarios->Logged() 
				 				&& 
				 				$this->m_CContenido->m_id_usuario_creador != $this->m_CSesionUsuario->m_id 
				 			)
				 		)
			);
		 			
	}
	
	function MostrarContenidoCompleto($__idcontenido__,$__template__='',$__show_all__=false) {
							 		
		 if ($this->m_CContenido!=null) {
		 	 if ($this->m_CContenido->m_id!=$__idcontenido__)
		 	 	$this->GetContenido($__idcontenido__);		   		 	 
		 }
		 
		 if (!$__show_all__ && !$this->EsValido($this->m_CContenido)) {
		 	$this->m_CContenido = null;
			ShowError("Contenido no disponible. Intente en otro momento." );			
		 }
		 	
		 if ($this->m_CContenido!=null) {		 	
		 	$this->m_CTiposContenidos->GetCompleto($this->m_CContenido);
		 	$this->m_CTiposContenidos->MostrarCompleto($this->m_CContenido , $__template__);
		 }

	}	
	
	/**
	 * Muestra por tipo, haciendo la consulta primero, luego publicando segun el termino "expansion" que puede ser:
	 * 
	 * "colapsado"
	 * "resumen"
	 * "completo"
	 * 
	 * también se puede especificar alguna consulta customizada en __filtro__ bajo la forma:
	 * ( contenidos.TITULO like '%texto%' AND contenidos.ID_CONTENIDO=123 )
	 * por ejemplo
	 *
	 * @param String $__expansion__  "colapsado","resumen","completo"
	 * @param Integer $__idseccion__  el id de la sección que contiene al contenido
	 * @param Integer $__tipo__ el id del tipo de contenido ( FICHA_SISTEMA, FICHA_XXXX, ...)
	 * @param Integer $_maxitems_  el número máximo de items de la consulta
	 * @param Integer $__excluyeaid__ el id de un contenido a excluir
	 * @param String $__template__ la plantilla customizada.... en caso de querer forzarla
	 * @param String $__limite_offset__ el indice desde donde arrancar
	 */
	function MostrarPorTipo( $__expansion__, $__idseccion__,$__tipo__,$_maxitems_='',$__excluyeaid__=-1, $__template__='', $__orden__='', $__filtro__='', $__limite_offset__="") {
			
			global $CLang;
		
			$and = '';
			if ($__excluyeaid__!=-1) {				
				if ($__filtro__!="") $and = " AND "; 
				$__filtro__.= $and.' ( contenidos.ID<>'.$__excluyeaid__.' ) ';
			}		
			
			$ids = $this->GetContenidos( $__tipo__, $__idseccion__, $__filtro__, $__orden__, $__limite_offset__, $_maxitems_ );
				
			if ( count($ids)>0 ) {		
				$c = 1;			
				
				foreach( $ids as $k=>$id ) {
					
					if ($this->m_columns>1) if ($c==1) echo $this->m_principiolinea;//empieza una linea	
					
					$CC = $this->GetContenidoCompleto($id);
					
					if ( $__expansion__=="completo" ) {
						
						$this->m_CTiposContenidos->MostrarCompleto( $CC, $__template__ );
						
					} else if ( $__expansion__=="resumen" ) {
						
						$this->m_CTiposContenidos->MostrarResumen( $CC, $__template__ );
						
					} else if ( $__expansion__=="colapsado" ) {
						
						$this->m_CTiposContenidos->MostrarColapsado( $CC, $__template__ );
						
					}					
					
					if ($this->m_columns>1) {						
						//if ($c<$this->m_columns) echo $this->m_separador;//agrega un separador
						//if ($c==$this->m_columns) { echo $this->m_finlinea; $c = 0; }//termina una linea
					}

					$c++;
				}
				//completa los faltantes, si no termino la linea
				if ((1 < $c) && ($c <= $this->m_columns)) {
					for($i=0;$i<($this->m_columns-$c);$i++) {
						//echo '<td></td>'.$this->m_separador;										
					}
					//echo '<td></td>'.$this->m_finlinea;
				}				
			} else {
				echo $CLang->Get('NORESULTS');
			}						
		
	}
	
	
	function Count($__idseccion__) {
		
		$this->m_tcontenidos->LimpiarSQL();
		$this->m_tcontenidos->FiltrarSQL('ID_SECCION','',$__idseccion__);
		$this->m_tcontenidos->Open();
		return $this->m_tcontenidos->nresultados;
		
	}
	
	function OrdenarContenido($__raiz__,$__idcontenido__=0,$__ordenar__="",$__id_tipocontenido__="") {

				$res = true;
				$iddest = 0;
				$PosContenidoDestino = 0;
				$PosContenidoFuente = 0;
				
				if ($__ordenar__=="") $__ordenar__="all";
				
				Debug("Ordenando __ordenar__:".$__ordenar__);
		
				if ($__ordenar__!="up" && $__ordenar__!="down" && $__ordenar__!="" && $__ordenar__!="all" ) {
					$ords = explode(",",$__ordenar__);

					$matches = array();
					preg_match_all( "/_(.*?)_/", $__ordenar__, $matches );
					foreach( $matches[0] as $match) {
						$match_txt = substr( $match, 1, strlen($match)-2 );
						Debug("MATCH:".$match." : ".$match_txt."<br>");
					}


					$iddest = substr( $matches[0][0], 1, strlen($matches[0][0])-2 );
					$idsource = substr( $matches[0][1], 1, strlen($matches[0][1])-2 );
					$before = true;
					//Debug("destino:".$iddest." fuente: ".$idsource." before: ".$before);
					
				}

									
				$this->m_tcontenidos->LimpiarSQL();
				$this->m_tcontenidos->FiltrarSQL('ID_SECCION','',$__raiz__);
				if ($__id_tipocontenido__!="") {
					$this->m_tcontenidos->FiltrarSQL('ID_TIPOCONTENIDO','',$__id_tipocontenido__);
				}
				$this->m_tcontenidos->OrdenSQL('contenidos.ORDEN ASC');				
				$this->m_tcontenidos->Open();
				
				$this->contenido[$__raiz__] = array();
				
				if ( $this->m_tcontenidos->nresultados>0 ) {							
							$i = 0;							
							//levantamos todas las secciones en el array de $this->rama[$__raiz__]
							while($_row_ = $this->m_tcontenidos->Fetch() ) {														
								//aqui guarda los indices
								$this->contenido[$__raiz__][$i] = new CContenido($_row_,$i+1);
								//echo "nitem:".$this->contenido[$__raiz__][$i]->m_nitem."  orden:".$this->contenido[$__raiz__][$i]->m_orden."<br>";
								if($__idcontenido__!=0) if ($this->contenido[$__raiz__][$i]->m_id==$__idcontenido__) $PosContenidoFuente = $i;
								if($iddest!=0) if ($this->contenido[$__raiz__][$i]->m_id==$iddest) $PosContenidoDestino = $i;
								$i++;								
							}
							
							if ( $__ordenar__=="up" ) {
								if ( $PosContenidoFuente > 0 ) {
									$this->contenido[$__raiz__][$PosContenidoFuente]->m_nitem--;
									$this->contenido[$__raiz__][$PosContenidoFuente-1]->m_nitem++;										
								}
							} else
							if ( $__ordenar__=="down" ) {
								if ( ($PosContenidoFuente+1) < $i ) {
									$this->contenido[$__raiz__][$PosContenidoFuente]->m_nitem++;
									$this->contenido[$__raiz__][$PosContenidoFuente+1]->m_nitem--;										
								}
							} else if ( $__ordenar__!="" && $__ordenar__!="all" ) {								
								if ($PosContenidoDestino>=0 && $PosContenidoFuente>=0) {
									
									if ($PosContenidoFuente<$PosContenidoDestino) {
										//Debug(" destino [$PosContenidoDestino] > fuente [$PosContenidoFuente]");
										///desplazamos a todos los subsisguientes al fuente, de un lugar hacia abajo
										for( $rs=($PosContenidoFuente+1); $rs<=($PosContenidoDestino-1) && $rs>=0; $rs++ ) {											
											$this->contenido[$__raiz__][$rs]->m_nitem--;
										}
										if($before) {
											///e insertamos justo antes del destino el fuente
											$this->contenido[$__raiz__][$PosContenidoFuente]->m_nitem = $this->contenido[$__raiz__][$PosContenidoDestino]->m_nitem-1;
										} else {
											///donde estaba el destino posicionamos ahora el fuente
											$this->contenido[$__raiz__][$PosContenidoFuente]->m_nitem = $this->contenido[$__raiz__][$PosContenidoDestino]->m_nitem;
											///desplazamos el destino hacia abajo
											$this->contenido[$__raiz__][$PosContenidoDestino]->m_nitem--;
										}
										
									} else if ($PosContenidoFuente>$PosContenidoDestino) {
										///desplazamos a todos los subsisguientes al destino, de un lugar hacia arriba
										//Debug(" fuente [$PosContenidoFuente] > destino [$PosContenidoDestino]");
										for( $rs=($PosContenidoDestino+1); $rs<=($PosContenidoFuente-1)  && $rs>=0; $rs++ ) {											
											$this->contenido[$__raiz__][$rs]->m_nitem++;
										}
										
										if($before) {
											///e insertamos justo antes del destino el fuente
											$this->contenido[$__raiz__][$PosContenidoFuente]->m_nitem = $this->contenido[$__raiz__][$PosContenidoDestino]->m_nitem;
											$this->contenido[$__raiz__][$PosContenidoDestino]->m_nitem++;
										} else {
											///e insertamos justo despues del destino el fuente
											$this->contenido[$__raiz__][$PosContenidoFuente]->m_nitem = $this->contenido[$__raiz__][$PosContenidoDestino]->m_nitem+1;
										}
										
									}
								}
							}							
							
							//luego los grabamos
							if ($i>0) {
								foreach($this->contenido[$__raiz__] as $contenido) {
									//Debug("tit:".$contenido->m_titulo." de orden:".$contenido->m_orden." pasa a orden:".$contenido->m_nitem."<br>");
									$this->m_tcontenidos->LimpiarSQL();
									$this->m_tcontenidos->SQL = 'UPDATE contenidos SET ORDEN='.$contenido->m_nitem.' WHERE ID='.$contenido->m_id;
									$res = $this->m_tcontenidos->EjecutaSQL();
									if (!$res) break; 																			
								}
							}
				}
				
				return $res;
			
	}

	function NbPages( $text, $intervales, $onchange ) {
		
		global $_nbpages_;
		
		if ($_nbpages_=="") $_nbpages_ = $intervales[0];		
		
		$cn = 0;
		$ech = '<input type="hidden" name="_intervalo_" value="'.$_intervalo_.'">';
		$ech.= '<select name="_nbpages_" class="_nbpages_" onchange="'.$onchange.'">';		
		if (is_array($intervales))
		foreach($intervales as $inter) {						
			$inter == $_nbpages_ ? $sel = "selected" : $sel = "";			
			$cn == 0 ? $ech.= '<option value="'.$inter.'" '.$sel.'>'.$text.'</option>' : $ech.= '<option value="'.$inter.'" '.$sel.'>'.$inter.'</option>';			
			$cn++;
		}
		$ech.= '</select>';
		
		return $ech;
	}	
	
	
	function MostrarEncabezados( $template, $nbtext = "Nb/Pages", $nbpages = array(6,12,24), $onchange = 'javascript:document.consulta.submit();',  $onpage = "") {

		global $_intervalo_;
		global $_nbpages_;
		global $__modulo__;
		
		if ($onpage=="") {
			$onpage = $_SERVER['PHP_SELF']."?";
		} else {
			if(strpos($onpage,"?")!=false) $onpage.="&";
		}
		
		if ( $_intervalo_=="" ) $_intervalo_ = 1;
		if ( $_nbpages_>0 ) $_nintervalos_ = ceil( $this->m_tcontenidos->totalitems / $_nbpages_ );
		
 		if ( $this->m_tcontenidos->nresultados > 0 ) {
			$pages = "";
			$ea = 1 + floor(($_intervalo_-1) / 5) * 5;
			$eb = min( $_nintervalos_ , $ea + 4 );			
			if ($_intervalo_ > 5 ) $pages.= '&nbsp;<a href="'.$onpage.'_intervalo_='.($ea-1).'&_nbpages_='.$_nbpages_.'&_trier_='.$_trier_.'&_affichage_='.$_affichage_.'&_recherche_='.$_recherche_.'" class="pageinter">...</a>&nbsp;';
			for($e=$ea,$or="",$br="",$c=0;$e<=$eb;$e++,$c++) {
				if ($e==$_intervalo_)
					$pages.= ''.$or.'&nbsp;<span class="pageintersel">'.$br.$e.'</span>&nbsp;';
				else
					$pages.= $or.'<a href="'.$onpage.'_intervalo_='.$e.'&_nbpages_='.$_nbpages_.'" class="pageinter">'.$br.$e.'</a>&nbsp;';
				$or = "&nbsp;";	
				$br = "";			
			}			
			if ( $_intervalo_ < ($_nintervalos_-5) && $_nintervalos_ > 5 ) $pages.= '&nbsp;<a href="'.$onpage.'_intervalo_='.$e.'&_nbpages_='.$_nbpages_.'&_trier_='.$_trier_.'&_affichage_='.$_affichage_.'&_recherche_='.$_recherche_.'" class="pageinter">...</a>&nbsp;';
 		}			

		
		return str_replace( array('{NRESULTADOS}','{FILTROPAGINAS}','{PAGINAS}') , array( $this->m_tcontenidos->totalitems, $this->NbPages( $nbtext, $nbpages, $onchange ) ,  $pages , $onchange ) , $template );
	}
	
	function Habilitar( $__idcontenido__ ) {		
		$CC = $this->GetContenido($__idcontenido__);
		if ($CC!=null) {			
			$CC->m_baja= 'S';
			$CC->ToGlobals();		
			if ( $_SESSION['nivel']==0 || $_SESSION['nivel']==1  || $_SESSION['idusuario']==$CC->m_id_usuario_creador ) {				
				return $this->m_tcontenidos->Modificari($__idcontenido__);
			}				
		}
	}

	function Deshabilitar( $__idcontenido__ ) {		
		$CC = $this->GetContenido($__idcontenido__);
		if ($CC!=null) {			
			$CC->m_baja= 'N';
			$CC->ToGlobals();				
			if ( $_SESSION['nivel']==0 || $_SESSION['nivel']==1 || $_SESSION['idusuario']==$CC->m_id_usuario_creador ) {				
				return $this->m_tcontenidos->Modificari($__idcontenido__);
			}				
		}
	}	
	
	function Actualizar( &$Contenido, $__actualizar_detalles__=true ) {
		
		if (!is_object($Contenido)) {
			DebugError("CContenidos::Actualizar > el parámetro no es un objeto válido");
			$this->PushError( new CError("CARD_UPDATE_ERROR", "Problems with card object. Not an object or not card: ")  );
			return false;
		}
		
		$_exito_ = $this->m_tcontenidos->ModificarRegistro( 
						$Contenido->m_id, $Contenido->FullArray() );
						
		if ($_exito_) {
			if ($__actualizar_detalles__) {
				$_exito_ = $this->m_CTiposContenidos->ConfirmarDetalles( "modificar", $Contenido->m_id_tipocontenido, $Contenido->m_id );
				$this->PushError( new CError("DETAIL_FIELD_ERROR", "Problems updating some details field.") );				
			}
			if ($_exito_) {
				$_exito_ = $this->ActualizarEditInfo( $Contenido->m_id, $Contenido->m_id_usuario_modificador);				 
			} else {
				ShowError("Hubo errores al guardar los detalles de la ficha: id:".$Contenido->m_id." título:".$Contenido->m_titulo );
			}
		} else {
			DebugError("CContenidos::Error modificando contenido");
			$this->PushError( new CError("CARD_UPDATE_ERROR", "Problems with some card fields:".$this->m_tcontenidos->exito) );
		}
		return $_exito_;
	}
	
	function ActualizarAutoria( $__id_contenido__, $__id_usuario__ ) {
		//actualizamos id_usuario_modificador
		//actualizamos id_usuario_creador
		//actualizamos actualizacion
		if (!is_numeric($__id_contenido__)) {
			DebugError("CContenidos::Actualizar > el parámetro __id_contenido__ no es un número");
			return false;
		}		
		
		$SQL = "UPDATE contenidos SET ID_USUARIO_CREADOR=".$__id_usuario__.",ID_USUARIO_MODIFICADOR=".$__id_usuario__.",ACTUALIZACION='NOW()' WHERE contenidos.ID=".$__id_contenido__;
		$_exito_ = $this->m_tcontenidos->EjecutaSQL($SQL);
		if (!$_exito_) {
			DebugError("CContenidos::ActualizarAutoría > no se pudo actualizar, creador, editor y fecha de actualización al contenido");
			Debug( $SQL.mysql_error());
		}
		return $_exito_;
	}
	
	function ActualizarEditInfo( $__id_contenido__ , $__id_usuario__) {
			//actualizamos id_usuario_modificador
			//actualizamos actualizacion
			$SQL = "UPDATE contenidos SET contenidos.ID_USUARIO_MODIFICADOR=".$__id_usuario__.",contenidos.ACTUALIZACION=NOW() WHERE contenidos.ID=".$__id_contenido__;
			$_exito_ = $this->m_tcontenidos->EjecutaSQL($SQL);
			if (!$_exito_) {
				DebugError("CContenidos::Actualizar > no se pudo actualizar editor y fecha de actualización al contenido");
				Debug( $SQL.mysql_error());
			}		
			return $_exito_;
	}
	
	/**
	 * Elimar un registro de la tabla contenidos teniendo en cuenta los permisos de usuario del sistema
	 * predeterminadamente se da permisos a Administradores Generales, y Administadores de Contenidos, 
	 * y al creador del contenido
	 *
	 * @param integer $__idcontenido__ id del contenido
	 * @return verdadero si lo pudo borrar o falso en otro caso
	 */
	function Eliminar( $__idcontenido__, $__eliminar_detalles__=true, $__eliminar_relaciones__=true,$__reordenar_contenido__=true ) {
		
		global $CLang;
		
		Debug( $CLang->Get("DELETINGCARD")." : ".$__idcontenido__ );
		
		$CC = $this->GetContenido($__idcontenido__);
		if (is_object($CC)) $idseccion = $CC->m_id_seccion;
		
		if (is_object($CC)) {			
			
			//ATENCION: no se pueden elminar los contenidos de fichas de sistema y ficha void
			if ($__idcontenido__==1 || $__idcontenido__==2) {
				$this->PushError( new CError("PERMISSION_NOT_GRANTED", "You are not allowed to modify this record. System card protected.") );
				return false;
			}
			
			$exito = $this->EliminarSubcontenidos( $__idcontenido__ );
			
			if ($exito) {
				///chequeamos si tiene contenidos dependientes (si es sub-contenido)
				$this->m_tcontenidos->LimpiarSQL();
				$this->m_tcontenidos->FiltrarSQL('ID_CONTENIDO','', $__idcontenido__);
				$this->m_tcontenidos->Open();
				if ($this->m_tcontenidos->nresultados>0) {
					$_R_ = $this->m_tcontenidos->Fetch();
					$Dependent = new CContenido($_R_); 
					DebugError("CContenidos::Eliminar PERMISSION_NOT_GRANTED : subcontent dependence");
					$this->PushError( new CError("PERMISSION_NOT_GRANTED", "You are not allowed to modify this record. Other content depends on it. ex: ".$Dependent->Titulo()) );
					return false;
				}
				
				if (	$_SESSION['nivel']==0 ||  //admin 
						$_SESSION['nivel']==1 ||  //editor
						$_SESSION['idusuario']==$CC->m_id_usuario_creador //owner 
						) {				
					$exito = $this->m_tcontenidos->Borrari($__idcontenido__);
					///reordenamos la seccion
					if ($__reordenar_contenido__ &&  is_numeric($idseccion)  && $idseccion>0) 
						$this->OrdenarContenido($idseccion);
					
					if ( $__eliminar_detalles__ && $exito ) {
						$exito = $this->EliminarDetalles( $__idcontenido__ );
					} else DebugError("CContenidos::Eliminar ELIMINAR CONTENIDO FALLO");
					if ( $__eliminar_relaciones__ && $exito ) {
						$exito = $this->EliminarRelaciones( $__idcontenido__ );
					} else DebugError("CContenidos::Eliminar ELIMINAR DETALLES");
					return $exito;
				} else {
					DebugError("CContenidos::Eliminar PERMISSION_NOT_GRANTED");
					$this->PushError( new CError("PERMISSION_NOT_GRANTED", "You are not allowed to modify this record.") );
					return false;
				}			
			} else DebugError("CContenidos::Eliminar ELIMINAR SUBCONTENIDOS");
		} else {
			DebugError("CContenidos::Eliminar RECORD_NOT_FOUND");
			$this->PushError( new CError("RECORD_NOT_FOUND", "Record id <$__idcontenido__> not found." ) );
			return false;
		}
	}
	
	function EliminarDetalles( $__id_contenido__ ) {
		return $this->m_CTiposContenidos->EliminarDetalles( $__id_contenido__ );		
	}
	
	function EliminarRelaciones( $__id_contenido__ ) {
		if (is_object($this->m_CRelaciones)) {
			return $this->m_CRelaciones->EliminarRelaciones( $__id_contenido__ );	
		} else {
			DebugError("CContenidos::m_CRelaciones no existe");
			return false;
		}
	}
	
	function EliminarSubcontenidos( $__id_contenido__ ) {
		$SQL = "DELETE FROM contenidos WHERE contenidos.ID_CONTENIDO=".$__id_contenido__;
		$_exito_ = $this->m_tcontenidos->EjecutaSQL($SQL);
		if (!$_exito_) {
			DebugError("CContenidos::EliminarSubcontenidos > no se pudo eliminar los sub-contenidos del contenido id: ".$__id_contenido__);
			Debug( $SQL.mysql_error());
			$this->PushError( new CError("ERROR", "No se pudo eliminar los sub-contenidos.") );
		}	
		return $_exito_;		
	}
	
	function EditPreprocess( $__tipocontenido__, &$tpl ) {
		
		$_e_ID = $GLOBALS['_e_ID'];

		$tpl = str_replace( "*ID*", $_e_ID, $tpl );
		$tpl = str_replace( "*IDTIPOCONTENIDO*", $__tipocontenido__, $tpl );
		
	}
	
	function GetMinMax( $__tipocontenido__, $nombre ) {
		
		$nombre = strtolower($nombre);

		$min = ""; //aun no
		$max = "";
		
		if ($this->m_CTiposContenidos->m_templatesedicion[$__tipocontenido__]) {
			$min = $this->m_CTiposContenidos->m_templatesedicion[$__tipocontenido__]["min".$nombre];
			$max = $this->m_CTiposContenidos->m_templatesedicion[$__tipocontenido__]["max".$nombre];
		}
		return array($min, $max);
		
	}

	function GetHtml( $__tipocontenido__, $nombre ) {
		
		$nombre = strtolower($nombre);

		$html = "";
		
		if ($this->m_CTiposContenidos->m_templatesedicion[$__tipocontenido__]) {
			$html = $this->m_CTiposContenidos->m_templatesedicion[$__tipocontenido__]["html".$nombre];
		}
		return $html;
		
	}
	
	
	function Edit( $__tipocontenido__ , $__CLang__="", $__CMultilang__="", $lang="", $form_name="" ) {
	
		global $CMultiLang;
		global $CLang;
		
		if ($__CLang__=="") $__CLang__ = $CLang;
		if ($__CMultilang__=="") $__CMultilang__ = $CMultiLang;
		
		$tpl = "<!--EDIT SECTION-->";
		$tpl.= $this->m_CTiposContenidos->m_templatesedicion[$__tipocontenido__]["template"];
		$maxtitulo = $this->m_CTiposContenidos->m_templatesedicion[$__tipocontenido__]["maxtitulo"];
		$maxcopete = $this->m_CTiposContenidos->m_templatesedicion[$__tipocontenido__]["maxcopete"];
		$maxcuerpo = $this->m_CTiposContenidos->m_templatesedicion[$__tipocontenido__]["maxcuerpo"];
		$htmlcopete = $this->m_CTiposContenidos->m_templatesedicion[$__tipocontenido__]["htmlcopete"];
		$htmlcuerpo = $this->m_CTiposContenidos->m_templatesedicion[$__tipocontenido__]["htmlcuerpo"];
		
		if (trim($form_name)!="") $form_name_point = trim($form_name)."."; else  $form_name_point="";
		
		$this->EditPreprocess(  $__tipocontenido__, $tpl );
		
		//echo $htmlcopete;
		//echo $htmlcuerpo;
		//translate
		$__CMultilang__->Translate( $tpl );
		$nn = 0;
		$cola = "";
		foreach($this->m_tcontenidos->campos as $nombre=>$campo) {
			
			if ( ! ( strpos( $tpl, $nombre ) === false )) {
				//editar campo
				list($min, $max) = $this->GetMinMax( $__tipocontenido__, $nombre );
				$html = $this->GetHtml($__tipocontenido__, $nombre);									
				
				$cceedit = $this->m_tcontenidos->EditarCampoStr($nombre,'','',$lang, $form_name, $min, $max, $html);
				/*
				if ($campo['tipo']=='BLOBTEXTO') 
					if ($htmlcopete=="html" && $nombre=="COPETE") $cceedit.= '<script> setForm(\''.$form_name.'\'); textareaEdit( \''.$form_name_point.'_e_'.$nombre.'\',\'\' ); </script>';
					if ($htmlcuerpo=="html" && $nombre=="CUERPO") $cceedit.= '<script> setForm(\''.$form_name.'\'); textareaEdit( \''.$form_name_point.'_e_'.$nombre.'\',\'\' ); </script>';
			s	*/
				//multidioma		
				if ( ($campo['tipo']=='TEXTOML' || $campo['tipo']=='BLOBTEXTOML')) {
					if ($__CMultilang__->Activo()) {
						foreach( $__CMultilang__->m_arraylangs as $idioma=>$codigo) {
							
							$cceedit.= '<div id="did'.$codigo.'_'.$nombre.'" class="did'.$codigo.'"><img src="../../inc/images/flags/'.$codigo.'.jpg" width="21" height="11" border="0">';														
							$cceedit.= $this->m_tcontenidos->EditarCampoStr( $nombre, '', '', $codigo, $form_name, $min, $max, $html );							
							$cceedit.= '</div>';
							
							//if ($campo['tipo']=='BLOBTEXTOML') {
							//	if ($htmlcopete=="html" && $nombre=="ML_COPETE") $cceedit.= '<script> setForm(\''.$form_name.'\'); textareaEdit( \''.$form_name_point.'_e_'.$nombre.'\',\''.$codigo.'\' ); </script>';
							//	if ($htmlcuerpo=="html" && $nombre=="ML_CUERPO") $cceedit.= '<script> setForm(\''.$form_name.'\'); textareaEdit( \''.$form_name_point.'_e_'.$nombre.'\',\''.$codigo.'\' ); </script>';
							//}
							
							
						}
					}				
				}			
				
				//replace
				$tpl = str_replace( "*".$nombre."*", $cceedit, $tpl );
			}
			
		}
		$tpl.= $cola."<!--FIN EDIT SECTION-->";		
		return $tpl;
	}	
}
?>
