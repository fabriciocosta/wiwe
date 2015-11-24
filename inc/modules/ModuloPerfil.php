<?Php
/**
*ModuloPerfil
*
**/

///datos de logueo
global $_email_;
global $_password_;

///sistema
global $_DIR_SITEABS;
global $CLang;
global $CMultiLang;

///modulo specific
global $_mod_; //> modo: profile, hacerse_proveedor, login, logout
global $_accion_; //> accion: register, insertnew, confirmnew, edit, confirmedit
global $_confirmaccion_; //> siguiente accion
global $execute; //> script ejecutado al final
global $newpass; //> nuevo password ???
global $UsuarioActual; //> Objeto del Usuario Actual CUsuario()
global $UsuarioNuevo; //> Objeto del Usuario NUEVO CUsuario()
global $ID_TIPOCONTENIDO_USUARIO; //> TipoContenido de la ficha personal
global $ID_TIPOSECCION_USUARIOS; //> seccion donde ba la ficha personal
global $_conditions_;

global $usererror;
global $signinError;
global $registerError;
global $_visualconfirmation_;
global $visual_code_ok;
global $nick_ok;
global $miss_ok;
global $pass_ok;

//===========================================
//    CUSTOM CONFIGURATION PARAMETERS
//===========================================

global $requiredfields; //> objeto de campos requeridos
global $requiredfields_register; //> objeto de campos requeridos
global $requiredfields_solousuario;
global $requiredfields_solousuario_register;
global $errorfieldmsg; //> mensaje o texto de error de campo requerido

global $role_class;

global $redirection;
global $NICK_IS_MAIL; /// bool: is mail is treated as nick
global $MAIL_CONFIRMATION_NEEDED; /// to activate the account , mail must be checked and clicked...
global $ADMINISTRATOR_CONFIRMATION_NEEDED; /// bool: account is not active until Admin checks it
global $REGISTERING_GRANTED;

global $base_link_activation;
global $admin_mail;
global $emailfrom;
global $variables,$mandatories,$results;

//===========================================
//    CUSTOM CONFIGURATION SETTINGS
//===========================================

					

					$emailfrom = array("email"=>"info@moldeointeractive.com.ar","name"=>$GLOBALS['_TITLE_']);
					$variables = array();
					$mandatories = array();
					$results = array();
					$admin_mail = "fabricio.costa@moldeointeractive.com.ar";
					
					$REGISTERING_GRANTED = true;
					$ID_TIPOCONTENIDO_USUARIO = FICHA_USUARIO;
					$ID_TIPOSECCION_USUARIOS = SECCION_USUARIOS; ///seccion Personas...
					$NICK_IS_MAIL = true;
					$MAIL_CONFIRMATION_NEEDED = true;
					$ADMINISTRATOR_CONFIRMATION_NEEDED = false;
					$base_link_activation = "http://www.moldeo.org/perfil/activation";
					
					global $redirecciones;
					global $_cID_;
					
					$redirecciones = array(
					
						//"login"=>array("/consultas_enviadas",1000),
						"login"=>array("/home",1000),
						"logout"=>array("/home",1000),
						"register"=>array("/home",1000),
						"errores"=>array("/home",1000),
						//"confirmrecord"=>array("/consultas_enviadas/confirmada",8000),
						"confirmrecord"=>array("/home",1000),
						//"confirmarusuario"=>array("/confirmar/".$_cID_."",8000)
						"wait"=>array("/home",10000),
					
					);


					//========================================================================
					// CAMPOS REQUERIDOS PARA EL REGISTRO
					//========================================================================

					$errorfieldmsg = "fielderror";


					/**
					 *
					 *   ROL DE PROVEEDOR
					 * 
					 **/
					
					$requiredfields = array(
							"NOMBRE"=>"",
							"APELLIDO"=>"",
							"MAIL"=>"",
							"NICK"=>"",
							/*
							"TELEFONO"=>"",
							"PAIS"=>"",
							"PROVINCIA"=>"",
							"CIUDAD"=>"",
							"DIRECCION"=>"",
							"CP"=>"",
							"CONTACTO"=>""
					*/ 
					);

					$requiredfields_register = $requiredfields;
					$requiredfields_register["PASSWORD"] = "";
					$requiredfields_register["_conditions_"] = "";


					/**
					 *
					 *   ROL DE USUARIO
					 * 
					 **/
					$requiredfields_solousuario = array(
					"NOMBRE"=>"",
					"APELLIDO"=>"",
					"MAIL"=>"",
					"NICK"=>"" ); 
					$requiredfields_solousuario_register = $requiredfields_solousuario;
					$requiredfields_solousuario_register["PASSWORD"] = "";
 

					/* PARA AMBOS - MISMA FICHA */
					
					$requiredfields_ficha_register = array(
					/*
						"USUARIO_NO_RECIBIR_NOTIFICACIONES"=>"",
						"USUARIO_TERMINOS"=>"",
					*/
					);					

					
//============================================================================================================
//
// 					CHECK ERRORS FUNCTIONS
//
//============================================================================================================					
					
$usererror = "";

function CheckMissingError() {
	global $miss_ok;
	global $CLang;
	global $usererror;
	global $errorfieldmsg;
	
	if (!$miss_ok) {
		$usererror.= ShowError( $CLang->Get('FIELDSAREMISSING'), false );
		
		global $requiredfields_register;
		foreach($requiredfields_register as $field=>$value) {
			if ($value==$errorfieldmsg) {
				DebugError("$field missing");
				$usererror.= ShowError( $field." is [".$value."]", false );
			}
			//$usererror.= ShowError( $field." is required", false );
		}
		 
		DebugError( "CAMPOS FALTANTES" );
	}												
}

function CheckVisualError() {
	global $CLang;
	global $_visualconfirmation_;
//	global $captchaResponse;
	global $visual_code_ok;
	global $usererror;
	global $respvisual;
	/*
	if ($captchaResponse) {
		$usererror.= ShowError( $captchaResponse['error-codes'], false );
	}*/
	if ($respvisual->is_valid) {
		$visual_code_ok = true;
		//$usererror.= ShowError( "hey", false );
	}
	else
	if (!$respvisual->is_valid) {
		$usererror.= ShowError( $CLang->Get('WRONGVISUALCONFIRMATIONCODE'), false );
	}
	else
	if ( !$visual_code_ok ) {
		$usererror.= ShowError( $CLang->Get('WRONGVISUALCONFIRMATIONCODE'), false );
		//DebugError( "ERROR en la confirmacion visual : ".$_visualconfirmation_." :".md5($_visualconfirmation_) ." code:".$_SESSION['code'] );
	}											
}

