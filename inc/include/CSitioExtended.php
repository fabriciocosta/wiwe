<?php


class CSitioExtended extends CSitio {
	
	//**ADDMODULE**//
	
						
	function ModuloHomepage() {
		global $__modulo__;
		if ($__modulo__=="home") {
			require('../../inc/modules/ModuloHomepage.php');
		}						
	}	


	function ModuloContacto() {
		global $__modulo__;
		if ($__modulo__=="contacto") {
			require('../../inc/modules/ModuloContacto.php');
		}						
	}

	
	///**************************************************************
	/// Administration for user level
	///**************************************************************
		
	///Muestra el formulario de edicion de datos del usuario
	function UserAdminEdit( $__accion__, &$Usuario, $__template__="" ) {
		
		global $CLang;
		
		if ($this->Usuarios->Logged()) {
			$Usuario = $this->Usuarios->GetSesionUsuario();
			
			if ( $Usuario->m_id != $_SESSION['loggedid'] ) {
				
				return "X";
				
			}
		
			switch($__accion__) {
				case "edituser":
						$this->Usuarios->m_tusuarios->Edicion($Usuario->m_id);
					break;
				case "deleteuser":
						$this->Usuarios->m_tusuarios->Edicion($Usuario->m_id);
					break;
				case "newuser":
					$this->Usuarios->m_tusuarios->Nuevo();
					break;
			}
	
			if ($__template__=="")
				$this->Usuarios->SetTemplateEdicionUsuario( 4 );
			else
				$this->Usuarios->SetTemplateEdicionUsuario( 4, $__template__);
				
			$resstr = $this->Usuarios->EditUsuario( 4, $CLang, $__template__);
			
			foreach( $CLang->m_Words as $field=>$value ) {
				$resstr = str_replace( array( "{".$field."}", ">[".$field."]<" ),  array( $value, ">".$value."<" ), $resstr );
			}		

		} else {
			ShowError( $CLang->Get("LOGERROR") );
		}
		
		return $resstr;
	}
	
	///Confirma y salva o elimina los datos del usuario
	function UserAdminConfirm( $__accion__, &$DatosUsuario, $newpassword="" ) {
		
		global $CLang;
		global $_debug_;
		global $_error_;

		$_exito_ = false;
		//$_debug_ = 'si';
		
		global $not_permitted;

		$not_permitted = ($this->Usuarios->Logged()) && ( $DatosUsuario->m_id != $_SESSION['loggedid'] );		
		
			if ($DatosUsuario!=null)
				$DatosUsuario->ToGlobals();
			
			if ( $__accion__=="confirmnewuser" || $__accion__=="confirmolduser") 
				$_error_ = $this->Usuarios->m_tusuarios->Verificar();//verifica y completa un valor: $errores , y listo
		
			if (!$_error_) {
				switch($__accion__) {
					case "confirmdeleteuser":
						if (!$not_permitted)
							$_exito_ = $this->Usuarios->Eliminar( $DatosUsuario->m_id );
						else DebugError("confirmdeleteuser not permitted : ".$CLang->Get("LOGERROR") );
						break;
					case "confirmnewuser":
						$_exito_ = $this->Usuarios->NuevoUsuario( $DatosUsuario );
						break;					
					case "confirmolduser":
						if (!$not_permitted)				
							$_exito_ = $this->Usuarios->ActualizarUsuario( $DatosUsuario, $newpassword );
						else DebugError("confirmdeleteuser not permitted : ".$CLang->Get("LOGERROR"));
						break;
					case "cancel":
						$_exito_ = true;
						$this->Usuarios->m_tusuarios->exito = $CLang->m_Words['CANCELLED'];
						break;
					default:
						$_exito_ = false;
						$this->Usuarios->m_tusuarios->exito = "ERROR: no action defined";
						DebugError( $this->Usuarios->m_tusuarios->exito );
						break;
				}
			} else {
				DebugError( "Usuarios->m_tusuarios->Verificar dio error" );
			}
		/*
		} else {
			echo '<div class="error">'.$CLang->Get("LOGERROR").'</div>';
		}
		*/					
		return $_exito_;
	}
	
