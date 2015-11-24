<?php

/**
 * class CUsuarios
 *
 * @version 20/05/2007
 * @copyright 2003 
 **/

 
class CUsuarios extends CErrorHandler {

	var $m_tusuarios;//tabla secciones
	
	var $m_CSecciones;
	var $m_CContenidos;	
	var $m_CRelaciones;
	
	var $m_CUsuario;
	var $m_CSesion;
	var $m_CSesionUsuario;
	
	var $m_CSenderUsuario; ///Usuarioque envia los mails (se puede asignar luego arbitrariamente)
	var $m_nick_sender;
	
	var $m_Sesion;
		
	//buffer
	var $m_templatesconsulta;
	var $m_templatesedicion;
	var $m_templatesedicionusuario;
	var $m_templatesmessages;
	
	function CUsuarios(&$__tusuarios__,&$__m_CSecciones__, &$__m_CContenidos__, &$__m_CRelaciones__ ) {
		
		$this->Set( $__tusuarios__, $__m_CSecciones__,  $__m_CContenidos__, $__m_CRelaciones__ );
	}
	
	function SetNickSender($nick_sender) {
		$this->m_nick_sender = $nick_sender;
	}
	
	function Set(&$__tusuarios__,&$__m_CSecciones__, &$__m_CContenidos__,  &$__m_CRelaciones__) {
		$this->m_CSecciones = &$__m_CSecciones__;
		$this->m_CContenidos = &$__m_CContenidos__;
		$this->m_CRelaciones = &$__m_CRelaciones__;
		$this->m_tusuarios = &$__tusuarios__;
		
		if ($this->m_nick_sender=='') $this->m_nick_sender = "admin";
		
		$this->m_CSenderUsuario = $this->GetUsuario( 0, $this->m_nick_sender );
		
		
		parent::CErrorHandler();
	}	

	function SetTemplateEdicion($__nivelusuario__=0, $__template__="") {
		global $__lang__;
		if ($__lang__!="") $l = $__lang__."."; else $l = "";
		if ($__template__=="") {
			$file = "../../inc/templates/USUARIO".$__nivelusuario__.".edicion.".$l."html";			
			if (!file_exists($file)) $file = "../../inc/templates/USUARIO.edicion.".$l."html";
			if (!file_exists($file)) $file = "../../inc/templates/USUARIO.edicion.html";
			$__template__ = implode('', file($file));
		}
		if ($__template__=="") { $__template__= "*NICK*".DebugError("CUsuarios::SetTemplateEdicion >> template vació"); }
		$this->m_templatesedicion[$__nivelusuario__] = $__template__;	
	}	

	function SetTemplateEdicionUsuario($__nivelusuario__=0, $__template__="") {
		global $__lang__;
		if ($__lang__!="") $l = $__lang__."."; else $l = "";
		if ($__template__=="") {
			$fjose = "../../inc/templates/USUARIO".$__nivelusuario__.".user.".$l."html";			
			if (file_exists($fjose)) {
				$__template__ = implode('', file($fjose));
			} else {
				$fjose = "../../inc/templates/USUARIO.user.".$l."html";
				$__template__ = implode('', file($fjose));				
			}		
		}
		$this->m_templatesedicionusuario[$__nivelusuario__] = $__template__;	
	}	
	
	function SetTemplateConsulta($__nivelusuario__=0, $__template__="") {
		global $__lang__;
		if ($__lang__!="") $l = $__lang__."."; else $l = "";
		if ($__template__=="") {
			$fjose = "../../inc/templates/USUARIO".$__nivelusuario__.".consulta.".$l."html";
			if (file_exists($fjose)) {
				$__template__ = implode('', file($fjose));
			} else {
				$fjose = "../../inc/templates/USUARIO.consulta.".$l."html";
				$__template__ = implode('', file($fjose));
			}
		}
		$this->m_templatesconsulta[$__nivelusuario__] = $__template__;	
	}	

	
	function SetTemplateMessage($__nivelusuario__=0, $__message_code__, $__template__="") {
		global $__lang__;
		if ($__lang__!="") $l = $__lang__."."; else $l = "";
		if ($__template__=="") {
			$fjose = "../../inc/templates/USUARIO".$__nivelusuario__.".message.".$__message_code__.".".$l."html";
			if (file_exists($fjose)) {
				$__template__ = implode('', file($fjose));
			} else {
				$fjose = "../../inc/templates/USUARIO.message.".$__message_code__.".".$l."html";
				if (file_exists($fjose)) {
					$__template__ = implode('', file($fjose));
				}
			}
		}
		if (!is_array($this->m_templatesmessages)) $this->m_templatesmessages = array( 0=>array());
		$this->m_templatesmessages[$__nivelusuario__][$__message_code__] = $__template__;
		return $__template__;
	}	
	