function CheckPasswordError() {
	
	global $pass_ok;
	global $CLang;
	global $usererror;
		
	if (!$pass_ok) {
		$usererror.= ShowError( $CLang->Get('PASSWORDCONFIRMATIONFAILED'), false );
		DebugError( "CONTRASEÑAS NO COINCIDEN" );
	}
}

function CheckNickError( &$Usuario ) {

		global $nick_ok;
		global $CLang;
		global $usererror;
		//global $UsuarioNuevo;

		if (!$nick_ok || !is_object( $Usuario )) {	
			if (is_object($Usuario) && trim($Usuario->m_nick)=="" ) {
				//$usererror.=  ShowError( $Usuario->m_nick." ".$CLang->Get('NICKISEMPTY'), false );	
			}	else {
				$usererror.=  ShowError( $Usuario->m_nick." ".$CLang->Get('NICKNOTAVAILABLE'), false );
			}			
			DebugError("Nick utilizado o vacío: [".$Usuario->m_nick."]" );
			$Usuario->m_nick = "";
			$Usuario->m_mail = "";													
		}
}

function CheckConditions() {
	global $_conditions_;
	global $CLang;
	global $usererror;
	Debug( "Conditions: ".$_conditions_ );
	if ($_conditions_!="on") {
			//$usererror.=  ShowError( $CLang->Get('MUSTACCEPTCONDITIONS'), false );
			//DebugError("conditions needed" );		
	}
}

function CheckAllErrors( &$UsuarioNuevo ) {
	
	CheckNickError( $UsuarioNuevo );
	CheckMissingError();
	CheckPasswordError();
	CheckVisualError();
	CheckConditions();
}

?>

<div id="listado">
	<div id="listadocontent"><?

//========================================================================
//
//
// CHEQUEO DE MODOS Y ACCIONES
//
//
//========================================================================

//========================================================================
// ACCIONES POST-LOGUEO EN ESPERA
//========================================================================

if ($_mod_=="login" && $_accion_=="confirmrecord") {
	
	Debug("Login para confirmar consulta");

}
	
	
	
//================================
// FALTA :
//   accion: confirm_alta
//   accion: confirm_baja
//================================

//verificamos si existe nuestro objeto
$this->Usuarios->CheckUser();

Debug(" mod:".$_mod_. " accion:".$_accion_);

if ($_mod_=="") $_mod_="profil";

if ($_mod_=="activation") {
	
	global $_activationcode_;
	
	$id_usuario = 0;
	$date = 0;
	$date_t = strtotime("-1 month");;
	$date_invalidate = strtotime("-1 week");
	$rand = 0;
	
	Debug("Activation code:".$_activationcode_);
	
	$code = trim($this->Usuarios->Decrypt($_activationcode_));
	
	$codes = explode( "#", $code );
	if (count($codes)==3) {
		$id_usuario = $codes[0];
		$date = $codes[1];
		$date_t = strtotime($date);
		$date_invalidate = strtotime("-1 week");
		$rand = $codes[2];
	}

	Debug(" Code:".$code." Id Usuario:".$id_usuario." date:".date( "d-m-Y",$date_t)." invalidate:".date( "d-m-Y",$date_invalidate)." rand:".$rand);
	
	if ( $date_invalidate > $date_t ) {
		$usererror.= ShowError( $CLang->Get("INVALIDACTIVATIONCODE"), false);
		$signinError = ShowError( $CLang->Get("INVALIDACTIVATIONCODE"), false);
		$_mod_ = "loginform";
		$ok = false;
	} else {	
		$UserToActivate = $this->Usuarios->GetUsuario($id_usuario);
		$ok = is_object($UserToActivate);
		if ($ok) {
			////ACTIVATE!!!
			$UserToActivate->m_baja = 'S';
			$ok = $this->Usuarios->ActualizarUsuario($UserToActivate);
			if ($ok) {
				$id_contenido = $UserToActivate->m_id_contenido;	
				$CFicha = $this->Contenidos->GetContenido($id_contenido);
				$ok = is_object( $CFicha );				
				if ($ok) {
					$CFicha->m_baja = 'S';
					$ok = $this->Contenidos->Actualizar( $CFicha, false );				
				}				
				
				if ($ok) {
					$signinError = ShowMessage( $CLang->Get("ACCOUNTACTIVATED"), false);
					$_mod_ = "loginform";
				}
			}
		}
		
		if (!$ok) {
			$usererror.= ShowError( $CLang->Get("ACTIVATIONFAILED"), false );
		}
	}
	

}


if ($_mod_=="confirmar_usuario") {
	
	if ($_accion_ == "") $_accion_ = "insertnew";
	$requiredfields = $requiredfields_solousuario;
	$requiredfields_register = $requiredfields_solousuario_register;
	$role_class = "role-usuario";
	if (!$this->Usuarios->Logged()) {
		Debug("luego confirmamos el pedido : ".$_cID_);	
	} else {
		$_accion_="confirmrecord";
		Debug("Ahora si confirmamos el pedido : ".$_cID_);
	}
}

if ($_mod_=="profil" && $_accion_=="") {
	$_accion_ = "edit";
}

if ($_mod_=="profil" && ( $_accion_ == "edit" || $_accion_ == "confirmedit") ) {
	if ( !$this->Usuarios->Logged() ) {
		$registerError = '<span class="error">Objeto inexistente</span>';
		$redirection = Redirection("errores",$redirecciones);
		$_mod_="errores";
	}

}

if ($this->Usuarios->Logged()) {
	$Usuario = $this->Usuarios->GetSesionUsuario();
	$FichaUsuario = $this->Contenidos->GetContenidoCompleto($Usuario->m_id_contenido);
	/*
	 * chequear roles y campos obligatorios..... de usuario o de ficha de usuario
	if () {
		
	}
	*/
	
	/*
	if ($FichaUsuario->m_detalles["USUARIO_PROVEEDOR"]->m_detalle=='[YES]' || $_mod_=="hacerse_proveedor") {
		$role_class = "role-proveedor";
	} else {
		$requiredfields = $requiredfields_solousuario;
		$requiredfields_register = $requiredfields_solousuario_register;
		$role_class = "role-usuario";
	}
*/
		$requiredfields = $requiredfields_solousuario;
		$requiredfields_register = $requiredfields_solousuario_register;
		$role_class = "role-usuario role-nivel-".$Usuario->m_nivel;

}


if ($_mod_=="register" && $_accion_=="" && $REGISTERING_GRANTED) {	
	$_accion_ = "insertnew";
}

