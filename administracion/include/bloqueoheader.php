<?
/*BLOQUEO DE USUARIOS*/
require '../admin/deftabla.php';//las tablas del administrador


		if (isset($_usuario_) && isset($_password_)) {		
			
			session_start();	
			$_SESSION['user']  = $_usuario_;
			$_SESSION['time']    = time();		
			
			$_tusuarios_->LimpiarSQL();
			$_usuario_ = strip_tags($_usuario_);
			$_password_ = strip_tags($_password_);
			$_tusuarios_->SQL = "SELECT usuarios.ID,usuarios.NICK,usuarios.NIVEL FROM usuarios WHERE usuarios.NICK='".$_usuario_."' AND usuarios.PASSWORD=".$GLOBALS['_PASSWORD_VERSION']."('".$_password_."') AND usuarios.NIVEL<1";
			$_tusuarios_->SQLCOUNT = "SELECT COUNT(*) FROM usuarios WHERE usuarios.NICK='".$_usuario_."' AND usuarios.PASSWORD=".$GLOBALS['_PASSWORD_VERSION']."('".$_password_."') AND usuarios.NIVEL<1";
			$_tusuarios_->Open();
			if ($_tusuarios_->nresultados==1) {
				//echo "OK";
				$rrr = $_tusuarios_->Fetch($_tusuarios_->resultados);
				$_SESSION['encrypt'] = crypt( $_usuario_,"ep");
				$_SESSION['idusuario'] = $rrr['usuarios.ID'];
				$_SESSION['nivel'] = $rrr['usuarios.NIVEL'];
				//$this->PasswordUpdateMD5($_password_);
				$_logueado_ = 'verdadero';
			} else {
				$_SESSION = array();
				
				// If it's desired to kill the session, also delete the session cookie.
				// Note: This will destroy the session, and not just the session data!
				if (isset($_COOKIE[session_name()])) {
				   setcookie(session_name(), '', time()-42000, '/');
				}			
				session_destroy();
				//echo "<script>window.history.go(-1);</script>";			
				//echo "<script>window.location.href = 'index.php';</script>";
					
			}
		} else {
			session_start();	
			//echo $_SESSION['user'];	
			if ($_SESSION['encrypt'] != crypt( $_SESSION['user'],"ep")) {
				$_SESSION = array();
				session_destroy();
				//echo "<script>window.location.href = 'index.php';</script>";		
			} else $_logueado_ = 'verdadero';
		}