	/**
	 * Procesa el contenido dado a través de la plantilla asociada, en el modo completo.
	 * Todos los campos son procesados.
	 *
	 * @param CContenido $__CContenido__
	 * @param Text $__template__
	 * @return Text el texto resultante del procesamiento de la plantilla
	 */
	function TextoConsulta( &$__CUsuario__,$__template__='') {
		global $__lang__;		
		
		$__nivelusuario__ = $__CUsuario__->m_nivel;
		
		if ($__template__=='' && isset($this->m_templatesconsulta[$__nivelusuario__])) $__template__ = $this->m_templatesconsulta[$__nivelusuario__];
		if ($__template__=='') $__template__ = "*usuarios.NOMBRE*".DebugError("CUsuarios::TextoConsulta >> Template is empty!!"); 
		
		
		$__template__ = str_replace( array("*ID*","*usuarios.ID*"), $__CUsuario__->m_id, $__template__);
		$__template__ = str_replace( array("*NICK*","*usuarios.NICK*"), $__CUsuario__->m_nick, $__template__);
		$__template__ = str_replace( array("*NOMBRE*","*usuarios.NOMBRE*"), $__CUsuario__->m_nombre, $__template__);
		$__template__ = str_replace( array("*APELLIDO*","*usuarios.APELLIDO*"), $__CUsuario__->m_apellido, $__template__);
		$__template__ = str_replace( array("*MAIL*","*usuarios.MAIL*"), $__CUsuario__->m_mail, $__template__);
		$__template__ = str_replace( array("*ICONO*","*usuarios.ICONO*"), $__CUsuario__->m_icono, $__template__);		
		$__template__ = str_replace( array("*CIUDAD*","*usuarios.CIUDAD*"), $__CUsuario__->m_ciudad, $__template__);
		$__template__ = str_replace( array("*PROVINCIA*","*usuarios.PROVINCIA*"), $__CUsuario__->m_provincia, $__template__);
		$__template__ = str_replace( array("*PAIS*","*usuarios.PAIS*"), $__CUsuario__->m_pais, $__template__);
		
		$__template__ = str_replace( array("*IDIOMAS*","*usuarios.IDIOMAS*"), $__CUsuario__->m_idiomas, $__template__);
		$__template__ = str_replace( array("*ID_CONTENIDO*","*usuarios.ID_CONTENIDO*"), $__CUsuario__->m_id_contenido, $__template__);
		
		
		$__template__ = str_replace( array("*NIVEL*","*usuarios.NIVEL*"), $__CUsuario__->m_nivel, $__template__);
		$__template__ = str_replace( array("*BAJA*","*usuarios.BAJA*"), $__CUsuario__->m_baja, $__template__);
		
		$__template__ = str_replace( array("*ACTUALIZACION*","*usuarios.ACTUALIZACION*"), Fecha($__CUsuario__->m_actualizacion), $__template__);
		$__template__ = str_replace( array("*ACTUALIZACION*","*usuarios.CREACION*"), Fecha($__CUsuario__->m_creacion), $__template__);
		//$this->m_CDetalles->MostrarDetallesColapsados( $__CContenido__->m_id, $__template__);
		
		/*REFORMATEAMOS LOS LINKS POR LAS DUDAS*/
		ReformatLinks($__template__);
		return $__template__;
												
	}
	
	function TextoCompleto(&$__CUsuario__,$__template__='') {
		
		global $__lang__;
		
		$__nivelusuario__ = $__CUsuario__->m_nivel;
		
		if ($__template__=='' && isset($this->m_templatescompleto[$__nivelusuario__])) $__template__ = $this->m_templatescompleto[$__nivelusuario__];
		if ($__template__=='') $__template__ = "*usuarios.NOMBRE*".DebugError("CUsuarios::TextoCompleto >> Template is empty!!"); 
			
			
		$matches = array();
		$valuesarray = $__CUsuario__->FullArray();
			
		preg_match_all( "/\*(.*?)\*/", $__template__, $matches );
		
		foreach( $matches[0] as $match) {
			$match_txt = substr( $match, 1, strlen($match)-2 );
			
			Debug("MATCH:".$match." : ".$match_txt."<br>");
			
			if (isset($valuesarray[$match_txt])) {
				$valuetxt = $__CUsuario__->Get( $match_txt );
				$__template__ = str_replace( $match, $valuetxt, $__template__ );
			}
		}
		
		return $__template__;		
		
	}
	

	function UsuarioRegistrado( $__nick__, $__password__ ) {
		
		$this->m_tusuarios->LimpiarSQL();
		$__nick__ = strip_tags( $__nick__ );
		$__password__ = strip_tags( $__password__ );
		$this->m_tusuarios->SQL = "SELECT usuarios.ID,usuarios.NICK,usuarios.NOMBRE,usuarios.APELLIDO,usuarios.MAIL FROM usuarios WHERE usuarios.NICK='".$__nick__."' AND usuarios.PASSWORD=".$GLOBALS['_PASSWORD_VERSION']."('".$__password__."') AND usuarios.BAJA='S'";
		$this->m_tusuarios->SQLCOUNT = "SELECT COUNT(*) FROM usuarios WHERE usuarios.NICK='".$__nick__."' AND usuarios.PASSWORD=".$GLOBALS['_PASSWORD_VERSION']."('".$__password__."') AND usuarios.BAJA='S'";
		$this->m_tusuarios->Open();
		if ($this->m_tusuarios->nresultados==0) {
			//try with email:
			$this->m_tusuarios->SQL = "SELECT usuarios.ID,usuarios.NICK,usuarios.NOMBRE,usuarios.APELLIDO,usuarios.MAIL FROM usuarios WHERE usuarios.MAIL='".$__nick__."' AND usuarios.PASSWORD=".$GLOBALS['_PASSWORD_VERSION']."('".$__password__."') AND usuarios.BAJA='S'";
			$this->m_tusuarios->SQLCOUNT = "SELECT COUNT(*) FROM usuarios WHERE usuarios.MAIL='".$__nick__."' AND usuarios.PASSWORD=".$GLOBALS['_PASSWORD_VERSION']."('".$__password__."') AND usuarios.BAJA='S'";
			$this->m_tusuarios->Open();
		}
		if 	(	$this->m_tusuarios->nresultados==0) {
			//try with email:
			$this->m_tusuarios->SQL = "SELECT usuarios.ID,usuarios.NICK,usuarios.NOMBRE,usuarios.APELLIDO,usuarios.MAIL FROM usuarios WHERE usuarios.MAIL='".$__nick__."' AND usuarios.PASSWORD=".$GLOBALS['_PASSWORD_VERSION']."('".$__password__."')";
			$this->m_tusuarios->SQLCOUNT = "SELECT COUNT(*) FROM usuarios WHERE usuarios.MAIL='".$__nick__."' AND usuarios.PASSWORD=".$GLOBALS['_PASSWORD_VERSION']."('".$__password__."')";
			$this->m_tusuarios->Open();
			if 	(	$this->m_tusuarios->nresultados==1) {
				$this->PushError( new CError("WAITING_FOR_ACTIVATION", "Waiting for activation, access denied." ) );
				return false;
			}
		}
		
		return ( $this->m_tusuarios->nresultados==1);
	}

