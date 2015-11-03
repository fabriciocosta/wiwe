<?php

/**
 * class CTiposDetalles
 *
 version 4.6 24/07/2007	  : cambios en MostrarColapsado.... agregado para los BLOBTEXTO... texto plano o html
 * @version 4.5 24/05/2007   : cambios en campo 'M' maletin para mostrar comentarios o link (_TEXT#* or _THUMB#* segun sea una galerry o un maletin
 * @version 4.4 13/10/2006   : corregido CTiposDetalles en 'L' para compatibilidad con componente textareaEdit (<br> => \n)
 * @version 4.3 23/09/2006   : corregido CTiposDetalles en 'D' A agregado V y no devuelve nada si el cmapo esta vacio para el caso de los detalles q tienen templates, y agregado a G,A,D: comentarios de texto *#IMAGEN_TEXT#*
 * @version 4.2 16/08/2006   : corregido CTiposDetalles en 'G' para incluir thumbnails + templates rows y columns
 * @version 4.1 12/08/2006   : modificado MostrarColapsado, agregado 'G' 'A' 'D' 'M' 'W'
 * @version 4.0 18/07/2006
 * @copyright 2004 
 **/
 
class CTiposDetalles extends CErrorHandler {

	var $m_ttiposdetalles;//tabla tiposcontenidos
	var $m_Str2IntArray,$m_Int2StrArray;
	var $m_tiposcontenidos;
	
	var $m_templates;
	var $m_templatesedicion;
	var $m_editionparameters;
	
	function CTiposDetalles(&$__ttiposdetalles__) {
		$this->m_class = "CTiposDetalles";
		$this->Set($__ttiposdetalles__);
	}
	
	function Set(&$__ttiposdetalles__) {

		global $__base__;
		
		$__base__ = array(
			"template"=>"",
			"templateheader"=>"",
			"templatefooter"=>"",
			"templatespacer"=>"",
			"templaterows"=>"",
			"templatecolumns"=>"",
			"tipocampo"=>"",
			"objeto"=>"",
			"multiplicator"=>"",
			"rounded"=>"",
			"rows"=>"",
			"cols"=>"",
		);
		
		$this->m_ttiposdetalles = &$__ttiposdetalles__;
	  $this->m_ttiposdetalles->LimpiarSQL();    
	  $this->m_ttiposdetalles->Open();		 
	  
		if ( $this->m_ttiposdetalles->nresultados>0 ) {
			while($_row_ = $this->m_ttiposdetalles->Fetch($this->m_ttiposdetalles->resultados) ) {
				$tipodetalle = new CTipoDetalle( $_row_ );				
				$this->m_Str2IntArray[$tipodetalle->m_tipo] = $tipodetalle->m_id;
				$this->m_Int2StrArray[$tipodetalle->m_id] = $tipodetalle->m_tipo;
				$this->m_tiposcontenidos[$tipodetalle->m_id] = $tipodetalle->m_id_tipocontenido;
				//define($tipodetalle->m_tipo,$tipodetalle->m_id);
				$__base__["tipocampo"] = $tipodetalle->m_tipocampo;
				$__base__["objeto"] = $tipodetalle;
				$this->m_templates[$tipodetalle->m_id] = $__base__;
				$this->m_templatesedicion[$tipodetalle->m_id] = $__base__;
			}
		}
		$this->m_ttiposdetalles->Close();
		parent::CErrorHandler();
	}
	
	function IsValid( &$__CDetalle__, $__id_tipocontenido__ ) {
		$valid = false;
		
		$valid = ( $this->m_tiposcontenidos[$__CDetalle__->m_id_tipodetalle] == $__id_tipocontenido__ );
		
		return $valid;
	}

	function TipoDetalleExists( $__tipo__) {
		$this->m_ttiposdetalles->LimpiarSQL();
		$this->m_ttiposdetalles->FiltrarSQL( 'TIPO', '', trim($__tipo__) );
		$this->m_ttiposdetalles->Open();
		if ($this->m_ttiposdetalles->nresultados>0) {
			return true;
		} else return false;
	}	
	
	function CrearTipoDetalle( &$__CTipoDetalle__) {
		if ( !$this->TipoDetalleExists($__CTipoDetalle__->m_tipo) && $__CTipoDetalle__->m_tipo!="" ) {
			
			$_exito_ = $this->m_ttiposdetalles->InsertarRegistro( $__CTipoDetalle__->FullArray() );
					
			if ($_exito_) {
				$__CTipoDetalle__->m_id = $this->m_ttiposdetalles->lastinsertid;
				return true;
			} 
		} else {
			if ($__CTipoDetalle__->m_tipo!="") ShowError("Tipo de detalle: ".$__CTipoDetalle__->m_tipo." already exists!");
		}
		return false;
	}	
	
	function GetTipoDetalle( $_id_tipodetalle_ ) {
		
		$this->m_ttiposdetalles->LimpiarSQL();			
	    $this->m_ttiposdetalles->FiltrarSQL('ID','',$_id_tipodetalle_);
	    $this->m_ttiposdetalles->Open();		
		
		if ( $this->m_ttiposdetalles->nresultados>0 ) {		
			$_row_ = $this->m_ttiposdetalles->Fetch();
			$TipoDetalle = new CTipoDetalle($_row_);
			return $TipoDetalle;			
		}	
		
		return null;
		
	}
	
	function GetTipoEntero($__str__) {
		$id =  $this->m_Str2IntArray[$__str__] ;
		if (is_numeric($id)) {
			return $id;
		} else {
			return 0;
		}		
	}

	function GetTipoStr($__int__) {
		$str =  $this->m_Int2StrArray[$__int__] ;
		if ($str!='') {
			return $str;
		} else {
			return "nada";
		}
	}
	
	function GetTipoCampo($__int__) {
		return $this->m_templates[$__int__]['tipocampo'];
	}