/*
if (isset($_usuario_) or isset($_password_)) {
		//primero chequeamos si el usuario ya está logueado!!!
		//ya lo sabemos gracias a $_usuariologs_, nos fijamos si coinciden los LOGS
		//si no coinciden tratamos de loguearnos
		if (!isset($_usuariologs_)) $_usuariologs_=-1;
		$tadminlog->LimpiarSQL();
		$tadminlog->SQL = "SELECT logusuarios.ID,logusuarios.NICK_USUARIO,logusuarios.ID_USUARIO,logusuarios.IP,logusuarios.LOGS FROM logusuarios WHERE logusuarios.NICK_USUARIO='".$_usuario_."' AND logusuarios.IP='".$HTTP_SERVER_VARS['REMOTE_ADDR']."' AND logusuarios.LOGS=".$_usuariologs_;
		$tadminlog->SQLCOUNT = "SELECT COUNT(*) FROM logusuarios WHERE logusuarios.NICK_USUARIO='".$_usuario_."' AND logusuarios.IP='".$HTTP_SERVER_VARS['REMOTE_ADDR']."' AND logusuarios.LOGS=".$_usuariologs_;
		$tadminlog->Open();
		$rowlogusuario = $tadminlog->Fetch($tadminlog->resultados);
							
		if (($tadminlog->nresultados==0) and isset($_usuario_) and isset($_password_)) {//si no lo está lo intentamos
			//LOGUEO
			$_logueado_= 'falso';
			//chequeamos si existe el usuario
			$tadmin->LimpiarSQL();
			$_usuario_ = Trim($_usuario_);
			$tadmin->SQL = "SELECT usuarios.ID,usuarios.NICK FROM usuarios WHERE usuarios.NICK='".$_usuario_."' AND usuarios.PASSWORD=".$GLOBALS['_PASSWORD_VERSION']."('".$_password_."')";
			$tadmin->SQLCOUNT = "SELECT COUNT(*) FROM usuarios WHERE usuarios.NICK='".$_usuario_."' AND usuarios.PASSWORD=".$GLOBALS['_PASSWORD_VERSION']."('".$_password_."')";
			$tadmin->Open();
			if ($tadmin->nresultados==1) {
				$rowusuario = $tadmin->Fetch($tadmin->resultados);
				$_logerror_ = 'Sin errores: generando 1 entrada en logusuarios';				
				$_logueado_ = 'verdadero';
				//aca deberiamos loguear al usuario en una tabla...
				$tadminlog->LimpiarSQL();
				$tadminlog->SQL = "INSERT INTO logusuarios (ID_USUARIO,NICK_USUARIO,IP) ";
				$tadminlog->SQL.= "VALUES(".$rowusuario['usuarios.ID'].",'".$rowusuario['usuarios.NICK']."','".$HTTP_SERVER_VARS['REMOTE_ADDR']."')";
				$tadminlog->EjecutaSQL();
				$_usuarioid_ = $rowusuario['usuarios.ID'];
				$_usuarionick_ = $rowusuario['usuarios.NICK'];
				$_usuariologs_=0;
			} else {
				$_logerror_ = 'Se encontraron '.$tadmin->nresultados.' usuarios!.';
				$_logueado_='falso';
			}
			//FIN LOGUEO
		} elseif ($tadminlog->nresultados==1) {//ya se encuentra logueado, con el mismo IP y todo...
			$_logerror_ = 'Sin errores: 1 entrada en logusuarios';
			$_logueado_= 'verdadero';//sumamos 1...
			//agregamos uno a la cantidad de logs (accesos de la misma sesion)
			$tadminlog->LimpiarSQL();
			$tadminlog->SQL= "UPDATE logusuarios ";
			$tadminlog->SQL.= " SET LOGS=".($rowlogusuario['logusuarios.LOGS'] + 1);
			$tadminlog->SQL.= " WHERE ID_USUARIO=".$rowlogusuario['logusuarios.ID_USUARIO'];
			echo $tadminlog->SQL;
			$tadminlog->EjecutaSQL();
			$_usuarioid_ = $rowlogusuario['logusuarios.ID_USUARIO'];
			$_usuarionick_ = $rowlogusuario['logusuarios.NICK_USUARIO'];
			$_usuariologs_++;
		} elseif ($tadminlog->nresultados>1) {
			$_logerror_ = 'Se encontraron '.$tadminlog->nresultados.' en logusuarios.';
			$_logueado_= 'falso';//no puede abrir dos sesiones o no tiene los datos suficientes
			//por las dudas volamos todas las sesiones
			echo "ATENCIÓN: SESIONES REPETIDAS: ".$tadminlog->nresultados."<br>";
			$tadminlog->LimpiarSQL();
			$tadminlog->SQL= "DELETE FROM logusuarios WHERE NICK_USUARIO='".$_usuario_."'";
			$tadminlog->EjecutaSQL();
		} else {
			$_logerror_ = 'Error en la salida: '.$tadminlog->nresultados.' resultados, SQL:'.$tadminlog->SQL.'<br>SQLCOUNT:'.$tadminlog->SQLCOUNT;
			$_logueado_= 'falso';
		}
} else {
	$_logerror_ = 'No están definidos usuario y password.';
	$_logueado_='falso';
}
*/

/// CHECK GROUP SECTIONS!!!! PERMISSIONS!!!!
if ($_logueado_=='verdadero') {
	$_usuarioid_ = $_SESSION['idusuario'];
	//genera un array con los ids de las secciones a las que accede el usuario
	$_tgrupossecciones_->LimpiarSQL();
	$_tgrupossecciones_->SQL = 'SELECT grupossecciones.ID_SECCION FROM grupossecciones,gruposusuarios WHERE grupossecciones.ID_GRUPO=gruposusuarios.ID_GRUPO and gruposusuarios.ID_USUARIO='.$_usuarioid_;
	$_tgrupossecciones_->SQLCOUNT = 'SELECT COUNT(grupossecciones.ID_SECCION) FROM grupossecciones,gruposusuarios WHERE grupossecciones.ID_GRUPO=gruposusuarios.ID_GRUPO and gruposusuarios.ID_USUARIO='.$_usuarioid_;
	$_tgrupossecciones_->Open();
	if ($_tgrupossecciones_->nresultados>0) {
		$_usuariosecciones_='';
		$coma = '';
		while ($rowsecciones=$_tgrupossecciones_->Fetch($_tgrupossecciones_->resultados)) {
			$_usuariosecciones_.= $coma.$rowsecciones['grupossecciones.ID_SECCION'];
			$coma = ',';			
		}
		if ($_debug_=='si') echo $_usuariosecciones_;
	}
	
}


?>