	function SesionIn( $__nick__, $__password__ ) {
		$_SESSION['logged'] = 'no';
		$_SESSION['loggedid'] = 0;
		$_SESSION['loggednick'] = "";
		if ($this->UsuarioRegistrado( $__nick__, $__password__ )) {
			$this->m_CSesionUsuario = $this->GetUsuario( 0, $__nick__ );			//$this->ActualizarUsuario($this->m_CSesionUsuario);
			if ($this->m_CSesionUsuario==null) {
				$this->m_CSesionUsuario = $this->GetUsuario( 0, '', $__nick__ ); 
			}			
			if ($this->m_CSesionUsuario==null) {
				return false;
			}
			$_SESSION['logged'] = "si";
			$_SESSION['loggedid'] = $this->m_CSesionUsuario->m_id;
			$_SESSION['loggednick'] = $this->m_CSesionUsuario->m_nick;
			return true;
		}
		return false;
	}

	function Logged() {
		if (isset($_SESSION['logged']) && $_SESSION['logged']=="si") return true;
		return false;
	}
	
	function SesionContinue() {
		Debug("CUsuarios::SesionContinue");
		if (isset($_SESSION['logged']) ) {
			$this->m_CSesionUsuario = $this->GetUsuario( $_SESSION["loggedid"] );
			if (is_object($this->m_CSesionUsuario)) {
				$_SESSION["loggedid"] = $this->m_CSesionUsuario->m_id;
				$_SESSION["loggednick"] = $this->m_CSesionUsuario->m_nick;
				Debug("user in session... all ok");
			} else {
				$_SESSION["loggedid"] = 0;
				$_SESSION["loggednick"] = "";
				$_SESSION['logged'] = 'no';
				DebugError("user object not available, logout");
			}			
		}
	}
	
	function SesionOut() {
		$this->m_CSesionUsuario = null;
		$_SESSION['logged'] = "no";
		$_SESSION["loggedid"] = "";
		$_SESSION["loggednick"] = "";
		return true;
	}	
	
	function CheckUser() {
		$this->m_CSesionUsuario = null;
		if ($this->Logged()) {
			
			$this->m_CSesionUsuario = $this->GetUsuario( $_SESSION["loggedid"] );
			
			if (!is_object($this->m_CSesionUsuario)) {
				
				$this->SesionOut();
				
				return false;
				
			}
			
			return true;
		}
		return false;		
	}
		
	function ResetearPassword( &$__Usuario__ ){
		
		$__Usuario__->password = $__Usuario__->m_nombre.rand(12345,65535);
		
		$this->ActualizarUsuario( $__Usuario__, $__Usuario__->password );
	}

	function GetPassword( &$__Usuario__ ) {
		if ($__Usuario__!=null)
			return $this->DeCrypt( $__Usuario__->m_passkey );
				
		return 0;		
	}
	
	function GetRoles( $__idusuario__ ) {
		
		//ShowMessage("Roles");		
		
		$Usuario = $this->GetUsuario($__idusuario__);
		
		if ( !is_object($Usuario) ) {
			ShowError("GetRoles, no existe el usuario");
			return false;
		}		
		
		//ShowMessage("Usuario:".$Usuario->m_nick);

		$FichaUsuario = NULL;
						
		if (is_numeric($Usuario->m_id_contenido) && $Usuario->m_id_contenido<=0) {
			ShowError("GetRoles, no tiene ficha de usuario asociada.");
			return false;			
		}		
		
		$FichaUsuario = $this->m_CContenidos->GetContenidoCompleto($Usuario->m_id_contenido);
			
		if (!is_object($FichaUsuario)) {
			ShowError("GetRoles, ficha de usuario no existente o búsqueda incorrecta: ".$Usuario->m_id_contenido );
			return false;						
		}
		
		//ShowMessage("Ficha Usuario:".$FichaUsuario->Titulo());		
		
		
		$_roles_ = array();
		
		$_tr_ = $this->m_CRelaciones->m_trelaciones;
		$_tr_->LimpiarSQL();
		$_tr_->FiltrarSQL('ID_CONTENIDO','', $FichaUsuario->m_id);
		$_tr_->FiltrarSQL('ID_TIPORELACION','', USUARIO_ROLES );
		$_tr_->Open();
		if ($_tr_->nresultados>0) {
			while( $_r_ = $_tr_->Fetch()) {
				$CRelacion = new CRelacion($_r_);
				$CRol = $this->m_CContenidos->GetContenidoCompleto( $CRelacion->m_id_contenido_rel );
				if (!is_object($CRol)) {
					ShowError("GetRoles, Rol referenciado no existe.".$CRelacion->m_id_contenido_rel);
					return false;
				}
				$_roles_[$CRol->m_id] = $CRol;
			}
		} else return false;
		
		
		//ShowMessage( 'Nivel: '.$Usuario->m_nivel );		
		//ShowMessage( $FichaUsuario->Titulo() );

		return $_roles_;
		
		
	}

	function SeccionPermitida( $idseccion, &$rol_secciones ) {
		if (is_array($rol_secciones) && is_object( $rol_secciones[$idseccion]) ) {
			return true;
		}
		return false;
	}

	function TipoSeccionPermitida( $idtiposeccion, &$rol_tipossecciones ) {
		if (is_array($rol_tipossecciones) && is_object( $rol_tipossecciones[$idtiposeccion]) ) {
			return true;
		}
		return false;
	}	
	
	function GetRolTiposSecciones( $__idrol__ ) {
			
			$_rol_tipossecciones_ = array();
			
			$_tr_ = $this->m_CRelaciones->m_trelaciones;
			$_tr_->LimpiarSQL();
			$_tr_->FiltrarSQL('ID_CONTENIDO','', $__idrol__);
			$_tr_->FiltrarSQL('ID_TIPORELACION','', ROL_TIPOSSECCIONES );
			$_tr_->Open();
			
			if ($_tr_->nresultados>0) {
				
				while( $_r_ = $_tr_->Fetch()) {
					
					$CRelacion = new CRelacion($_r_);
					$CTipoSeccion = $this->m_CSecciones->m_CTiposSecciones->GetTipoSeccion( $CRelacion->m_peso );
					
					if (!is_object($CTipoSeccion)) {
						
						ShowError("GetRolTiposSecciones, Seccion referenciada no existe.".$CRelacion->m_peso );
						return false;
						
					}
					
					$_rol_tipossecciones_[$CTipoSeccion->m_id] = $CTipoSeccion;
					
				}
			} else return false;
		
			return $_rol_tipossecciones_;
	}	
	