	function SetTemplate($__idtipodetalle__,$__template__="",$__header__="",$__footer__="",$__spacer__="",$__rows__="",$__columns__="") {
		$this->m_templates[$__idtipodetalle__]["template"] = $__template__;
		$this->m_templates[$__idtipodetalle__]["templateheader"] = $__header__;
		$this->m_templates[$__idtipodetalle__]["templatefooter"] = $__footer__;
		$this->m_templates[$__idtipodetalle__]["templatespacer"] = $__spacer__;
		$this->m_templates[$__idtipodetalle__]["templaterows"] = $__rows__;
		$this->m_templates[$__idtipodetalle__]["templatecolumns"] = $__columns__;
	}
	
	function SetTemplateMultiplicator($__idtipodetalle__, $mul ) {
		$this->m_templates[$__idtipodetalle__]["multiplicator"] = $mul;	
	}
	
	function SetTemplateRounded($__idtipodetalle__ ) {
		$this->m_templates[$__idtipodetalle__]["rounded"] = "rounded";	
	}	
	
	function SetParameters( $__idtipodetalle__, $columns = 80, $rows = 8 ) {
		
		$this->m_editionparameters[$__idtipodetalle__]["rows"] = $rows;
		$this->m_editionparameters[$__idtipodetalle__]["columns"] = $columns;
		
	}

	function SetScript( $__idtipodetalle__, $__scriptfunction__ ) {
		$this->m_editionparameters[$__idtipodetalle__]["script"] = $__scriptfunction__;
	}
	
	function SetTemplateEdicion( $__idtipodetalle__,$__template__="",$__header__="",$__footer__="",$__spacer__="",$__rows__="",$__columns__="",$__reserved__="") {
		$this->m_templatesedicion[$__idtipodetalle__]["template"] = $__template__;
		$this->m_templatesedicion[$__idtipodetalle__]["templateheader"] = $__header__;
		$this->m_templatesedicion[$__idtipodetalle__]["templatefooter"] = $__footer__;
		$this->m_templatesedicion[$__idtipodetalle__]["templatespacer"] = $__spacer__;
		$this->m_templatesedicion[$__idtipodetalle__]["templaterows"] = $__rows__;
		$this->m_templatesedicion[$__idtipodetalle__]["templatecolumns"] = $__columns__;
		$this->m_templatesedicion[$__idtipodetalle__]["reserved"] = $__reserved__;
	}
	
	///specific for autocomplete custom combos... to avoid auto select editor
	function SetTemplateEdicionAutoComplete( $__idtipodetalle__) {
		$this->m_templatesedicion[$__idtipodetalle__]["reserved"] = "autocomplete";		
	} 
		