//automaticamente registro de usuario
if ($_mod_=="hacerse_proveedor" && $_accion_!="confirmnew") {
	
	///CONVERSION A PROVEEDOR!!!! ATENCION
	if ($this->Usuarios->Logged()) {
		$_mod_="hacerse_proveedor";
		//$_accion_="edit";
		if ($_accion_=="") {
			$_accion_="edit";
		}
	
	} else {
		if ($_accion_=="") $_accion_ = "insertnew";
	}

}



Debug("CHECK mod:".$_mod_. " accion:".$_accion_);

//========================================================================
//
//
// LOGIN
//
//
//========================================================================

if ($_mod_=="login") {
		
		Debug("login: ".$_email_." password:".$_password_);		
		
		if ($_email_!="" && $_password_!="" ) {
			//try to signin:
			if ( $this->Usuarios->SesionIn( $_email_, $_password_)) {
				
				Debug("Inicio de sesion correcto");				
				$Usuario = $this->Usuarios->GetSesionUsuario();
				
				if ($_accion_=="confirmrecord") {

					///niente aun
				
				} else {
					
				
					//login normal
					
					Debug("Modo hacia perfil");				
					$_mod_ = "profil";
					$_accion_ = "";
	
					Debug("Redirigiendo si necesario");
					$redirection = Redirection("login", $redirecciones);
				}
		
			} else {
				$signinError = ShowError( $CLang->Get("LOGERROR"), false);
			}
			
		} else {
			$signinError = ShowError( $CLang->Get("LOGERROR"), false);
		}
		
		$_mod_ = "loginform";
}

if ($_mod_=="loginform") {
	Debug("ModuloPerfil:: loginform ");
	$signinError.= $this->Usuarios->FormLogin(false);
	
}

if ($_mod_=="forgotpassword") {
	Debug("ModuloPerfil:: recovery form ");
	if (trim($_email_)!="") {
		$signinError = $this->Usuarios->FormRecoveryConfirm(false);
		if ($this->Usuarios->ErrorsCount()>0) 
				$signinError.= $this->Usuarios->FormRecovery( false );
	} else $signinError = $this->Usuarios->FormRecovery( false );
	
}


if ($_accion_=="confirmrecord" && $this->Usuarios->Logged()) {
	if ($_cID_!="" && $_cID_>0) {						
	
		$Record = $this->Contenidos->GetContenidoCompleto($_cID_);
		$_exito_ = $this->UserAdminRecordPostProcess($Record);
		Debug("confirmrecord >> confirmaccion:".$_confirmaccion_);						
		if ($_confirmaccion_=="confirmmessage") {
			$this->Sistema( "SISTEMA_CONFIRMACION_ESPERA", $redirection );
			$redirection.= Redirection("confirmrecord", $redirecciones); 
		}	
	} else {
		DebugError("No hay id de registro definido : [".$_cID_."]");
		$redirection = Redirection("home", $redirecciones);
	}
}

//========================================================================
//
//
// LOGOUT
//
//
//========================================================================

if ($_mod_=="logout") {
	
	Debug("logout");
	$_accion_ = "";
	
	if ($this->Usuarios->Logged()) {
		
		if ($this->Usuarios->SesionOut()) {
			$redirection = Redirection("logout", $redirecciones);	
		} else {
			DebugError("Logout failed");
		}
					
	} else {
		DebugError("not logged, cannot logout...");
	}
	
	Debug("login mode active");
	$_mod_="login"; 
}

//========================================================================
//
//
// UNREGISTER
//
//
//========================================================================


if ($_mod_=="closeaccount") {
	
	Debug("closeaccount");
	$_accion_ = "";
	
	if ($this->Usuarios->Logged()) {
		
		
					
	}
	
	$_mod_="profil"; 
}

//========================================================================
//
//
// REGISTER - REGISTER - REGISTER - REGISTER - REGISTER - REGISTER
//
//   
//========================================================================