	///Record Edit
	function UserAdminRecordEdit( $__accion__, $__id_tipocontenido__, &$ContenidoAsociado, $__template__="") {
		
		global $CLang, $CMultiLang;
		global $__lang__;

		if ($this->Usuarios->Logged()) {
		
			if ( $ContenidoAsociado->m_id_usuario_creador != $_SESSION['loggedid'] ) {
				
				return ShowError($CLang->Get("PERMISSION_NOT_GRANTED")." id:".$ContenidoAsociado->m_id_usuario_creador, false );
				
			}		
			switch($__accion__) {
				case "editrecord":
					$this->Contenidos->m_tcontenidos->Edicion( $ContenidoAsociado->m_id );
					$ContenidoAsociado->SetFromGlobals();
					//$_e_ID_USUARIO_MODIFICADOR = $_SESSION['idusuario'];
					break;
				case "newrecord":
					$this->Contenidos->m_tcontenidos->Nuevo();
					if ($ContenidoAsociado!=null) {
						$ContenidoAsociado->ToGlobals();
					}
					break;
				case  "deleterecord":
					$this->Contenidos->m_tcontenidos->Edicion( $ContenidoAsociado->m_id );
					$ContenidoAsociado->SetFromGlobals();
					break;
				default:
					break;				
			}
			if ($__template__=="") $this->SetUserTemplates($__id_tipocontenido__);
			else $this->SetUserTemplates($__id_tipocontenido__, $__template__);
			
//			global $__modulo__;
//			//filtramos el campo de secciones... para que no aparezcan SYSTEM y ROOT
//			if ($__modulo__=="register") {
//				//AND ID_TIPOSECCION<>8 == SECTION_BOATS
//				$nested = '/*SPECIAL*/ (ID_TIPOSECCION<>1 AND ID_TIPOSECCION<>2 AND ID_TIPOSECCION<>7 AND ID_TIPOSECCION<>8 AND ID_TIPOSECCION<>'.SECTION_BOATS.')';
//				$this->TiposContenidos->m_templatesedicion[$__id_tipocontenido__]["template"] = str_replace( "*ID_SECCION*", $this->Contenidos->m_tcontenidos->EditarCampoStr("ID_SECCION",$nested,'',$__lang__),$this->TiposContenidos->m_templatesedicion[$__id_tipocontenido__]["template"] );
//			}
	
			$resstr = $this->Contenidos->Edit( $__id_tipocontenido__);

			if (strpos( $resstr, "*DETALLES*") === false ) {
				$resstr = $this->EditarDetalles( $__accion__, $__id_tipocontenido__, $resstr, $ContenidoAsociado );
			} else $resstr = str_replace( "*DETALLES*", $this->EditarDetalles($__accion__, $__id_tipocontenido__, "", $ContenidoAsociado), $resstr );
					
			
			//translate all words
			/*
			foreach( $CLang->m_Words as $field=>$value ) {
				$resstr = str_replace( array( "{".$field."}", ">[".$field."]<", "__".$field."__" ), array( $value, ">".$value."<", $value ), $resstr );
			}*/
			$resstr = $CLang->Translate($resstr);
		} else {
			ShowError( $CLang->Get("LOGERROR") );
		}
				
		return $resstr;		
	}

	///Record Confirm
	function UserAdminRecordConfirm( $__accion__, &$ContenidoAsociado ) {
		global $CLang;
		
		global $_error_;
		global $_errores_;		

		if ($this->Usuarios->Logged()) {
	
			if ( $ContenidoAsociado->m_id_usuario_creador != $_SESSION['loggedid'] ) {
				
				return "X";
				
			}
						
			$_exito_ = false;
			$_error_ = false;
			//$_debug_ = 'si';
			//ACCIONES //atencion esto viene de ensaladas portenas
			
			if ($ContenidoAsociado==null)
				$ContenidoAsociado = new CContenido(); //create from globals
			
			if ( $__accion__=="confirmnewrecord" || $__accion__=="confirmeditrecord" ) $_error_ = $this->Contenidos->m_tcontenidos->Verificar();//verifica y completa un valor: $errores , y listo
		
			if (!$_error_) {
				switch($__accion__) {
					case "confirmdeleterecord":
						$_exito_ = $this->Contenidos->m_tcontenidos->Borrari($ContenidoAsociado->m_id);
						$this->Contenidos->OrdenarContenido($ContenidoAsociado->m_id_seccion);
						break;
					case "confirmnewrecord":
						$_exito_ = $this->Contenidos->m_tcontenidos->Insertar();
						$ContenidoAsociado->m_id = mysql_insert_id($this->Contenidos->m_tcontenidos->CONN);
						$this->Contenidos->OrdenarContenido($ContenidoAsociado->m_id_seccion);					
						break;
					case "confirmeditrecord":
						$_exito_ = $this->Contenidos->m_tcontenidos->Modificari($ContenidoAsociado->m_id);
						break;
					case "cancelrecord":
						$_exito_ = true;
						$this->Contenidos->m_tcontenidos->exito = $CLang->m_Words['CANCELLED'];
						break;
					default:
						$_exito_ = false;
						$this->Contenidos->m_tcontenidos->exito = $CLang->m_ErrorMessages['NOACTIONDEFINED'];
						break;				
				}										
				if ($_exito_) {
					$_exito_ = $this->ConfirmarDetalles( $__accion__, $ContenidoAsociado->m_id_tipocontenido, $ContenidoAsociado->m_id );
				}
			} else $_exito_=false;
		} else {
			ShowError( $CLang->Get("LOGERROR") );
		}
				
		return $_exito_;	
	}
	