	function GetRolSecciones( $__idrol__ ) {
			
			$_rol_secciones_ = array();
			
			$_tr_ = $this->m_CRelaciones->m_trelaciones;
			$_tr_->LimpiarSQL();
			$_tr_->FiltrarSQL('ID_CONTENIDO','', $__idrol__);
			$_tr_->FiltrarSQL('ID_TIPORELACION','', ROL_SECCIONES );
			$_tr_->Open();
			
			if ($_tr_->nresultados>0) {
				
				while( $_r_ = $_tr_->Fetch()) {
					
					$CRelacion = new CRelacion($_r_);
					$CSeccion = $this->m_CSecciones->GetSeccion( $CRelacion->m_id_seccion_rel );
					
					if (!is_object($CSeccion)) {
						
						ShowError("GetRolSecciones, Seccion referenciada no existe.".$CRelacion->m_id_seccion_rel);
						return false;
						
					}
					
					$_rol_secciones_[$CSeccion->m_id] = $CSeccion;
					
				}
			} else return false;
		
			return $_rol_secciones_;
	}
	
	function GetUsuarioSecciones( $__idusuario__ ) {

		$roles = $this->GetRoles( $__idusuario__ );
		
		if ($roles===false) {
			ShowError("Usuarios::RolGetSecciones: Sin roles");
			return false;
		}
		
		$usuario_secciones = array();
		
		if (is_array($roles)) {
			
			foreach($roles as $idrol=>$CRol) {
				
				//ShowMessage('Rol #'.$idrol.' : '.$CRol->Titulo());
				
				$_rol_secciones_ = $this->GetRolSecciones($idrol);

				if (is_array($_rol_secciones_))
					foreach($_rol_secciones_ as $idseccion=>$CSeccion) {
						$usuario_secciones[$idseccion] = $CSeccion;
					}
				
			}
			
		}
		
		return $usuario_secciones;		
		
	}
	
	
	function GetUsuarioAccesoHabilitar( $__idusuario__ ) {
		
		//ShowMessage("GetUsuarioAccesoHabilitar:".$__idusuario__);
		
		$roles = $this->GetRoles( $__idusuario__ );
		
		if ($roles===false) {
			ShowError("Usuarios::RolGetSecciones: Sin roles");
			return true;
		}

		$acceso_habilitar = false;
		
		if (is_array($roles)) {
			
			foreach($roles as $idrol=>$CRol) {
				//ShowMessage( 'Rol: '.$CRol->Titulo() );
				if (is_object($CRol->m_detalles["ROL_APPROVAL"])) {
					//ShowMessage('ROL_APPROVAL:'.$CRol->m_detalles["ROL_APPROVAL"]->m_detalle);
					if ($CRol->m_detalles["ROL_APPROVAL"]->m_detalle=="[YES]") {
						$acceso_habilitar = true;
						break;
					}
				}
				
			}
			
		} else {
			ShowMessage("Atención: no hay roles definidos para este usuario.");
		}
		
		return $acceso_habilitar;
		
	}
	
	
	function GetUsuarioAccesoUsuarios( $__idusuario__ ) {
		
		//ShowMessage("GetUsuarioAccesoUsuarios:".$__idusuario__);
		
		$roles = $this->GetRoles( $__idusuario__ );
		
		if ($roles===false) {
			ShowError("Usuarios::RolGetSecciones: Sin roles");
			return true;
		}

		$acceso_contenidos_otros_usuarios = false;
		
		if (is_array($roles)) {
			
			foreach($roles as $idrol=>$CRol) {
				//ShowMessage( 'Rol: '.$CRol->Titulo() );
				if (is_object($CRol->m_detalles["ROL_USER_ACCESS"])) {
					//ShowMessage('ROL_USER_ACCESS:'.$CRol->m_detalles["ROL_USER_ACCESS"]->m_detalle);
					if ($CRol->m_detalles["ROL_USER_ACCESS"]->m_detalle=="[YES]") {
						$acceso_contenidos_otros_usuarios = true;
						break;
					}
				}
				
			}
			
		} else {
			ShowMessage("Atención: no hay roles definidos para este usuario.");
		}
		
		return $acceso_contenidos_otros_usuarios;
		
	}
	
	function GetUsuarioTiposSecciones( $__idusuario__ ) {

		$roles = $this->GetRoles( $__idusuario__ );
		
		if ($roles===false) {
			ShowError("Usuarios::RolGetSecciones: Sin roles");
			return false;
		}
		
		$usuario_tipossecciones = array();
		
		if (is_array($roles)) {
			
			foreach($roles as $idrol=>$CRol) {
				
				//ShowMessage('Rol #'.$idrol.' : '.$CRol->Titulo());
				
				$_rol_tipossecciones_ = $this->GetRolTiposSecciones($idrol);

				if (is_array($_rol_tipossecciones_))
					foreach($_rol_tipossecciones_ as $idseccion=>$CTipoSeccion) {
						$usuario_tipossecciones[$idseccion] = $CTipoSeccion;
					}
				
			}
			
		}
		
		return $usuario_tipossecciones;		
		
	}	
	
	function GetUsuario( $__idusuario__, $__nick__="", $__mail__="" ) {
		if ($__idusuario__==0 && $__nick__=="" && $__mail__=="") {
			return null;
		}
		$this->m_tusuarios->LimpiarSQL();			
		if ($__nick__=="" && $__mail__=="" ) {
			$this->m_tusuarios->FiltrarSQL('ID','',$__idusuario__);
		} 
		if ($__nick__!="") {
			$this->m_tusuarios->FiltrarSQL('NICK','',$__nick__);
		}
		if ($__mail__!="") {
			$this->m_tusuarios->FiltrarSQL('MAIL','',$__mail__);
		}
		$this->m_tusuarios->Open();		
		
		if ( $this->m_tusuarios->nresultados==1 ) {
			$_row_ = $this->m_tusuarios->Fetch();
			$this->m_CUsuario = new CUsuario($_row_);
			return $this->m_CUsuario;
		} else {
			if ( $this->m_tusuarios->nresultados==0 ) ShowError("CUsuarios::GetUsuario > no se encuentra el usuario, ".$__idusuario__.",".$__nick__.",".$__mail__);
			if ( $this->m_tusuarios->nresultados>1 ) ShowError("CUsuarios::GetUsuario > demasiados usuarios para esta búsqueda, ".$__idusuario__.",".$__nick__.",".$__mail__);
			return null;
		}
		
	}
	