if ( $_accion_=="confirmnew" || $_accion_=="insertnew" ) {
			
			Debug("procesando nuevo usuario : _accion_ :".$_accion_);
			
			global $_visualconfirmation_;
			global $_conditions_;
			global $captchaResponse;
			$_conditions_ = "on";
			
			if ($this->Usuarios->Logged()) {
				
				Debug("Está logueado, deberá desloguearse");
				DebugError($CLang->Get("SIGNOUTTOREGISTER"));
				
				$registerError = '<span class="error">'.$CLang->Get("SIGNOUTTOREGISTER").'</span>';
				$_mod_ = "errores";
				$redirection = Redirection( "errores", $redirecciones );
				
				
			} else
			if ($_accion_=="confirmnew") {
				
				Debug(" Confirmando Datos Usuario nuevo");
				
				//========================================================================
				// TOMA Y VERIFICACION DE DATOS
				//========================================================================
				Debug('TOMA Y VERIFICACION DE DATOS');						

				$UsuarioNuevo = new CUsuario();
				$UsuarioNuevo->m_nick = $UsuarioNuevo->m_mail;
				$UsuarioNuevo->m_pais = "Argentina";

				$UsuarioNuevo->ToGlobals();			

				$ContenidoNuevo = new CContenido();
				
				$nick_ok = ! $this->Usuarios->NickUtilizado($GLOBALS['_e_NICK']);
				$miss_ok = (!$UsuarioNuevo->RequiredFields( $requiredfields_register, $errorfieldmsg  )) && 
						   (!$ContenidoNuevo->RequiredFields( $requiredfields_ficha_register, $errorfieldmsg));
				$pass_ok = $UsuarioNuevo->PasswordVerification();					
				//$visual_code_ok = $_visualconfirmation_!="" && $_SESSION['code']==md5($_visualconfirmation_);
				
				$privatekey = "6LdRlxETAAAAAFuhS5AjEO7oOIgIRWqdvV4PQNU6";
				global $respvisual;
				$respvisual = recaptcha_check_answer ($privatekey,
											 $_SERVER["REMOTE_ADDR"],
											 $_POST["recaptcha_challenge_field"],
											 $_POST["recaptcha_response_field"]);
				$visual_code_ok = $respvisual->is_valid;
				
/*
				if ($GLOBALS['g-recaptcha-response']) {
					
					$fields = array(
						'secret' => '6LdRlxETAAAAAFuhS5AjEO7oOIgIRWqdvV4PQNU6',
						'response' => $_SESSION['g-recaptcha-response'],
						'remoteip'=> $_SERVER['REMOTE_ADDR']
					);
					{
					  "success": true|false,
					  "error-codes": [...]   // optional
					}
					//$response = json_decode( http_post_fields("https://www.google.com/recaptcha/api/siteverify", $fields ) );
					//$response['success']
					echo "response:".$response["success"];
					$captchaResponse = $response;					
				}
*/
				
				//$visual_code_ok = true;
				//$response["success"]==true;
				

				//========================================================================
				// CONFIRMACION NUEVO USUARIO
				//========================================================================
				
				if ( $visual_code_ok && $miss_ok && $pass_ok && $nick_ok) {
					Debug("CONFIRMACION VISUAL OK");
					
					Debug("NICK:".$UsuarioNuevo->m_nick." MAIL:".$UsuarioNuevo->m_mail." PASS:".$UsuarioNuevo->m_password);
					
					Debug("CONFIRMACION VISUAL OK");
					
					//========================================================================
					// GUARDANDO DATOS DE USUARIO A LA BASE
					//========================================================================
					Debug("Guardando Datos de Usuario");
					//$test = $this->UserAdminConfirm( "confirmnewuser", $UsuarioNuevo, $UsuarioNuevo->m_password );
					$test = $this->Usuarios->NuevoUsuario( $UsuarioNuevo );
					
					if ($test) {
						Debug("Nuevo usuario guardado!");
						
						//$registerError = '<span style="color:#00CC00">[new user confirmed]</span>';

						
						//Debug("Logueo inminente!");
						
						//if ($this->Usuarios->SesionIn( $UsuarioNuevo->m_nick, $UsuarioNuevo->m_password)) {
							
							//Debug("Logueado.");
							
							//========================================================================
							// CREANDO FICHA USUARIO
							//========================================================================
							
					 		$TC = $this->TiposContenidos->GetTipoContenido($ID_TIPOCONTENIDO_USUARIO);
							Debug('<h3>ALTA FICHA USUARIO '.substr($TC->m_tipo,6).'</h3>');
							
							$SeccionUsuarios = $this->Secciones->GetSeccionByType( $ID_TIPOSECCION_USUARIOS );
							
							//taking from globals!!!
							$ContenidoNuevo = new CContenido();
							$ContenidoNuevo->m_id_tipocontenido = $ID_TIPOCONTENIDO_USUARIO;
							$ContenidoNuevo->m_id_seccion = $SeccionUsuarios->m_id;
							$ContenidoNuevo->m_id_contenido = 1;
							$ContenidoNuevo->m_titulo = $UsuarioNuevo->m_nombre." ".$UsuarioNuevo->m_apellido;
							$ContenidoNuevo->m_ml_titulo = "";
							$ContenidoNuevo->m_copete = "";
							$ContenidoNuevo->m_ml_copete = "";
							$ContenidoNuevo->m_cuerpo = "";
							$ContenidoNuevo->m_ml_cuerpo = "";
							$ContenidoNuevo->m_id_usuario_creador = $UsuarioNuevo->m_id;
							$ContenidoNuevo->m_id_usuario_modificador = $UsuarioNuevo->m_id;
							$ContenidoNuevo->m_baja = "N";	
	
							
							//create edit form
							
							//create a new one and save...
							//then open to edit...that's better for gallerys, if something fail, we delete it
							
							///$NewRecord = $this->Contenidos->CrearContenidoCompleto( $ID_TIPOCONTENIDO_USUARIO, $ContenidoNuevo );
							//$ContenidoNuevo = $this->Contenidos->CrearContenidoCompleto( $ID_TIPOCONTENIDO_USUARIO, $ContenidoNuevo );
							$ContenidoNuevo->ToGlobals();
							//$_exito_ = $this->Contenidos->m_tcontenidos->Insertar();
							
							$ContenidoNuevo = $this->Contenidos->CrearContenidoCompleto( $ID_TIPOCONTENIDO_USUARIO, $ContenidoNuevo, false );
							$_exito_ = is_object($ContenidoNuevo);
							/*
							$ContenidoNuevo->ToGlobals();
							$_exito_ = $this->Contenidos->m_tcontenidos->Insertar();
							*/
						
							if ($_exito_) {
								Debug("ModuloPerfil:: FICHA CREADA");
							
								$ContenidoNuevo = $this->Contenidos->GetContenidoCompleto( $ContenidoNuevo->m_id );

								//$ContenidoNuevo->m_id = $this->Contenidos->m_tcontenidos->lastinsertid;
								//$this->Contenidos->OrdenarContenido($ContenidoNuevo->m_id_seccion);
								
								$UsuarioNuevo->m_id_contenido = $ContenidoNuevo->m_id;
								//$test = $this->UserAdminConfirm( "confirmolduser", $UsuarioNuevo );
								$test = $this->Usuarios->ActualizarUsuario( $UsuarioNuevo );
								
								if ($test) {
									Debug("ModuloPerfil:: FICHA ASIGNADA!!!");
								
									//================================================================
									//
									//					CONFIRMACION DE ENVIO DE MAILS
									//
									//================================================================
									
									
									if ( $MAIL_CONFIRMATION_NEEDED || $ADMINISTRATOR_CONFIRMATION_NEEDED ) {
										
									
												///NO SE LOGUEA... MANDA MAIL..... con un link para cliquear y confirmar...
												///debemos guardar un codigo de acceso para eso....		
												
												$code = $UsuarioNuevo->m_id."#".date("d-m-Y",strtotime("now"))."#".rand();
												
												if ($MAIL_CONFIRMATION_NEEDED) {
													
															$link_activation = $base_link_activation.'/'.$this->Usuarios->Crypt( $code );
															
															$template = $UsuarioNuevo->m_nick.' '.$CLang->Get("CLICKTOACTIVATE").' :  <a href="'.$link_activation.'" target="_blamk">'.$CLang->Get("ACTIVATEACCOUNT").'</a>';
															
															Debug("MAIL_CONFIRMATION_NEEDED <br>".$template);
								
															$this->SendMessage( $variables,
																				$mandatories,
																				$results,
																				$emailfrom,								
																				$GLOBALS["_TITLE_"],								
																				$UsuarioNuevo->m_nick,								
																				$template );	
																				
															$results["errores"] = 0;
															
															if ($results["errores"]>0) {
																
																$usererror.= ShowError("Error sending confirmation message", false);
																
																DebugError("Error sending message");
																
																$this->Usuarios->Eliminar($UsuarioNuevo->m_id);
																
																$_accion_="confirmnew";
																
															}	else {
																//$redirection = Redirection("home", $redirecciones);
																$signinError.= ShowMessage( $CLang->Get("MAIL_CONFIRMATION_NEEDED"), false );
																
																$_mod_ = "wait";																																
															}													 							
												}
												
												if ($ADMINISTRATOR_CONFIRMATION_NEEDED) {
															$link_activation = $base_link_activation.'/'.$this->Usuarios->Crypt( $code );
															
															$template = $UsuarioNuevo->m_nick.' '.$CLang->Get("CLICKTOACTIVATE").' :  <a href="'.$link_activation.'" target="_blank">'.$CLang->Get("ACTIVATEACCOUNT").'</a>';
																							
															Debug("ADMINISTRATOR_CONFIRMATION_NEEDED <br>".$template);
															
															$this->SendMessage( $variables,
																				$mandatories,
																				$results,
																				$emailfrom,								
																				$GLOBALS["_TITLE_"],								
																				$admin_mail,								
																				$template );				
																				
															$results["errores"] = 0;
					
															if ($results["errores"]>0) {
																
																$usererror.= ShowError("Error sending confirmation message", false);
																
																DebugError("Error sending message");
																
																$this->Usuarios->Eliminar($UsuarioNuevo->m_id);
																
																$_accion_="confirmnew";
																
															} else {
																//$redirection = Redirection("home", $redirecciones);
																$signinError.= ShowMessage( $CLang->Get("ADMINISTRATOR_CONFIRMATION_NEEDED"), false );
																																
																$_mod_ = "wait";
																
															}
												
												}
													
													
										} //fin mandando mensajes de confirmacion
										 else {

												//=============================
												//
												//		LOGUEO DIRECTO Y REDIRECCION		
												//
												//=============================

												if ($this->Usuarios->SesionIn( $UsuarioNuevo->m_nick, $UsuarioNuevo->m_password)) {
												
													Debug("Logueado.");
												
													if ($_mod_=="confirmar_usuario") {
														$redirection = Redirection("confirmarusuario", $redirecciones);
													}
													else $redirection = Redirection("register", $redirecciones);
												
													$_mod_="profil";
												
												} else {
													
													DebugError("Logueo imposible");
													
												}
											
										}
									
									//================================================================
									//
									//					CONFIRMACION DE ENVIO DE MAILS  FINALIZADA
									//
									//================================================================									
									
									
								} else {
									DebugError("FICHA CREADA NO ASIGNADA!!!");
								}
								
							} else DebugError("No se pudo crear la ficha : [$ContenidoNuevo] _exito_ = ".$_exito_);								
							
							
							/*
							//ENVOIE DE LEMAIL
							global $results;
							global $variables;
							global $mandatories;
							global $template;
							($UsuarioAdmin->m_sexo=="M") ? $cher = "Cher" : $cher = "Chère"; 
							$template = '<div align="center">
				<table cellpadding="0" cellspacing="0" height="610"
				 width="904">
				  <tbody>
				    <tr>
				      <td background="../../inc/images/ecard.jpg" height="610">
				      <table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="350">&nbsp;</td><td>
				      <div
				 style="color: #646464; font-family: Arial,Helvetica; line-height: 18px; font-size: 12px;"
				 align="center">'.$cher.' '.ucwords($UsuarioAdmin->m_nombre).'
				'.ucwords($UsuarioAdmin->m_apellido).',
				      <br>
				      <br>
				      <b>Bienvenue sur Qiofil.com !</b>
				      <br>
				      <br>
				      <br>
				Nous vous rappelons vos codes d\'accès :<br>
				Votre e-mail : <b>'.$UsuarioAdmin->m_nick.'</b><br>
				Votre mot de passe : <b>'.$UsuarioAdmin->m_password.'</b>
				      <br>
				      <br>
				      <br>
				A très bientôt ! </div></td></tr></table>
				      </td>
				    </tr>
				  </tbody>
				</table>
				</div><br>';
							$template = str_replace("\n","",$template);
							$variables = array();
							$mandatories = array();
							
							//$embedimages = array("../../inc/images/ecard.jpg");
							$embedimages  = "";

							$this->SendMessage( $variables,
												$mandatories,
												$results,
												"RRCC",								
												"RRCC - BIENVENIDO",								
												$UsuarioAdmin->m_nick,								
												 $template,
												 $embedimages);

							
							$redirection = "<script>
							function redirection() {
									window.location.href='".$_DIR_SITEABS."/principal/home/perfil.php';
							}
							
							window.onload = redirection;
							</script>";									
							*/
							
							
/*
						} else {
							DebugError("Logueo imposible");
						}
						*/

					} else {
						DebugError("No se pudieron guardar los datos... UserAdminConfirm:".$test."<br>");
						//$_mod_="register";
						$_accion_ = "confirmnew";
						//$registerError = '<span style="color:#CC0000">[new user NOT confirmed: '.$this->Usuarios->GetLastError().']</span>';
						$mcerror = $this->Usuarios->GetLastError();
						$usererror = ShowError( $CLang->Get($mcerror->m_tipo), false );
					}

				} else {  ///$visual_code_ok && $miss_ok && $pass_ok && $nick_ok					
				
					CheckAllErrors( $UsuarioNuevo );
					//$_mod_ = "register";
					$_accion_="confirmnew";
				} //end confirm new
				
			} else if ($_accion_=="insertnew") {
				
				//========================================================================
				// CREAMOS GLOBALMENTE EL REGISTRO NUEVO
				//========================================================================
				Debug('CREAMOS GLOBALMENTE EL REGISTRO NUEVO');
				
				$UsuarioNuevo = new CUsuario(0);
				
				//========================================================================
				// CREAMOS LA FICHA USUARIO DESDE 0
				//========================================================================
				$TipoContenido = $this->TiposContenidos->GetTipoContenido($ID_TIPOCONTENIDO_USUARIO);
				
				$SeccionUsuarios = $this->Secciones->GetSeccionByType( $ID_TIPOSECCION_USUARIOS );
				
				$ContenidoNuevo = new CContenido(0);
				$ContenidoNuevo->m_id_tipocontenido = $ID_TIPOCONTENIDO_USUARIO;
				$ContenidoNuevo->m_id_seccion = $SeccionUsuarios->m_id;
				$ContenidoNuevo->m_id_contenido = 1;
				$ContenidoNuevo->m_titulo = "nuevo usuario";
				$ContenidoNuevo->m_ml_titulo = "";
				$ContenidoNuevo->m_copete = $UsuarioNuevo->m_nombre." ".$UsuarioNuevo->m_apellido;
				$ContenidoNuevo->m_ml_copete = "";
				$ContenidoNuevo->m_cuerpo = "";
				$ContenidoNuevo->m_ml_cuerpo = "";
				$ContenidoNuevo->m_id_usuario_creador = $UsuarioNuevo->m_id;
				$ContenidoNuevo->m_id_usuario_modificador = $UsuarioNuevo->m_id;
				$ContenidoNuevo->m_baja = "N";
				
				//pass to globals
				$ContenidoNuevo->ToGlobals();
				
				//create a new one and save...
				//then open to edit...that's better for gallerys, if something fail, we delete it
				/*
				$NewRecord = $this->Contenidos->CrearContenidoCompleto( $ID_TIPOCONTENIDO_USUARIO, $ContenidoNuevo );
			
				if ($NewRecord!=nil) {
					$NewRecord = $this->Contenidos->GetContenidoCompleto($NewRecord->m_id);
					$_cID_ = $NewRecord->m_id;
					$UsuarioAdmin->m_id_contenido = $NewRecord->m_id;
				} else echo "Couldn't create record";			
				*/	

			}

} else if ($_accion_=="confirmedit" || $_accion_=="edit") {
	
//========================================================================
//
//
// EDIT - EDIT - EDIT - EDIT - EDIT - EDIT - EDIT - EDIT - EDIT - EDIT
//
//   
//========================================================================
		
	
		Debug( "EDITAMOS USUARIO YA EXISTENTE" );	
		//========================================================================
		// TOMA DE DATOS
		//========================================================================
				
		$UsuarioActual = $this->Usuarios->GetSesionUsuario();
		
		if ( is_object($UsuarioActual) ) {
		
			$old_nick = $UsuarioActual->m_nick;
			
			if ($_accion_=="confirmedit") {
				$UsuarioActual->SetFromGlobals();
				if ($NICK_IS_MAIL) 
					$UsuarioActual->m_nick = $UsuarioActual->m_mail;
			}
			
			//PASSWORD
			
			if ( isset($GLOBALS["_e_PASSWORD"]) && ($GLOBALS["_e_PASSWORD"]!="" && $GLOBALS["_e_PASSWORD_confirm"]!="" ) 
				&& $GLOBALS["_e_PASSWORD"] == $GLOBALS["_e_PASSWORD_confirm"] ) {
				
				$newpass = $GLOBALS["_e_PASSWORD_confirm"];
			} else $newpass = "";
			
			//========================================================================
			// VERIFICACION DE DATOS
			//========================================================================	
			
			$miss_ok = !$UsuarioActual->RequiredFields( $requiredfields,  $errorfieldmsg );
			$nick_ok = ( $UsuarioActual->m_nick == $old_nick ) || 
					   ( $UsuarioActual->m_nick != $old_nick && ! $this->Usuarios->NickUtilizado( $UsuarioActual->m_nick ) );
			
			if ($_accion_=="confirmedit") {
					   $pass_ok = !(
						 $GLOBALS["_e_PASSWORD"] != $GLOBALS["_e_PASSWORD_confirm"] && 
						($GLOBALS["_e_PASSWORD"]!='' || $GLOBALS["_e_PASSWORD_confirm"]!='')
						);

			}
	
			//========================================================================
			// CONFIRMACION DE DATOS
			//========================================================================		
			if ($_accion_=="confirmedit") {
						
				if ( $miss_ok && $pass_ok && $nick_ok) {	

					Debug("Guardamos los datos de usuario editados");					
					if ($this->UserAdminConfirm( "confirmolduser", $UsuarioActual, $newpass )) {
						$UsuarioActual = $this->Usuarios->GetSesionUsuario();
						//$USEREDIT = $this->UserAdminEdit( "edituser", $UsuarioActual, $__template__ );
						$redirection = Redirection( "confirmrecord", $redirecciones);
					} else {
						//echo '<span style="color:#CC0000">[old user edit NOT confirmed: '.$this->Usuarios->GetLastError().']</span>';
						DebugError( 'Error de actualización de usuario');
						//$usererror = '<span style="color:#CC0000">'.$CLang->m_ErrorMessages[$this->Usuarios->GetLastError()->m_tipo].'</span>';
						//$USEREDIT = $this->UserAdminEdit( "edituser", $UsuarioActual, $__template__ );					
					}
	
					//==================================
					//    RE VERIFICACION DE DATOS
					//==================================
					Debug("Verificación de datos de ficha usuario editada");
					$ContenidoAsociado = new CContenido();//create from globals
					$ContenidoAsociado->m_id = $UsuarioActual->m_id_contenido;
					$ContenidoAsociado->m_titulo = $UsuarioActual->m_nombre." ".$UsuarioActual->m_apellido;
					$ContenidoAsociado->m_id_usuario_creador = $UsuarioActual->m_id;
					$ContenidoAsociado->m_id_usuario_modificador = $UsuarioActual->m_id;
					$ContenidoAsociado->ToGlobals();
					/*
					$ConfirmRecord->m_id = $Usuario->m_id_contenido;				
					$ConfirmRecord->m_baja = "S";
					$ConfirmRecord->m_titulo = $Usuario->m_nick;
					$ConfirmRecord->m_ml_titulo = "";
					$ConfirmRecord->m_copete = $Usuario->m_nombre." ".$Usuario->m_apellido;
					$ConfirmRecord->m_ml_copete = "";
					$ConfirmRecord->m_ml_cuerpo = "";
					$ConfirmRecord->ToGlobals();
					*/
					
					$_exito_ = $this->Contenidos->m_tcontenidos->Modificari($ContenidoAsociado->m_id);
					if ($_exito_) {
						$_exito_ = $this->ConfirmarDetalles( "confirmeditrecord", $ContenidoAsociado->m_id_tipocontenido, $ContenidoAsociado->m_id );
						$_confirmaccion_ = "confirmedit";
					}
					
					if ( $_exito_ ) {
						$this->UserAdminRecordPostProcess( $ContenidoAsociado );
						Debug("Actualizacion correcta");
					} else {
						DebugError( "error en confirmacion de datos" );
					}
					
					/*		
					if ($this->UserAdminRecordConfirm( "confirmeditrecord", $ConfirmRecord )) {
						//echo '<span style="color:#00CC00">[old record confirmed]</span>';
						//post process:
						$this->UserAdminRecordPostProcess( $ConfirmRecord );
						$RECORDEDIT = $this->UserAdminRecordEdit( "editrecord", $ID_TIPOCONTENIDO, $ConfirmRecord );
						//$this->UserAdminRecordPostProcessAll();
						//echo "<script>window.location.href='../../principal/home/panel.php';</script>";
					} else {
						echo '<span style="error">Error de actualización de ficha personal</span>';
						//echo $this->Detalles->GetLastError();
						$recorderror = DebugError( $CLang->m_ErrorMessages[$this->Detalles->GetLastError()->m_tipo] );
						$RECORDEDIT = $this->UserAdminRecordEdit( "editrecord", $ID_TIPOCONTENIDO, $ConfirmRecord );
						//$_confirmaccion_ = "confirmedit";
					}		
			*/
					
				} else {
					CheckNickError( $UsuarioActual );
					CheckMissingError();
					CheckPasswordError();
					//CheckConditions();
					
					$_mod_ = "profil";
					$_accion_="edit";
				}
			} else {
				Debug("Solo editamos los datos");
				$FichaUsuario = $this->Contenidos->GetContenido($UsuarioActual->m_id_contenido);
				///SI EL USUARIO NO TIENE FICHA ASIGNADA SE LA CREAMOS DE 0
				if ($UsuarioActual->m_id_contenido=='' || $UsuarioActual->m_id_contenido<=2 || !is_object($FichaUsuario)) {
					//========================================================================
					// CREAMOS LA FICHA USUARIO DESDE 0
					//========================================================================
					$TipoContenido = $this->TiposContenidos->GetTipoContenido($ID_TIPOCONTENIDO_USUARIO);
					
					$SeccionUsuarios = $this->Secciones->GetSeccionByType( $ID_TIPOSECCION_USUARIOS );
					
					$ContenidoNuevo = new CContenido(0);
					$ContenidoNuevo->m_id_tipocontenido = $ID_TIPOCONTENIDO_USUARIO;
					$ContenidoNuevo->m_id_seccion = $SeccionUsuarios->m_id;
					$ContenidoNuevo->m_id_contenido = 1;
					$ContenidoNuevo->m_titulo = $UsuarioActual->m_nick;
					$ContenidoNuevo->m_ml_titulo = "";
					$ContenidoNuevo->m_copete = $UsuarioActual->m_nombre." ".$UsuarioActual->m_apellido;
					$ContenidoNuevo->m_ml_copete = "";
					$ContenidoNuevo->m_cuerpo = "";
					$ContenidoNuevo->m_ml_cuerpo = "";
					$ContenidoNuevo->m_id_usuario_creador = $UsuarioActual->m_id;
					$ContenidoNuevo->m_id_usuario_modificador = $UsuarioActual->m_id;
					$ContenidoNuevo->m_baja = "N";
					
					//pass to globals
					$ContenidoNuevo->ToGlobals();
					$ContenidoNuevo = $this->Contenidos->CrearContenidoCompleto( '', $ContenidoNuevo);
					$UsuarioActual->m_id_contenido = $ContenidoNuevo->m_id;
					$this->Usuarios->ActualizarUsuario($UsuarioActual);				
				}
				
			}
		} ///fin $UsuarioActual
		else {
			ShowError("UserAdminData is null");
		}
	
		$_confirmaccion_ = "confirmedit";
}