	function UserAdminRecordPostProcessAll( ) {
		
	}
	
	function UserAdminRecordPostProcess( &$ContenidoAsociado ) {

			
	}
	
	function HighestPrice( &$CRentPrice ) {
		
		$pricetarif = 1000000000;
		$creca = 0;
		$PriceTable = array();
						
		if ( $CRentPrice !='') {
			$crecs = 0;					
			$lines = explode("\n", $CRentPrice);										
			foreach( $lines as $linestr ) {
				if (trim($linestr)!="") {
					$PriceTable[ $crecs ] = XData2Array( $linestr );
					if ( $pricetarif > floor( trim($PriceTable[$crecs]['tarif']['values']) ) ) {
						$creca = $crecs;
						$pricetarif = floor( trim($PriceTable[$crecs]['tarif']['values']) );
					}
					$crecs++;
				}
			}
		}
		
		return $pricetarif;
	}	
	
	function EditarDetalles( $__accion__, $__id_tipocontenido__, $template = "", &$ContenidoAsociado ) {
		global $CLang;
		global $CMultiLang;
		
		$template_todos = "";
		
		if ($template!="") {			
			if ( ! strpos( $template, "*DETALLES*") === false ) {
				$template_todos = $template;
				$template = "";
			}						
		}
		
		$resstr = '';
		if ($template=="") {
			$resstr.= '<table border="0" cellpadding="3" cellspacing="1" class="MAD_EDIT_DET">';
		}
			$this->TiposDetalles->m_ttiposdetalles->LimpiarSQL();		
			$this->TiposDetalles->m_ttiposdetalles->FiltrarSQL('ID_TIPOCONTENIDO','',$__id_tipocontenido__);
			$this->TiposDetalles->m_ttiposdetalles->Open();
			
			if ($this->TiposDetalles->m_ttiposdetalles->nresultados > 0) {//por cada tipo de detalles iteramos
				//imprimos los campos a editar....
				while ($row_tiposdetalles = $this->TiposDetalles->m_ttiposdetalles->Fetch()) {
				
					//imprimimos el nombre del campo (TIPO) por cada Tipo de detalle
					$inputs = "";
					if ($template=="") {
						$resstr.= '<tr>';
						$resstr.= '<td class="MAD_EDIT_DET_TIT"><span class="MAD_EDIT_DET_TIT">'.$row_tiposdetalles['tiposdetalles.DESCRIPCION'].'</span></td>';
					}
					 
					if ($__accion__=="editrecord" || $__accion__=="deleterecord") { //busca los reg. de detalles existentes para el contenido 
						//$this->Detalles->m_tdetalles->debug='si';	
		  				$this->Detalles->m_tdetalles->LimpiarSQL();
			      		$this->Detalles->m_tdetalles->FiltrarSQL('ID_TIPODETALLE','',$row_tiposdetalles['tiposdetalles.ID']);
			      		$this->Detalles->m_tdetalles->FiltrarSQL('ID_CONTENIDO','',$ContenidoAsociado->m_id);
			      		$this->Detalles->m_tdetalles->Open();
		    		} elseif ($__accion__=="newrecord" ) {
		    			$this->Detalles->m_tdetalles->nresultados = 0;
		    		}
					
					$row_detalles = "";

					$CTipoDetalle = new CTipoDetalle($row_tiposdetalles);
					if ($template=="") {
						$resstr.= '<td class="MAD_EDIT_DET_FIELD">';
					}
					
					if ($this->Detalles->m_tdetalles->nresultados > 0) { //MODIFICAR 
						
						$row_detalles = $this->Detalles->m_tdetalles->Fetch();
						
						$CDetalle = new CDetalle($row_detalles);
						
						//la accion
						$inputs.= '<input name="_adetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" value="modificar">';
						//el id del detalle
						$inputs.= '<input name="_idetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" value="'.$CDetalle->m_id.'">';						
						
						
						//tomamos el valor del contenido asociado para sobreescribir
						if (isset($GLOBALS['_edetalle_'.$CTipoDetalle->m_tipo])) {
							$CDetalle->m_detalle = $GLOBALS['_edetalle_'.$CTipoDetalle->m_tipo];
							$CDetalle->m_ml_detalle = $GLOBALS['_emldetalle_'.$CTipoDetalle->m_tipo];
							$CDetalle->m_txtdata = $GLOBALS['_edetalle_'.$CTipoDetalle->m_tipo];
							$CDetalle->m_ml_txtdata = $GLOBALS['_emldetalle_'.$CTipoDetalle->m_tipo];
						}
						if (is_array($ContenidoAsociado->m_detalles)) {
							$CDetalleCustom = $ContenidoAsociado->m_detalles[$CTipoDetalle->m_tipo];
							if (is_object($CDetalleCustom)) {
								if ($CDetalle->m_id==$CDetalleCustom->m_id && 
									$CDetalle->m_id_tipodetalle==$CDetalleCustom->m_id_tipodetalle) {
									$CDetalle =  $CDetalleCustom;
								}
							}
						}
					} else {
						///else lo construimos de 0
						
						$CDetalle = new CDetalle(array('detalles.ID'=>'',
						'detalles.ID_TIPODETALLE'=>$CTipoDetalle->m_id,
						'detalles.ENTERO'=>0,
						'detalles.FRACCION'=>0,
						'detalles.DETALLE'=>'',
						'detalles.ML_DETALLE'=>'',
						'detalles.TXTDATA'=>'',
						'detalles.ML_TXTDATA'=>'',
						'detalles.BINDATA'=>'',
						'detalles.ID_USUARIO_CREADOR'=>'0',
						'detalles.ID_USUARIO_MODIFICADOR'=>'0',
						'detalles.ACTUALIZACION'=>'NOW()',
						'detalles.BAJA'=>'N'));
						
						if (isset($GLOBALS['_edetalle_'.$CTipoDetalle->m_tipo])) {
							$CDetalle->m_detalle = $GLOBALS['_edetalle_'.$CTipoDetalle->m_tipo];
							$CDetalle->m_ml_detalle = $GLOBALS['_emldetalle_'.$CTipoDetalle->m_tipo];
							$CDetalle->m_txtdata = $GLOBALS['_edetalle_'.$CTipoDetalle->m_tipo];
							$CDetalle->m_ml_txtdata = $GLOBALS['_emldetalle_'.$CTipoDetalle->m_tipo];
						}
						
						//check details if we are still editig new rec
						//
						/*
						if ($CTipoDetalle->m_tipocampo=="X" || $CTipoDetalle->m_tipocampo=="Y")
							echo $CDetalle->m_id_tipodetalle." : ".$CTipoDetalle->m_tipo." TIPOCAMPO:".$CTipoDetalle->m_tipocampo.' TXTDATA:<textarea cols="40" rows="3">'.$CTipoDetalle->m_txtdata."</textarea><br>";
						*/
						if (isset($GLOBALS["_edetalle_".$CTipoDetalle->m_tipo]))
							$this->Detalles->Check( $CDetalle, $CTipoDetalle, $CLang, $CMultiLang, $this->Contenidos, $this->Secciones );
						$CDetalle->m_txtdata = str_replace( "\|", "|", $CDetalle->m_txtdata );
						/*
						if ($CTipoDetalle->m_tipocampo=="X" || $CTipoDetalle->m_tipocampo=="Y")
							echo "DATA: GLOBALS:".$GLOBALS["_edetalle_".$CTipoDetalle->m_tipo].' CHECK: <textarea cols="40" rows="3">'.$CDetalle->m_txtdata."</textarea><br><br>";
						*/
						$inputs.= '<input name="_adetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" value="insertar">';					
						$inputs.= '<input name="_idetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" value="">';					
						
					}

					$inputs.= $this->Detalles->Edit( $CDetalle, $CTipoDetalle, $CLang, $CMultiLang, $this->Contenidos, $this->Secciones );				
					
					if ($template=="") {
						$resstr.= $inputs;								
						$resstr.= '</td>';
						$resstr.= '</tr>';
					} else {
						$template = str_replace( "*#".$CTipoDetalle->m_tipo."#*", $inputs, $template );
					}
				}			
			}
		if ($template=="") {	
			$resstr.= '</table>';
			if ($template_todos!="") {
				$resstr = str_replace( "*DETALLES*", $resstr, $template_todos );
			}
			return $resstr;
		} else {
			return $template;
		}
						
	}
	