	function GetSesionUsuario() {
		return $this->m_CSesionUsuario;
	}
	
	function NickUtilizado($__nick__) {
		$this->m_tusuarios->LimpiarSQL();
		$this->m_tusuarios->FiltrarSQL('NICK','',$__nick__);
		$this->m_tusuarios->Open();
		return ( $this->m_tusuarios->nresultados>0 );
	}
	
	function MailUtilizado($__mail__) {
		$this->m_tusuarios->LimpiarSQL();
		$this->m_tusuarios->FiltrarSQL('MAIL','',$__mail__);
		$this->m_tusuarios->Open();
		return ( $this->m_tusuarios->nresultados>0 );
	}	

	function NuevoUsuario( &$__CNuevoUsuario__ ) {
		global $CLang;
		//$this->m_tusuarios->debug ='si';
		if ($__CNuevoUsuario__->m_nick=='' || $__CNuevoUsuario__->m_password=='') {
			$CError = new CError("NICKORPASSWORDEMPTY","inserting new user >> CUsuarios::NuevoUsuario");
			//$this->m_CErrores->PushError( $CError );
			$this->PushError( $CError );
			return false;			
		}
		
		if ( !$this->NickUtilizado( $__CNuevoUsuario__->m_nick ) ) {
			
			$__CNuevoUsuario__->m_passmd5 = md5($__CNuevoUsuario__->m_password);
			$__CNuevoUsuario__->m_passkey = $this->Crypt( $__CNuevoUsuario__->m_password );			
			
			if ( $this->m_tusuarios->InsertarRegistro( $__CNuevoUsuario__->FullArray() ) ) {
				$__CNuevoUsuario__->m_id = $this->m_tusuarios->lastinsertid;
				return true;
			} else {
				$CError = new CError("RECORD_CREATION_ERROR","inserting new user >> CUsuarios::NuevoUsuario");
				//$this->m_CErrores->PushError( $CError );
				$this->PushError( $CError );
				return false;
			}
		} else {
			$CError = new CError("CLIENTALREADYREGISTERED","inserting new user >> CUsuarios::NuevoUsuario");
			//$this->m_CErrores->PushError( $CError );
			$this->PushError( $CError );
			return false;	
		}
	}
	
	/**
	 * Elimar un registro de la tabla usuarios teniendo en cuenta los permisos de usuario del sistema
	 * predeterminadamente se da permisos a Administradores Generales, y Administadores de Contenidos, 
	 * y solamente se pueden eliminar usuarios ( no administradores de contenidos ) 
	 * ( Los administradores de contenidos deberian esconderse unicamente )
	 * TODO: correctamente plantear lo que se puede borrar o no
	 *
	 * @param integer $__idcontenido__ id del contenido
	 * @return verdadero si lo pudo borrar o falso en otro caso
	 */
	function Eliminar( $__idusuario__ ) {		
		
		///no se puede borrar el root admin
		if ($__idusuario__==1 ) {
			$this->PushError( new CError("ROOT_ADMIN_ERROR", "Couldn't delete root admin ") );
			return false;
		}
		
		$CU = $this->GetUsuario($__idusuario__);
				
		if (is_object( $CU ) ) {			
			if ( (	$_SESSION['nivel']==0	)
					 ) {				

				///si este usuario tiene contenidos asociados, pasarlos al super admin
				$update_usuario_id = "UPDATE contenidos SET ID_USUARIO_CREADOR=1 
				WHERE ( ID_USUARIO_CREADOR=$__idusuario__ ) ";
				if (! $this->m_tusuarios->EjecutaSQL( $update_usuario_id )) {
					$this->PushError( new CError("QUERY_ERROR", "Couldn't override contenidos ID_USUARIO_CREADOR ") );
					return false;	
				}					 	
					 	
				///si este usuario tiene contenidos asociados, pasarlos al super admin
				$update_usuario_id = "UPDATE contenidos SET ID_USUARIO_MODIFICADOR=1 
				WHERE ( ID_USUARIO_MODIFICADOR=$__idusuario__) ";
				if (! $this->m_tusuarios->EjecutaSQL( $update_usuario_id )) {
					$this->PushError( new CError("QUERY_ERROR", "Couldn't override contenidos ID_USUARIO_MODIFICADOR ") );
					return false;	
				}
				
				$update_usuario_id = "UPDATE secciones SET ID_USUARIO_CREADOR=1,ID_USUARIO_MODIFICADOR=1 
				WHERE ( ID_USUARIO_MODIFICADOR=$__idusuario__ OR ID_USUARIO_CREADOR=$__idusuario__ ) ";
				if (! $this->m_tusuarios->EjecutaSQL( $update_usuario_id )) {
					$this->PushError( new CError("QUERY_ERROR", "Couldn't override secciones ID_USUARIO_* ") );
					return false;	
				}
				
				$update_usuario_id = "UPDATE detalles SET ID_USUARIO_CREADOR=1,ID_USUARIO_MODIFICADOR=1 
				WHERE ( ID_USUARIO_MODIFICADOR=$__idusuario__ OR ID_USUARIO_CREADOR=$__idusuario__ ) ";
				if (! $this->m_tusuarios->EjecutaSQL( $update_usuario_id )) {
					$this->PushError( new CError("QUERY_ERROR", "Couldn't override detalles ID_USUARIO_* ") );
					return false;	
				}	
				
				$update_usuario_id = "UPDATE relaciones SET ID_USUARIO_CREADOR=1,ID_USUARIO_MODIFICADOR=1 
				WHERE ( ID_USUARIO_MODIFICADOR=$__idusuario__ OR ID_USUARIO_CREADOR=$__idusuario__ ) ";
				if (! $this->m_tusuarios->EjecutaSQL( $update_usuario_id )) {
					$this->PushError( new CError("QUERY_ERROR", "Couldn't override relaciones ID_USUARIO_* ") );
					return false;	
				}
				
				///eliminamos la ficha asociada:
				$id_contenido = $CU->m_id_contenido;
				/*
				$update_usuario_id = "DELETE FROM contenidos WHERE ID=$id_contenido AND ( ID_USUARIO_MODIFICADOR=$__idusuario__ OR ID_USUARIO_CREADOR=$__idusuario__ ) ";
				if (! $this->m_tusuarios->EjecutaSQL( $update_usuario_id )) {
					$this->PushError( new CError("QUERY_ERROR", "Couldn't delete ") );
					return false;	
				}
				*/

				/*//dar de baja contenidos de este usuario automaticamente.*/
				$update_usuario_id = "UPDATE contenidos SET contenidos.BAJA='N' WHERE contenidos.ID=$id_contenido AND ( contenidos.ID_USUARIO_CREADOR=$__idusuario__) ";
				if (! $this->m_tusuarios->EjecutaSQL( $update_usuario_id )) {
					$this->PushError( new CError("QUERY_ERROR", "Couldn't deactivate user card ") );
					return false;	
				}

				return $this->m_tusuarios->Borrari($__idusuario__);
			} else {
				$this->PushError( new CError("PERMISSION_NOT_GRANTED", "You are not allowed to modify this record. level needed: 0 , your level:".$CU->m_nivel) );
				return false;
			}				
		} else {
			$this->PushError( new CError("RECORD_NOT_FOUND", "Record id <$__idusuario__> not found." ) );
			return false;
		}
		
		return false;
	}	
	
