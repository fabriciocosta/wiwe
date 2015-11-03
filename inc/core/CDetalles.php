<?php

/**
 * class CDetalles
 *
 5.4 24/07/2007 : agregado en Edit, configuracion de campos Texto, si con editor html o no...
 * 5.3 18/06/2007  agregado textareaEdit2... para fichas no de sistema
 * 5.2 14/06/2007 arreglado bugs de Galeria y filtrado HTML...ver CTabla
 * 5.1 25/05/2007 optimizado Reference
 * 5.0 25/05/2007 agregado Edit() y areglaod Reference y Maletin y Gallery y edicion de archivos con Link...
 * @version 4.0 18/07/2006
 * @copyright 2004 
 **/

 
class CDetalles extends CErrorHandler {

	var $m_tdetalles;//tabla contenidos
	var $m_CTiposDetalles;//miembro de la clase CTiposContenidos
	//var $m_CErrores;
	
	function CDetalles(&$__tdetalles__,&$__m_CTiposDetalles__) {
		$this->m_class = "CDetalles";
		$this->Set( $__tdetalles__,  $__m_CTiposDetalles__ );
	}
	
	function Set(&$__tdetalles__,&$__m_CTiposDetalles__) {
		$this->m_CTiposDetalles = &$__m_CTiposDetalles__;
		$this->m_tdetalles = &$__tdetalles__;
		parent::CErrorHandler();
	}
	
	function Actualizar( &$__CDetalle__ ) {
		return $this->m_tdetalles->ModificarRegistro( 
						$__CDetalle__->m_id, $__CDetalle__->FullArray() );
	}	

	function EliminarDetalles( $__id_contenido__, $__force__=true ) {
		$SQL = "DELETE FROM detalles WHERE detalles.ID_CONTENIDO=".$__id_contenido__;
		$_exito_ = $this->m_tdetalles->EjecutaSQL($SQL);
		if (!$_exito_) {
			DebugError("CDetalles::EliminarDetalles > no se pudo eliminar los detalles del contenido id: ".$__id_contenido__);
			Debug( $SQL.mysql_error());
		}
		return $_exito_;			
	}
	
	function MostrarDetallesColapsados( $__idcontenido__, &$__template__) {
		$this->MostrarDetalles( $__idcontenido__, $__template__ );
	}
	
	function MostrarDetalles( $__idcontenido__, &$__template__) {

		//reconocer los tipos de detalles en $__template__
		//reemplazar **TIPODETALLE** por Detalle
		
		$matches = array();
		$sep = "";
    $tdetalles = "(";		

		/*FUNCTIONS*/
		preg_match_all( "/(\*\#)(.*?)(\#\*)/", $__template__, $matches );
		//echo "<pre>".	print_r($matches,true)."</pre>";

		foreach( $matches[0] as $k=>$match) {
			//$match_txt = substr( $match[0], 1, strlen($match)-2 );
			$tipodet = $matches[2][$k];
			//$parameters = $matches[2][$k];
			//echo "<br>MATCH:".$match." => TIPO: ".$tipodet."<br>";
			$idtdetalle = $this->m_CTiposDetalles->GetTipoEntero($tipodet);
			if (is_numeric($idtdetalle) && $idtdetalle>0 ) {
					$tdetalles.= $sep.$idtdetalle;
					//echo "VARIABLE: (".$tipodet.") id_tipodetalle ( ".$idtdetalle." )<br>";
					$sep = ",";				
			}			

		}		
		$tdetalles.= ")";
		

		if ($tdetalles=="()") {
			$__template__ = str_replace( "*DETALLES*", "", $__template__);
			return;
		}
		
		
		$td = $this->m_tdetalles;

		//PARA LOS QUE NO TIENEN REFERENCIAS A OTROS CONTENIDOS:
		$td->QuitarReferencias();
		$td->AgregarReferencias( array("tiposdetalles.TIPOCAMPO"),
												array("tiposdetalles"),
												array("tiposdetalles.TIPOCAMPO NOT LIKE 'RC'",
														"tiposdetalles.TIPOCAMPO NOT LIKE 'R'",
													  	"tiposdetalles.TIPOCAMPO NOT LIKE 'RS'",
														"tiposdetalles.TIPOCAMPO NOT LIKE 'H'") );
		$td->LimpiarSQL();			
	  $td->FiltrarSQL('ID_CONTENIDO','/*SPECIAL*/ ID_TIPODETALLE IN '.$tdetalles, $__idcontenido__);
	  $td->Open();
	  
	  //echo "<!--";
	  //echo "CDetalles->MostrarDetallesColapsados() ".$this->m_tdetalles->SQL." : nresultados: ".$this->m_tdetalles->nresultados;
	  //echo "-->";		
		
	  $detalles_a = array();
		$i_a = 0;
		if ( $td->nresultados>0 ) {		
			while($_row_ = $td->Fetch() ) {				
				$detalles_a[$i_a] = new CDetalle($_row_);
				$i_a++;
			}
		}		 
		
		for( $i_a = 0; $i_a< count($detalles_a); $i_a++) {
				$CDetalle = $detalles_a[$i_a];
				$mdetalle = $this->m_CTiposDetalles->Mostrar($CDetalle);
				$__template__ = str_replace( "*#".$this->m_CTiposDetalles->GetTipoStr($CDetalle->m_id_tipodetalle)."#*", $mdetalle, $__template__);
				//THUMBNAILS
				if ( ($this->m_CTiposDetalles->GetTipoCampo($CDetalle->m_id_tipodetalle)=="I") 
				|| ($this->m_CTiposDetalles->GetTipoCampo($CDetalle->m_id_tipodetalle)=="F")) {					
					if (basename($mdetalle)=="spacer.gif")
						$thm = $mdetalle;
					else $thm = dirname($mdetalle)."/thm/".basename($mdetalle);
					$__template__ = str_replace( "*#".$this->m_CTiposDetalles->GetTipoStr($CDetalle->m_id_tipodetalle)."_THUMB#*", $thm, $__template__);
				}
			
			
		}	 

		//ESPECIAL PARA CONTENIDOS REFERENCIADOS A PARTIR DEL CAMPO detalles.ENTERO "RC"
		$td->QuitarReferencias();
		$td->AgregarReferencias( array("tiposdetalles.TIPOCAMPO","tiposdetalles.TIPO","REFERENCIA.*"),
												array("tiposdetalles","contenidos REFERENCIA"),
												array("( tiposdetalles.TIPOCAMPO LIKE 'RC' OR tiposdetalles.TIPOCAMPO LIKE 'R' )",
														"REFERENCIA.ID=detalles.ENTERO") );
		$td->LimpiarSQL();			
	    $td->FiltrarSQL('ID_CONTENIDO','/*SPECIAL*/ ID_TIPODETALLE IN '.$tdetalles,$__idcontenido__);
	    $td->Open();
				
	  //echo "<!--";
	  //echo "CDetalles->MostrarDetallesColapsados()<br>".$this->m_tdetalles->SQL."<br>nresultados: ".$this->m_tdetalles->nresultados;
	  //echo "-->";	
	  	    
		if ( $td->nresultados>0 ) {		
			while($_row_ = $td->Fetch() ) {
				$CDetalle = new CDetalle($_row_);		
				$mdetalle = $this->m_CTiposDetalles->Mostrar($CDetalle);
				/*echo " => [".$mdetalle."]";*/
				$__template__ = str_replace( "*#".$this->m_CTiposDetalles->GetTipoStr($CDetalle->m_id_tipodetalle)."#*", $mdetalle, $__template__);

			}
		}

		//ESPECIAL PARA SECCIONES REFERENCIADAS A PARTIR DEL CAMPO detalles.ENTERO "RS"
		$td->QuitarReferencias();
		$td->AgregarReferencias( array("tiposdetalles.TIPOCAMPO","tiposdetalles.TIPO","REFERENCIA.*"),
									array("tiposdetalles","secciones REFERENCIA"),
									array("( tiposdetalles.TIPOCAMPO LIKE 'RS' OR tiposdetalles.TIPOCAMPO LIKE 'H' )","REFERENCIA.ID=detalles.ENTERO") );
		$td->LimpiarSQL();
		$td->FiltrarSQL('ID_CONTENIDO','/*SPECIAL*/ ID_TIPODETALLE IN '.$tdetalles,$__idcontenido__);
		$td->Open();
		if ( $td->nresultados>0 ) {
			while($_row_ = $td->Fetch() ) {
				$CDetalle = new CDetalle($_row_);
				$mdetalle = $this->m_CTiposDetalles->Mostrar($CDetalle);
				$__template__ = str_replace( "*#".$this->m_CTiposDetalles->GetTipoStr($CDetalle->m_id_tipodetalle)."#*", $mdetalle, $__template__);
		
			}
			}		
		$td->QuitarReferencias();
		
	}
	
	function GetDetalle( $_id_detalle_ ) {
		
		$this->m_tdetalles->LimpiarSQL();			
	    $this->m_tdetalles->FiltrarSQL('ID','',$_id_detalle_);
	    $this->m_tdetalles->Open();		
		
		if ( $this->m_tdetalles->nresultados>0 ) {		
			$_row_ = $this->m_tdetalles->Fetch($this->m_tdetalles->resultados);
			$detalle = new CDetalle($_row_);
			return $detalle;			
		}	
		
		return null;	
				
	}
	
	function IsValid( &$__CDetalle__, &$__CContenido__ ) {
		
		$valid = false;
		
		$valid = $this->m_CTiposDetalles->IsValid( $__CDetalle__, $__CContenido__->m_id_tipocontenido );
		
		/*
			aquí le faltaría chequear si los contenidos son validos....??
			o mejor dicho si este detalle es o no un duplicado... en la tabla tdetalles
		*/
		
		return $valid;
	}
	
	function NuevosDetalles( $__id_tipocontenido__, &$__CContenido__ ) {
		
		$detalles = array();
		$_td = $this->m_CTiposDetalles->m_ttiposdetalles;
		
		//por cada tipodetalle que sea de tipocontenido...
		$_td->LimpiarSQL();
		$_td->FiltrarSQL('ID_TIPOCONTENIDO','', $__id_tipocontenido__ );
		$_td->Open();	
		
		if ( $_td->nresultados>0 ) {		
			while($_row_ = $_td->Fetch() ) {
				
				$CTipoDetalle = new CTipoDetalle($_row_);
				
				$CDetalle = new CDetalle();	
				$CDetalle->m_id_tipodetalle = $CTipoDetalle->m_id;
				$CDetalle->m_id_contenido = $__CContenido__->m_id;		
				$CDetalle->m_id_usuario_creador = $__CContenido__->m_id_usuario_creador;
				$CDetalle->m_id_usuario_modificador = $__CContenido__->m_id_usuario_modificador;
				$CDetalle->m_baja = 'S';
								
				$detalles[$CTipoDetalle->m_tipo] = $CDetalle;					
			}
		}
		return $detalles;
	}
	
	/**
	 * Crea los detalles desde el mismo objeto padre de CContenido->m_detalles[]
	 * o vacíos (valores genéricos o predeterminados)
	 * o bien de los valores globales de los detalles editados por el usuario
	 *
	 * modifica la variable global exito
	 * 
	 * @param CContenido $__CContenido__
	 * @return el array de detalles creados o falso si ocurrio un error
	 */
	function CrearDetallesCompletos( &$__CContenido__ ) {
		
		global $_exito_;
		
		$_exito_ = true;		
		$detalles = array();
		$_td = $this->m_CTiposDetalles->m_ttiposdetalles;
		
		
		//por cada tipodetalle que sea de tipocontenido...
		$_td->LimpiarSQL();
		$_td->FiltrarSQL('ID_TIPOCONTENIDO','',$__CContenido__->m_id_tipocontenido);
		$_td->Open();	
		
		if ( $_td->nresultados>0 ) {		
			while($_row_ = $_td->Fetch() ) {
				
				$CTipoDetalle = new CTipoDetalle($_row_);
				$CDetalle = new CDetalle();
				if ( isset( $__CContenido__->m_detalles[$CTipoDetalle->m_tipo] ) ) {
					//Debug("CrearDetallesCompletos::Asignando __CContenido__->m_detalles");
					$CCustom = $__CContenido__->m_detalles[$CTipoDetalle->m_tipo];
					$CDetalle->m_detalle = $CCustom->m_detalle;
					$CDetalle->m_ml_detalle = $CCustom->m_ml_detalle;
					$CDetalle->m_txtdata = $CCustom->m_txtdata;
					$CDetalle->m_ml_txtdata = $CCustom->m_ml_txtdata;
					$CDetalle->m_id_usuario_creador = $CCustom->m_id_usuario_creador;
					$CDetalle->m_id_usuario_modificador = $CCustom->m_id_usuario_modificador;
					$CDetalle->m_fraccion = $CCustom->m_fraccion;
					$CDetalle->m_entero = $CCustom->m_entero;
				} else {
					//Debug("CrearDetallesCompletos:: detalle vacío ".$CTipoDetalle->m_tipo);
					$CDetalle->m_detalle = "";
					$CDetalle->m_ml_detalle = "";
					$CDetalle->m_txtdata = "";
					$CDetalle->m_ml_txtdata = "";
					$CDetalle->m_fraccion = 0;
					$CDetalle->m_entero = 0;	
				}
				
				$CDetalle->m_id_tipodetalle = $CTipoDetalle->m_id;
			
				$_exito_ = $this->CrearDetalleCompleto( $__CContenido__, $CDetalle);
			
				if ($_exito_) {
					//Debug( "CrearDetallesCompletos::".$this->m_tdetalles->exito. " : ".$this->m_tdetalles->SQL);
					$detalles[$CTipoDetalle->m_tipo] = $CDetalle;
				} else {
					DebugError("CrearDetallesCompletos:: Detalle no se inserto : ".$CTipoDetalle->m_tipo);
					break;
				}
			}
		}
		if ($_exito_)
			return $detalles;
		
		return false;
		
	}
	
	/**
	 * Crea un detalle completo para ese contenido
	 *
	 * @param unknown_type $__CContenido__
	 * @param unknown_type $__CDetalle__
	 * @return unknown
	 */
	function CrearDetalleCompleto( &$__CContenido__, &$__CDetalle__ ) {
		
		///falta chequear si el id_tipodetalle es válido!!!
		if ( ! $this->IsValid( $__CDetalle__, $__CContenido__)) {
			DebugError("CDetalles::CrearDetalleCompleto > Detalle no es válido");
			return false;
		} 
		$__CDetalle__->m_id_contenido = $__CContenido__->m_id;
		$__CDetalle__->m_id_usuario_creador = $__CContenido__->m_id_usuario_creador;
		$__CDetalle__->m_id_usuario_modificador = $__CContenido__->m_id_usuario_modificador;
		$__CDetalle__->m_baja = 'S';
		
		$reg = 	array(	'ENTERO'=>$__CDetalle__->m_entero,
						'FRACCION'=>$__CDetalle__->m_fraccion,
						'ID_CONTENIDO'=>$__CDetalle__->m_id_contenido,
						'ID_TIPODETALLE'=>$__CDetalle__->m_id_tipodetalle,
						'DETALLE'=>$__CDetalle__->m_detalle,
						'ML_DETALLE'=>$__CDetalle__->m_ml_detalle,
						'TXTDATA'=>$__CDetalle__->m_detalle, 
						'ML_TXTDATA'=>$__CDetalle__->m_ml_detalle,
						'BINDATA'=>'');
		
		$_exito_ = $this->m_tdetalles->InsertarRegistro( $reg );
				
		if ($_exito_) {
			$__CDetalle__->m_id = $this->m_tdetalles->lastinsertid;
			return true;
		} else return false;
	}
	
	function GetDetallesColapsados( $__idcontenido__ ) {
		
		$detalles = array();
		
		$this->m_tdetalles->LimpiarSQL();	
	    $this->m_tdetalles->FiltrarSQL('ID_CONTENIDO','',$__idcontenido__);
	    $this->m_tdetalles->Open();		
		
		if ( $this->m_tdetalles->nresultados>0 ) {		
			while($_row_ = $this->m_tdetalles->Fetch($this->m_tdetalles->resultados) ) {
				$detalle = new CDetalle($_row_);
				$detalles[$this->m_CTiposDetalles->GetTipoStr($detalle->m_id_tipodetalle)] = $detalle;
			}
		}	
		$this->m_tdetalles->Close();
		
		return $detalles;	
	}
	
	function GetDetallesCompletos( $__idcontenido__ ) {
		return $this->GetDetallesColapsados( $__idcontenido__ );
	}

	function GetDetallesResumenes( $__idcontenido__ ) {
		return $this->GetDetallesColapsados( $__idcontenido__ );
	}
	