//========================================================================
// FORMULARIO DE REGISTRO COMPLETO
//========================================================================

Debug( "mod: $_mod_"." accion:".$_accion_ );

if (
	(	($_mod_=="profil" || $_mod_=="hacerse_proveedor") &&  $this->Usuarios->Logged()) || 
	( ( $_mod_=="profil" || $_mod_=="hacerse_proveedor" || $_mod_=="confirmar_usuario" || $_mod_=="register") && ( $_accion_=="insertnew" || $_accion_=="confirmnew" ) ) ) {

	
	
	$USEREDIT = "";
	
	//========================================================================
	// CARGAMOS EL TEMPLATE QUE USAREMOS DE FORMULARIO
	//========================================================================
			
	Debug('CARGAMOS EL TEMPLATE QUE USAREMOS DE FORMULARIO');
				
	$fjose = "../../inc/templates/USUARIO.userprofile.html";
				
	if (file_exists($fjose)) {
		$__template__ = implode('', file($fjose));
		
	} else DebugError("template file doesn't exist : ".$fjose);

	$__template__ = str_replace( "[ROLECLASS]", $role_class, $__template__ );

	//========================================================================
	// EDITAMOS UN USUARIO YA EXISTENTE
	//========================================================================	
	if ( $_accion_=="edit" || $_accion_=="confirmedit") {
			
		//========================================================================
		// EDICION TEMPLATE
		//========================================================================
		if ($NICK_IS_MAIL) { 
			
			$__template__ = str_replace( array("*NICK*","#NICK#"), 
												array('*NICK*',"hidden"),
												$__template__);	

			$execute = '<script>
				document.register._e_NICK.value = "'.$_e_nick_.'";
				</script>
				';
		}												
												
		$__template__ = str_replace( array("{VERIFICATIONVISUELLE}","#VERIFICACIONVISUAL#","#_conditions_#") , array("","hidden","hidden"), $__template__ );
		
		Debug('Campos requeridos en el formulario');
		$UsuarioActual->UpdateRequiredFields( $requiredfields, $__template__ );
		
			
		$USEREDIT = $this->UserAdminEdit( "edituser", $UsuarioActual, $__template__ );
		
				
		/** FICHA PERSONAL */
			////TIENE QUE BUSCAR EL CONTENIDO CORRESPONDIENTE A ESTE USUARIO
			/// si no lo encuentra deberíamos volver a crearlo....
			// hacer una funcion en CSitioExtended que cree el contenido asociado al usuario...
			Debug('Ficha usuario:'.$UsuarioActual->m_id_contenido);
			$ContenidoAsociado = $this->Contenidos->GetContenidoCompleto($UsuarioActual->m_id_contenido);
			
			if (is_object($ContenidoAsociado)) {
				//$ID_TIPOCONTENIDO = $Contenido->m_id_tipocontenido;
				//$TC = $this->TiposContenidos->GetTipoContenido($ID_TIPOCONTENIDO_USUARIO);
				
				//echo '<h3>MODIFICANDO '.substr($TC->m_tipo,6).'</h3>';
	
				$this->Contenidos->m_tcontenidos->Edicion( $ContenidoAsociado->m_id );
				$ContenidoAsociado->SetFromGlobals();
				$this->TiposContenidos->SetTemplateEdicionUsuario( $ID_TIPOCONTENIDO_USUARIO, $USEREDIT );
				
				Debug("Editing : ".$ID_TIPOCONTENIDO_USUARIO);
				
				
				
				$USEREDIT = $this->Contenidos->Edit( $ID_TIPOCONTENIDO_USUARIO );
				
				Debug("Editing Details : ".$ID_TIPOCONTENIDO_USUARIO);
				if ($_mod_=="hacerse_proveedor") {
					$GLOBALS['_edetalle_USUARIO_PROVEEDOR'] = "[YES]";
				}				
				$USEREDIT = $this->EditarDetalles( "editrecord", $ID_TIPOCONTENIDO_USUARIO, $USEREDIT, $ContenidoAsociado );
				
				//if ($_accion_=="confirmnew") {
				$ContenidoAsociado->UpdateRequiredFields( $requiredfields_ficha_register, $USEREDIT );

			} else {
				DebugError("Error de Contenido Asociado");
			}	
			
			
			//$USEREDIT = $this->UserAdminRecordEdit( "editrecord", $ID_TIPOCONTENIDO_USUARIO, $Contenido, $USEREDIT );
			
		/** FIN FICHA PERSONAL */

		//========================================================================
		// PROXIMA ACCION
		//========================================================================
		$_confirmaccion_ = "confirmedit";
			
	} else	
	//================================================================================================
	//		EDICION DE USUARIO NUEVO!!
	//================================================================================================	
	if ( ($_accion_=="insertnew" || $_accion_=="confirmnew") && !$this->Usuarios->Logged()) {			
			
			//========================================================================
			// MODIFICAMOS EL TEMPLATE CON LOS CAMPOS VALIDADOS 
			//========================================================================
			Debug('MODIFICAMOS EL TEMPLATE CON LOS CAMPOS VALIDADOS');			
			
			// reemplazamos el NICK editable por uno no editable
			$_e_nick_ = "";
			if (isset($GLOBALS["_e_NICK"])) $_e_nick_ = $GLOBALS["_e_NICK"]; 
			if ($NICK_IS_MAIL) 
					$__template__ = str_replace( array("*NICK*","#NICK#"), 
												array('<input name="_e_NICK" type="text" READONLY value="'.$_e_nick_.'" size="45" class="form-control">',"hidden"),
												$__template__);
													
			//CiviliteEdit( "",$__template__);
			
			// Reflejamos los errores en el template
			if ($_accion_=="confirmnew") {
				$UsuarioNuevo->UpdateRequiredFields( $requiredfields_register, $__template__ );
				$__template__ = str_replace("#VERIFICACIONVISUAL#", $errorfieldmsg, $__template__ );
			}
			
			
			
			// mostramos el codigo de verificacion visual !!!! muy importante !!!			
			$publickey = "6LdRlxETAAAAAE_GMgNisjr4ClkB3CAb-9BQA61M"; // you got this from the signup page
			$htmlvisual = '<img width="200" height="70" src="'.$_DIR_SITEABS.'/inc/include/getimage.php?mode=image" alt="" /><br>
<input type="text" name="_visualconfirmation_" value="" />';
			$htmlvisual = recaptcha_get_html($publickey);
			//$htmlvisual = '<div class="g-recaptcha" data-sitekey="6LdRlxETAAAAAE_GMgNisjr4ClkB3CAb-9BQA61M"></div>';
			

			$__template__ = str_replace("{VERIFICATIONVISUELLE}",$htmlvisual,$__template__);			
			//$__template__ = str_replace( "{VERIFICATIONVISUELLE}" , "", $__template__ );

			$execute = '<script>
				document.register._e_NICK.value = "'.$_e_nick_.'";
				</script>
				';

			//===============================================================================
			//	AHORA SI PROCESAMOS EL TEMPLATE CON EL NUEVO USUARIO PARA EDITAR SUS CAMPOS 
			//===============================================================================
			Debug('AHORA SI PROCESAMOS EL TEMPLATE CON EL NUEVO USUARIO PARA EDITAR SUS CAMPOS');

			///pasamos los campos a globales
			$UsuarioNuevo->ToGlobals();
			///Seteamos el template  y lo editamos
			$USEREDIT = $this->Usuarios->EditUsuario( 4, $CLang, $__template__);

			$ContenidoNuevo->ToGlobals();
		
			//========================================================================
			// EDITAMOS FICHA USUARIO (a manopla)
			//========================================================================

			if ($_accion_=="insertnew") {
				//asigna algunas campos si no estan por sus valores por defecto
				$this->Contenidos->m_tcontenidos->Nuevo();				
			}

			$this->TiposContenidos->SetTemplateEdicionUsuario( $ID_TIPOCONTENIDO_USUARIO, $USEREDIT );
			
			Debug("Editing Ficha Usuario: ".$ID_TIPOCONTENIDO_USUARIO);
			$USEREDIT = $this->Contenidos->Edit( $ID_TIPOCONTENIDO_USUARIO );
			
			Debug("Editing Details : ".$ID_TIPOCONTENIDO_USUARIO);
			if ($_mod_=="hacerse_proveedor") {
				$GLOBALS['_edetalle_USUARIO_PROVEEDOR'] = "[YES]";
			}		
				
			$USEREDIT = $this->EditarDetalles( "newrecord", $ID_TIPOCONTENIDO_USUARIO, $USEREDIT, $ContenidoNuevo );
			
			//if ($_accion_=="confirmnew") {
				$ContenidoNuevo->UpdateRequiredFields( $requiredfields_ficha_register, $USEREDIT );
			//}
			
			//========================================================================
			//	SIGUIENTE PASO: CONFIRMAR
			//========================================================================
			Debug('SIGUIENTE PASO: CONFIRMAR');						
			$_confirmaccion_ = "confirmnew";
	
	}

	//========================================================================
	//	TRADUCIMOS LA INTERFACE
	//========================================================================
	Debug('TRADUCIMOS LA INTERFACE');	
	$CMultiLang->Translate($USEREDIT);	
	

	if ( $redirection == "") {
?>
<!-- 
<a href="javascript:togglediv('perfil');">Editar Mi Perfil</a>

	<div id="perfil" style="display:<? if ($UsuarioActual->m_nombre=="" || $usererror!="") {
                          	echo "block";
                          } else {
                          	echo "none";
                          }
                          	?>;">
        	
                       
                          	<div id="formperfil">
                          	-->
                          <form name="register" onsubmit="javascript:return SignUpValidation();" id="register" method="post"  enctype="multipart/form-data" action="<?=$_DIR_SITEABS?>/principal/home/perfil.php">
                          	<div id="div_debug" class="debugdetails">
                          	<input type="text" name="_mod_" value="<?=$_mod_?>">
                          	<input type="text" name="_cID_" value="<?=$_cID_?>">
							<input type="text" name="_accion_" value="<?=$_confirmaccion_?>">
							</div>
                          	<?
								//if ($usererror!="") $usererror = '<div class="profileerror">'.$usererror.'</div>';
								$usererror = str_replace(array("<div","</div"),array("<span","</span"), $usererror); 
								if ($usererror!="") {
									
								} else $USEREDIT = str_replace( "[ALERT]", "alert-closed", $USEREDIT ); 
								
								$USEREDIT = str_replace("[ERROR]",$usererror, $USEREDIT); 
								
								//$USEREDIT = str_replace("[RECORDEDIT]",$RECORDEDIT,$USEREDIT);
								echo $USEREDIT;
								echo $execute;
                          	
                          	?>
    					</form>

 <!--    					</div>
 -->
 <!-- 
 </div>
 -->
	
	<?
}
	
} else {
	echo $signinError;
	echo $registerError;
}

?>

<?=$redirection?>
	
	</div>
</div>