	function Edit( $__nivelusuario__, $CLang ) {
		
		$this->SetTemplateEdicion($__nivelusuario__);
		
		$tpl = $CLang->Translate( $this->m_templatesedicion[$__nivelusuario__]);
		
		$matches = array();
			
		preg_match_all( "/\*(.*?)\*/", $tpl, $matches );
		
		foreach( $matches[0] as $match) {
			$match_txt = substr( $match, 1, strlen($match)-2 );
			//echo "MATCH:".$match." : ".$match_txt."<br>";
			$tpl = str_replace( $match, $this->m_tusuarios->EditarCampoStr($match_txt), $tpl);
		}
		
		return $tpl;

	}
	
	function EditUsuario( $__nivelusuario__, $CLang, $__template__='' ) {
		
		if ($this->m_templatesedicionusuario[$__nivelusuario__]!="" && $__template__=="")
			$__template__ = $this->m_templatesedicionusuario[$__nivelusuario__];
			
		$this->SetTemplateEdicionUsuario($__nivelusuario__, $__template__);

		$tpl = $__template__;
		if ($__template__=='')
			$tpl = $CLang->Translate( $this->m_templatesedicionusuario[$__nivelusuario__]);		

		
		$matches = array();
			
		preg_match_all( "/\*(.*?)\*/", $tpl, $matches );
		
		foreach( $matches[0] as $match) {
			$match_txt = substr( $match, 1, strlen($match)-2 );
			//echo "MATCH:".$match." : ".$match_txt."<br>";

				switch($match_txt) {
					case "IDIOMASX":
						//echo $GLOBALS['_e_IDIOMAS'];
						$idios = explode(",",$GLOBALS['_e_IDIOMAS']);
						foreach($idios as $idiom) {
							$GLOBALS[trim(strtoupper($idiom))] = " checked";
						}
						$tpl_idiomas = '
						<input type="hidden" value="'.$GLOBALS['_e_IDIOMAS'].'" name="_e_IDIOMAS" id="_e_IDIOMAS">
						<table width="100%" border="0" cellpadding="0" cellspacing="15">
							<tr>
								<td width="5%" align="right"><input '.$GLOBALS['__ENGLISH__'].' type="checkbox" name="_idioma_english_" id="_idioma_english_" onchange="javascript:IdiomasValidation(\'register\');"></td>
								<td  width="45%" align="left">'.$CLang->m_Words["ENGLISH"].'</td>
								<td  width="5%" align="right"><input  '.$GLOBALS['__PORTUGUESE__'].' type="checkbox" name="_idioma_portuguese_" id="_idioma_portuguese_" onchange="javascript:IdiomasValidation(\'register\');"></td>
								<td width="45%" align="left">'.$CLang->m_Words["PORTUGUESE"].'</td>
							</tr>
							<tr>
								<td width="5%" align="right"><input  '.$GLOBALS['__SPANISH__'].' type="checkbox" name="_idioma_spanish_" id="_idioma_spanish_" onchange="javascript:IdiomasValidation(\'register\');"></td>
								<td  width="45%" align="left">'.$CLang->m_Words["SPANISH"].'</td>
								<td  width="5%" align="right"><input  '.$GLOBALS['__ITALIAN__'].' type="checkbox" name="_idioma_italian_" id="_idioma_italian_" onchange="javascript:IdiomasValidation(\'register\');"></td>
								<td width="45%" align="left">'.$CLang->m_Words["ITALIAN"].'</td>
							</tr>
							<tr>
								<td width="5%" align="right"><input  '.$GLOBALS['__FRENCH__'].' type="checkbox" name="_idioma_french_" id="_idioma_french_" onchange="javascript:IdiomasValidation(\'register\');"></td>
								<td  width="45%" align="left">'.$CLang->m_Words["FRENCH"].'</td>
								<td  width="5%" align="right"><input '.$GLOBALS['__CHINESE__'].' type="checkbox" name="_idioma_chinese_" id="_idioma_chinese_" onchange="javascript:IdiomasValidation(\'register\');"></td>
								<td width="45%" align="left">'.$CLang->m_Words["CHINESE"].'</td>
							</tr>
							<tr>
								<td width="5%" align="right"><input  '.$GLOBALS['__GERMAN__'].' type="checkbox" name="_idioma_german_" id="_idioma_german_" onchange="javascript:IdiomasValidation(\'register\');"></td>
								<td  width="45%" align="left">'.$CLang->m_Words["GERMAN"].'</td>
								<td  width="5%" align="right"><input  '.$GLOBALS['__JAPANESE__'].' type="checkbox" name="_idioma_japanese_" id="_idioma_japanese_" onchange="javascript:IdiomasValidation(\'register\');"></td>
								<td width="45%" align="left">'.$CLang->m_Words["JAPANESE"].'</td>
							</tr>														
						</table>
						';
						
						$tpl = str_replace( "*".$nombre."*",  $tpl_idiomas, $tpl );
						break;
					default:
						if (isset( $this->m_tusuarios->campos[$match_txt]) )
							$tpl = str_replace( $match,  $this->m_tusuarios->EditarCampoStr($match_txt), $tpl );
						break;
				}
		}
		
		return $tpl;			
		
	
	}	
	