	function MostrarColapsados($__idcontenido__,$__excluyeaid__=-1) {
					
			echo '<tr><td height="2"><img src="'.$_DIR_SITEABS.'/inc/images/spacer.gif" height="10"></td></tr>';	
					
			$this->m_tdetalles->LimpiarSQL();
			//$GLOBALS['_f_ID_CONTENIDO'] = $__idcontenido__;			
			if ($__excluyeaid__>=1) $this->m_tdetalles->FiltrarSQL('ID_CONTENIDO','/*SPECIAL*/detalles.ID<>'.$__excluyeaid__,$__idcontenido__);
			else $this->m_tdetalles->FiltrarSQL('ID_CONTENIDO','',$__idcontenido__);
			$this->m_tdetalles->Open();		
				
			if ( $this->m_tdetalles->nresultados>0 ) {
			
				while($_row_ = $this->m_tdetalles->Fetch($this->m_tdetalles->resultados) ) {
					//$_CContenido_ = new CContenido($_row_);
					//$this->m_CTiposContenidos->Mostrar($_CContenido_);
					$this->m_CTiposDetalles->Mostrar((new CDetalle($_row_)) );
				}
			}						
	}

	function MostrarColapsadosPorTipo($__idcontenido__,$__tipo__,$__excluyeaid__=-1) {
					
			echo '<tr><td height="2"><img src="'.$_DIR_SITEABS.'/inc/images/spacer.gif" height="10"></td></tr>';	
					
			$this->m_tdetalles->LimpiarSQL();
			//$GLOBALS['_f_ID_TIPODETALLE'] = $__tipo__;			
			$this->m_tdetalles->FiltrarSQL('ID_TIPODETALLE','',$__tipo__);			
			//$GLOBALS['_f_ID_SECCION'] = $__idseccion__;			
			if ($__excluyeaid__>=1) {				
				$this->m_tdetalles->FiltrarSQL('ID_CONTENIDO','/*SPECIAL*/detalles.ID<>'.$__excluyeaid__,$__idseccion__);
			} else $this->m_tdetalles->FiltrarSQL('ID_SECCION','',$__idseccion__);
			//echo $this->m_tcontenidos->SQL;
			$this->m_tdetalles->Open();		
							
			if ( $this->m_tdetalles->nresultados>0 ) {
			
				while($_row_ = $this->m_tdetalles->Fetch($this->m_tdetalles->resultados) ) {
					//$_CContenido_ = new CContenido($_row_);
					//$this->m_CTiposContenidos->Mostrar($_CContenido_);
					$this->m_CTiposDetalles->Mostrar((new CDetalle($_row_)) );
				}
			}						
	}

	function MostrarDetalleColapsado($__iddetalle__) {
					
			echo '<tr><td height="2"><img src="'.$_DIR_SITEABS.'/inc/images/spacer.gif" height="10"></td></tr>';	
					
			$this->m_tdetalles->LimpiarSQL();
			//$GLOBALS['_f_ID'] = $__iddetalle__;			
			$this->m_tdetalles->FiltrarSQL('ID','',$__iddetalle__);
			$this->m_tdetalles->Open();		
				
			if ( $this->m_tdetalles->nresultados>0 ) {
				while($_row_ = $this->m_tdetalles->Fetch($this->m_tdetalles->resultados) ) {
					//$_CContenido_ = new CContenido($_row_);
					//$this->m_CTiposContenidos->Mostrar($_CContenido_);
					$this->m_CTiposDetalles->Mostrar((new CDetalle($_row_)) );
				}
				
			}						
			
	}	
	
	function MostrarDetalleResumen($__iddetalle__) {
					
			echo '<tr><td height="2"><img src="'.$_DIR_SITEABS.'/inc/images/spacer.gif" height="10"></td></tr>';	
					
			$this->m_tdetalles->LimpiarSQL();
			//$GLOBALS['_f_ID'] = $__iddetalle__;			
			$this->m_tdetalles->FiltrarSQL('ID','',$__iddetalle__);
			$this->m_tdetalles->Open();		
				
			if ( $this->m_tdetalles->nresultados>0 ) {
				while($_row_ = $this->m_tdetalles->Fetch($this->m_tdetalles->resultados) ) {
					//$_CContenido_ = new CContenido($_row_);
					//$this->m_CTiposContenidos->Mostrar($_CContenido_);
					$this->m_CTiposDetalles->MostrarResumen((new CDetalle($_row_)) );
				}
				
			}						
			
	}	
		
	function MostrarDetalleCompleto($__iddetalle__) {
					
			echo '<tr><td height="2"><img src="'.$_DIR_SITEABS.'/inc/images/spacer.gif" height="10"></td></tr>';	
					
			$this->m_tdetalles->LimpiarSQL();
			if ($__iddetalle__) {
				//$GLOBALS['_f_ID'] = $__iddetalle__;
				$this->m_tdetalles->FiltrarSQL('ID','',$__iddetalle__);
				$this->m_tdetalles->Open();		
					
				if ( $this->m_tdetalles->nresultados>0 ) {
					while($_row_ = $this->m_tdetalles->Fetch($this->m_tdetalles->resultados) ) {
						//$_CContenido_ = new CContenido($_row_);
						//$this->m_CTiposContenidos->Mostrar($_CContenido_);
						$this->m_CTiposDetalles->MostrarCompleto((new CDetalle($_row_)) );
					}
					
				}						
			}	
	}	
	
	function SubContentBrowse( &$Contenidos, $_id_contenido_, $_id_detalle_, $_id_tipodetalle_ ) {
		global $_accion_;
		global $_ordenar_;
		global $_id_subcontenido_;
		global $__lang__;
		global $lang;
		global $CLang;
		global $CMultiLang;
		global $delete;
		global $CNew;
		
		global $Admin;
		global $Sitio;
		
		///normal content browse like dmin...
		///el conjunto de divs con cada contenido, escondido, listo para ser abierto, editado y confirmado
		if ($_id_contenido_=="") return;
		if ($_id_detalle_=="") return;
		if ($_id_tipodetalle_=="") return;
		

		///Contenido Padre
		$Contenido = $Contenidos->GetContenido($_id_contenido_);
		
		///Tipo de detalle asociado al padre
		$TipoDetalle = $this->m_CTiposDetalles->GetTipoDetalle($_id_tipodetalle_);
		$ADataDef = XData2Array($TipoDetalle->m_txtdata);
		
		///Tipo de Contenido del sub-contenido a crear
		$idtipocontenido = TiposParsing( $ADataDef['id_tipocontenido']['values'] );
		
		$html_copete = $ADataDef['copete']['values'];
		$html_cuerpo = $ADataDef['cuerpo']['values'];
		
		if ($html_copete=="" || !isset($html_copete)) $html_copete = "txt";
		if ($html_cuerpo=="" || !isset($html_cuerpo)) $html_cuerpo = "txt";
		
		$resstr = "";
		$TipoContenido = $Contenidos->m_CTiposContenidos->GetTipoContenido($idtipocontenido);
		
		echo  '<body style="background-color:#FFFFFF;">';
		echo "<!--__lang__:".$__lang__."-->";
		echo "<!--lang:".$lang."-->";
		//echo  "<br>id_contenido:".$_id_contenido_;
		//echo  "<br>id_detalle:".$_id_detalle_;
		//echo  "<br>id_tipodetalle:".$_id_tipodetalle_."";
		
		if ($_accion_=="nuevo") {
			//echo "nuevo";
			$CNew = new CContenido();
			/*
			Debug( "CDetalles::SubContentBrowse CNew:".$CNew->m_titulo );
			foreach($CNew->FullArray() as $k=>$v) {
				echo "k:".$k." v:".$v."<br>";
			}*/
			if ( is_object($Admin) && is_object($Admin->UsuarioAdmin)) {
				$CNew->m_id_usuario_creador = $Admin->UsuarioAdmin->m_id;
				$CNew->m_id_usuario_modificador = $Admin->UsuarioAdmin->m_id;
			} else if ( is_object($Sitio) && is_object($Sitio->Usuarios->m_CSesionUsuario) ) {
				$CNew->m_id_usuario_creador = $Sitio->Usuarios->m_CSesionUsuario->m_id;
				$CNew->m_id_usuario_modificador = $Sitio->Usuarios->m_CSesionUsuario->m_id;
			} else {
				DebugError( "CDetalles::SubContentBrowse error no hay usuario en sesion" );
			}
			//$CNew->m_orden = 10000000; ///a lo ultimo
			
		
			//$exi = $Contenidos->m_tcontenidos->InsertarRegistro($CNew->FullArray());
			///crea el contenido sin crear los detalles
			$CNew = $Contenidos->CrearContenidoCompleto( "", $CNew, false, true );
			
			if (is_object($CNew)) {				
				$CNew = $Contenidos->GetContenidoCompleto($CNew->m_id);
				if (file_exists("../../inc/include/adminpostprocess_subcontent.php")) {
					require "../../inc/include/adminpostprocess_subcontent.php";
				}
				
			} else DebugError("CDetalles::SubContentBrowse error creando registro");
			
		} else if ($_accion_=="modificar") {
			//echo "modificar";
			//echo "usid:".$GLOBALS['_e_ID_USUARIO_MODIFICADOR'];
			$CNew = new CContenido();
			$CNew->m_id = $GLOBALS['_id_subcontenido_'];
			//echo $CNew->m_id;
			/* 
			echo "usid:".$CNew->m_id_usuario_modificador;
			foreach($CNew->FullArray() as $k=>$v) {
				echo "k:".$k." v:".$v."<br>";
			}*/

			if ( is_object($Admin) && is_object($Admin->UsuarioAdmin)) {
				$CNew->m_id_usuario_creador = $Admin->UsuarioAdmin->m_id;
				$CNew->m_id_usuario_modificador = $Admin->UsuarioAdmin->m_id;
			} else if ( is_object($Sitio) && is_object($Sitio->Usuarios->m_CSesionUsuario) ) {
				$CNew->m_id_usuario_creador = $Sitio->Usuarios->m_CSesionUsuario->m_id;
				$CNew->m_id_usuario_modificador = $Sitio->Usuarios->m_CSesionUsuario->m_id;
			} else {
				DebugError( "CDetalles::SubContentBrowse error no hay usuario en sesion" );
			}
			
			
			if ($delete) {
				/*
				 *$exi = $Contenidos->m_tcontenidos->Borrari( $CNew->m_id );
				$exi = $Contenidos->m_tcontenidos->ModificarRegistro( $CNew->m_id, $CNew->FullArray() );
				*/
				$exi = $Contenidos->Eliminar( $CNew->m_id );
				
				if ($exi) {
					//$Contenidos->m_CTiposContenidos->ConfirmarDetalles(  "borrar", $CNew->m_id_tipocontenido, $CNew->m_id );
					//$Contenidos->OrdenarContenido( $CNew->m_id_seccion, 0, "", $CNew->m_id_tipocontenido );
				} else ShowError("CDetalles::SubContentBrowse() error borrando registro");				
			} else {
				//$exi = $Contenidos->m_tcontenidos->ModificarRegistro( $CNew->m_id, $CNew->FullArray() );
				$exi = $Contenidos->Actualizar( $CNew );
				
				if ($exi) {
					//$Contenidos->m_CTiposContenidos->ConfirmarDetalles(  $_accion_, $CNew->m_id_tipocontenido, $CNew->m_id );
					//$Contenidos->OrdenarContenido( $CNew->m_id_seccion, $CNew->m_id, "", $CNew->m_id_tipocontenido );
					if (file_exists("../../inc/include/adminpostprocess_subcontent.php")) {
						require "../../inc/include/adminpostprocess_subcontent.php";
					}
				} else ShowError("CDetalles::SubContentBrowse() error modificando registro");
			}
			//echo "exi:".$exi;
		} else if ($_accion_=="ordenar") {
			//echo  "ordenando:".$_ordenar_;
			$Contenidos->OrdenarContenido( $Contenido->m_id_seccion, $_id_subcontenido_, $_ordenar_, $idtipocontenido  );
		}
		

		echo '<table cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" width="100%" height="100%"><tr><td valign="top">';

		
		$subcontenidos = array();
				
		$Contenidos->m_tcontenidos->LimpiarSQL();
		$Contenidos->m_tcontenidos->FiltrarSQL('ID_CONTENIDO','',$_id_contenido_);
		$Contenidos->m_tcontenidos->FiltrarSQL('ID_TIPOCONTENIDO','',$idtipocontenido);
		$Contenidos->m_tcontenidos->OrdenSQL('contenidos.ID_TIPOCONTENIDO,contenidos.ORDEN ASC');
		$Contenidos->m_tcontenidos->Open();
		if ( $Contenidos->m_tcontenidos->nresultados>0 ) {
			//echo "resultados:".$Contenidos->m_tcontenidos->nresultados."<br>";
			$counter = 0;
			while($_row_ = $Contenidos->m_tcontenidos->Fetch() ) {				
				$subcontenidos[$counter] = $_row_['contenidos.ID'];
				$counter++;
			}
		}	

		$Contenidos->m_CTiposContenidos->SetTemplateSubEdicion($idtipocontenido,"",$html_copete,$html_cuerpo);
		$Contenidos->m_CTiposContenidos->SetTemplateSubConsulta($idtipocontenido);
		
			foreach($subcontenidos as $n=>$id) {
				$CC = $Contenidos->GetContenido($id);
				$CC->ToGlobals();
				
				//$__lang__=="" ? $CC->m_titulo = $CC->m_titulo : $CC->m_titulo = $Contenidos->m_tcontenidos->TextoML( $CC->m_ml_titulo, $__lang__);										
				$resstr.= $Contenidos->m_CTiposContenidos->TextoConsulta( $CC );				
				$resstr.= '<div id="subcontent_'.$CC->m_id.'" style="display:none;">';
				$resstr.= '<form name="formsubcontent_'.$CC->m_id.'" method="post"  enctype="multipart/form-data" action="subcontentbrowse.php">';
				$resstr.= '<input type="hidden" value="modificar" name="_accion_">';
				$resstr.= '<input type="hidden" value="" name="_ordenar_">';
				$resstr.= '<input type="hidden" value="'.$CC->m_id.'" name="_id_subcontenido_">';
				$resstr.= '<input type="hidden" value="'.$_id_contenido_.'" name="_id_contenido_">';
				$resstr.= '<input type="hidden" value="'.$_id_detalle_.'" name="_id_detalle_">';
				$resstr.= '<input type="hidden" value="'.$_id_tipodetalle_.'" name="_id_tipodetalle_">';
				$resstr.= '<input type="hidden" value="'.$CC->m_id.'" name="_e_ID">';
				
			  
				$tmpl = $Contenidos->Edit( $idtipocontenido, $CLang, $CMultiLang, $__lang__, 'formsubcontent_'.$CC->m_id );
				
				if (strpos( $tmpl, "*DETALLES*") === false ) {
					$tmpl = $Contenidos->m_CTiposContenidos->EditarDetalles( $CC, "modificar", $tmpl );
				} else $tmpl = str_replace( "*DETALLES*", $Contenidos->m_CTiposContenidos->EditarDetalles( $CC, "modificar"), $tmpl );
				$resstr.= $tmpl;
				$resstr.= '<br><input type="submit" value="'.$CLang->m_Words['SAVE'].'" name="Save"><input type="submit" value="'.$CLang->m_Words['DELETE'].'" name="delete"></form></div>';
		}
		
		$CC = new CContenido();
		$CC->m_id = 0;
		$CC->m_id_contenido = $Contenido->m_id;
		$CC->m_id_tipocontenido = $idtipocontenido;
		$CC->m_id_seccion = $Contenido->m_id_seccion;
		$CC->m_id_usuario_creador = $Contenido->m_id_usuario_creador;
		$CC->m_id_usuario_modificador = $Contenido->m_id_usuario_modificador;
		$CC->m_titulo = "";
		$CC->m_ml_titulo = "";
		$CC->m_copete = "";
		$CC->m_ml_copete = "";
		$CC->m_cuerpo = "";
		$CC->m_ml_cuerpo = "";
		$CC->m_fechaalta = "NOW()";
		$CC->m_fechabaja = "NOW()";
		$CC->m_fechaevento = "NOW()";
		$CC->ToGlobals();
		
		//$resstr = '<span><a href="#new" onclick="javascript:toggleDivAll(\'subcontent_'.$CC->m_id.'\');">[+]'.$TipoContenido->m_descripcion.'</a></span><br>'.$resstr;
		
		$resstr.= '<span><a href="#new" onclick="javascript:toggleDivAll(\'subcontent_'.$CC->m_id.'\');" name="new">[+]'.$TipoContenido->m_descripcion.'</a></span>';
		$resstr.= '<div id="subcontent_'.$CC->m_id.'" style="display:none;">';
		$resstr.= '<form name="formsubcontent_'.$CC->m_id.'" method="post"  enctype="multipart/form-data" action="subcontentbrowse.php">';
		$resstr.= '<input type="hidden" value="nuevo" name="_accion_">';
		$resstr.= '<input type="hidden" value="'.$_id_contenido_.'" name="_id_contenido_">';
		$resstr.= '<input type="hidden" value="'.$_id_detalle_.'" name="_id_detalle_">';
		$resstr.= '<input type="hidden" value="'.$_id_tipodetalle_.'" name="_id_tipodetalle_">';
		$tmpl = $Contenidos->Edit( $idtipocontenido, $CLang, $CMultiLang, $__lang__, 'formsubcontent_'.$CC->m_id );
		if (strpos( $tmpl, "*DETALLES*") === false ) {
			$tmpl = $Contenidos->m_CTiposContenidos->EditarDetalles( $CC, "nuevo", $tmpl );
		} else $tmpl = str_replace( "*DETALLES*", $Contenidos->m_CTiposContenidos->EditarDetalles( $CC, "nuevo"), $tmpl );
		$resstr.= $tmpl;
		$resstr.= '<br><input type="submit" value="'.$CLang->m_Words['SAVE'].'" name="Save">';		
		$resstr.= '</form></div>';
			
		foreach($CMultiLang->m_arraylangs as $idioma=>$code) {
			//$resstr.= "<script>toggleDivAll('did".$code."')</script>";			
			$resstr.= "<script>toggleDivAll('did".$code."')</script>";
		}
		echo $resstr;			
		
		
		echo '</td></tr></table>';
		echo "</body>";
		
		
	}	
	