	function Mostrar($__CDetalle__) {
		
		global $__lang__;
		global $CLang;
		
		$idtipodetalle = $__CDetalle__->m_id_tipodetalle;
		//TIPO
		$tipocampo = $this->m_templates[$idtipodetalle]["tipocampo"];
		$html = $this->m_templates[$idtipodetalle]["objeto"]->m_txtdata;

		//TRADUCCION A LANG
		if ($__lang__!='' && ($tipocampo=="T" || $tipocampo=="L" || $tipocampo=="B")) {
			//$__CDetalle__->m_titulo = $this->m_ttiposdetalles->TextoML($__CDetalle__->m_ml_titulo,$__lang__);
			$detalle_lang = trim($this->m_ttiposdetalles->TextoML($__CDetalle__->m_ml_detalle,$__lang__));
			$txtdata_lang = trim($this->m_ttiposdetalles->TextoML($__CDetalle__->m_ml_txtdata,$__lang__));
			if ($detalle_lang!="") {
				$__CDetalle__->m_detalle = $detalle_lang;
			}
			if ($txtdata_lang!="") {
				$__CDetalle__->m_txtdata = $txtdata_lang;
			}			
		}
		
		switch($__CDetalle__->m_id_tipodetalle) {

			default:				
								
				//TEMPLATE
				$tmpl = $this->m_templates[$idtipodetalle]["template"];//linea o texto
				$tmplHD = $this->m_templates[$idtipodetalle]["templateheader"];//encabezado
				$tmplFT = $this->m_templates[$idtipodetalle]["templatefooter"];//pie
				$tmplSP = $this->m_templates[$idtipodetalle]["templatespacer"];//espacio vacio
				$tmplRO = $this->m_templates[$idtipodetalle]["templaterows"];//row separator
				$tmplCO = $this->m_templates[$idtipodetalle]["templatecolumns"];//columnas...
				
				$tmplMUL = $this->m_templates[$idtipodetalle]["multiplicator"];//multiplicador para fracciones
				$tmplRND = $this->m_templates[$idtipodetalle]["rounded"];//rounded
								
			  if ($tmpl != "") {			  	
			  	if ($tipocampo=="T") {//TEXT MAX 200 Chars
			  		if (is_numeric($__CDetalle__->m_detalle)) {
			  			if (is_numeric($tmplMUL)) $__CDetalle__->m_detalle=$__CDetalle__->m_detalle*$tmplMUL;
			  			if ($tmplRND=="rounded")  $__CDetalle__->m_detalle = floor($__CDetalle__->m_detalle);
			  		}
			  		$str = "";
			  		if (trim($__CDetalle__->m_detalle)!="") {
				  		$str = str_replace('*#'.$this->m_Int2StrArray[$idtipodetalle].'#*',$__CDetalle__->m_detalle,$tmpl);
				  		if ($str!="") $str = $tmplHD.$str.$tmplFT;
			  		}
			  		return $str;
			  	} else if ($tipocampo=="C") { //checkbox					
					return( $tmplHD.$CLang->m_Words[str_replace( array("[","]"), array(""), $__CDetalle__->m_detalle )].$tmplFT );
				} else if ( ($tipocampo=="S") || ($tipocampo=="L") || ($tipocampo=="G") || ($tipocampo=="W") || ($tipocampo=="M") || ($tipocampo=="B")) {//LIST ITEMS
					
					$TipoDetalle = $this->GetTipoDetalle($__CDetalle__->m_id_tipodetalle);
					$ADataDef = XData2Array($TipoDetalle->m_txtdata);
					$maxuploads = $ADataDef['maxuploads']['values'];
					$max = $ADataDef['max']['values'];
					
			  		if ($tipocampo=="L") $__CDetalle__->m_txtdata = str_replace("<br>","\n",$__CDetalle__->m_txtdata);
			  		if ($tipocampo=="B") if ($html!="html") $__CDetalle__->m_txtdata = str_replace("\n","<br>",$__CDetalle__->m_txtdata);
			  		$strxpl = explode("\n",$__CDetalle__->m_txtdata);
			  		$str = "";
			  		$cnt = 1;
			  		$cn = 0;			  		
			  		foreach($strxpl as $line) {
			  			$cn++;
			  		    if ( ($tmplCO!="") && ($cnt==($tmplCO+1)) ) {
			  				$str.= $tmplRO;
			  				$cnt = 2;			  				
			  			} else $cnt++;
			  			//$line = trim($line);
			  			$lineX = explode("::", $line );
			  			if ($lineX[0]!="") {			  							  							  						  					
			  				$tmplHD = str_replace('*#'.$this->m_Int2StrArray[$idtipodetalle].':'.($cnt-2).'#*',wiwe_dir_siteabs($lineX[0]),$tmplHD);
			  				$tmplHD = str_replace('*#'.$this->m_Int2StrArray[$idtipodetalle].'_THUMB:'.($cnt-2).'#*',wiwe_dir_siteabs(str_replace('imagen/','imagen/thm/',$lineX[0])),$tmplHD);
		  					$tmplHD = str_replace('*#'.$this->m_Int2StrArray[$idtipodetalle].'_TEXT:'.($cnt-2).'#*',$lineX[1],$tmplHD);
			  				$str_aux = str_replace('*#'.$this->m_Int2StrArray[$idtipodetalle].'#*',$lineX[0],$tmpl);			  				
			  				if ($tipocampo=="G") {
			  				$str_aux = str_replace('*#'.$this->m_Int2StrArray[$idtipodetalle].'_THUMB#*',wiwe_dir_siteabs(dirname($lineX[0]).'/thm/'.basename($lineX[0])),$str_aux);
			  				$str_aux = str_replace('*#'.$this->m_Int2StrArray[$idtipodetalle].'_TEXT#*',$lineX[1],$str_aux);
			  				}
			  				if ($tipocampo=="M") {			  					
			  					$str_aux = str_replace('*#'.$this->m_Int2StrArray[$idtipodetalle].'_TEXT#*',$lineX[1],$str_aux);
			  				}			  				
			  				$str.= $str_aux;
			  			}
			  			if ($cn==$max) break;			  			
			  		}
			  		//for($K=0;$K<(($cnt-1) % $tmplCO);$K++) $str.=$tmplSP;
			  		if (trim($str)!="") $str = $tmplHD.$str.$tmplFT;
			  		return $str;			  		
				} else if (($tipocampo=="I") || ($tipocampo=="F")) {//IMAGE			  		
					if (($__CDetalle__->m_detalle=="empty") || ($__CDetalle__->m_detalle==""))
						return wiwe_dir_siteabs("../../inc/images/spacer.gif");
					else
						return( wiwe_dir_siteabs($__CDetalle__->m_detalle) );
				} else if (($tipocampo=="D") || ($tipocampo=="A") | ($tipocampo=="V")) {//DOCUMENT or VIDEO			  		
					if (($__CDetalle__->m_detalle=="empty") || ($__CDetalle__->m_detalle=="")) {
						return "";
					} else	{
						return str_replace('*#'.$this->m_Int2StrArray[$idtipodetalle].'#*',$__CDetalle__->m_detalle,$tmpl);
					}
				} else if ($tipocampo=="X") {
					$str = "";
					$AData = XData2Array( $__CDetalle__->m_txtdata );
					//<field type=select values=a|b|c />
					//$Field > "field"
					//$Values > array("type"=>"select", "values"=>"a|b|c")
					if (is_array($AData))
					foreach($AData as $Field=>$Values) {								
						if ($Field!="") {
							$SplitXDataValues = split( "\|", $Values['values'] );
							$sp = "";
							foreach($SplitXDataValues as $Value) {							
								$Value = trim($Value);
								if ($Value!="")
								if ($Values['type']=="select") {											
									$str.= $sp.str_replace( array('*#'.$this->m_Int2StrArray[$idtipodetalle].'#*','*#'.$this->m_Int2StrArray[$idtipodetalle].':FIELD#*'),array($Value,$Field),$tmpl);
									$sp = $tmplSP;									
								} else if ($Values['type']=="checkbox") {											
									$str.= $sp.str_replace('*#'.$this->m_Int2StrArray[$idtipodetalle].'#*',$Value,$tmpl);
									$sp = $tmplSP;
								}
							}
						}								
					}
					if (trim($str)!="") $str = $tmplHD.$str.$tmplFT;
					return $str;
				} else if ($tipocampo=="Y") {
					$CRecordDefinition = new CXMLRecordDefinition( XData2Array( $this->m_templates[$idtipodetalle]["objeto"]->m_txtdata ) );
					$str = $tmplHD;
					//old records
					$crecs = 0;				
					if ($__CDetalle__->m_txtdata!='') {					
						$lines = explode("\n",$__CDetalle__->m_txtdata);										
						foreach( $lines as $linestr ) {
							if (trim($linestr)!="") {							
								$str.= $CRecordDefinition->Draw( XData2Array( $linestr ),  $tmpl );
								$crecs++;
							}
						}
					}
					return $str.$tmplFT;						
				} else if ($tipocampo=="R" || $tipocampo=="RC") {
					$str = $tmplHD;
					$titulo_ref = "";
					if (is_object($__CDetalle__->m_CReference)) {
						$titulo_ref = $__CDetalle__->m_CReference->Titulo();
						if ($titulo_ref!="") {
							$str.= str_replace(
										array( 	
												'*#'.$this->m_Int2StrArray[$idtipodetalle].':ID#*',
												'*#'.$this->m_Int2StrArray[$idtipodetalle].':TIPO#*', 
												'*#'.$this->m_Int2StrArray[$idtipodetalle].'#*',
												'*#'.$this->m_Int2StrArray[$idtipodetalle].':TITULO#*',
											  '*#'.$this->m_Int2StrArray[$idtipodetalle].':TITULOURL#*',
												'*#'.$this->m_Int2StrArray[$idtipodetalle].':SECCIONURL#*' 
										), 
										array(
												$__CDetalle__->m_entero,
												$__CDetalle__->m_CReference->m_id_seccion,
												$titulo_ref,
												$titulo_ref,
												$__CDetalle__->m_CReference->TituloURL(),
												$__CDetalle__->m_CReference->m_id_seccion
										) 
										,  $tmpl);
							return $str.$tmplFT;
						}
					}
					return "";							
				} else if ($tipocampo=="H" || $tipocampo=="RS") {
					$str = $tmplHD;
					$titulo_ref = "";
					$titulo_ref_url = "";
					if (is_object($__CDetalle__->m_CReference)) {
						$titulo_ref = $__CDetalle__->m_CReference->Nombre();
						$titulo_ref_url = $__CDetalle__->m_CReference->Nombre();
					}
					$str = str_replace( array( 	'*#'.$this->m_Int2StrArray[$idtipodetalle].':ID#*', 
												'*#'.$this->m_Int2StrArray[$idtipodetalle].':NOMBRE#*',
												'*#'.$this->m_Int2StrArray[$idtipodetalle].':NOMBREURL#*' ), 
										array( 	$__CDetalle__->m_entero, 
												$titulo_ref,
												$titulo_ref_url) 
										,  $tmpl);	
					return $str.$tmplFT;					
				} else if ($tipocampo=="O" || $tipocampo=="RCx") {
					$str = $tmplHD;
			
					$TipoDetalle = $this->GetTipoDetalle($__CDetalle__->m_id_tipodetalle);
					
					$tipospl = explode( "\n", $TipoDetalle->m_txtdata );
					
					$referenciaEstilo = trim($tipospl[0]);
					$referenciaSQL = trim($tipospl[1]);
					$referenciaSQLCOUNT = trim($tipospl[2]);
					
				global $_trelaciones_;
					$_trelaciones_->QuitarReferencias();
					$_trelaciones_->AgregarReferencias(
							array(	"CREL.ID",
									"CREL.ML_TITULO",
									"CREL.TITULO",
									"CSEC.NOMBRE" ),
							array(	"contenidos CREL","secciones CSEC"),
							array(	"relaciones.ID_CONTENIDO_REL=CREL.ID",
									"CSEC.ID=CREL.ID_SECCION",
									"relaciones.ID_TIPORELACION=".$__CDetalle__->m_id_tipodetalle)
					);
					$_trelaciones_->LimpiarSQL();
					$_trelaciones_->FiltrarSQL( "ID_CONTENIDO" , "", $__CDetalle__->m_id_contenido );
					$_trelaciones_->Open();
					$spacer = "";
					if ( $_trelaciones_->nresultados > 0 ) {
						while( $rels = $_trelaciones_->Fetch() ) {
							$CC = new CContenido();
							$CC->m_id = $rels["CREL.ID"];
							$CC->m_titulo = $rels["CREL.TITULO"];
							$CC->m_ml_titulo = $rels["CREL.ML_TITULO"];
					
							$str.= $spacer.str_replace( array( 	'*#'.$this->m_Int2StrArray[$idtipodetalle].':ID#*',
									'*#'.$this->m_Int2StrArray[$idtipodetalle].':TITULO#*',
									'*#'.$this->m_Int2StrArray[$idtipodetalle].':TITULOURL#*',
									'*#'.$this->m_Int2StrArray[$idtipodetalle].':SECCION#*',
									'*#'.$this->m_Int2StrArray[$idtipodetalle].':SECCIONURL#*' ),
									array( 	$CC->m_id,
											$CC->Titulo(),
											$CC->TituloURL(),
											$rels["CSEC.NOMBRE"],
											strtolower($rels["CSEC.NOMBRE"]) ),
									$tmpl);
							$spacer = $tmplSP;
						}
						$_trelaciones_->QuitarReferencias();
						return $str.$tmplFT;
							
					} else {
						$_trelaciones_->QuitarReferencias();
						$str.= $spacer."";
						return "";
					}
					
					/*
					$str = str_replace( array( 	'*#'.$this->m_Int2StrArray[$idtipodetalle].':ID#*', 
												'*#'.$this->m_Int2StrArray[$idtipodetalle].':TITULO#*' ), 
										array( 	$__CDetalle__->m_entero, 
												$__CDetalle__->m_CReference->m_titulo ),  $tmpl);
						*/							
							
								
				} else if ($tipocampo=="P" || $tipocampo=="RSx") {
					
					$str = $tmplHD;		
					
					$TipoDetalle = $this->GetTipoDetalle($__CDetalle__->m_id_tipodetalle);
					
					$tipospl = explode( "\n", $TipoDetalle->m_txtdata );
					
					$referenciaEstilo = trim($tipospl[0]);
					$referenciaSQL = trim($tipospl[1]);
					$referenciaSQLCOUNT = trim($tipospl[2]);
					
					global $_trelaciones_;
					$_trelaciones_->AgregarReferencias(
					array(	"secciones.ID",
							"secciones.ML_NOMBRE",
							"secciones.NOMBRE" ),
					array(	"secciones"),
					array(	"relaciones.ID_SECCION_REL=secciones.ID",
							"relaciones.ID_TIPORELACION=".$__CDetalle__->m_id_tipodetalle)
					);
					$_trelaciones_->LimpiarSQL();
					$_trelaciones_->FiltrarSQL( "ID_CONTENIDO" , "", $__CDetalle__->m_id_contenido );
					$_trelaciones_->Open();
					$spacer = "";
					if ( $_trelaciones_->nresultados > 0 ) {
						 while( $rels = $_trelaciones_->Fetch() ) {
						 	$CS = new CSeccion();
						 	$CS->m_id = $rels["secciones.ID"];
						 	$CS->m_nombre = $rels["secciones.NOMBRE"];
						 	$CS->m_ml_nombre = $rels["secciones.ML_NOMBRE"];
						 	
						 	$str.= $spacer.str_replace( array( 	'*#'.$this->m_Int2StrArray[$idtipodetalle].':ID#*', 
												'*#'.$this->m_Int2StrArray[$idtipodetalle].':NOMBRE#*',
											 	'*#'.$this->m_Int2StrArray[$idtipodetalle].':NOMBREURL#*' ), 
										array( 	$CS->m_id, 
												$CS->Nombre(),
												$CS->NombreURL() ),  
												$tmpl);
							$spacer = $tmplSP;
						 }
							$_trelaciones_->QuitarReferencias();
						 return $str.$tmplFT;						 
					} else {
						$_trelaciones_->QuitarReferencias();						
						$str.= $spacer." - ";
						return "";
					}

					/*
					$str = str_replace( array( 	'*#'.$this->m_Int2StrArray[$idtipodetalle].':ID#*', 
												'*#'.$this->m_Int2StrArray[$idtipodetalle].':TITULO#*' ), 
										array( 	$__CDetalle__->m_entero, 
												$__CDetalle__->m_CReference->m_titulo ),  $tmpl);
						*/															
				} else if ($tipocampo=="RTCx") {
					/*están asociadas a través del PESO*/
					$str = $tmplHD;
			
					$TipoDetalle = $this->GetTipoDetalle($__CDetalle__->m_id_tipodetalle);
					
					$tipospl = explode( "\n", $TipoDetalle->m_txtdata );
					
					$referenciaEstilo = trim($tipospl[0]);
					$referenciaSQL = trim($tipospl[1]);
					$referenciaSQLCOUNT = trim($tipospl[2]);
					
					global $_trelaciones_;
					$_trelaciones_->AgregarReferencias(
					array(	"CTREL.ID",
							"CTREL.TIPO",
							"CTREL.DESCRIPCION" ),
					array(	"tiposcontenidos CREL"),
					array(	"relaciones.PESO=CTREL.ID",
							"relaciones.ID_TIPORELACION=".$__CDetalle__->m_id_tipodetalle)
					);
					$_trelaciones_->LimpiarSQL();
					$_trelaciones_->FiltrarSQL( "ID_CONTENIDO" , "", $__CDetalle__->m_id_contenido );
					$_trelaciones_->Open();
					$spacer = "";
					if ( $_trelaciones_->nresultados > 0 ) {
						 while( $rels = $_trelaciones_->Fetch() ) {
						 	$CT = new CTipoContenido();
						 	$CT->m_id = $rels["CTREL.ID"];
						 	$CT->m_tipo = $rels["CTREL.TIPO"];
						 	//$CT->m_ml_titulo = $rels["CREL.ML_TITULO"];
						 	
						 	$str.= $spacer.str_replace( array( 	'*#'.$this->m_Int2StrArray[$idtipodetalle].':ID#*', 
												'*#'.$this->m_Int2StrArray[$idtipodetalle].':TIPO#*',
											 	'*#'.$this->m_Int2StrArray[$idtipodetalle].':DESCRIPCION#*' ), 
										array( 	$CT->m_id, 
												$CT->m_tipo,
												$CT->m_descripcion ),  
												$tmpl);
							$spacer = $tmplSP;
						 }
					} else {
						$str.= $spacer." - ";
					}
					$_trelaciones_->QuitarReferencias();
					/*
					$str = str_replace( array( 	'*#'.$this->m_Int2StrArray[$idtipodetalle].':ID#*', 
												'*#'.$this->m_Int2StrArray[$idtipodetalle].':TITULO#*' ), 
										array( 	$__CDetalle__->m_entero, 
												$__CDetalle__->m_CReference->m_titulo ),  $tmpl);
						*/							
					return $str.$tmplFT;														
				} else if ($tipocampo=="RTSx") {
					/*están asociadas a través del PESO*/
					$str = $tmplHD;
			
					$TipoDetalle = $this->GetTipoDetalle($__CDetalle__->m_id_tipodetalle);
					
					$tipospl = explode( "\n", $TipoDetalle->m_txtdata );
					
					$referenciaEstilo = trim($tipospl[0]);
					$referenciaSQL = trim($tipospl[1]);
					$referenciaSQLCOUNT = trim($tipospl[2]);
					
					global $_trelaciones_;
					$_trelaciones_->AgregarReferencias(
					array(	"CTREL.ID",
							"CTREL.TIPO",
							"CTREL.DESCRIPCION" ),
					array(	"tipossecciones CREL"),
					array(	"relaciones.PESO=CTREL.ID",
							"relaciones.ID_TIPORELACION=".$__CDetalle__->m_id_tipodetalle)
					);
					$_trelaciones_->LimpiarSQL();
					$_trelaciones_->FiltrarSQL( "ID_CONTENIDO" , "", $__CDetalle__->m_id_contenido );
					$_trelaciones_->Open();
					$spacer = "";
					if ( $_trelaciones_->nresultados > 0 ) {
						 while( $rels = $_trelaciones_->Fetch() ) {
						 	$CTS = new CTipoSeccion();
						 	$CTS->m_id = $rels["CTREL.ID"];
						 	$CTS->m_tipo = $rels["CTREL.TIPO"];
						 	//$CT->m_ml_titulo = $rels["CREL.ML_TITULO"];
						 	
						 	$str.= $spacer.str_replace( array( 	'*#'.$this->m_Int2StrArray[$idtipodetalle].':ID#*', 
												'*#'.$this->m_Int2StrArray[$idtipodetalle].':TIPO#*',
											 	'*#'.$this->m_Int2StrArray[$idtipodetalle].':DESCRIPCION#*' ), 
										array( 	$CTS->m_id, 
												$CTS->m_tipo,
												$CTS->m_descripcion ),  
												$tmpl);
							$spacer = $tmplSP;
						 }
					} else {
						$str.= $spacer." - ";
					}
					$_trelaciones_->QuitarReferencias();
					/*
					$str = str_replace( array( 	'*#'.$this->m_Int2StrArray[$idtipodetalle].':ID#*', 
												'*#'.$this->m_Int2StrArray[$idtipodetalle].':TITULO#*' ), 
										array( 	$__CDetalle__->m_entero, 
												$__CDetalle__->m_CReference->m_titulo ),  $tmpl);
						*/							
					return $str.$tmplFT;														
				} else if ($tipocampo=="N") {
					if (is_numeric($tmplMUL)) $__CDetalle__->m_entero=$__CDetalle__->m_entero*$tmplMUL;
					if ($tmplRND=="rounded")  $__CDetalle__->m_entero = floor($__CDetalle__->m_entero);
					return( $__CDetalle__->m_entero );
				} else if ($tipocampo=="E") {
					if (is_numeric($tmplMUL)) $__CDetalle__->m_fraccion=$__CDetalle__->m_fraccion*$tmplMUL;
					if ($tmplRND=="rounded")  $__CDetalle__->m_fraccion = floor($__CDetalle__->m_fraccion);
					$str = explode("."," ".FormatPrice($__CDetalle__->m_fraccion)." ");
					if(count($str)>1) {
						$int = $str[0];
						if ($str[1]) $frac = $str[1];
					} else {
						$int = $__CDetalle__->m_fraccion;
						$frac = "00";												
					}
			  		return $tmplHD.$int.$tmplSP.$frac.$tmplFT;					
				
				} else if ($tipocampo=="U") {
			  		
			  		$str = "";
			  		
			  		$TipoDetalle = $this->GetTipoDetalle( $__CDetalle__->m_id_tipodetalle );
			  		$ADataDef = XData2Array($TipoDetalle->m_txtdata);
			  		
			  		$idtipocontenido = $ADataDef['id_tipocontenido']['values'];
			  		
			  		global $_tcontenidos_;
			  		global $_tcontenidos2_;
			  		global $Sitio;
			  		global $Admin;
			  		
			  		if (isset($Sitio)) $TiposContenidosA = $Sitio->TiposContenidos;
			  		if (isset($Admin)) $TiposContenidosA = $Admin->TiposContenidos;
			  		
			  		
			  		$_tcontenidos2_ = new Tabla('contenidos',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
			  		
			  		$subcontenidos = array();
			  		$_tcontenidos2_->CopiarTabla($_tcontenidos_);
			  		
			  		$_tcontenidos2_->QuitarReferencias();
				  	$_tcontenidos2_->LimpiarSQL();
				  	$_tcontenidos2_->FiltrarSQL('ID_CONTENIDO','',$__CDetalle__->m_id_contenido);
					$_tcontenidos2_->FiltrarSQL('ID_TIPOCONTENIDO','',$idtipocontenido);
					$_tcontenidos2_->OrdenSQL('contenidos.ID_TIPOCONTENIDO,contenidos.ORDEN ASC');
					$_tcontenidos2_->Open();
					if ( $_tcontenidos2_->nresultados>0 ) {
						//$str.= "resultados:".$_tcontenidos2_->nresultados."<br>";
						$counter = 0;
						
						while($_row_ = $_tcontenidos2_->Fetch() ) {				
							$subcontenidos[$counter] = $_row_['contenidos.ID'];
							$counter++;
						}
					}
					
					if (isset($Sitio)) $ContenidosA = $Sitio->Contenidos;
			  		if (isset($Admin)) $ContenidosA = $Admin->Contenidos;
			  		$sep = "";
					foreach($subcontenidos as $n=>$id) {
							$CC = $ContenidosA->GetContenidoCompleto($id);
							$str.= $sep.$TiposContenidosA->TextoCompleto( $CC );
							$sep = $tmplSP;
					}
					
					
					if (trim($str)!="")		{
						$str = str_replace( 
							'*#'.$this->m_Int2StrArray[$idtipodetalle].'#*', 
							$str,  
							$tmpl );
			  		return $tmplHD.$str.$tmplFT;
					} else return "";
			  		
				} else if ($tipocampo=="K") {
					$str = "";
					if ( $__CDetalle__->m_detalle != "" ) {
						if ( !is_numeric(strpos( $__CDetalle__->m_detalle, "http:\\")) && !is_numeric(strpos( $__CDetalle__->m_detalle, "http://"))) {
							$__CDetalle__->m_detalle = "http://".$__CDetalle__->m_detalle;
						}
						$str = str_replace( 
									'*#'.$this->m_Int2StrArray[$idtipodetalle].'#*', 
									$__CDetalle__->m_detalle,  
									$tmpl	);
						return $str;						
					}
					return "";
				}
			  } else {
			  	
			  	// NO TEMPLATES!!!
			  	
			  	if ($tipocampo=="T") {//TEXT MAX 200 Chars
			  		if (is_numeric($__CDetalle__->m_detalle)) {
			  			if (is_numeric($tmplMUL)) $__CDetalle__->m_detalle=$__CDetalle__->m_detalle*$tmplMUL;
			  			if ($tmplRND=="rounded")  $__CDetalle__->m_detalle = floor($__CDetalle__->m_detalle);
			  		}			  		
			  		return( $tmplHD.$__CDetalle__->m_detalle.$tmplFT );
			  	} else if ( ($tipocampo=="L") || ($tipocampo=="G") || ($tipocampo=="W") || ($tipocampo=="M") ) {//LIST ITEMS			  		
			  		return( $tmplHD.$__CDetalle__->m_txtdata.$tmplFT );
				} else if ($tipocampo=="B") {//BLOBLTEXT
			  		return( $tmplHD.$__CDetalle__->m_txtdata.$tmplFT );
			  	} else if ($tipocampo=="C") { //checkbox					
					return( $CLang->m_Words[str_replace( array("[","]"), array(""), $__CDetalle__->m_detalle )] );
				} else if (($tipocampo=="I") || ($tipocampo=="F")) {//IMAGE			  		
					if (($__CDetalle__->m_detalle=="empty") || ($__CDetalle__->m_detalle==""))
						return wiwe_dir_siteabs("../../inc/images/spacer.gif");
					else
						return wiwe_dir_siteabs($__CDetalle__->m_detalle);
				} else if (($tipocampo=="D") || ($tipocampo=="A")) {//DOCUMENT			  		
					if (($__CDetalle__->m_detalle=="empty") || ($__CDetalle__->m_detalle==""))
						return wiwe_dir_siteabs("../../inc/images/spacer.gif");
					else
						return( wiwe_dir_siteabs($__CDetalle__->m_detalle) );						
				} else if ($tipocampo=="X") {
					$str = "";
					$AData = XData2Array( $__CDetalle__->m_txtdata );						
					foreach($AData as $Field=>$Values) {								
						if ($Field!="") {
							//$str.=''.$Field.'';
							if ($Values['type']=="select") {
								$str.='';
							} else if ($Values['type']=="checkbox") {
								$str.='';
							}
							$SplitXDataValues = split( "\|", $Values['values'] );
							foreach($SplitXDataValues as $Value) {
								if ($Values['type']=="select") {											
									$str.=''.$Value.'';
								} else if ($Values['type']=="checkbox") {											
									//$pos = strpos( $AData[$Field]['values'],$Value);
									//if (is_numeric($pos)) $selected = "checked"; else $selected="";
									$str.=''.$Value.'';
								}
							}
							if ($Values['type']=="select") {
								$str.='';	
							} else if ($Values['type']=="checkbox") {
								$str.='';
							}
						}								
					}
					return $str;
				} else if ($tipocampo=="Y") {
					$CRecordDefinition = new CXMLRecordDefinition( XData2Array( $this->m_templates[$idtipodetalle]["objeto"]->m_txtdata ) );
					$str =  '<table cellpadding="4" cellspacing="0" border="0">';
					//old records
					$crecs = 0;				
					if ($__CDetalle__->m_txtdata!='') {					
						$lines = explode("\n",$__CDetalle__->m_txtdata);										
						foreach( $lines as $linestr ) {
							if (trim($linestr)!="") {							
								$str.= $CRecordDefinition->Draw( XData2Array( $linestr ), ""  );
								$crecs++;
							}
						}
					}
					$str.= '</table>';
					return $str;					
				} else if ($tipocampo=="R" || $tipocampo=="RC") {
					$titulo_ref = "";
					if (is_object($__CDetalle__->m_CReference)) {
						$titulo_ref = $__CDetalle__->m_CReference->Titulo();
					}// else echo "error!!!";										
					return $titulo_ref;
				} else if ($tipocampo=="R" || $tipocampo=="RCx") {
					
					$tmplHD = '<div class="content-relations RCx_'.$this->m_Int2StrArray[$idtipodetalle].'">';
					$tmplFT = '</div>';
					$tmplSP = '<div class="related_separator">, </div>';
					$tmpl = '<div class="related"><a title="" id="'.'*#'.$this->m_Int2StrArray[$idtipodetalle].':ID#*'.'">'.'*#'.$this->m_Int2StrArray[$idtipodetalle].':TITULO#*'.'</a></div>';

					$str = $tmplHD;
					
					$TipoDetalle = $this->GetTipoDetalle($__CDetalle__->m_id_tipodetalle);
						
					$tipospl = explode( "\n", $TipoDetalle->m_txtdata );
						
					$referenciaEstilo = trim($tipospl[0]);
					$referenciaSQL = trim($tipospl[1]);
					$referenciaSQLCOUNT = trim($tipospl[2]);
						
					global $_trelaciones_;
					$_trelaciones_->QuitarReferencias();
					$_trelaciones_->AgregarReferencias(
							array(	"CREL.ID",
									"CREL.ML_TITULO",
									"CREL.TITULO",
									"CSEC.NOMBRE" ),
							array(	"contenidos CREL","secciones CSEC"),
							array(	"relaciones.ID_CONTENIDO_REL=CREL.ID",
									"CSEC.ID=CREL.ID_SECCION",
									"relaciones.ID_TIPORELACION=".$__CDetalle__->m_id_tipodetalle)
					);
					$_trelaciones_->LimpiarSQL();
					$_trelaciones_->FiltrarSQL( "ID_CONTENIDO" , "", $__CDetalle__->m_id_contenido );
					$_trelaciones_->Open();
					$spacer = "";
					if ( $_trelaciones_->nresultados > 0 ) {
						while( $rels = $_trelaciones_->Fetch() ) {
							$CC = new CContenido();
							$CC->m_id = $rels["CREL.ID"];
							$CC->m_titulo = $rels["CREL.TITULO"];
							$CC->m_ml_titulo = $rels["CREL.ML_TITULO"];
					
							$str.= $spacer.str_replace( array( 	'*#'.$this->m_Int2StrArray[$idtipodetalle].':ID#*',
									'*#'.$this->m_Int2StrArray[$idtipodetalle].':TITULO#*',
									'*#'.$this->m_Int2StrArray[$idtipodetalle].':TITULOURL#*',
									'*#'.$this->m_Int2StrArray[$idtipodetalle].':SECCION#*' ),
									array( 	$CC->m_id,
											$CC->Titulo(),
											$CC->TituloURL(),
											$rels["CSEC.NOMBRE"] ),
									$tmpl);
							$spacer = $tmplSP;
						}
						$_trelaciones_->QuitarReferencias();
						return $str.$tmplFT;
							
					} else {
						$_trelaciones_->QuitarReferencias();
						$str.= $spacer."";
						return "";
					}
					
				} else if ($tipocampo=="H" || $tipocampo=="RS") {					
					$titulo_ref = "";
					$titulo_ref_url = "";
					if (is_object($__CDetalle__->m_CReference)) {
						$titulo_ref = $__CDetalle__->m_CReference->Nombre();
						$titulo_ref_url = $__CDetalle__->m_CReference->Nombre();
					}// else echo "error!!!";
					return $titulo_ref;
				} else if ($tipocampo=="P" || $tipocampo=="RSx") {
						
					$tmplHD = '<div class="content-relations RSx_'.$this->m_Int2StrArray[$idtipodetalle].'">';
					$tmplFT = '</div>';
					$tmplSP = '<div class="related_separator">, </div>';
					$tmpl = '<div class="related"><a title="" id="'.'*#'.$this->m_Int2StrArray[$idtipodetalle].':ID#*'.'">'.'*#'.$this->m_Int2StrArray[$idtipodetalle].':NOMBRE#*'.'</a></div>';

					$str = $tmplHD;
						
					$TipoDetalle = $this->GetTipoDetalle($__CDetalle__->m_id_tipodetalle);
						
					$tipospl = explode( "\n", $TipoDetalle->m_txtdata );
						
					$referenciaEstilo = trim($tipospl[0]);
					$referenciaSQL = trim($tipospl[1]);
					$referenciaSQLCOUNT = trim($tipospl[2]);
						
					global $_trelaciones_;
					$_trelaciones_->AgregarReferencias(
							array(	"secciones.ID",
									"secciones.ML_NOMBRE",
									"secciones.NOMBRE" ),
							array(	"secciones"),
							array(	"relaciones.ID_SECCION_REL=secciones.ID",
									"relaciones.ID_TIPORELACION=".$__CDetalle__->m_id_tipodetalle)
					);
					$_trelaciones_->LimpiarSQL();
					$_trelaciones_->FiltrarSQL( "ID_CONTENIDO" , "", $__CDetalle__->m_id_contenido );
					$_trelaciones_->Open();
					$spacer = "";
					if ( $_trelaciones_->nresultados > 0 ) {
						while( $rels = $_trelaciones_->Fetch() ) {
							$CS = new CSeccion();
							$CS->m_id = $rels["secciones.ID"];
							$CS->m_nombre = $rels["secciones.NOMBRE"];
							$CS->m_ml_nombre = $rels["secciones.ML_NOMBRE"];
				
							$str.= $spacer.str_replace( array( 	'*#'.$this->m_Int2StrArray[$idtipodetalle].':ID#*',
									'*#'.$this->m_Int2StrArray[$idtipodetalle].':NOMBRE#*',
									'*#'.$this->m_Int2StrArray[$idtipodetalle].':NOMBREURL#*' ),
									array( 	$CS->m_id,
											$CS->Nombre(),
											$CS->NombreURL() ),
									$tmpl);
							$spacer = $tmplSP;
						}
						$_trelaciones_->QuitarReferencias();
						return $str.$tmplFT;
					} else {
						$_trelaciones_->QuitarReferencias();
						$str.= $spacer." - ";
						return "";
					}					
				} else if ($tipocampo=="N") {
					if (is_numeric($tmplMUL)) $__CDetalle__->m_entero=$__CDetalle__->m_entero*$tmplMUL;
					if ($tmplRND=="rounded")  $__CDetalle__->m_entero = floor($__CDetalle__->m_entero);
					return( $__CDetalle__->m_entero );
				} else if ($tipocampo=="E") {
					if (is_numeric($tmplMUL)) $__CDetalle__->m_fraccion=$__CDetalle__->m_fraccion*$tmplMUL;
					if ($tmplRND=="rounded")  $__CDetalle__->m_fraccion = floor($__CDetalle__->m_fraccion);
			  		return $__CDetalle__->m_fraccion;								  								  	
				}  else if ($tipocampo=="U") {
			  		$str = "";
			  		
			  		$TipoDetalle = $this->GetTipoDetalle( $__CDetalle__->m_id_tipodetalle );
			  		$ADataDef = XData2Array($TipoDetalle->m_txtdata);
			  		
			  		$idtipocontenido = $ADataDef['id_tipocontenido']['values'];
			  		
			  		global $_tcontenidos_;
			  		global $_tcontenidos2_;
			  		global $Sitio;
			  		global $Admin;
			  		
			  		if (isset($Sitio)) $TiposContenidosA = $Sitio->TiposContenidos;
			  		if (isset($Admin)) $TiposContenidosA = $Admin->TiposContenidos;
			  		
			  		
			  		$_tcontenidos2_ = new Tabla('contenidos',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
			  		
			  		$subcontenidos = array();
			  		$_tcontenidos2_->CopiarTabla($_tcontenidos_);
			  		
			  		$_tcontenidos2_->QuitarReferencias();
				  	$_tcontenidos2_->LimpiarSQL();
				  	$_tcontenidos2_->FiltrarSQL('ID_CONTENIDO','',$__CDetalle__->m_id_contenido);
					$_tcontenidos2_->FiltrarSQL('ID_TIPOCONTENIDO','',$idtipocontenido);
					$_tcontenidos2_->OrdenSQL('contenidos.ID_TIPOCONTENIDO,contenidos.ORDEN ASC');
					$_tcontenidos2_->Open();
					if ( $_tcontenidos2_->nresultados>0 ) {
						//$str.= "resultados:".$_tcontenidos2_->nresultados."<br>";
						$counter = 0;
						
						while($_row_ = $_tcontenidos2_->Fetch() ) {				
							$subcontenidos[$counter] = $_row_['contenidos.ID'];
							$counter++;
						}
					}
					
					if (isset($Sitio)) $ContenidosA = $Sitio->Contenidos;
			  		if (isset($Admin)) $ContenidosA = $Admin->Contenidos;
			  		$sep = "";
					foreach($subcontenidos as $n=>$id) {
							$CC = $ContenidosA->GetContenidoCompleto($id);
							$str.= $sep.$TiposContenidosA->TextoCompleto( $CC );
					}
					
			  		return $str;
			  	} else if ($tipocampo=="K") {
					if ( $__CDetalle__->m_detalle == "" ) {
						$__CDetalle__->m_detalle = "#";
					}				  		
					return( $__CDetalle__->m_detalle );
				} else if ($tipocampo=="FTT") {
					if ($__CDetalle__->m_detalle=="") {
						return "&nbsp;";
					} else return( $__CDetalle__->m_detalle );
			  	} else return( $__CDetalle__->m_detalle );			  	
			  }
				break;
				
		} 
		
	} 
	
	function MostrarColapsado( $__CDetalle__ ) {
		return $this->Mostrar($__CDetalle__);
	}
} 
?>