	function ConfirmarDetalles( $__accion__, $__id_tipocontenido__, $__id_contenido__ ) {

		global $CLang,$CMultiLang;
		global $_exito_;
	
		$_exito_ = true;
		Debug('[confirming details: idtipocontenido:'.$__id_tipocontenido__.' idcontenido:'.$__id_contenido__.']');
		if ($__accion__!='cancelrecord') {
			//$GLOBALS['_debug_'] = 'si';	
			//$this->TiposDetalles->m_ttiposdetalles->debug = 'si';
			
		  $this->TiposDetalles->m_ttiposdetalles->LimpiarSQL();
		  $this->TiposDetalles->m_ttiposdetalles->FiltrarSQL('ID_TIPOCONTENIDO','',$__id_tipocontenido__);
		  $this->TiposDetalles->m_ttiposdetalles->Open();
		  
			if ($this->TiposDetalles->m_ttiposdetalles->nresultados > 0) {//por cada tipodedetalle de este contenido
				//imprimos los campos a editar....
				while ($row_tiposdetalles = $this->TiposDetalles->m_ttiposdetalles->Fetch()) {  
					
					$TipoDetalle = new CTipoDetalle($row_tiposdetalles);					
					$CDetalle = new CDetalle(array(
					'detalles.ID'=>$GLOBALS['_idetalle_'.$TipoDetalle->m_tipo],
					'detalles.ID_TIPODETALLE'=>$TipoDetalle->m_id,
					'detalles.ID_CONTENIDO'=>$__id_contenido__,
					'detalles.ENTERO'=>0,
					'detalles.FRACCION'=>0,
					'detalles.DETALLE'=>$GLOBALS['_edetalle_'.$TipoDetalle->m_tipo],
					'detalles.ML_DETALLE'=>$GLOBALS['_emldetalle_'.$TipoDetalle->m_tipo],
					'detalles.TXTDATA'=>$GLOBALS['_edetalle_'.$TipoDetalle->m_tipo],
					'detalles.ML_TXTDATA'=>$GLOBALS['_emldetalle_'.$TipoDetalle->m_tipo],
					'detalles.BINDATA'=>''));
					
					Debug("Updating ".$TipoDetalle->m_tipo." => i:".$GLOBALS['_idetalle_'.$TipoDetalle->m_tipo]." e:".$GLOBALS['_edetalle_'.$TipoDetalle->m_tipo]);
					
					if (($__accion__=='confirmeditrecord') || ($__accion__=='confirmnewrecord')) {      	
						$_exito_ = $_exito_ && $this->Detalles->Confirm( $CDetalle, $TipoDetalle, $CLang, $CMultiLang, $this->Contenidos, $this->Secciones );
		      		} else if ($__accion__=='confirmdeleterecord') {
			        	//borrarlos
			        	$this->Detalles->m_tdetalles->LimpiarSQL();
			        	$this->Detalles->m_tdetalles->SQL = 'DELETE FROM detalles WHERE ID_CONTENIDO='.$__id_contenido__;
			        	$_exito_ = $_exito_ && $this->Detalles->m_tdetalles->EjecutaSQL();
		      		}
		  	}
		  }
		
		}
		return $_exito_;
		
	}
		