	function GalleryBrowse( $_id_detalle_, $_id_tipodetalle_ ) {
		echo '<body><style> span,body,input,select,div,td {font-family: Verdana,Arial; font-size:10px; color:#000000;}</style>';
		$CDetalle = $this->GetDetalle( $_id_detalle_ );
		$CTipoDetalle = $this->m_CTiposDetalles->GetTipoDetalle( $_id_tipodetalle_ );
		
		$ADataDef = XData2Array( $CTipoDetalle->m_txtdata );
		$maxuploads = $ADataDef['maxuploads']['values'];
		$thmwidth = $ADataDef['thmwidth']['values']+4;
		$thmheight = $ADataDef['thmheight']['values']+4;
		
		$CDetalle->m_txtdata = trim($CDetalle->m_txtdata);
		if ($CTipoDetalle->m_tipocampo=="I" || $CTipoDetalle->m_tipocampo=="F") {
			$max = 1;
			if ($CDetalle->m_detalle=="") $nvalue = 0;
			else $nvalue=1;
		} else {
			$max = $ADataDef['max']['values'];
			if ($CDetalle->m_txtdata!="") {
				$gfile = explode( "\n", $CDetalle->m_txtdata );
				$nvalue = count($gfile);
			} else {
				$gfile = "";
				$nvalue = 0;
			}
		}
		
		$tpl = $this->m_CTiposDetalles->m_templatesedicion[$CTipoDetalle->m_id];
		$params = $this->m_CTiposDetalles->m_editionparameters[$CTipoDetalle->m_id];
		if (is_array($tpl) && $tpl["template"]!="") {
			$tmpl = $tpl["template"];//linea o texto
			$tmplHD = $tpl["templateheader"];//encabezado
			$tmplFT = $tpl["templatefooter"];//pie
			$tmplSP = $tpl["templatespacer"];//espacio vacio
			$tmplRO = $tpl["templaterows"];//row separator
			$tmplCO = $tpl["templatecolumns"];//columnas...
			$tmplMUL = $tpl["multiplicator"];//multiplicador para fracciones
			$tmplRND = $tpl["rounded"];//rounded
		}		
		if (is_array( $params ) ) {
			$rows = $params["rows"];	
			$cols = $params["columns"];
		}
		$tmplCO = $cols;		
		$tmplRO = "<br>";
				
		$resstr = "";
		$resstr.= '<div id="div_detalle_'.$CTipoDetalle->m_tipo.'_DATA" style="z-index:1000;left:30; height:30; position:absolute; height:80; width:400; display:none;">';
		$resstr.= '<iframe name="galeriaedit_'.$CTipoDetalle->m_id.'" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="NO" src="'.$_DIR_SITEABS.'/inc/core/galleryedit.php?_id_tipodetalle_='.$CTipoDetalle->m_id.'&_id_detalle_='.$CDetalle->m_id.'&_accion_=add&_pos_=-1" width="400" height="80">You need iframe\'s</iframe>';
		$resstr.= '</div>';
		$resstr.= '<table width="'.$cols*$thmwidth.'" height="100%" cellpadding="0" cellspacing="0" background="'.$_DIR_SITEABS.'/inc/images/fondgal.png"><tr><td valign="top">';
		$resstr.= '<table cellpadding="0" cellspacing="0" width="'.$cols*$thmwidth.'" border="0"><tr><td nowrap>';
		$cnt = 1;
		$cntr = 0;
		$cn = 0;			  		
		for( $gind = 0; $gind < $nvalue; $gind++ ) {
      		if ( ($tmplCO!="") && ($cnt==($tmplCO+1)) ) {
  				$resstr.= $tmplRO;
  				$cntr++;
  				$cnt = 2;			  				
  			} else $cnt++;
  			
  						
  			if ($CTipoDetalle->m_tipocampo=="I" || $CTipoDetalle->m_tipocampo=="F") {
  				$lsrc = $CDetalle->m_detalle;
  				$lcmt = $lsrc;
  			} else {
	  			if ($gfile[$gind]!='') {  			
					$lineX = explode("::",$gfile[$gind]);
					$lsrc = $lineX[0];
					$lcmt = $lineX[1];
				}
  			}
			//if ($lsrc!="") {
			 	$resstr.= '<img id="_img_'.$cn.'" width="'.$ADataDef['thmwidth']['values'].'" height="'.$ADataDef['thmheight']['values'].'" src="'.$lsrc.'" border="0" alt="'.$lcmt.'" title="'.$lcmt.'" hspace="2" vspace="2">';
			 	$resstr.= '<div id="_div_'.$cn.'" style="z-index:1; position:absolute; top:10px; left:10px; ">';
				if ($gind>0) $resstr.= '<a href="javascript:window.galleryedit(\'div_detalle_'.$CTipoDetalle->m_tipo.'\',\''.$_id_detalle_.'\',\''.$_id_tipodetalle_.'\',\'editup\','.$cn.');"><img src="'.$_DIR_SITEABS.'/inc/images/upmini.gif" border="0" alt="edit up" title="edit up"></a>&nbsp;';
				if ($gind<($nvalue-1)) $resstr.= '<a href="javascript:window.galleryedit(\'div_detalle_'.$CTipoDetalle->m_tipo.'\',\''.$_id_detalle_.'\',\''.$_id_tipodetalle_.'\',\'editdown\','.$cn.');"><img src="'.$_DIR_SITEABS.'/inc/images/downmini.gif" border="0" alt="edit down" title="edit down"></a>&nbsp;';
			 	$resstr.= '<a href="javascript:window.galleryedit(\'div_detalle_'.$CTipoDetalle->m_tipo.'\',\''.$_id_detalle_.'\',\''.$_id_tipodetalle_.'\',\'edit\','.$cn.');"><img src="'.$_DIR_SITEABS.'/inc/images/editar.gif" border="0" alt="edit" title="edit"></a>&nbsp;';
			 	$resstr.= '<a href="javascript:galleryerase('.$_id_detalle_.','.$_id_tipodetalle_.','.$cn.');"><img src="'.$_DIR_SITEABS.'/inc/images/delete.gif" border="0" alt="erase" title="erase"></a></div>';
			 	$cntleft = ($cnt-2) * ($ADataDef['thmwidth']['values']+2) + 18;
			 	$cnttop =  $cntr * ($ADataDef['thmheight']['values']+2) + $ADataDef['thmheight']['values'] - 18;
				$resstr.= '<script> document.getElementById(\'_div_'.$cn.'\').style.left='.$cntleft.'+\'px\'; 
									document.getElementById(\'_div_'.$cn.'\').style.top='.$cnttop.'+\'px\';
									
							</script>';
			//}
			$cn++;
		}
		$resstr.= '</td></tr></table>';		
		$resstr.= '</td></tr></table>';
		if ($nvalue>=$max) {
			$resstr.= '<script> window.parent.hidediv(\'div_detalle_'.$CTipoDetalle->m_tipo.'_ADD\'); 
					</script>';		
		} else {
			$resstr.= '<script> window.parent.showdiv(\'div_detalle_'.$CTipoDetalle->m_tipo.'_ADD\'); 
					</script>';
		}
		$resstr.= '</body>';
		echo $resstr;
	}
	
	function GalleryEdit( $_id_detalle_, $_id_tipodetalle_, $_accion_="add", $_pos_=-1 ) {
		
		global $CLang;
		
		echo '<body><style> span,body,input,select,div,td,textarea,button {font-family: Verdana,Arial; font-size:10px; color:#000000;}</style>';
		echo "<!--_accion_:".$_accion_." iddet:".$_id_detalle_." idtdet:".$_id_tipodetalle_." pos: ".$_pos_."<br>-->";
		
		$CDetalle = $this->GetDetalle( $_id_detalle_ );
		$CTipoDetalle = $this->m_CTiposDetalles->GetTipoDetalle( $_id_tipodetalle_ );
		
		if ($CDetalle==null || $CTipoDetalle==null) echo "error";
		
		$ADataDef = XData2Array( $CTipoDetalle->m_txtdata );
		
		if ( $_accion_=="confirm" ) {
			global $_SITEROOT_;
			global $_exito_;
			$_exito_ = false;
		
			$detentry = "";
			
			if ($CTipoDetalle->m_tipocampo=="I" || $CTipoDetalle->m_tipocampo=="F" ) {
				//SINGLE IMAGE OR PHOTO
				
				$tmpname = $_FILES['_fdetalle_'.$CTipoDetalle->m_tipo.'_F']["tmp_name"];
				$name = $_FILES['_fdetalle_'.$CTipoDetalle->m_tipo.'_F']["name"];
				$name = uniquecode(clean(replace_flat($name)));
				$tmpmov = $CTipoDetalle->m_tipo.$CDetalle->m_id.$name;
				$tmpmov = replace_specials($tmpmov);
				$ADataDef = XData2Array($CTipoDetalle->m_txtdata);
				if ($tmpname!="") {		    				
					if (is_uploaded_file($tmpname)) {
						$_exito_ = rename($tmpname,$_SITEROOT_.'/tmp/'.$tmpmov);		    					
						if($_exito_) $_exito_ = thumbnail( $_SITEROOT_, '/tmp/'.$tmpmov, $ADataDef['width']['values'], "/archivos/imagen", $tmpmov, $ADataDef['height']['values']);
						//if($_exito_) $_exito_ = rename_ftp('/archivos/imagen/'.$tmpmov,'/tmp/'.$tmpmov);
						if($_exito_) {
							chmod_ftp('/archivos/imagen/'.$tmpmov);		    						
							//thumbnail(S_SITEROOT_,$_urlnuevo_,121,dirname($_urlnuevo_)."/thm",basename($_urlnuevo_));
							thumbnail( $_SITEROOT_, "/archivos/imagen/".$tmpmov, $ADataDef['thmwidth']['values'], "/archivos/imagen/thm", $tmpmov, $ADataDef['thmheight']['values']);		    						
							chmod_ftp('/archivos/imagen/thm/'.$tmpmov);
							$CDetalle->m_detalle = '../../archivos/imagen/'.$tmpmov;
						}
					} else if ($CDetalle->m_detalle!="empty") {
						$tmpmov = basename( $CDetalle->m_detalle );		    					
						//tratamos de generar el thumbnail de la imagen:
						thumbnail( $_SITEROOT_, "/archivos/imagen/".$tmpmov, $ADataDef['thmwidth']['values'], "/archivos/imagen/thm", $tmpmov, $ADataDef['thmheight']['values']);
						chmod_ftp('/archivos/imagen/thm/'.$tmpmov);
					} else {
						//$this->m_CErrores->PushError( new CError("UPLOADERROR:".$tmpname,"uploading error confirming detail") );
						$this->PushError( new CError("UPLOADERROR:".$tmpname,"uploading error confirming detail") );
						$_exito_=false;
					}
				}

				$clines = 1;
				$max = 1;
			} else {
				$maxuploads = $ADataDef['maxuploads']['values'];
				$max = $ADataDef['max']['values'];
				
				$CDetalle->m_txtdata = trim($CDetalle->m_txtdata);
				
				if ($CDetalle->m_txtdata=="") {
					$clines = 0;
					$gfile = "";
				} else {
					
					$gfile = explode( "\n", $CDetalle->m_txtdata );
					$clines = count( $gfile );
				}
							
				$tmpname = $_FILES['_fdetalle_'.$CTipoDetalle->m_tipo.'_F']["tmp_name"];					
				$tmpname = trim($tmpname);
				if ($tmpname!="") {    							
					$link = $GLOBALS['_fdetalle_'.$CTipoDetalle->m_tipo.'_L'];
					$comment = $GLOBALS['_fdetalle_'.$CTipoDetalle->m_tipo.'_T'];
					if ( is_uploaded_file($tmpname)) {
		    			$name = $_FILES['_fdetalle_'.$CTipoDetalle->m_tipo.'_F']["name"];
		    			$name = uniquecode(clean(replace_flat($name)));    				
		    			$tmpmov = $CTipoDetalle->m_tipo.$CDetalle->m_id.$name;
		    			$tmpmov = replace_specials($tmpmov);
						$_exito_ = rename( $tmpname, $_SITEROOT_.'/tmp/'.$tmpmov );			    					
						if ($CTipoDetalle->m_tipocampo=='G') {//para Imagenes
							if($_exito_) $_exito_ = thumbnail( $_SITEROOT_, '/tmp/'.$tmpmov, $ADataDef['width']['values'], "/archivos/imagen", $tmpmov, $ADataDef['height']['values']);
		    				if($_exito_) {
		    					chmod_ftp('/archivos/imagen/'.$tmpmov);
		    					//thumbnail(S_SITEROOT_,$_urlnuevo_,121,dirname($_urlnuevo_)."/thm",basename($_urlnuevo_));
		    					thumbnail( $_SITEROOT_, "/archivos/imagen/".$tmpmov, $ADataDef['thmwidth']['values'], "/archivos/imagen/thm", $tmpmov, $ADataDef['thmheight']['values']);
		    					chmod_ftp('/archivos/imagen/thm/'.$tmpmov);
		    					$detentry = '../../archivos/imagen/'.$tmpmov;
		    					$detentry.="::".$comment;
		    					$sep = "\n";
		    				}
						} else {//para DOCS...y otros
							if($_exito_) $_exito_ = rename_ftp('/archivos/documentacion/'.$tmpmov,'/tmp/'.$tmpmov);
							chmod_ftp('/archivos/documentacion/'.$tmpmov);
							$detentry = '../../archivos/documentacion/'.$tmpmov;
							$detentry.="::".$comment;
							$sep = "\n";
						}
					} else if( $link!="" ) {					
						$detentry = $link;
						$detentry.= "::".$comment;
						$sep = "\n";
					} else {
						//$this->m_CErrores->PushError( new CError("UPLOADERROR:".$tmpname,"uploading error confirming detail") );
						$this->PushError( new CError("UPLOADERROR:".$tmpname,"uploading error confirming detail") );
						$_exito_=false;
					}
				}
				
				if ($_exito_) {		
					//INSERTING IN THE CORRECT PLACE ON GALLERY		
					if ($_pos_!=-1 && $_pos_!="") {
						//reemplazar ese indice en particular
						if ( 0<=$_pos_ && $_pos_<count($gfile) ) {
							$gfile[$_pos_] = $detentry;
						}
					} else $clines++;
					
					$CDetalle->m_detalle = "";
					$sep = "";
					if(is_array($gfile))
					foreach( $gfile as $dentry ) {
						$CDetalle->m_detalle.= $sep.$dentry;
						$sep = "\n";
					}
					if ($_pos_==-1 || $_pos_=="") $CDetalle->m_detalle.= $sep.$detentry;
				}
			
			}
			
			if ($_exito_) {				
				
				echo "<!--detalle:".$CDetalle->m_detalle."-->";
				
				$reg = 	array('ENTERO'=>$CDetalle->m_entero,
						'FRACCION'=>$CDetalle->m_fraccion,
						'ID_CONTENIDO'=>$CDetalle->m_id_contenido,
						'ID_TIPODETALLE'=>$CDetalle->m_id_tipodetalle,
						'DETALLE'=>$CDetalle->m_detalle,
						'ML_DETALLE'=>$CDetalle->m_ml_detalle,
						'TXTDATA'=>$CDetalle->m_detalle, 
						'ML_TXTDATA'=>$CDetalle->m_ml_detalle,
						'BINDATA'=>'');					
											
				$_exito_ = $this->m_tdetalles->ModificarRegistro( $CDetalle->m_id , $reg );
				/*
				if ($edicion=='modificar') {				
					$_exito_ = $this->m_tdetalles->ModificarRegistro( $CDetalle->m_id , $reg );
				} else if ($edicion=='insertar') {				
					$_exito_ = $this->m_tdetalles->InsertarRegistro( $reg );
				}
				*/
				if ( $_exito_ ) {
					echo '<script>
					window.parent.parent.document.getElementById(\'_ndetalle_'.$CTipoDetalle->m_tipo.'\').value = '.$clines.';
					window.parent.location.href = \''.$_DIR_SITEABS.'/inc/core/gallerybrowse.php?__lang__='.$__lang__.'&_id_tipodetalle_='.$CTipoDetalle->m_id.'&_id_detalle_='.$CDetalle->m_id.'\';
					</script>					
						';
					if ($max<=$clines) {
						//limite passé
						echo '<script>
								window.parent.parent.document.getElementById(\'_ndetalle_'.$CTipoDetalle->m_tipo.'\').value = '.$clines.';
								</script>';
					}
				}				
			}
			
		}

		
		if ( $_accion_=="editup" && $_pos_!=-1 && $_pos_>0) {
			$CDetalle->m_detalle = "";
			$sep = "";
			$p = 0;
			$gfile = explode( "\n", $CDetalle->m_txtdata );
			$clines = count($gfile);
			
			$aux = $gfile[$_pos_-1];
			$gfile[$_pos_-1] = $gfile[$_pos_];
			$gfile[$_pos_] = $aux;
			
			foreach( $gfile as $dentry ) {
				$CDetalle->m_detalle.= $sep.$dentry;
				$sep = "\n";
			}
			
			echo "<!--detalle:".$CDetalle->m_detalle."-->";
			
			$reg = 	array('ENTERO'=>$CDetalle->m_entero,
					'FRACCION'=>$CDetalle->m_fraccion,
					'ID_CONTENIDO'=>$CDetalle->m_id_contenido,
					'ID_TIPODETALLE'=>$CDetalle->m_id_tipodetalle,
					'DETALLE'=>$CDetalle->m_detalle,
					'ML_DETALLE'=>$CDetalle->m_ml_detalle,
					'TXTDATA'=>$CDetalle->m_detalle, 
					'ML_TXTDATA'=>$CDetalle->m_ml_detalle,
					'BINDATA'=>'');					
										
			$_exito_ = $this->m_tdetalles->ModificarRegistro( $CDetalle->m_id , $reg );
			if ( $_exito_ ) {
					echo '<script>
					window.parent.parent.document.getElementById(\'_ndetalle_'.$CTipoDetalle->m_tipo.'\').value = '.$clines.';
					window.parent.location.href = \''.$_DIR_SITEABS.'/inc/core/gallerybrowse.php?__lang__='.$__lang__.'&_id_tipodetalle_='.$CTipoDetalle->m_id.'&_id_detalle_='.$CDetalle->m_id.'\';
					</script>					
						';
					if ($max<=$clines) {
						//limite passé
						echo '<script>
								window.parent.parent.document.getElementById(\'_ndetalle_'.$CTipoDetalle->m_tipo.'\').value = '.$clines.';
								</script>';
					}
				}
		} else if ( $_accion_=="editdown" && $_pos_>=0 ) {
			$CDetalle->m_detalle = "";
			$sep = "";
			$p = 0;
			$gfile = explode( "\n", $CDetalle->m_txtdata );
			$clines = count($gfile);
			
			$aux = $gfile[$_pos_+1];
			$gfile[$_pos_+1] = $gfile[$_pos_];
			$gfile[$_pos_] = $aux;
			
			foreach( $gfile as $dentry ) {
				$CDetalle->m_detalle.= $sep.$dentry;
				$sep = "\n";
			}			
			echo "<!--detalle:".$CDetalle->m_detalle."-->";
			
			$reg = 	array('ENTERO'=>$CDetalle->m_entero,
					'FRACCION'=>$CDetalle->m_fraccion,
					'ID_CONTENIDO'=>$CDetalle->m_id_contenido,
					'ID_TIPODETALLE'=>$CDetalle->m_id_tipodetalle,
					'DETALLE'=>$CDetalle->m_detalle,
					'ML_DETALLE'=>$CDetalle->m_ml_detalle,
					'TXTDATA'=>$CDetalle->m_detalle, 
					'ML_TXTDATA'=>$CDetalle->m_ml_detalle,
					'BINDATA'=>'');					
										
			$_exito_ = $this->m_tdetalles->ModificarRegistro( $CDetalle->m_id , $reg );
			if ( $_exito_ ) {
					echo '<script>
					window.parent.parent.document.getElementById(\'_ndetalle_'.$CTipoDetalle->m_tipo.'\').value = '.$clines.';
					window.parent.location.href = \''.$_DIR_SITEABS.'/inc/core/gallerybrowse.php?__lang__='.$__lang__.'&_id_tipodetalle_='.$CTipoDetalle->m_id.'&_id_detalle_='.$CDetalle->m_id.'\';
					</script>					
						';
					if ($max<=$clines) {
						//limite passé
						echo '<script>
								window.parent.parent.document.getElementById(\'_ndetalle_'.$CTipoDetalle->m_tipo.'\').value = '.$clines.';
								</script>';
					}
				}
		} else if ( $_accion_=="delete" && $_pos_!=-1) {
			$CDetalle->m_detalle = "";
			$clines = 0;
			
			if ( $CTipoDetalle->m_tipocampo!="I" && $CTipoDetalle->m_tipocampo!="F" ) {
				$gfile = explode( "\n", $CDetalle->m_txtdata );
				$sep = "";
				$p = 0;
				$clines = count($gfile);
				if ($clines>0) {			
					foreach( $gfile as $dentry ) {
						if ($p!=$_pos_) {
							$CDetalle->m_detalle.= $sep.$dentry;
							$sep = "\n";
						}
						$p++;
					}
				}
			}
			
			echo "<!--detalle:".$CDetalle->m_detalle."-->";
			
			$reg = 	array('ENTERO'=>$CDetalle->m_entero,
					'FRACCION'=>$CDetalle->m_fraccion,
					'ID_CONTENIDO'=>$CDetalle->m_id_contenido,
					'ID_TIPODETALLE'=>$CDetalle->m_id_tipodetalle,
					'DETALLE'=>$CDetalle->m_detalle,
					'ML_DETALLE'=>$CDetalle->m_ml_detalle,
					'TXTDATA'=>$CDetalle->m_detalle, 
					'ML_TXTDATA'=>$CDetalle->m_ml_detalle,
					'BINDATA'=>'');					
										
			$_exito_ = $this->m_tdetalles->ModificarRegistro( $CDetalle->m_id , $reg );
			if ( $_exito_ ) {
				$clines--;
				echo '<script>
				window.parent.parent.document.getElementById(\'_ndetalle_'.$CTipoDetalle->m_tipo.'\').value = '.$clines.';
				window.parent.location.href = \''.$_DIR_SITEABS.'/inc/core/gallerybrowse.php?__lang__='.$__lang__.'&_id_tipodetalle_='.$CTipoDetalle->m_id.'&_id_detalle_='.$CDetalle->m_id.'\';
				</script>					
					';
				if ($max<=$clines) {
					//limite passé
					echo '<script>
							window.parent.parent.document.getElementById(\'_ndetalle_'.$CTipoDetalle->m_tipo.'\').value = '.$clines.';
							</script>';
					}
			}			
		}		
		
		if ($_accion_=="add" || 
			$_accion_=="edit") { 
			echo '<form name="formupload" method="post"  enctype="multipart/form-data" action="'.$_DIR_SITEABS.'/inc/core/galleryedit.php" onSubmit="javascript: if(document.formupload._fdetalle_'.$CTipoDetalle->m_tipo.'_F.value!=\'\') return true; else return false; ">				  
				  <table width="100%" height="100%" bgcolor="#FFFFFF" cellpadding="3" cellspacing="0" border="1">				  
				  <tr><td bgcolor="#FFFFFF"  valign="top">
				  '.$CLang->m_Words[strtoupper($_accion_)].'<br>
				  <input id="_pos_" name="_pos_" value="'.$_pos_.'" type="hidden">
				  <input id="_id_detalle_" name="_id_detalle_" value="'.$_id_detalle_.'" type="hidden"> 
				  <input id="_id_tipodetalle_" name="_id_tipodetalle_" value="'.$_id_tipodetalle_.'" type="hidden">
				  <input id="_accion_" name="_accion_" type="hidden" value="confirm">
				  File<br>							
				  <input id="_fdetalle_'.$CTipoDetalle->m_tipo.'_F" name="_fdetalle_'.$CTipoDetalle->m_tipo.'_F" type="file" size="50" value="">
				  <div style="display:none;">
				  <br>
				  <input id="_fdetalle_'.$CTipoDetalle->m_tipo.'_L" name="_fdetalle_'.$CTipoDetalle->m_tipo.'_L" type="text" size="50" value="">				  
				  <br>Commentary<br>
				  <input id="_fdetalle_'.$CTipoDetalle->m_tipo.'_T" name="_fdetalle_'.$CTipoDetalle->m_tipo.'_T" type="text" value="" size="50">
				  </div>
				  <button type="submit">'.$CLang->m_Words['UPLOAD'].'</button>
				  <button onclick="javascript:window.parent.hidediv(\'div_detalle_'.$CTipoDetalle->m_tipo.'_DATA\');">'.$CLang->m_Words['CANCEL'].'</button>
				  </td></tr></table>
				  </form>';
		}
		
		echo '</body>';
	}
	
	function Edit( $CDetalle, $CTipoDetalle, $CLang=null, $CMultiLang=null, $Contenidos=null, $Secciones=null ) {
		global $__lang__;
		global $_DIR_SITEABS;

		if ($CLang==null) $CLang = $GLOBALS['CLang'];
		if ($CMultiLang==null) $CMultiLang = $GLOBALS['CMultiLang'];		
				
		$resstr = "";		
		$rows = 8;
		$cols = 80;
		$params = $this->m_CTiposDetalles->m_editionparameters[$CTipoDetalle->m_id];
		$tpl = $this->m_CTiposDetalles->m_templatesedicion[$CTipoDetalle->m_id];
		$tmpl = "";
		$script = $params["script"];
		if (is_array($tpl) && $tpl["template"]!="") {
			$tmpl = $tpl["template"];//linea o texto
			$tmplHD = $tpl["templateheader"];//encabezado
			$tmplFT = $tpl["templatefooter"];//pie
			$tmplSP = $tpl["templatespacer"];//espacio vacio
			$tmplRO = $tpl["templaterows"];//row separator
			$tmplCO = $tpl["templatecolumns"];//columnas...
			$tmplMUL = $tpl["multiplicator"];//multiplicador para fracciones
			$tmplRND = $tpl["rounded"];//rounded
			//ShowMessage("CDetalles::Edit > ONE templates para ".$CTipoDetalle->m_descripcion);
			//ShowMessage("CDetalles::Edit > template <textarea>".$tmpl."</textarea>");
			//ShowMessage("CDetalles::Edit > header <textarea>".$tmplHD."</textarea>");
			//ShowMessage("CDetalles::Edit > footer <textarea>".$tmplFT."</textarea>");
		} else {
			//ShowMessage("CDetalles::Edit > NO templates para ".$CTipoDetalle->m_descripcion);
		}
		
		if (is_array( $params ) ) {
			$rows = $params["rows"];	
			$cols = $params["columns"];
		}
		//el valor editable
		switch($CTipoDetalle->m_tipocampo) {
			case "U"://SUB CONTENTS
				$ADataDef = XData2Array($CTipoDetalle->m_txtdata);
				$width = "100%";
				$height = "500";

				$resstr.= '<iframe class="subcontent_edit" name="subcontent_'.$CTipoDetalle->m_id.'" id="subcontent_'.$CTipoDetalle->m_tipo.'"
				marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" 
				src="'.html_entity_decode(''.$_DIR_SITEABS.'/inc/core/subcontentbrowse.php?test=1&_id_contenido_='.$CDetalle->m_id_contenido.'&_id_tipodetalle_='.$CTipoDetalle->m_id.'&_id_detalle_='.$CDetalle->m_id.'&__lang__='.$__lang__.'&rand='.rand(1111,99999).'" 
				width="'.$width.'" height="'.$height).'">You need iframe\'s</iframe>';
				break;			
			case "T": //TEXT
				$tipospl = explode( "\n", $CTipoDetalle->m_txtdata );	
				$resstr.=  '<input id="_edetalle_'.$CTipoDetalle->m_tipo.'" name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="text" size="'.$cols.'" value="'.$CDetalle->m_detalle.'">';
				$resstr.= TextCounter( '_edetalle_'.$CTipoDetalle->m_tipo, $tipospl[1], $tipospl[2] );
				break;
			case "N": //NUM-ENTERO						
				$resstr.=  '<input id="_edetalle_'.$CTipoDetalle->m_tipo.'" name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="text" size="5" value="'.$CDetalle->m_entero.'" onblur = "javascript: this.value = forcenumeric (this.value, 0, 1);">';
				break;
			case "E": //EXP-FRACCION							
				$resstr.=  '<input id="_edetalle_'.$CTipoDetalle->m_tipo.'" name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="text" size="5" value="'.$CDetalle->m_fraccion.'" onblur = "javascript: this.value = forcenumeric (this.value, 2, 1);">';
				break;						
			case "L": //LIST TEXT or //BLOB
			case "B": 				
				$tipospl = explode( "\n", $CTipoDetalle->m_txtdata );	
				//ShowMessage("HOLA: ".$CTipoDetalle->m_tipo.": ".print_r($tipospl,true));
				$resstr.=  '<textarea id="_edetalle_'.$CTipoDetalle->m_tipo.'" name="_edetalle_'.$CTipoDetalle->m_tipo.'" cols="'.$cols.'" rows="'.$rows.'" class="wiweedit">'.$CDetalle->m_txtdata.'</textarea>';
				if (
						is_numeric( strpos( $CTipoDetalle->m_txtdata , "html" ) ) 
					||
						is_numeric( strpos( $CTipoDetalle->m_txtdata , "filteredhtml" ) ) 
					) {
					$resstr.=  '<script> textareaEdit( \'_edetalle_'.$CTipoDetalle->m_tipo.'\',\'\' ); </script>';
				}
				$resstr.= TextCounter( '_edetalle_'.$CTipoDetalle->m_tipo, $tipospl[1], $tipospl[2] );					
				break;
			case "I":
			case "F": //IMAGE / FOTO
				if ($tpl["template"]!="") {
					$ADataDef = XData2Array($CTipoDetalle->m_txtdata);
					$maxuploads = 1;
					$max = 1;
					$thmwidth = $ADataDef['thmwidth']['values']+4;
					$thmheight = $ADataDef['thmheight']['values']+4;
					$CDetalle->m_txtdata = trim ( $CDetalle->m_txtdata );
					if ($CDetalle->m_detalle=="") {
						$nvalue = 0;
					} else $nvalue = 1;
					
					$width = ($thmwidth+4) * 3;//para tener lugar para la ventana del uploader....
					$height = ($thmheight+1) * 1;
					( $max > $nvalue ) ? $display_add = 'inline' : $display_add = 'none';
					$resstr.= '<div id="div_detalle_'.$CTipoDetalle->m_tipo.'_ADD" style="display:'.$display_add.';"><button type="button" onclick="javascript:galleryedit(\'div_detalle_'.$CTipoDetalle->m_tipo.'\',\''.$CDetalle->m_id.'\',\''.$CTipoDetalle->m_id.'\',\'add\',\'-1\');">'.$CLang->m_Words["ADDIMAGE"].'</button></div><br>';					
					$resstr.= '<input id="_ndetalle_'.$CTipoDetalle->m_tipo.'" name="_ndetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" value="'.$nvalue.'">';
					$resstr.= '<iframe name="galeria_'.$CTipoDetalle->m_id.'" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" src="../../inc/core/gallerybrowse.php?__lang__='.$__lang__.'&_single_=yes&_id_tipodetalle_='.$CTipoDetalle->m_id.'&_id_detalle_='.$CDetalle->m_id.'" width="'.$width.'"height="'.$height.'">You need iframe\'s</iframe>';
				} else {
					if ($CDetalle->m_detalle!='') $resstr.=  '<a href="'.$CDetalle->m_detalle.'" rel="lightbox"  id="_edetalle_'.$CTipoDetalle->m_tipo.'_IMG"><img  id="_edetalle_'.$CTipoDetalle->m_tipo.'_IMGTHM" src="'.str_replace('imagen/','imagen/thm/',$CDetalle->m_detalle).'" border="0" alt=""></a><img src="'.$_DIR_SITEABS.'/inc/images/delete.gif" border="0" onclick="javascript:erase(\'_edetalle_'.$CTipoDetalle->m_tipo.'\');"><br>';
					$resstr.=  '<input name="_edetalle_'.$CTipoDetalle->m_tipo.'" id="_edetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" size="25" value="'.$CDetalle->m_detalle.'"><input name="_fdetalle_'.$CTipoDetalle->m_tipo.'" type="file">';
				}
				break;
			case "V":
				if ($CDetalle->m_detalle!='') $resstr.=  '<a title="" href="'.$CDetalle->m_detalle.'" target="_blank">'.$CDetalle->m_detalle.'</a><br>';
				$resstr.=  '<input name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" size="80" value="'.$CDetalle->m_detalle.'">
				<input name="_fdetalle_'.$CTipoDetalle->m_tipo.'" type="file">';
				break;						
			case "D":
			case "A":						
				if ($CDetalle->m_detalle!='') $resstr.=  '<a title="" href="'.$CDetalle->m_detalle.'" target="_blank" id="_edetalle_'.$CTipoDetalle->m_tipo.'_LNK">'.$CDetalle->m_detalle.'</a><img src="'.$_DIR_SITEABS.'/inc/images/delete.gif" border="0" onclick="javascript:erase(\'_edetalle_'.$CTipoDetalle->m_tipo.'\');"><br>';
				$resstr.=  '<input name="_edetalle_'.$CTipoDetalle->m_tipo.'" id="_edetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" size="80" value="'.$CDetalle->m_detalle.'">
				<input id="_fdetalle_'.$CTipoDetalle->m_tipo.'" name="_fdetalle_'.$CTipoDetalle->m_tipo.'" type="file">';
				break;
			case "G":
			case "W":
			case "M":				
				if ($tpl["template"]!="") {
					$ADataDef = XData2Array($CTipoDetalle->m_txtdata);
					$maxuploads = $ADataDef['maxuploads']['values'];
					$max = $ADataDef['max']['values'];
					$thmwidth = $ADataDef['thmwidth']['values']+4;
					$thmheight = $ADataDef['thmheight']['values']+4;
					$CDetalle->m_txtdata = trim ( $CDetalle->m_txtdata );
					if ($CDetalle->m_txtdata!="") {
						$gfile = explode("\n",$CDetalle->m_txtdata);
						$nvalue = count($gfile);
					} else {
						$gfile = "";
						$nvalue = 0;
					}
					
					$width = $thmwidth * $cols;
					$height = $thmheight * ($max / $cols);
					
					( $max > $nvalue ) ? $display_add = 'inline' : $display_add = 'none';
					$resstr.= '<div id="div_detalle_'.$CTipoDetalle->m_tipo.'_ADD" style="display:'.$display_add.';"><button type="button" onclick="javascript:galleryedit(\'div_detalle_'.$CTipoDetalle->m_tipo.'\',\''.$CDetalle->m_id.'\',\''.$CTipoDetalle->m_id.'\',\'add\',\'-1\');">'.$CLang->m_Words["ADDMOREIMAGES"].'</button></div>';					
					$resstr.= '<input id="_ndetalle_'.$CTipoDetalle->m_tipo.'" name="_ndetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" value="'.$nvalue.'">';
					$resstr.= '<iframe name="galeria_'.$CTipoDetalle->m_id.'" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" src="'.$_DIR_SITEABS.'/inc/core/gallerybrowse.php?__lang__='.$__lang__.'&_id_tipodetalle_='.$CTipoDetalle->m_id.'&_id_detalle_='.$CDetalle->m_id.'" width="'.$width.'"height="'.$height.'">You need iframe\'s</iframe>';
				} else {				
					//select tipo lista, con botones de subir,bajar,borrar, + preview en el costado derecho...
					//abajo archivos para subir en forma simultánea archivos
					$ADataDef = XData2Array($CTipoDetalle->m_txtdata);
					$maxuploads = $ADataDef['maxuploads']['values'];
					$max = $ADataDef['max']['values'];
					//$editablelink = $ADataDef['editablelink']['values'];
					$gfile = explode("\n",$CDetalle->m_txtdata);				
					$resstr.=  '<table cellpadding="4" cellspacing="0" border="0">';
					$resstr.=  '<tr><td colspan="2">';
					if ($CTipoDetalle->m_tipocampo=="G") 					
						$resstr.=  '<img id="_edetalle_'.$CTipoDetalle->m_tipo.'_gal_preview_thm" src="" border="0" alt=""><br>';				
					$resstr.=  '<input id="_edetalle_'.$CTipoDetalle->m_tipo.'_gal_max" name="_edetalle_'.$CTipoDetalle->m_tipo.'_gal_max" type="hidden" size="80" value="'.$max.'"><br>';					
					$resstr.=  '<input id="_edetalle_'.$CTipoDetalle->m_tipo.'_gal_TLINK" name="_edetalle_'.$CTipoDetalle->m_tipo.'_gal_TLINK" type="hidden" size="80" value="" onchange="javascript:gallerylink(\'_edetalle_'.$CTipoDetalle->m_tipo.'\');"><br>';
					$resstr.=  'Edit: <input id="_edetalle_'.$CTipoDetalle->m_tipo.'_gal_TEDIT" name="_edetalle_'.$CTipoDetalle->m_tipo.'_gal_TEDIT" type="text" size="80" value="" onchange="javascript:galleryedit(\'_edetalle_'.$CTipoDetalle->m_tipo.'\');">';							
					$resstr.=  '</td></tr>';		
					$resstr.=  '<tr><td  valign="top">';
					$resstr.=  '<input id="_edetalle_'.$CTipoDetalle->m_tipo.'" name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" value="'.$CDetalle->m_txtdata.'">';
					$resstr.=  '<select id="_edetalle_'.$CTipoDetalle->m_tipo.'_gal" name="_edetalle_'.$CTipoDetalle->m_tipo.'_gal" size="'.max(array(count($gfile),$maxuploads)).'" onchange="javascript:galleryshow(\'_edetalle_'.$CTipoDetalle->m_tipo.'\');gallerydownload(\'_edetalle_'.$CTipoDetalle->m_tipo.'\');">';
					for( $gind = 0; $gind < count($gfile); $gind++ ) {
						if ($gfile[$gind]!='') {
							$lineX = explode("::",$gfile[$gind]);
							$gind==0 ? $sel = "selected" : $sel = "";									
						 	$resstr.= '<option value="'.$lineX[0].'" '.$sel.'>'.$lineX[1].'</option>';
						}
					}
					$resstr.=  '</select>';							
					$resstr.=  '</td><td valign="top" align="center">';
					$resstr.=  '<a href="javascript:galleryup(\'_edetalle_'.$CTipoDetalle->m_tipo.'\');"><img src="'.$_DIR_SITEABS.'/inc/images/up.gif" border="0" alt="Up" title="Up"></a><br>
						  <a href="javascript:gallerydown(\'_edetalle_'.$CTipoDetalle->m_tipo.'\');"><img src="'.$_DIR_SITEABS.'/inc/images/down.gif" border="0" alt="Down" title="Down"></a><br>
						  <a href="javascript:gallerydelete(\'_edetalle_'.$CTipoDetalle->m_tipo.'\');"><img src="'.$_DIR_SITEABS.'/inc/images/delete.gif" border="0"  alt="Delete" title="Delete"></a><br>
						  <a id="_edetalle_'.$CTipoDetalle->m_tipo.'_gal_download" href="'.$lineX[0].'" target="_blank"><img src="'.$_DIR_SITEABS.'/inc/images/download.gif" border="0"  alt="Download" title="Download"></a><br>';
					$resstr.=  '</td></tr>';
					$resstr.=  '<tr><td colspan="2"  valign="top">';
					
					for( $if=0; $if < $maxuploads ; $if++ ) {
						$resstr.=  'Upload '.$if.'<br><input name="_fdetalle_'.$CTipoDetalle->m_tipo.'_T'.$if.'" type="text" size="80" value="Comm. #'.$if.'"><br>';
						$resstr.= '<input name="_fdetalle_'.$CTipoDetalle->m_tipo.'_L'.$if.'" type="hidden" size="80" value=""><br>';
						$resstr.= '<input name="_fdetalle_'.$CTipoDetalle->m_tipo.'_F'.$if.'" type="file" size="80"><br>';
						if ($if<($maxuploads-1)) $resstr.=  '<hr>';
					}
					$resstr.=  '</td></tr></table><script>galleryshow(\'_edetalle_'.$CTipoDetalle->m_tipo.'\');gallerydownload(\'_edetalle_'.$CTipoDetalle->m_tipo.'\');</script>';
				}
				break;
			case "C": //CHECKBOX				
				if ($CDetalle->m_detalle == "[YES]") { 
					$ssi="selected"; 
					$sno=""; 
					$scheck="checked";
				} else if ($CDetalle->m_detalle == "[NO]") {
						$ssi=""; 
						$sno="selected";
						$scheck="";
				} else {
						$default_yes = is_numeric( strpos( $CTipoDetalle->m_txtdata,"[YES]") );
						if ($default_yes) { $scheck="checked"; $ssi = "selected";  $sno = ""; } 
						else { $scheck=""; $ssi = "";  $sno = "selected"; }
				}
				
				if (is_numeric(strpos( $CTipoDetalle->m_txtdata,"checkbox") ) ) {
						
					$resstr.=  '<input type="checkbox" name="_edetalle_'.$CTipoDetalle->m_tipo.'" '.$scheck.'>';					
						
				} else {
					
					$resstr.=  '<select name="_edetalle_'.$CTipoDetalle->m_tipo.'" ><option value="[YES]" '.$ssi.'>'.$CLang->m_Words['YES'].'</option><option value="[NO]"  '.$sno.'>'.$CLang->m_Words['NO'].'</option></select>';
										
				}
				break;
			case "X": //XML DATA
				///tomamos la estructura de los datos del CTipoDetalle->m_txtdata
				$CRecordDefinition = new CXMLRecordDefinition( XData2Array( $CTipoDetalle->m_txtdata ) );
				if ($tmpl!="") $resstr.= $tmplHD;
				else $resstr.=  '<table cellpadding="4" cellspacing="0" border="0">';
				
				///aplicamos los datos del registro editado > CDetalle->m_txtdata
				//if ($CDetalle->m_txtdata=="") ShowError("no txtdata valid for detalle m_id:".$CDetalle->m_id.", m_id_contenido:".$CDetalle->m_id_contenido.", detalle: ".$CDetalle->m_detalle);
				if ($tmpl!="") $resstr.= $CRecordDefinition->Edit( '_edetalle_'.$CTipoDetalle->m_tipo, XData2Array( $CDetalle->m_txtdata ), $tmpl, $script  );
				else $resstr.= $CRecordDefinition->Edit( '_edetalle_'.$CTipoDetalle->m_tipo, XData2Array( $CDetalle->m_txtdata ), '', $script  );
				
				
				if ($tmpl!="") $resstr.= $tmplFT;
				else $resstr.=  '</table>';
				break;
				
				
			case "Y": //XML TABLE RECORD				
				
				$CRecordDefinition = new CXMLRecordDefinition( XData2Array( $CTipoDetalle->m_txtdata ) );				

				$thead = "<thead>";
				foreach($CRecordDefinition->m_CFields as $CField) {
					$thead.= "<th>".$CField->m_name."</th>";
				}
				$thead.= "<th></th></thead>";
				
				
				if ($tmpl!="") $resstr.= $tmplHD;
				else $resstr.=  '<table cellpadding="4" cellspacing="0" border="0">'.$thead."<tbody id=\"##TABLE##\">";
				

				
				//old records
				$crecs = 0;				
				$crecs_valids = 0;
				//ShowMessage("CDetalles:Edit:Y> Hay datos en la tabla XML: <textarea>".$CDetalle->m_txtdata."</textarea>");
				if ($CDetalle->m_txtdata!='') {					
					$lines = explode("\n",$CDetalle->m_txtdata);										
					foreach( $lines as $linestr ) {
						if (trim($linestr)!="") {							
							$resstr.= $CRecordDefinition->Edit( '_edetalle_'.$CTipoDetalle->m_tipo.'_'.$crecs.'_', 
														XData2Array( $linestr ), 
														$tmpl, 
														$script  );
							
							$crecs++;
							$crecs_valids++;
						}
					}
				}

				//new records
				$CRecordDefinition->m_newrecords = 0;
				//ShowMessage("CDetalles:Edit:Y> Máximos registros posibles: maxrecords:".$CRecordDefinition->m_maxrecords."");
				if ( $crecs < $CRecordDefinition->m_maxrecords ) {
					//$newrecs = min( array($CRecordDefinition->m_newrecords, $CRecordDefinition->m_maxrecords - $crecs) );
					$newrecs = $CRecordDefinition->m_maxrecords - $crecs;
					//ShowMessage("CDetalles:Edit:Y> Nuevos registros: newrecords,restantes:".$CRecordDefinition->m_newrecords.",".$newrecs);
					for( $i=0; $i < $newrecs ; $i++ ) {

						$newrec = 	$CRecordDefinition->Edit( '_edetalle_'.$CTipoDetalle->m_tipo.'_'.$crecs.'_', 
																									"", 																									
																									$tmpl, 
																									$script );
						if ($crecs_valids>0 || ($crecs_valids==0 && $i>0) ) $resstr.= str_replace( "xmlrecord", "xmlrecord_agregar", $newrec );
						else $resstr.= $newrec;
						
						$crecs++;							
					}
				}

				/*
				$new_record = str_replace(	"xmlrecord",
																	"xmlrecord_agregar",
																	$CRecordDefinition->Edit( '_edetalle_'.$CTipoDetalle->m_tipo.'_X_', 
																									"", 																									
																									$tmpl, 
																									$script )										
																);
																*/
																
				if ($tmpl=="") $agregar = '<tfoot><tr><td colspan="'.count($CRecordDefinition->m_CFields).'"></td><td>##AGREGAR##</td></tr></tfoot>';

				if ($tmpl!="") $resstr.= $new_record.$tmplFT;
				else $resstr.=  $new_record.'</tbody>'.$agregar.'</table>';
				
				
				$resstr = str_replace( array("##AGREGAR##","##TABLE##","##NRECORDS##"), 
																array( '<a href="javascript:XMLAgregar(\'label__edetalle_'.$CTipoDetalle->m_tipo.'\',\''.$crecs_valids.'\',\''.$CRecordDefinition->m_maxrecords.'\');">Agregar</a>',
																				'label__edetalle_'.$CTipoDetalle->m_tipo,
																				$crecs_valids ), 
																$resstr );
				
				//ShowMessage("CDetalles:Edit:Y> resultado: <textarea>".$resstr."</textarea>");
				break;
			case "R": //CONTENT REFERENCE
			case "RC": //CONTENT REFERENCE
				//referencia a un listado por SQL->idresultante o combinacion...
				//posibilidad de navegar a ese mismo contenido a traves de un navegador				
				$tipospl = explode( "\n", $CTipoDetalle->m_txtdata );							
				if (is_array($tipospl)) {
					if ( trim($tipospl[0])=="COMBO") {
						$referenciaSQL = TiposParsing(trim($tipospl[1]));
						$referenciaSQLCOUNT = TiposParsing(trim($tipospl[2]));
						//$referenciaCAMPO = trim($tipospl[3]);
						$referenciaSCRIPT = TiposParsing(trim($tipospl[3]));
						$resstr.= '<SELECT name="_edetalle_'.$CTipoDetalle->m_tipo.'" id="_edetalle_'.$CTipoDetalle->m_tipo.'_SEL" '.$referenciaSCRIPT.'>';
					} else if (trim($tipospl[0])=="COMBOOPTIONAL") {
						$referenciaTEXTO = trim($tipospl[1]);
						$referenciaSQL = TiposParsing(trim($tipospl[2]));
						$referenciaSQLCOUNT = TiposParsing(trim($tipospl[3]));
						$referenciaSCRIPT = TiposParsing(trim($tipospl[4]));
						$resstr.=  '<SELECT name="_edetalle_'.$CTipoDetalle->m_tipo.'" id="_edetalle_'.$CTipoDetalle->m_tipo.'_SEL" '.$referenciaSCRIPT.'>';
						($CDetalle->m_detalle == 0 || $CDetalle->m_detalle=="") ? $sel="selected" : $sel = ""; 
						$resstr.=  '<OPTION value="0" '.$sel.'>'.$referenciaTEXTO.'</OPTION>';								
					} else if (trim($tipospl[0])=="COMBOAJAX") {
						
						$referenciaTEXTO = trim($tipospl[1]);
						$referenciaTIPOID = trim($tipospl[2]);
						
						global $_tcontenidos2_;
						if (is_numeric($CDetalle->m_entero) && $CDetalle->m_entero>0) {
							$_tcontenidos2_->LimpiarSQL();
							$_tcontenidos2_->FiltrarSQL('ID','',$CDetalle->m_entero);
							$_tcontenidos2_->Open();
							if ($_tcontenidos2_->nresultados>0) {
								$_row_ = $_tcontenidos2_->Fetch();
								$CC = new CContenido($_row_);
								$titulo_ref = $CC->Titulo();
							} else {
								DebugError($CTipoDetalle->m_tipo." error, referencia a objeto no encontrada. id : [".$CDetalle->m_entero."]");
							}
						}
						/*
						<input class="style9" style="border-width: 0px;width: 220px;" type="text" 
						onfocus="javascript:DestinationManager.OnFocus('<?=$CLang->m_Words["TYPEDESTINATION"]?>');" value="<?=$CLang->m_Words["TYPEDESTINATION"]?>" name="textdestination" id="textdestination" 
						onblur="javascript:DestinationManager.OnBlur();" 
						onkeypress="startautocompletedestination( event, 'searchdestination(\'textdestination\',\'divdestination\')',300);" />				
						*/
						
						$divcontent = "div".$CTipoDetalle->m_tipo;
						$textcontent = "text".$CTipoDetalle->m_tipo;
						$fieldid = "_edetalle_".$CTipoDetalle->m_tipo;
						$fieldtext = "_edetalle_".$CTipoDetalle->m_tipo.'_TXT';
						
						$content_manager = $divcontent.'_manager';
						
						$resstr.= "<script> 
						var $content_manager;
						$content_manager = new ContentManager( $referenciaTIPOID,'$divcontent','$textcontent','$fieldtext', '$fieldid' );
						</script>";
						
												
						$resstr.=  '<input name="'.$textcontent.'" id="'.$textcontent.'" type="text" value="'.$titulo_ref.'" 
							onfocus="javascript:'.$content_manager.'.OnFocus();"
							onblur="javascript:'.$content_manager.'.OnBlur();"
							onkeypress="javascript:'.$content_manager.'.StartAutoComplete( event, 300 );">';
						
						$resstr.=  '<input name="'.$fieldid.'" id="'.$fieldid.'" type="hidden" value="'.$CDetalle->m_entero.'">';
						$resstr.=  '<input name="'.$fieldtext.'" id="'.$fieldtext.'" type="hidden" value="'.$titulo_ref.'">';
						
						$resstr.=  '<div id="'.$divcontent.'" style="position:absolute;display:none;"></div>';
						$resstr.=  '<div id="'.$divcontent.'loader" style="position:absolute;display:none;"></div>';
						
					}
					global $_tcontenidos2_;
					if ( trim($tipospl[0])=="COMBO" || trim($tipospl[0])=="COMBOOPTIONAL") {
						$_tcontenidos2_->LimpiarSQL();
						$_tcontenidos2_->SQL = $referenciaSQL;
						$_tcontenidos2_->SQLCOUNT = $referenciaSQLCOUNT;
						$_tcontenidos2_->Open();
						if ( $_tcontenidos2_->nresultados>0 ) {
							while($_row_ = $_tcontenidos2_->Fetch() ) {
								$CC = new CContenido($_row_);
								$CC->m_id== $CDetalle->m_entero ? $sel = "selected" : $sel = "";
								$resstr.= '<OPTION value="'.$CC->m_id.'" '.$sel.'>'.$CC->Titulo().'</OPTION>';										
							}
						}								
						$resstr.=  '</SELECT>';
					}							
				} else {
					$tipos = "&_tipocontenido_=";							
					foreach ($tipospl as $k) { $tipos.= "|".$k; }						
					$resstr.=  '<input name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="text" size="'.$cols.'" value="'.$CDetalle->m_detalle.'"><a href="javascript:navegadorcontenido(\'confirmar._edetalle_'.$CTipoDetalle->m_tipo.'\',\''.$tipos.'\');"><span class="modulo_admin_detalle_campo">'.$CLang->m_Words['SELECTCONTENT'].'</span></a>';
				}							
				break;
			case "H": //SECTION REFERENCE
			case "RS": //CONTENT REFERENCE
				//referencia a un listado por SQL->idresultante o combinacion...
				//posibilidad de navegar a ese mismo contenido a traves de un navegador				
				$tipospl = explode( "\n", $CTipoDetalle->m_txtdata );							
				if (is_array($tipospl)) {
					if ( trim($tipospl[0])=="COMBO") {
						$referenciaSQL = TiposParsing(trim($tipospl[1]));
						$referenciaSQLCOUNT = TiposParsing(trim($tipospl[2]));
						//$referenciaCAMPO = trim($tipospl[3]);
						$referenciaSCRIPT = TiposParsing(trim($tipospl[3]));
						$resstr.= '<SELECT name="_edetalle_'.$CTipoDetalle->m_tipo.'" id="_edetalle_'.$CTipoDetalle->m_tipo.'_SEL" '.$referenciaSCRIPT.'>';
					} else if (trim($tipospl[0])=="COMBOOPTIONAL") {
						$referenciaTEXTO = trim($tipospl[1]);
						$referenciaSQL = TiposParsing(trim($tipospl[2]));
						$referenciaSQLCOUNT = TiposParsing(trim($tipospl[3]));
						$referenciaSCRIPT = TiposParsing(trim($tipospl[4]));
						$resstr.=  '<SELECT name="_edetalle_'.$CTipoDetalle->m_tipo.'" id="_edetalle_'.$CTipoDetalle->m_tipo.'_SEL" '.$referenciaSCRIPT.'>';
						($CDetalle->m_detalle == 0 || $CDetalle->m_detalle=="") ? $sel="selected" : $sel = ""; 
						$resstr.=  '<OPTION value="0" '.$sel.'>'.$referenciaTEXTO.'</OPTION>';								
					} else if (trim($tipospl[0])=="COMBOAJAX") {
						
						$referenciaTEXTO = trim($tipospl[1]);
						$referenciaTIPOID = trim($tipospl[2]);
						global $_tsecciones2_;
						if (is_numeric($CDetalle->m_entero) && $CDetalle->m_entero>0) {
							$_tsecciones2_->LimpiarSQL();
							$_tsecciones2_->FiltrarSQL('ID','',$CDetalle->m_entero);
							$_tsecciones2_->Open();
							if ($_tsecciones2_->nresultados>0) {
								$_row_ = $_tsecciones2_->Fetch();
								$CS = new CSeccion($_row_);
								$titulo_ref = $CS->Nombre();
							} else {
								DebugError($CTipoDetalle->m_tipo." error, referencia a objeto no encontrada. id : [".$CDetalle->m_entero."]");
							}	
						}
						/*
						<input class="style9" style="border-width: 0px;width: 220px;" type="text" 
						onfocus="javascript:DestinationManager.OnFocus('<?=$CLang->m_Words["TYPEDESTINATION"]?>');" value="<?=$CLang->m_Words["TYPEDESTINATION"]?>" name="textdestination" id="textdestination" 
						onblur="javascript:DestinationManager.OnBlur();" 
						onkeypress="startautocompletedestination( event, 'searchdestination(\'textdestination\',\'divdestination\')',300);" />				
						*/
						
						$divcontent = "div".$CTipoDetalle->m_tipo;
						$textcontent = "text".$CTipoDetalle->m_tipo;
						$fieldid = "_edetalle_".$CTipoDetalle->m_tipo;
						$fieldtext = "_edetalle_".$CTipoDetalle->m_tipo.'_TXT';
						
						$content_manager = $divcontent.'_manager';
						
						$resstr.= "<script> 
						var $content_manager;
						$content_manager = new ContentManager( $referenciaTIPOID,'$divcontent','$textcontent','$fieldtext', '$fieldid' );
						</script>";
						
												
						$resstr.=  '<input name="'.$textcontent.'" id="'.$textcontent.'" type="text" value="'.$nombre_ref.'" 
							onfocus="javascript:'.$content_manager.'.OnFocus();"
							onblur="javascript:'.$content_manager.'.OnBlur();"
							onkeypress="javascript:'.$content_manager.'.StartAutoComplete( event, 300 );">';
						
						$resstr.=  '<input name="'.$fieldid.'" id="'.$fieldid.'" type="hidden" value="'.$CDetalle->m_entero.'">';
						$resstr.=  '<input name="'.$fieldtext.'" id="'.$fieldtext.'" type="hidden" value="'.$nombre_ref.'">';
						
						$resstr.=  '<div id="'.$divcontent.'" style="position:absolute;display:none;"></div>';
						$resstr.=  '<div id="'.$divcontent.'loader" style="position:absolute;display:none;"></div>';
						
					}
					global $_tsecciones2_;
					if ( trim($tipospl[0])=="COMBO" || trim($tipospl[0])=="COMBOOPTIONAL") {
						$_tsecciones2_->LimpiarSQL();
						$_tsecciones2_->SQL = $referenciaSQL;
						$_tsecciones2_->SQLCOUNT = $referenciaSQLCOUNT;
						$_tsecciones2_->Open();
						if ( $_tsecciones2_->nresultados>0 ) {
							while($_row_ = $_tsecciones2_->Fetch() ) {
								$CS = new CSeccion($_row_);
								$CS->m_id== $CDetalle->m_entero ? $sel = "selected" : $sel = "";
								$resstr.= '<OPTION value="'.$CS->m_id.'" '.$sel.'>'.$CS->Nombre().'</OPTION>';										
							}
						}								
						$resstr.=  '</SELECT>';
					}							
				} else {
					$tipos = "&_tipocontenido_=";							
					foreach ($tipospl as $k) { $tipos.= "|".$k; }						
					$resstr.=  '<input name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="text" size="'.$cols.'" value="'.$CDetalle->m_detalle.'"><a href="javascript:navegadorcontenido(\'confirmar._edetalle_'.$CTipoDetalle->m_tipo.'\',\''.$tipos.'\');"><span class="modulo_admin_detalle_campo">'.$CLang->m_Words['SELECTSECTION'].'</span></a>';
				}							
				break;				
			case "O": //REFERENCE TO SEVERAL CONTENTS
			case "RCx": //REFERENCE TO SEVERAL CONTENTS
				//referencia a un listado por SQL->idresultante o combinacion...
				//posibilidad de navegar a ese mismo contenido a traves de un navegador								
				$tipospl = explode( "\n", $CTipoDetalle->m_txtdata );							
				if ($CDetalle->m_id_contenido=="") {
					$resstr.=  '<input name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="text" value="'.$CDetalle->m_detalle.'">';					
				} else if (is_array($tipospl)) {
					$referenciaEstilo = trim($tipospl[0]);
					$referenciaSQL = TiposParsing(trim($tipospl[1]));
					$referenciaSQLCOUNT = TiposParsing(trim($tipospl[2]));
					$hide = "";
					if (isset($tipospl[3])) $hide = trim($tipospl[3]);
					if ($hide=="") $hide='1';
					$lastcallback = "";					
					if ( count( $tipospl ) == 5 ) {
						$lastcallback = trim($tipospl[4]);
					}
					
					$resstr.= '<table width="100%" cellpadding="0" cellspacing="0"><tr><td>';					
					$resstr.= '<div class="relaciones relaciones-'.$CTipoDetalle->m_tipo.' scroll-pane" id="div_relaciones_'.$CTipoDetalle->m_tipo.'" name="div_relaciones_'.$CTipoDetalle->m_tipo.'">
					</div><div id="div_relaciones_'.$CTipoDetalle->m_tipo.'loader" name="div_relaciones_'.$CTipoDetalle->m_tipo.'loader" style="margin:0px;padding:0px;display:none;"></div>';
					$resstr.= '<div class="pop-relaciones pop-relaciones-'.$CTipoDetalle->m_tipo.'" id="div_select_relaciones_'.$CTipoDetalle->m_tipo.'" name="div_select_relaciones_'.$CTipoDetalle->m_tipo.'"></div>
					<div id="div_select_relaciones_'.$CTipoDetalle->m_tipo.'loader" name="div_select_relaciones_'.$CTipoDetalle->m_tipo.'loader" style="margin:0px;padding:0px;display:none;"></div>';
					
					if ($referenciaEstilo=="COMBO" || $referenciaEstilo=="LISTBOX") {					
						if ($hide=='1') $resstr.= '<a class="add-relaciones add-relaciones-'.$CTipoDetalle->m_tipo.'" href="javascript:relaciones_add( \''.$CTipoDetalle->m_tipo.'\', \'contenidos\','.$CDetalle->m_id_contenido.',\''.$referenciaSQL.'\',\''.$referenciaSQLCOUNT.'\', \''.$hide.'\');">'.$CLang->m_Words["ADD"].' '.$CTipoDetalle->Descripcion().'</a>';
					} else if ($referenciaEstilo=="AUTOCOMPLETE") {
						$resstr.= '<div class="add-relaciones-autocomplete add-relaciones-autocomplete-'.$CTipoDetalle->m_tipo.'"><label></label>';
						$resstr.= "
						<input 
        	onblur=\"javascript:setTimeout(' hidediv(\'div_select_relaciones_".$CTipoDetalle->m_tipo."\')', 1000 );\"
        	onfocus=\"javascript:document.getElementById('autocomplete_".$CTipoDetalle->m_tipo."').value='';\"
        	onkeypress=\"preventSubmit(event);\"
        	onkeyup=\"setTimeout('relaciones_add_autocomplete( \''+event.keyCode+'\', \'autocomplete_".$CTipoDetalle->m_tipo."\',\'text\',\'".$CTipoDetalle->m_tipo."\',\'contenidos\',\'".$CDetalle->m_id_contenido."\',\'".$referenciaSQL."\',\'".$referenciaSQLCOUNT."\',\'".$hide."\',\'".$lastcallback."\')',300)\"
        	type=\"text\"
        	class=\"text add-relaciones-autocomplete\" 
        	id=\"autocomplete_".$CTipoDetalle->m_tipo."\"
        	name=\"autocomplete_".$CTipoDetalle->m_tipo."\" 
        	value=\"".$CLang->m_Words["PLEASETYPE"]." ".$CTipoDetalle->Descripcion()."\" 
			autocomplete=\"off\"></div>";
        	
					}
					
					$resstr.= '<script>';
					$resstr.= 'relaciones_update( \''.$CTipoDetalle->m_tipo.'\', \'contenidos\','.$CDetalle->m_id_contenido.',\''.$referenciaSQL.'\',\''.$referenciaSQLCOUNT.'\',\''.$hide.'\');';
					if ($hide=='0' && ($referenciaEstilo=="COMBO" || $referenciaEstilo=="LISTBOX")) $resstr.= 'relaciones_add( \''.$CTipoDetalle->m_tipo.'\', \'contenidos\','.$CDetalle->m_id_contenido.',\''.$referenciaSQL.'\',\''.$referenciaSQLCOUNT.'\',\''.$hide.'\',\''.$lastcallback.'\');';					
					$resstr.= '</script>';
					
					$resstr.= '</td></tr></table>';
					$resstr.=  '<input name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" size="'.$cols.'" value="'.$CDetalle->m_detalle.'">';
				} else {
					$tipos = "&_tipocontenido_=";							
					foreach ($tipospl as $k) { $tipos.= "|".$k; }						
					$resstr.=  '<input name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="text" size="'.$cols.'" value="'.$CDetalle->m_detalle.'"><a href="javascript:navegadorcontenido(\'confirmar._edetalle_'.$CTipoDetalle->m_tipo.'\',\''.$tipos.'\');"><span class="modulo_admin_detalle_campo">'.$CLang->m_Words['SELECTCONTENT'].'</span></a>';
				}							
				break;
		case "RTCx": //REFERENCE TO SEVERAL CONTENT TYPES
				//referencia a un listado por SQL->idresultante o combinacion...
				//posibilidad de navegar a ese mismo contenido a traves de un navegador								
				$tipospl = explode( "\n", $CTipoDetalle->m_txtdata );							
				if ($CDetalle->m_id_contenido=="") {
					$resstr.=  '<input name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="text" value="'.$CDetalle->m_detalle.'">';					
				} else if (is_array($tipospl)) {
					$referenciaEstilo = trim($tipospl[0]);
					$referenciaSQL = TiposParsing(trim($tipospl[1]));
					$referenciaSQLCOUNT = TiposParsing(trim($tipospl[2]));
					$hide = "";
					if (isset($tipospl[3])) $hide = trim($tipospl[3]);
					if ($hide=="") $hide='1';
					$lastcallback = "";					
					if ( count( $lastcallback ) == 5 ) {
						$lastcallback = trim($tipospl[4]);
					}
					
					$resstr.= '<table width="100%" cellpadding="0" cellspacing="0"><tr><td>';					
					$resstr.= '<div class="relaciones relaciones-'.$CTipoDetalle->m_tipo.' scroll-pane" id="div_relaciones_'.$CTipoDetalle->m_tipo.'" name="div_relaciones_'.$CTipoDetalle->m_tipo.'">
					</div><div id="div_relaciones_'.$CTipoDetalle->m_tipo.'loader" name="div_relaciones_'.$CTipoDetalle->m_tipo.'loader" style="margin:0px;padding:0px;display:none;"></div>';
					$resstr.= '<div class="pop-relaciones pop-relaciones-'.$CTipoDetalle->m_tipo.'" id="div_select_relaciones_'.$CTipoDetalle->m_tipo.'" name="div_select_relaciones_'.$CTipoDetalle->m_tipo.'"></div>
					<div id="div_select_relaciones_'.$CTipoDetalle->m_tipo.'loader" name="div_select_relaciones_'.$CTipoDetalle->m_tipo.'loader" style="margin:0px;padding:0px;display:none;"></div>';
					if ($hide=='1') $resstr.= '<a class="add-relaciones" href="javascript:relaciones_add( \''.$CTipoDetalle->m_tipo.'\', \'tiposcontenidos\','.$CDetalle->m_id_contenido.',\''.$referenciaSQL.'\',\''.$referenciaSQLCOUNT.'\', \''.$hide.'\');">'.$CLang->m_Words["ADD"].' '.$CTipoDetalle->Descripcion().'</a>';
					$resstr.= '<script>';
					$resstr.= 'relaciones_update( \''.$CTipoDetalle->m_tipo.'\', \'tiposcontenidos\','.$CDetalle->m_id_contenido.',\''.$referenciaSQL.'\',\''.$referenciaSQLCOUNT.'\',\''.$hide.'\');';
					if ($hide=='0') $resstr.= 'relaciones_add( \''.$CTipoDetalle->m_tipo.'\', \'tiposcontenidos\','.$CDetalle->m_id_contenido.',\''.$referenciaSQL.'\',\''.$referenciaSQLCOUNT.'\',\''.$hide.'\',\''.$lastcallback.'\');';
					$resstr.= '</script>';				
					$resstr.= '</td></tr></table>';
					$resstr.=  '<input name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" size="'.$cols.'" value="'.$CDetalle->m_detalle.'">';
				} else {
					$tipos = "&_tipocontenido_=";							
					foreach ($tipospl as $k) { $tipos.= "|".$k; }						
					$resstr.=  '<input name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="text" size="'.$cols.'" value="'.$CDetalle->m_detalle.'"><a href="javascript:navegadorcontenido(\'confirmar._edetalle_'.$CTipoDetalle->m_tipo.'\',\''.$tipos.'\');"><span class="modulo_admin_detalle_campo">'.$CLang->m_Words['SELECTCONTENT'].'</span></a>';
				}							
				break;
			case "RTSx": //REFERENCE TO SEVERAL SECTION TYPES
				//referencia a un listado por SQL->idresultante o combinacion...
				//posibilidad de navegar a ese mismo contenido a traves de un navegador								
				$tipospl = explode( "\n", $CTipoDetalle->m_txtdata );							
				if ($CDetalle->m_id_contenido=="") {
					$resstr.=  '<input name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="text" value="'.$CDetalle->m_detalle.'">';					
				} else if (is_array($tipospl)) {
					$referenciaEstilo = trim($tipospl[0]);
					$referenciaSQL = TiposParsing(trim($tipospl[1]));
					$referenciaSQLCOUNT = TiposParsing(trim($tipospl[2]));
					$hide = "";
					if (isset($tipospl[3])) $hide = trim($tipospl[3]);
					if ($hide=="") $hide='1';
					$lastcallback = "";					
					if ( count( $lastcallback ) == 5 ) {
						$lastcallback = trim($tipospl[4]);
					}
					
					$resstr.= '<table width="100%" cellpadding="0" cellspacing="0"><tr><td>';					
					$resstr.= '<div class="relaciones relaciones-'.$CTipoDetalle->m_tipo.' scroll-pane" id="div_relaciones_'.$CTipoDetalle->m_tipo.'" name="div_relaciones_'.$CTipoDetalle->m_tipo.'">
					</div><div id="div_relaciones_'.$CTipoDetalle->m_tipo.'loader" name="div_relaciones_'.$CTipoDetalle->m_tipo.'loader" style="margin:0px;padding:0px;display:none;"></div>';
					$resstr.= '<div class="pop-relaciones pop-relaciones-'.$CTipoDetalle->m_tipo.'" id="div_select_relaciones_'.$CTipoDetalle->m_tipo.'" name="div_select_relaciones_'.$CTipoDetalle->m_tipo.'"></div>
					<div id="div_select_relaciones_'.$CTipoDetalle->m_tipo.'loader" name="div_select_relaciones_'.$CTipoDetalle->m_tipo.'loader" style="margin:0px;padding:0px;display:none;"></div>';
					if ($hide=='1') $resstr.= '<a class="add-relaciones" href="javascript:relaciones_add( \''.$CTipoDetalle->m_tipo.'\', \'tipossecciones\','.$CDetalle->m_id_contenido.',\''.$referenciaSQL.'\',\''.$referenciaSQLCOUNT.'\', \''.$hide.'\');">'.$CLang->m_Words["ADD"].' '.$CTipoDetalle->Descripcion().'</a>';
					$resstr.= '<script>';
					$resstr.= 'relaciones_update( \''.$CTipoDetalle->m_tipo.'\', \'tipossecciones\','.$CDetalle->m_id_contenido.',\''.$referenciaSQL.'\',\''.$referenciaSQLCOUNT.'\',\''.$hide.'\');';
					if ($hide=='0') $resstr.= 'relaciones_add( \''.$CTipoDetalle->m_tipo.'\', \'tipossecciones\','.$CDetalle->m_id_contenido.',\''.$referenciaSQL.'\',\''.$referenciaSQLCOUNT.'\',\''.$hide.'\',\''.$lastcallback.'\');';
					$resstr.= '</script>';				
					$resstr.= '</td></tr></table>';
					$resstr.=  '<input name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" size="'.$cols.'" value="'.$CDetalle->m_detalle.'">';
				} else {
					$tipos = "&_tipocontenido_=";							
					foreach ($tipospl as $k) { $tipos.= "|".$k; }						
					$resstr.=  '<input name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="text" size="'.$cols.'" value="'.$CDetalle->m_detalle.'"><a href="javascript:navegadorcontenido(\'confirmar._edetalle_'.$CTipoDetalle->m_tipo.'\',\''.$tipos.'\');"><span class="modulo_admin_detalle_campo">'.$CLang->m_Words['SELECTCONTENT'].'</span></a>';
				}							
				break;							
			case "P": //REFERENCE TO SEVERAL SECTIONS
			case "RSx": //REFERENCE TO SEVERAL SECTIONS
				//referencia a un listado por SQL->idresultante o combinacion...
				//posibilidad de navegar a ese mismo contenido a traves de un navegador								
				$tipospl = explode( "\n", $CTipoDetalle->m_txtdata );							
				if ($CDetalle->m_id_contenido=="") {
					$resstr.=  '<input name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="text" value="'.$CDetalle->m_detalle.'">';					
				} else if (is_array($tipospl)) {
					$referenciaEstilo = trim($tipospl[0]);
					$referenciaSQL = TiposParsing(trim($tipospl[1]));
					$referenciaSQLCOUNT = TiposParsing(trim($tipospl[2]));
					$hide = "";
					$lastcallback = "";
					if (isset($tipospl[3])) $hide = trim($tipospl[3]);
					if ($hide=="") $hide='1';
					if (count($tipospl)==5) {
						$lastcallback = trim($tipospl[4]);
					}					
					$resstr.= '<table width="100%" cellpadding="0" cellspacing="0"><tr><td>';					
					$resstr.= '<div class="relaciones relaciones-'.$CTipoDetalle->m_tipo.'" id="div_relaciones_'.$CTipoDetalle->m_tipo.'" name="div_relaciones_'.$CTipoDetalle->m_tipo.'">
					</div><div id="div_relaciones_'.$CTipoDetalle->m_tipo.'loader" name="div_relaciones_'.$CTipoDetalle->m_tipo.'loader" style="margin:0px;padding:0px;display:none;"></div>';
					$resstr.= '<div class="pop-relaciones pop-relaciones-'.$CTipoDetalle->m_tipo.'" id="div_select_relaciones_'.$CTipoDetalle->m_tipo.'" name="div_select_relaciones_'.$CTipoDetalle->m_tipo.'"></div>
					<div id="div_select_relaciones_'.$CTipoDetalle->m_tipo.'loader" name="div_select_relaciones_'.$CTipoDetalle->m_tipo.'loader" style="margin:0px;padding:0px;display:none;"></div>';
					if ($hide=='1') $resstr.= '<a class="add-relaciones" href="javascript:relaciones_add( \''.$CTipoDetalle->m_tipo.'\', \'secciones\','.$CDetalle->m_id_contenido.',\''.$referenciaSQL.'\',\''.$referenciaSQLCOUNT.'\', \''.$hide.'\',\''.$lastcallback.'\');">'.$CLang->m_Words["ADD"].' '.$CTipoDetalle->Descripcion().'</a>';
					$resstr.= '<script>';
					$resstr.= 'relaciones_update( \''.$CTipoDetalle->m_tipo.'\', \'secciones\','.$CDetalle->m_id_contenido.',\''.$referenciaSQL.'\',\''.$referenciaSQLCOUNT.'\',\''.$hide.'\');';
					if ($hide=='0') $resstr.= 'relaciones_add( \''.$CTipoDetalle->m_tipo.'\', \'secciones\','.$CDetalle->m_id_contenido.',\''.$referenciaSQL.'\',\''.$referenciaSQLCOUNT.'\',\''.$hide.'\',\''.$lastcallback.'\');';
					$resstr.= '</script>';					
					$resstr.= '</td></tr></table>';
					$resstr.=  '<input name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" size="'.$cols.'" value="'.$CDetalle->m_detalle.'">';
				} else {
					$tipos = "&_tipocontenido_=";							
					foreach ($tipospl as $k) { $tipos.= "|".$k; }						
					$resstr.=  '<input name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="text" size="'.$cols.'" value="'.$CDetalle->m_detalle.'"><a href="javascript:navegadorcontenido(\'confirmar._edetalle_'.$CTipoDetalle->m_tipo.'\',\''.$tipos.'\');"><span class="modulo_admin_detalle_campo">'.$CLang->m_Words['SELECTCONTENT'].'</span></a>';
				}							
				break;
			case "FD":
				$resstr.=  '<input id="_edetalle_'.$CTipoDetalle->m_tipo.'" name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="text" value="'.$CDetalle->m_detalle.'">'.UI_DatePicker('_edetalle_'.$CTipoDetalle->m_tipo);
				break;		
			case "FDT":
				$resstr.=  '<input id="_edetalle_'.$CTipoDetalle->m_tipo.'" name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="text" value="'.$CDetalle->m_detalle.'">'.UI_DateTimePicker('_edetalle_'.$CTipoDetalle->m_tipo);
				break;		
			case "FTT":
				$resstr.=  '<input id="_edetalle_'.$CTipoDetalle->m_tipo.'" name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="text" value="'.$CDetalle->m_detalle.'">'.UI_TimePicker('_edetalle_'.$CTipoDetalle->m_tipo);
				break;		
			case "K"://LINK
				$resstr.=  '<input id="_edetalle_'.$CTipoDetalle->m_tipo.'" name="_edetalle_'.$CTipoDetalle->m_tipo.'" type="text" size="'.$cols.'" value="'.$CDetalle->m_detalle.'">';
				break;
			case "S"://SELECT
				//echo "detalle:".$CDetalle->m_detalle;
				$resstr.=  '<select name="_edetalle_'.$CTipoDetalle->m_tipo.'" id="_edetalle_'.$CTipoDetalle->m_tipo.'_SEL">';
				$soptions = explode( "\n" , $CTipoDetalle->m_txtdata );	
				sort($soptions);
				foreach( $soptions as $sopt ) {
					$sopt = trim($sopt);
					$soptx = explode( ":", $sopt );
					$val = trim($soptx[0]);		
					($val == $sopt) ? $nam = $val : $nam = $soptx[1];
					(trim($CDetalle->m_detalle) == $val) ? $sel = "selected" : $sel = "";
					$resstr.=  '<option value="'.$val.'" '.$sel.'>'.$nam.'</option>';															
				}
				$resstr.=  '</select>';
				break;
		}
		
		
		
		if ($CMultiLang->Activo()) {						
			switch($CTipoDetalle->m_tipocampo) {
				case "T":								
					$resstr.=  '<input id="_emldetalle_'.$CTipoDetalle->m_tipo.'" name="_emldetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" value="'.$CDetalle->m_ml_detalle.'">';
					foreach( $CMultiLang->m_arraylangs as $idioma=>$codigo ) {
						$resstr.=  '<table cellpadding="0" cellspacing="0"><tr><td><div id="did'.$codigo.'_emldetalle_'.$CTipoDetalle->m_tipo.'" class="did'.$codigo.'"><img src="'.$_DIR_SITEABS.'/inc/images/flags/'.$codigo.'.jpg" width="21" height="11" border="0"><br>';
						$resstr.=  '<input id="_emldetalle_'.$CTipoDetalle->m_tipo.'_'.$codigo.'" name="_emldetalle_'.$CTipoDetalle->m_tipo.'_'.$codigo.'" type="text" value="'.TextoML($CDetalle->m_ml_detalle,$codigo).'" size="" onChange="javascript:completeML(\'_emldetalle_'.$CTipoDetalle->m_tipo.'\',\''.$codigo.'\')" >';													
						$resstr.=  '</div></td></tr></table>';
					}
					break;								
				case "L":
				case "B":
					$resstr.=  '<input id="_emldetalle_'.$CTipoDetalle->m_tipo.'" name="_emldetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" value="'.str_replace('"',"&quot;",$CDetalle->m_ml_detalle).'">';																
					foreach( $CMultiLang->m_arraylangs as $idioma=>$codigo ) {
						$resstr.=  '<table cellpadding="0" cellspacing="0"><tr><td><div id="did'.$codigo.'_emldetalle_'.$CTipoDetalle->m_tipo.'"  class="did'.$codigo.'"><img src="'.$_DIR_SITEABS.'/inc/images/flags/'.$codigo.'.jpg" width="21" height="11" border="0"><br>';
						$resstr.=  '<textarea id="_emldetalle_'.$CTipoDetalle->m_tipo.'_'.$codigo.'" name="_emldetalle_'.$CTipoDetalle->m_tipo.'_'.$codigo.'" cols="'.$cols.'" rows="'.$rows.'" onChange="javascript:completeML(\'_emldetalle_'.$CTipoDetalle->m_tipo.'\',\''.$codigo.'\')">'.TextoML($CDetalle->m_ml_txtdata,$codigo).'</textarea>';
						$resstr.=  '</div></td></tr></table>';
						if ($CTipoDetalle->m_txtdata=="html" || $CTipoDetalle->m_txtdata=="filteredhtml") {
							$resstr.=  '<script> textareaEdit( \'_emldetalle_'.$CTipoDetalle->m_tipo.'\',\''.$codigo.'\'); </script>';
						}
					}								
					break;
				default:
					$resstr.=  '<input id="_emldetalle_'.$CTipoDetalle->m_tipo.'" name="_emldetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" value="'.$CDetalle->m_ml_detalle.'" size="">';
					break;
			}										
		} else {
			$resstr.=  '<input id="_emldetalle_'.$CTipoDetalle->m_tipo.'" name="_emldetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" value="'.$CDetalle->m_ml_detalle.'" size="">';						
		}
		
		/**ATENCION AQUI REVISAR PARA TOD-OS LOS TIPOS DE CAMPO QUE SE CUMPLA OK*/
		if ($tmpl!="" && is_numeric( strpos($tmpl, "*#".$CTipoDetalle->m_tipo."#*") ) ) {
			$resstr = $tmplHD.str_replace( "*#".$CTipoDetalle->m_tipo."#*", $resstr, $tmpl ).$tmplFT;
		}		
		
		return $resstr;		
		
	}
	
	
	/**
	*	Para aquellos campos especiales, formatea el campo en funcion de su tipo
	* 	Como es el caso de los archivos que se suben o los campos q necesitan un formateo especial como registros XML
	* */
	function Check( &$CDetalle, $TipoDetalle, $CLang=null, $CMultiLang=null, $Contenidos=null, $Secciones=null ) {
		
		global $_SITEROOT_;
		//global $_exito_;
		$_exito_ = true;
		if ($CLang==null) $CLang = $GLOBALS['CLang'];
		if ($CMultiLang==null) $CMultiLang = $GLOBALS['CMultiLang'];
		
		$CDetalle->m_ml_detalle = str_replace("&quot;",'"',$CDetalle->m_ml_detalle);

		switch($TipoDetalle->m_tipocampo) {
			
			case "U":
				$CDetalle->m_detalle = "";
				$CDetalle->m_txtdata = $CDetalle->m_detalle;
				$_exito_ = true;
				break;
							
			case "G":
			case "M":
			case "W":
			//MULTIPLE ARCHIVES WITH COMMENTS    							
			//primero tomamos la lista del select en el orden dado...
			( ($CDetalle->m_detalle != "") && ($CDetalle->m_detalle != "empty")) ? $sep = "\n": $sep ="";
				
				//luego por cada archivo existente, agregamos una entrada...a la lista
			$ADataDef = XData2Array($TipoDetalle->m_txtdata);    						
			for( $if=0; $if < $ADataDef['maxuploads']['values']; $if++) {
				$tmpname = $_FILES['_fdetalle_'.$TipoDetalle->m_tipo.'_F'.$if]["tmp_name"];
				
				$tmpname = trim($tmpname);
				if ($tmpname!="") {    							
					$link = $GLOBALS['_fdetalle_'.$TipoDetalle->m_tipo.'_L'.$if];
					$comment = $GLOBALS['_fdetalle_'.$TipoDetalle->m_tipo.'_T'.$if];
					if ( is_uploaded_file($tmpname)) {
	    				$name = $_FILES['_fdetalle_'.$TipoDetalle->m_tipo.'_F'.$if]["name"];
	    				$name = uniquecode(clean(replace_flat($name)));    				
	    				$tmpmov = $TipoDetalle->m_tipo.$CDetalle->m_id.$name;
	    				$tmpmov = replace_specials($tmpmov);
						$_exito_ = tmp_to_local($tmpname,$_SITEROOT_.'/tmp/'.$tmpmov);			    					
						if ($TipoDetalle->m_tipocampo=='G') {//para Imagenes
							if($_exito_) $_exito_ = thumbnail( $_SITEROOT_, '/tmp/'.$tmpmov, $ADataDef['width']['values'], "/archivos/imagen", $tmpmov, $ADataDef['height']['values']);
	    					if($_exito_) {
	    						chmod_ftp('/archivos/imagen/'.$tmpmov);
	    						//thumbnail(S_SITEROOT_,$_urlnuevo_,121,dirname($_urlnuevo_)."/thm",basename($_urlnuevo_));
	    						thumbnail( $_SITEROOT_, "/archivos/imagen/".$tmpmov, $ADataDef['thmwidth']['values'], "/archivos/imagen/thm", $tmpmov, $ADataDef['thmheight']['values']);
	    						chmod_ftp('/archivos/imagen/thm/'.$tmpmov);
	    						$CDetalle->m_detalle.= $sep.wiwe_dir_siteabs('../../archivos/imagen/'.$tmpmov);
	    						$CDetalle->m_detalle.="::".$comment;
	    						$sep = "\n";
	    					}
						} else {//para DOCS...y otros
							if($_exito_) $_exito_ = rename_ftp('/archivos/documentacion/'.$tmpmov,'/tmp/'.$tmpmov);
							chmod_ftp('/archivos/documentacion/'.$tmpmov);
							$CDetalle->m_detalle.= $sep.wiwe_dir_siteabs('../../archivos/documentacion/'.$tmpmov);
							$CDetalle->m_detalle.="::".$comment;
							$sep = "\n";
						}
					} else if( $link!="" ) {					
						$CDetalle->m_detalle.= $sep.$link;
						$CDetalle->m_detalle.="::".$comment;
						$sep = "\n";
					} else {
						//$this->m_CErrores->PushError( new CError("UPLOADERROR:".$tmpname,"uploading error confirming detail") );
						$this->PushError( new CError("UPLOADERROR:".$tmpname,"uploading error confirming detail") );
						$_exito_=false;
					}
				}
			}
			$CDetalle->m_txtdata = $CDetalle->m_detalle;
			break;			
				
			case "D":
			case "A":
			case "V":
				
				$tmpname = $_FILES['_fdetalle_'.$TipoDetalle->m_tipo]["tmp_name"];
				echo "Receiving file: ".$tmpname;
				
				$name = $_FILES['_fdetalle_'.$TipoDetalle->m_tipo]["name"];
				$size = $_FILES['_fdetalle_'.$TipoDetalle->m_tipo]["size"];
				$type = $_FILES['_fdetalle_'.$TipoDetalle->m_tipo]["type"];
				$elerror = $_FILES['_fdetalle_'.$TipoDetalle->m_tipo]["error"];
				// UPLOAD_ERR_OK         Value: 0; There is no error, the file uploaded with success.
				// UPLOAD_ERR_INI_SIZE   Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.
				// UPLOAD_ERR_FORM_SIZE  Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
				// UPLOAD_ERR_PARTIAL    Value: 3; The uploaded file was only partially uploaded.
				// UPLOAD_ERR_NO_FILE    Value: 4; No file was uploaded.
				// UPLOAD_ERR_NO_TMP_DIR Value: 6; Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.
				// UPLOAD_ERR_CANT_WRITE Value: 7; Failed to write file to disk. Introduced in PHP 5.1.0.
				// UPLOAD_ERR_EXTENSION  Value: 8; File upload stopped by extension. Introduced in PHP 5.2.0.
				switch ($elerror) {
					case UPLOAD_ERR_OK:
						$response = 'There is no error, the file uploaded with success.';
						break;
					case UPLOAD_ERR_INI_SIZE:
						$response = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
						break;
					case UPLOAD_ERR_FORM_SIZE:
						$response = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
						break;
					case UPLOAD_ERR_PARTIAL:
						$response = 'The uploaded file was only partially uploaded.';
						break;
					case UPLOAD_ERR_NO_FILE:
						$response = 'No file was uploaded.';
						break;
					case UPLOAD_ERR_NO_TMP_DIR:
						$response = 'Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.';
						break;
					case UPLOAD_ERR_CANT_WRITE:
						$response = 'Failed to write file to disk. Introduced in PHP 5.1.0.';
						break;
					case UPLOAD_ERR_EXTENSION:
						$response = 'File upload stopped by extension. Introduced in PHP 5.2.0.';
						break;
					default:
						$response = 'Unknown error';
						break;
				}			

			if ($elerror!=UPLOAD_ERR_OK) echo "Error: ".$response;
				
			//SINGLE ARCHIVE OR DOCUMENT	      					
			$name = uniquecode(clean(replace_flat($name)));
			$tmpmov = $TipoDetalle->m_tipo.$CDetalle->m_id.$name;
			$tmpmov = replace_specials($tmpmov);
			echo "name of file: " + $name;
			
			if ($tmpname!="" && $elerror==UPLOAD_ERR_OK) {							
				if ( is_uploaded_file($tmpname) ) {															
					/*
					$_exito_ = tmp_to_local( $tmpname, $_SITEROOT_.'/tmp/'.$tmpmov );
					if($_exito_) $_exito_ = rename_ftp('/archivos/documentacion/'.$tmpmov,'/tmp/'.$tmpmov);
					chmod_ftp('/archivos/documentacion/'.$tmpmov);
					*/
					$_exito_ = tmp_to_local( $tmpname, $_SITEROOT_.'/archivos/documentacion/'.$tmpmov );
					$CDetalle->m_detalle = wiwe_dir_siteabs('../../archivos/documentacion/'.$tmpmov);
				} else {
					//$this->m_CErrores->PushError( new CError("UPLOADERROR:".$tmpname,"uploading error confirming detail") );
					$this->PushError( new CError("UPLOADERROR:".$tmpname,"uploading error confirming detail") );
					$_exito_=false;
				}
			} else echo "Error: ".$response;
			$CDetalle->m_txtdata = $CDetalle->m_detalle;
			break;		      				
			
			case "I":
			case "F":
				//SINGLE IMAGE OR PHOTO				
				$tmpname = $_FILES['_fdetalle_'.$TipoDetalle->m_tipo]["tmp_name"];				
				$name = $_FILES['_fdetalle_'.$TipoDetalle->m_tipo]["name"];
				$size = $_FILES['_fdetalle_'.$TipoDetalle->m_tipo]["size"];
				$type = $_FILES['_fdetalle_'.$TipoDetalle->m_tipo]["type"];
				$elerror = $_FILES['_fdetalle_'.$TipoDetalle->m_tipo]["error"];
				// UPLOAD_ERR_OK         Value: 0; There is no error, the file uploaded with success.
				// UPLOAD_ERR_INI_SIZE   Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.
				// UPLOAD_ERR_FORM_SIZE  Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
				// UPLOAD_ERR_PARTIAL    Value: 3; The uploaded file was only partially uploaded.
				// UPLOAD_ERR_NO_FILE    Value: 4; No file was uploaded.
				// UPLOAD_ERR_NO_TMP_DIR Value: 6; Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.
				// UPLOAD_ERR_CANT_WRITE Value: 7; Failed to write file to disk. Introduced in PHP 5.1.0.
				// UPLOAD_ERR_EXTENSION  Value: 8; File upload stopped by extension. Introduced in PHP 5.2.0.
				switch ($elerror) {
					case UPLOAD_ERR_OK:
						$response = 'There is no error, the file uploaded with success.';
						break;
					case UPLOAD_ERR_INI_SIZE:
						$response = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
						break;
					case UPLOAD_ERR_FORM_SIZE:
						$response = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
						break;
					case UPLOAD_ERR_PARTIAL:
						$response = 'The uploaded file was only partially uploaded.';
						break;
					case UPLOAD_ERR_NO_FILE:
						$response = 'No file was uploaded.';
						break;
					case UPLOAD_ERR_NO_TMP_DIR:
						$response = 'Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.';
						break;
					case UPLOAD_ERR_CANT_WRITE:
						$response = 'Failed to write file to disk. Introduced in PHP 5.1.0.';
						break;
					case UPLOAD_ERR_EXTENSION:
						$response = 'File upload stopped by extension. Introduced in PHP 5.2.0.';
						break;
					default:
						$response = 'Unknown error';
						break;
				}				
				
				$name = uniquecode(clean(replace_flat($name)));
				$tmpmov = $TipoDetalle->m_tipo.$CDetalle->m_id.$name;
				$tmpmov = replace_specials($tmpmov);
				$ADataDef = XData2Array($TipoDetalle->m_txtdata);
				if (is_numeric($size) && $size>4*2048*2048) {
					$this->PushError( new CError("UPLOADERROR","uploading SIZE>2048*2048*4 error confirming detail ".$TipoDetalle->m_tipo." file:".$name." size:".$size." type:".$type." error:".$elerror) );
					$_exito_=false;
				} else
				if (trim($tmpname)!="" && trim($name)!="") {
					if (is_uploaded_file($tmpname)) {						
						$_exito_ = tmp_to_local($tmpname,$_SITEROOT_.'/tmp/'.$tmpmov);		    					
						if($_exito_) $_exito_ = thumbnail( $_SITEROOT_, '/tmp/'.$tmpmov, $ADataDef['width']['values'], "/archivos/imagen", $tmpmov, $ADataDef['height']['values']);
						//if($_exito_) $_exito_ = rename_ftp('/archivos/imagen/'.$tmpmov,'/tmp/'.$tmpmov);
						if($_exito_) {
							chmod_ftp('/archivos/imagen/'.$tmpmov);		    						
							//thumbnail(S_SITEROOT_,$_urlnuevo_,121,dirname($_urlnuevo_)."/thm",basename($_urlnuevo_));
							$_exito_ = thumbnail( $_SITEROOT_, "/archivos/imagen/".$tmpmov, $ADataDef['thmwidth']['values'], "/archivos/imagen/thm", $tmpmov, $ADataDef['thmheight']['values']);		    						
							chmod_ftp('/archivos/imagen/thm/'.$tmpmov);
							$CDetalle->m_detalle = wiwe_dir_siteabs('../../archivos/imagen/'.$tmpmov);
						}
					} else if ($CDetalle->m_detalle!="empty") {
						$tmpmov = basename( $CDetalle->m_detalle );		    					
						//tratamos de generar el thumbnail de la imagen:
						$_exito_ = thumbnail( $_SITEROOT_, "/archivos/imagen/".$tmpmov, $ADataDef['thmwidth']['values'], "/archivos/imagen/thm", $tmpmov, $ADataDef['thmheight']['values']);
						chmod_ftp('/archivos/imagen/thm/'.$tmpmov);
					} else {
						$this->PushError( new CError("UPLOADERROR","uploading error while confirming detail ".$TipoDetalle->m_tipo." file:".$tmpname) );
						$_exito_=false;
					}
				} else {
					if ( $elerror!=UPLOAD_ERR_NO_FILE ) {
						$this->PushError( new CError("UPLOADERROR","uploading error confirming detail ".$TipoDetalle->m_tipo." file:".$name." size:".$size." type:".$type." error:".$elerror) );
						$_exito_=false;
					} else $_exito_=true; //no se intento subir ninguna imagen
				}
				//ShowMessage("exito:[".$exstr."] file:".print_r($_FILES['_fdetalle_'.$TipoDetalle->m_tipo],true));
				$CDetalle->m_txtdata = $CDetalle->m_detalle;
				break;  				
		    		
			case "X":
			$CRecordDefinition = new CXMLRecordDefinition( XData2Array( $TipoDetalle->m_txtdata ) );
			$CDetalle->m_txtdata = "";
			$recconfirm = $CRecordDefinition->Confirm( '_edetalle_'.$TipoDetalle->m_tipo );
			if ($recconfirm!="") {
				$CDetalle->m_txtdata.= $nline.$recconfirm;
				$nline = "\n";
			}				
			$CDetalle->m_detalle = $CDetalle->m_txtdata;				
			break;
			
			case "Y": //XML TABLE RECORD
			
			$CRecordDefinition = new CXMLRecordDefinition( XData2Array( $TipoDetalle->m_txtdata ) );
			$CDetalle->m_txtdata = "";
			
			if ($CRecordDefinition->m_maxrecords!="") {
				$nline = "";
				for( $i=0; $i < $CRecordDefinition->m_maxrecords; $i++ ) {
					
					$var_input = '_edetalle_'.$TipoDetalle->m_tipo.'_'.$i.'_';					
					$recconfirm = $CRecordDefinition->Confirm( $var_input );
					if ($recconfirm!="") {
						$CDetalle->m_txtdata.= $nline.$recconfirm;
						$nline = "\n";
					}				
				}
			}
			$CDetalle->m_detalle = $CDetalle->m_txtdata;
			break;	

			case "C":
				
				if ($CDetalle->m_detalle=="on" || $CDetalle->m_detalle == "[YES]") {
					$CDetalle->m_detalle = "[YES]";
				} else $CDetalle->m_detalle = "[NO]";
				break;
			
			default:
				break;
		}		    			 	
		
						
		if ($TipoDetalle->m_tipocampo=='E' || $TipoDetalle->m_tipocampo=='N' || $TipoDetalle->m_tipocampo=='R' || is_numeric($CDetalle->m_detalle) ) { 
			$CDetalle->m_entero = $CDetalle->m_detalle;
			$CDetalle->m_fraccion = $CDetalle->m_detalle;
		} else {
			//$CDetalle->m_entero = 0;
			//$CDetalle->m_fraccion = 0;
		}
		
		return $_exito_; 
	}
	
	function Confirm( $CDetalle, $TipoDetalle, $CLang=null, $CMultiLang=null, $Contenidos=null, $Secciones=null ) {
		
		global $_SITEROOT_;
		//global $_exito_;
		
		if ($CLang==null) $CLang = $GLOBALS['CLang'];
		if ($CMultiLang==null) $CMultiLang = $GLOBALS['CMultiLang'];
		
		$edicion = $GLOBALS['_adetalle_'.$TipoDetalle->m_tipo];
		
		if ($edicion=="") {
			DebugError('CDetalle::Confirm >> _adetalle_'.$TipoDetalle->m_tipo." accion no definida");
			$_exito_ = false;
			return $_exito_;	
		}

		$CDetalle->m_ml_detalle = str_replace("&quot;",'"',$CDetalle->m_ml_detalle);
		
		if ($CDetalle->m_id=='' ) $CDetalle->m_id = $CDetalle->m_id_contenido;

		$_exito_ = $this->Check( $CDetalle, $TipoDetalle, $CLang, $CMultiLang, $Contenidos, $Secciones );
  			
		if($_exito_) {			
					
			if ( $TipoDetalle->m_tipocampo == 'X' ) {
				$filtrohtmltemp = $this->m_tdetalles->filtrohtml;
				$this->m_tdetalles->filtrohtml = null;
			}
					
			$reg = 	array(	'ENTERO'=>$CDetalle->m_entero,
							'FRACCION'=>$CDetalle->m_fraccion,
							'ID_CONTENIDO'=>$CDetalle->m_id_contenido,
							'ID_TIPODETALLE'=>$CDetalle->m_id_tipodetalle,
							'DETALLE'=>$CDetalle->m_detalle,
							'ML_DETALLE'=>$CDetalle->m_ml_detalle,
							'TXTDATA'=>$CDetalle->m_detalle, 
							'ML_TXTDATA'=>$CDetalle->m_ml_detalle,
							'BINDATA'=>'');					
								
			if ($edicion=='modificar') {				
				$_exito_ = $this->m_tdetalles->ModificarRegistro( $CDetalle->m_id , $reg );
				if (!$_exito_) {
					DebugError("CDetalle::Confirm >> Error modificando detalle : ".$CDetalle->m_id);
					//ShowError("CDetalle:Confirm >> Error al guardar el detalle: <b>".$TipoDetalle->m_tipo."::".$TipoDetalle->m_descripcion."</b> con el valor siguiente: <i><b><pre>".$CDetalle->m_detalle."</pre></i></b>" );
					$this->PushError( new CError("DETAIL_FIELD_UPDATE_ERROR", "Problems updating detail <b style=\"color:red;\">".$TipoDetalle->m_tipo."::".$TipoDetalle->m_descripcion."</b> con el valor siguiente: <textarea>".$CDetalle->m_detalle."</textarea>" ) );
				}				
			} else if ($edicion=='insertar') {
				$_exito_ = $this->m_tdetalles->InsertarRegistro( $reg );
				if (!$_exito_) {
					DebugError("CDetalle::Confirm >>Insertando detalle : ".$reg['DETALLE']." en id_contenido:".$reg['ID_CONTENIDO']." tipo:".$TipoDetalle->m_tipo);
					$this->PushError( new CError("DETAIL_FIELD_INSERT_ERROR", "Problems inserting detail <b style=\"color:red;\">".$TipoDetalle->m_tipo."::".$TipoDetalle->m_descripcion."</b> con el valor siguiente: <textarea>".$CDetalle->m_detalle."</textarea>" ) );
				}
			}
			
			if ( $TipoDetalle->m_tipocampo == 'X' ) {
				$this->m_tdetalles->filtrohtml = $filtrohtmltemp;
			}
			
		} else {
			DebugError("CDetalle::Confirm >>Check no fue pasado");
			$this->PushError( new CError("DETAIL_FIELD_CHECK_ERROR", "Problems checking <b style=\"color:red;\">".$TipoDetalle->m_tipo."::".$TipoDetalle->m_descripcion."</b> con el valor siguiente <textarea>".$CDetalle->m_detalle."</textarea>" ) );
		}
		return $_exito_;		
	}
	
}
?>