	function ActualizarUsuario( &$UsuarioActual, $newpassword ="" ) {
		
		if ($UsuarioActual==null || !is_object($UsuarioActual)) {
			ShowError("CUsuarios::ActualizarUsuario > not an object.");
			return;
		}
		
		if ( $newpassword=="" ) {
			
			$UsuarioActual->m_password = "";
			
		} else {
			
			Debug("Password update");
			
			$UsuarioActual->m_password = $newpassword;
			$UsuarioActual->m_passmd5 = md5($UsuarioActual->m_password);
			$UsuarioActual->m_passkey = $this->Crypt( $UsuarioActual->m_password );
			
			Debug("Password update ok.");
			
		}
		return $this->m_tusuarios->ModificarRegistro( $UsuarioActual->m_id, $UsuarioActual->FullArray() );	
		
	}
	
	function Deshabilitar( &$UsuarioActual ) {
			$UsuarioActual->m_baja = 'N';
			return $this->ActualizarUsuario($UsuarioActual);
				
	}
	
	
	function ShowLogin( $pre="", $temp="", $post = "", $_template_ = "{LINK}", $_header_='<div class="logininfo">', $_footer_='</div>', $_separator_='', $_templateitemmenu_="{LINK}", $_headermenu_='<hr/>', $_footermenu_='' ) {
		
		global $CLang;
		
		$resstr = $_header_.$pre;
				
		if ($this->Logged()) {
			if (is_object( $this->m_CSesionUsuario )) {
				$resstr.= str_replace(	array( "{PROFILELINK}", "{USERNAME}", "{USERLINK}"), 
										array( "/perfil", $this->m_CSesionUsuario->m_nick, '<a href="/perfil">'.$this->m_CSesionUsuario->m_nick.'</a>' ),
										$_template_ );
				
				$resstr.= $_headermenu_;
				
				$resstr.= $_separator_.str_replace( "{LINK}", ' <a href="/perfil">'.$CLang->Get('PROFILE').'</a>', $_templateitemmenu_);
				
				if (ModuloInstalled('panel')) {
					$resstr.= $_separator_.str_replace( "{LINK}", ' <a href="/panel">'.$CLang->Get('PANEL').'</a>', $_templateitemmenu_);
				}
				
				$resstr.= $_separator_.str_replace( "{LINK}", ' <a href="/perfil/logout">'.$CLang->Get("LOGOUT").'</a>', $_templateitemmenu_);
				
				$resstr.= $_footermenu_;
			}
		} else {
			$resstr.= str_replace( "{LINK}", '<a href="/perfil/loginform">'.$CLang->Get("LOGIN").'</a>', $_template_);
		}
		$resstr.= $post.$_footer_;
		return $resstr;
	}
	
	function FormRecovery($echo = true) {
		
		global $CLang;
		
		$ftemplate = "../../inc/include/templateuserformrecovery.php";

		$resstr = '
				<div id="recovery">				
				<form id="formrecovery" name="formrecovery" method="post" action="/perfil/forgotpassword">
				
				<div class="container">
					<div class="row">
						<div class="col-sm-6 col-md-4 col-md-offset-4">
							<h1 class="text-center login-title">{PASSWORDRECOVERY}</h1>
							<div class="account-wall">
							
								<input type="text" autofocus="" required="" placeholder="{MUSTINSERTMAIL}" class="form-control" name="_email_">
								<button name="submit" type="submit" class="btn btn-lg btn-primary btn-block">{SEND}</button>
							</div>
						</div>
					</div>
				</div>
					';
				
		if (file_exists($ftemplate)) {
			require $ftemplate;
		}
		
		$resstr.= '
		</form>		
		</div>
		';
				
		if ($echo) echo $resstr;
		else return $resstr;
		
	}
	