	function SetUserTemplates($idtipocontenido=0,$__template__="") {
		global $_TIPOS_;
		if($idtipocontenido==0)
			foreach($_TIPOS_['tiposcontenidos'] as $tipo=>$id) {
				if ($id>2) $this->TiposContenidos->SetTemplateEdicionUsuario($id);
			}
		else {
			if ($__template__=="") $this->TiposContenidos->SetTemplateEdicionUsuario($idtipocontenido);
			else $this->TiposContenidos->SetTemplateEdicionUsuario($idtipocontenido, $__template__);
		}
				

		if (file_exists('../../inc/include/templatesuser.php')) {
			require('../../inc/include/templatesuser.php');			
		}
	}
	
	//_email_ must be defined
	function SendMessage( &$variables, &$mandatories, &$results, $emailfrom, $sitename, $enviara, &$template ) {
		
		global $CLang;
		
		$results['errores'] = 0;
		
		if (!function_exists("checkEmail")) {
			require "../../inc/core/validateemail.php";	
			require "../../inc/include/phpmailer/class.phpmailer.php";
		}
		
		$CMail = new PHPMailer();
		$CMail->IsMail();
		
		//CAMPOS OBLIGATORIOS
		$msgerror = '<span class="error">'.$CLang->m_ErrorMessages['REQUIREDFIELD'].'</span>';
		$msgmailerror = '<span class="error">'.$CLang->m_ErrorMessages['INVALIDEMAIL'].'</span>';

		foreach($mandatories as $field=>$value) {
			if ( $variables[$field]=="" ) {
				$mandatories[$field] = "<br>".$msgerror;
				$results['errores']++;
			} else {
				if ($field=="_email_") {
					if (checkEmail($variables[$field])==false) {
						$results['errores']++;
						$mandatories[$field] = "<br>".$msgmailerror;
					}
				}
			}
		}
		
		if ($template!="") {
			$mensaje1 = $template;
		} else {
			$mensaje1 = "";
			foreach( $variables as $field=>$value ) {
				$mensaje1.= " {".$field."}: "."*".$field."*  "."\n";
			}
		}
			
		if (isset($results['errores']) && $results['errores']==0) {
			
			$motivo = $sitename;
			
			if (count($variables)>0)
				foreach( $variables as $field=>$value ) {
					$emailname = str_replace( array("*".$field."*","{".$field."}"), array($value, $CLang->m_Words[strtoupper($field)] ), $emailname);
					$mensaje1 = str_replace( array("*".$field."*","{".$field."}"), array($value, $CLang->m_Words[strtoupper($field)] ), $mensaje1 );
				}
				
			if (is_array($emailfrom)) {
				$emailfrom_mail = $emailfrom["email"];
				$emailfrom_name = $emailfrom["name"];
			} else {
				$emailfrom_mail = $emailfrom_name = $emailfrom;
			}
			$CMail->From = $emailfrom_mail;
			$CMail->FromName = $emailfrom_name;
			$CMail->AddAddress($enviara, $sitename);
			if (isset($variables['_email_'])) $CMail->AddReplyTo( $variables['_email_'], $emailname );	
			$CMail->IsHTML(true);
			
			$CMail->Subject = $motivo;			
			$CMail->Body = str_replace("\n","<br>",$mensaje1);
			$CMail->AltBody = $mensaje1;
					
			if (!$CMail->Send()) {
				$results['errores']=1;
				$results['result'] = '<span class="error">'.$CLang->m_ErrorMessages['MAILNOTSENT'].'<br></span>';
			} else {
				//reset
				foreach($variables as $field=>$value) {
					$variables[$field] = "";
				}
				$results['result'] = $CLang->m_Messages['MAILSENT'].'<br>';
			}
		} else $results['result'] = '<span class="error">'.$CLang->m_ErrorMessages['MAILNOTSENT'].'<br>'.$CLang->m_Words['FILLTHEFORM'].'<br></span>';
		
		
	}

	function UpdateMessage( &$variables, &$mandatories, &$results, &$template ) {
			//values
			foreach($variables as $field=>$value) {
				 if ($field == "_civilite_")  { 
				 	$template = str_replace( "*".$field.$value."*", " checked", $template);
				 	$template = str_replace(	array("*".$field."MISS*", "*".$field."MADAM*", "*".$field."MISTER*"), 
				 								array("","",""), 
				 								$template);
				 } else $template = str_replace( array("*".$field."*"), array($value), $template);
			}
			
			//errors
			foreach($mandatories as $field=>$value) {
				$template = str_replace( array("#".$field."#"), array($value), $template);
			}
				
			$template = str_replace( '*result*', $results['result'], $template );
	}
	
	
	
}

?>