	function FormRecoveryConfirm( $echo = true, $__email__="", $_template_txt_='', $_template_html_='' ) {
		
		global $CLang;
		global $_email_;
		$resstr = "";
		if ($__email__=="") $__email__ = $_email_;
		
		
		if ( $__email__!="" && ( $this->NickUtilizado($__email__) || $this->MailUtilizado($__email__) ) )  {

			$Us = $this->GetUsuario('',trim($__email__));
			if (!is_object($Us)) {
				//try email
				$Us = $this->GetUsuario('','',trim($__email__));
			}
			$passk = trim( $this->GetPassword( $Us ) );
			
					if (!function_exists("checkEmail")) {
						require "../../inc/core/validateemail.php";	
						require "../../inc/include/phpmailer/class.phpmailer.php";
					}
	
					$CMail = new PHPMailer();
					$CMail->From = $this->m_CSenderUsuario->Mail();
					$CMail->FromName = $this->m_CSenderUsuario->Nombre();
					$CMail->AddAddress( $__email__, $Us->NombreCompleto() );
					$CMail->AddReplyTo( $CMail->From, $CMail->FromName );	
					$CMail->IsHTML(true);
					
					$CMail->Subject = $CLang->Get('PASSWORDRECOVERYSUBJECT');
			
					$mensaje1_txt = $this->SetTemplateMessage( $Us->m_nivel, "passwordrecovery_txt", $_template_txt_ );
					$mensaje1_html = $this->SetTemplateMessage( $Us->m_nivel, "passwordrecovery_html", $_template_html_ );
					
					if (trim($mensaje1_txt)=="") $mensaje1_txt = " 					
					Hola *NOMBRE*, 					
					Hemos recibido tu pedido de recuperación de contraseña desde la dirección de correo *MAIL*.										
					Esta es tu contraseña para ingresar al sitio: *PASSWORD*
										
					Saludos";
										
					if (trim($mensaje1_html)=="") $mensaje1_html = " 
					Hola *NOMBRE*, 
					<br>Hemos recibido tu pedido de recuperación de contraseña desde la dirección de correo *NICK*.<br><br>
					Esta es tu contraseña para ingresar al sitio: *PASSWORD*
					<br><br>Saludos.
					";

					//$CMail->AddEmbeddedImage( "../../inc/images/ecard.jpg", "embed1", "ecard.jpg", 'base64',"image/jpeg");
					//$mensaje1 = str_replace( '"../../inc/images/ecard.jpg"',  '"cid:embed1"',$mensaje1);
					
					$mensaje1_txt = str_replace(
						array( "[NOMBRE]","*NOMBRE*","[APELLIDO]","*APELLIDO*","[NICK]","*NICK*","[MAIL]","*MAIL*","[NOMBRECOMPLETO]","*NOMBRECOMPLETO*", "[PASSWORD]","*PASSWORD*" ),
						array( $Us->m_nombre,$Us->m_nombre, $Us->m_apellido,$Us->m_apellido, $Us->m_nick,$Us->m_nick, $Us->m_mail,$Us->m_mail, $Us->NombreCompleto(),$Us->NombreCompleto(), $passk,$passk ),
						$mensaje1_txt
						);
					$mensaje1_html = str_replace(
						array( "[NOMBRE]","*NOMBRE*","[APELLIDO]","*APELLIDO*","[NICK]","*NICK*","[MAIL]","*MAIL*","[NOMBRECOMPLETO]","*NOMBRECOMPLETO*", "[PASSWORD]","*PASSWORD*" ),
						array( $Us->m_nombre,$Us->m_nombre, $Us->m_apellido,$Us->m_apellido, $Us->m_nick,$Us->m_nick, $Us->m_mail,$Us->m_mail, $Us->NombreCompleto(),$Us->NombreCompleto(), $passk,$passk ),
						$mensaje1_html
						);
						
					$CLang->Translate( $mensaje1_txt );
					$CLang->Translate( $mensaje1_html );					
					//$resstr = $mensaje1;
					
					$CMail->Body = $mensaje1_html;
					$CMail->AltBody = $mensaje1_txt;
					
					//ShowMessage( $CMail->Body );
					//ShowMessage( $CMail->AltBody );
					
					if (!$CMail->Send()) {
						$resstr.= ShowError($CLang->Get("MAILNOTSENT"), false );		
						$this->PushError( new CError("MAILNOTSENT","") );		
					} else {
						$resstr.= ShowMessage($CLang->Get("PASSWORDMAILSENT"), false );
					}
					
			
		} else {
			$resstr = ShowError($CLang->Get("MAILUNREGISTERED"), false );
			$this->PushError( new CError("MAILUNREGISTERED","") );
		}		
		
		if ($echo) echo $resstr;
		else return $resstr;		
		
	}
	
	function FormLogin($echo = true, $previous_url_to_go="") {
		
		
		global $CLang;
		global $formlogin_printed;
		global $_cID_;
		global $_accion_;
		
		if ($formlogin_printed==true) {
			return;
		}
		
		
		$ftemplate = "../../inc/include/templateuserformlogin.php";

		$resstr.= '
		<div id="login">
			<form id="formlogin" name="formlogin" method="post" action="/perfil/login">
				
				<input type="hidden" value="'.$previous_url_to_go.'" name="previous_url"/>
				
				<div id="message">'.$CLang->Get('LOGINMESSAGE').'</div>
				
				<div>
				
					<label id="usermail">'.$CLang->Get('USEREMAIL').'</label>
				
					<div id="usermailinput"><input name="_email_" type="text" value=""></div>
				
				</div>
				
				<div>
				
				<label id="userpassword">'.$CLang->Get('PASSWORD').'</label>
				
				<div id="usermailinput"><input name="_password_" type="password" value=""></div>
				
				</div>
				
				<div id="loginsend"><input class="inputbutton" name="submit" type="submit" value="'.$CLang->Get('LOGIN').'"></div>
				
				<div id="forgotpassword">			
					<a href="/perfil/forgotpassword">'.$CLang->Get('FORGOTPASSWORD').'</a>
				</div>

				<div id="register">			
					<a href="/perfil/register">'.$CLang->Get('SIGNUP').'</a>
				</div>			
				
				<div id="div_debug" class="debugdetails">
					<div id="id_pedido">
						<input type="text" value="'.$_cID_.'" name="_cID_">
						<input type="text" value="'.$_accion_.'" name="_accion_">
					</div>
				</div>
			</form>		
		</div>
		';
		
		if (file_exists($ftemplate)) {
			require $ftemplate;
		}

				
		$formlogin_printed = true;
		if ($echo) echo $resstr;
		else return $resstr;
		
	}	
	
	function Crypt( $data, $ckey1="", $ckey2="" ) {
		
		if (function_exists('mcrypt_module_open')) {
		
			$cipher = mcrypt_module_open(MCRYPT_BLOWFISH,'','cbc','');
	
		
			if ($ckey1=="") $ckey1 = md5("wiwecryptkey");
			if ($ckey2=="") $ckey2 = "huildara";	
							
			mcrypt_generic_init( $cipher, $ckey1, $ckey2 );
			
			$enc = mcrypt_generic( $cipher, $data);
			$enc = urlsafe_b64encode($enc);
										
			mcrypt_generic_deinit($cipher);			
		} else {
			$enc = $data;
		}
		return $enc;	
		
	}
	
	function DeCrypt($__passkey__) {
		
		if (function_exists('mcrypt_module_open')) {
			$cipher = mcrypt_module_open(MCRYPT_BLOWFISH,'','cbc','');
			
			$key = md5("wiwecryptkey");
			$ivkey = "huildara";
			
			mcrypt_generic_init( $cipher, $key, $ivkey );
			$dec = mdecrypt_generic( $cipher, urlsafe_b64decode($__passkey__) );
			mcrypt_generic_deinit($cipher);
		} else {
			$dec = $__passkey__;
		}		
		return $dec;
		
	}	
	
}
?>