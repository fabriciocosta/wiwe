<?

define('FPDF_FONTPATH','../../inc/fpdf/font/');
require "../../inc/fpdf/fpdf.php";

class CAdmin  extends CErrorHandler {

	var $Secciones, $Contenidos, $Archivos, $Detalles, $TiposSecciones, $TiposContenidos, $TiposArchivos, $TiposDetalles, $Usuarios, $Logs;
	var $Relaciones,$TiposRelaciones;
	var $ComboEmpresas;
	var $Functions;

	var $UsuarioAdmin;

	var $AdminPermisos;

	//------------------------------------------------------

	function CAdmin(&$__tsecciones__,
					&$__ttipossecciones__,
					&$__tcontenidos__,
					&$__ttiposcontenidos__,
					&$__tarchivos__,
					&$__ttiposarchivos__,
					&$__tdetalles__,
					&$__ttiposdetalles__,
					&$__tusuarios__,
					&$__tlogs__,
					&$__trelaciones__,
					&$__ttiposrelaciones__) {


		$this->TiposDetalles = new CTiposDetalles($__ttiposdetalles__);

		$this->Detalles = new CDetalles($__tdetalles__, $this->TiposDetalles );


		$this->TiposRelaciones = new CTiposRelaciones( $__ttiposrelaciones__, $this->TiposDetalles );

		$this->Relaciones = new CRelaciones( $__trelaciones__, $this->TiposRelaciones );


		$this->TiposSecciones = new CTiposSecciones($__ttipossecciones__);

		$this->Secciones = new CSecciones($__tsecciones__, $this->TiposSecciones );
		$this->Secciones->m_CRelaciones = $this->Relaciones;


		$this->TiposContenidos = new CTiposContenidos($__ttiposcontenidos__, $this->Detalles );

		$this->Contenidos = new CContenidos($__tcontenidos__, $this->TiposContenidos, $this->Relaciones, $this->Usuarios );
		$this->Contenidos->m_CRelaciones = $this->Relaciones;

		$this->TiposArchivos = new CTiposArchivos($__ttiposarchivos__);

		$this->Archivos = new CArchivos($__tarchivos__,$this->TiposArchivos);


		$this->Usuarios = new CUsuarios( $__tusuarios__, $this->Secciones, $this->Contenidos, $this->Relaciones );


		$this->Logs = new CLogs( $__tlogs__, $this->Contenidos);

		$this->Contenidos->m_CUsuarios = $this->Usuarios;
	}

	/*
	 * WIWE y la categorización escalar
	 *
	 * Se tienen 3 escalas básicas para relacionar
	 * 		1) SECCIONES : categorización de escala 1
	 * 		2) CONTENIDOS : categorización de escala 0
	 * 		3) DETALLES : categorización de escala -1
	 * */

	function CheckCoherence() {

		//SISTEMA: escala 1.618 > Maneja secciones
			//MODULOS : escala fraccional (programacion>)

		//SECCIONES : root = escala 1 : escala(1) > agrupacion de objetos
			//TIPOSSECCIONES : categorización de escala(1)

		//CONTENIDOS :  escala(0) > objetos
			//TIPOSCONTENIDOS : categorización de escala(0)

		//MODULOS

		//DETALLES :  escala(-1) > propiedades de objetos
			//TIPOSDETALLES : categorización de escala(-1)

		//RELACIONES: escalas (-2) > lenguaje
			//TIPOSRELACIONES : categorización de escala ( -2 ), a veces se asocia a la -1

		$formulas = array(
				"{SECCION_ARB_NOT_OK}" => " id_seccion not in (select id from secciones)",
				"{SECCION_CAT_NOT_OK}" => " id_tiposeccion not in (select id from tipossecciones)",

				"{CONTENIDO_ARB_NOT_OK}"=>" id_contenido not in (select id from contenidos)",
				"{CONTENIDO_CAT_NOT_OK}"=>" id_tipocontenido not in (select id from tiposcontenidos)",

				"{DETALLE_ARB_NOT_OK}"=>" id_detalle not in (select id from detalles)",
				"{DETALLE_CAT_NOT_OK}"=>" id_tipodetalle not in (select id from tiposdetalles)"
		);

		$escalas = array(
				"1"=>"secciones",
				"0"=>"contenidos",
				"-1"=>"detalles"
		);

	}

	function FixCoherence() {

	}


	//-----------------------------------------------------

	function IniciarSesion() {

		global $_usuario_, $_password_, $_SESSION;

		global  $_nxintervalo_;
		global $_KEYWORDS_;
		global $_DESCRIPTION_;

		$_KEYWORDS_ = "";
		$_DESCRIPTION_ = "";

		if ($_nxintervalo_!="" && !isset($_COOKIE['nxintervalo'])) {
			setcookie('nxintervalo',$_nxintervalo_);
			//echo "cookie set:".$_nxintervalo_;
		} else if ( isset($_COOKIE['nxintervalo']) ) {

			if ($_nxintervalo_!=$_COOKIE['nxintervalo'] && $_nxintervalo_!="") {

				setcookie('nxintervalo',$_nxintervalo_);
				//echo "set new cookie:".$_nxintervalo_;
			} else $_nxintervalo_ = $_COOKIE['nxintervalo'];
			//echo "cookie get:".$_COOKIE['nxintervalo'];
		}
		if (isset($_usuario_) && isset($_password_)) {

			session_start();
			$_SESSION['user']  = $_usuario_;
			$_SESSION['time']    = time();

			$this->Usuarios->m_tusuarios->LimpiarSQL();
			$_usuario_ = strip_tags($_usuario_);
			$_password_ = strip_tags($_password_);
			$this->Usuarios->m_tusuarios->SQL = "SELECT usuarios.ID,usuarios.NICK,usuarios.NIVEL FROM usuarios WHERE usuarios.NICK='".$_usuario_."' AND usuarios.PASSWORD=".$GLOBALS['_PASSWORD_VERSION']."('".$_password_."') AND usuarios.NIVEL<=3";
			$this->Usuarios->m_tusuarios->SQLCOUNT = "SELECT COUNT(*) FROM usuarios WHERE usuarios.NICK='".$_usuario_."' AND usuarios.PASSWORD=".$GLOBALS['_PASSWORD_VERSION']."('".$_password_."') AND usuarios.NIVEL<=3";
			$this->Usuarios->m_tusuarios->Open();
			if ($this->Usuarios->m_tusuarios->nresultados==1) {
				//echo "OK";
				$rrr = $this->Usuarios->m_tusuarios->Fetch($this->Usuarios->m_tusuarios->resultados);
				$_SESSION['encrypt'] = crypt( $_usuario_,"ep");
				$_SESSION['idusuario'] = $rrr['usuarios.ID'];
				$_SESSION['nivel'] = $rrr['usuarios.NIVEL'];

				$this->PasswordUpdateMD5($_password_);

				$this->UsuarioAdmin = $this->Usuarios->GetUsuario($_SESSION['idusuario']);
				if (!is_object($this->UsuarioAdmin) || $this->UsuarioAdmin->m_id!=$_SESSION['idusuario']) {
					DebugError("CAdmin::IniciarSesion > objeto Usuario no corresponde");
				}
				$this->AdminRoles();
			} else {
				$_SESSION = array();

				// If it's desired to kill the session, also delete the session cookie.
				// Note: This will destroy the session, and not just the session data!
				if (isset($_COOKIE[session_name()])) {
				   setcookie(session_name(), '', time()-42000, '/');
				}
				session_destroy();
				//echo "<script>window.history.go(-1);</script>";
				echo "<script>window.location.href = 'index.php';</script>";
			}
		} else $this->ContinuarSesion();

	}

	//-----------------------------------------------------

	function ContinuarSesion() {
		global $_SESSION;

		session_start();

		//echo $_SESSION['user'];
		if ($_SESSION['encrypt'] != crypt( $_SESSION['user'],"ep")) {
			$_SESSION = array();
			session_destroy();
			echo "<script>window.location.href = 'index.php';</script>";
		}

		$this->UsuarioAdmin = $this->Usuarios->GetUsuario($_SESSION['idusuario']);
		$this->AdminRoles();
		if (!is_object($this->UsuarioAdmin) || $this->UsuarioAdmin->m_id!=$_SESSION['idusuario']) {
			DebugError("CAdmin::ContinuarSesion > objeto Usuario no corresponde");
		}
	}


	function AdminRoles() {

		//ROLES DE ADMIN
		$this->AdminPermisos["ROL_USER_ACCESS"] = true;
		$this->AdminPermisos["ROL_APPROVAL"] = true;

		if ( $this->UsuarioAdmin->m_nivel > 1 /*SOLO DATA ENTRYS*/) {

			$this->AdminPermisos["ROL_USER_ACCESS"] = $this->Usuarios->GetUsuarioAccesoUsuarios( $this->UsuarioAdmin->m_id );
			$this->AdminPermisos["ROL_APPROVAL"] = $this->Usuarios->GetUsuarioAccesoHabilitar( $this->UsuarioAdmin->m_id );

			//ShowMessage("Filtrando contenidos para este Data Admin:".$this->UsuarioAdmin->m_nivel." Edita otros usuarios:".$this->AdminPermisos["ROL_USER_ACCESS"] );

		}


	}

	//-----------------------------------------------------

	function PasswordUpdateMD5($_password_) {
		global $_SESSION;

		$this->Usuarios->m_tusuarios->LimpiarSQL();
		$this->Usuarios->m_tusuarios->SQL = "UPDATE usuarios SET usuarios.PASSMD5=MD5('".$_password_."') WHERE usuarios.ID=".$_SESSION['idusuario'];
		$this->Usuarios->m_tusuarios->EjecutaSQL();

	}

	//-----------------------------------------------------

	function GetCounter() {

		return $this->Logs->GetCounter();
	}

	//-----------------------------------------------------

	function SetTemplates($idtipocontenido=0) {
		global $_TIPOS_;
		if($idtipocontenido==0)
			foreach($_TIPOS_['tiposcontenidos'] as $tipo=>$id) {
				$this->TiposContenidos->SetTemplateConsulta( $id );
				$this->TiposContenidos->SetTemplateEdicion( $id, "",  "html", "html" );
			}
		else $this->TiposContenidos->SetTemplateEdicion($idtipocontenido,  "", "html", "html");

		if (file_exists('../../inc/include/templatesadmin.php')) {
			require('../../inc/include/templatesadmin.php');
		}
	}


	//-----------------------------------------------------
	function HeaderSection( $titulo, $ramaseccion ) {
		//Imprimimos la seccion:
		echo '<!--HEADERSECTION--><table width="100%" cellpadding="0" cellspacing="0" border="0" class="MAD_HEADER"><tr>';
		echo '<td height="39" align="left" valign="bottom"><span class="MAD_TIT">';
		echo $titulo;
		echo '</span></td>';
		echo '</tr><tr>';
		echo '<td height="2" class="MAD_HEADER_BG_LN"><img src="../../inc/images/spacer.gif" width="100" height="2" border="0"></td>';
		echo '</tr><tr>';
		echo '<td height="15" class="MAD_HEADER_BG_BAND"><span class="MAD_SECTION">'.$ramaseccion.'</span></td>';
		echo '</tr><tr>';
		echo '<td height="2" class="MAD_HEADER_BG_LN"><img src="../../inc/images/spacer.gif" width="100" height="2" border="0"></td>';
		echo '</td>';
		echo '</tr></table><!--FIN HEADERSECTION-->';
	}

	//-----------------------------------------------------
	function ConfirmRibbon( $java = "confirmar()" , $permission = true ) {
		echo '<!--CONFIRMRIBBON--><div id="confirm-ribbon"  class="MAD_EDIT_CONFIRM">';
		if ($permission) echo '<div class="ok"><a href="javascript:'.$java.';"><span class="MAD_EDIT_CONFIRM"><img src="../../inc/images/ok.png" border="0"  hspace="3" vspace="3"></span></div>';
		echo '<div class="cancel"><a href="javascript:cancelar();"><span class="MAD_EDIT_CONFIRM"><img src="../../inc/images/cancel.png" border="0" hspace="3" vspace="3"></span></a></div>
		</div><!--FIN CONFIRMRIBBON-->';
	}

	//-----------------------------------------------------

	function MostrarArbol( $onlynavigate = false, $echoactivate = true ) {

		$user_is_cg = false;

		if (is_object($this->UsuarioAdmin)) {
			$user_is_cg = ( $this->UsuarioAdmin->m_nick == "cg_admin" );
		}

		if (file_exists('../../inc/include/templatesadminarbol.php')) {
			require('../../inc/include/templatesadminarbol.php');
		} else {
			global $_TIPOS_;
			foreach( $_TIPOS_['tipossecciones'] as $tipo=>$id ) {
				$this->TiposSecciones->SetTemplateArbolNodo($id,'','','','','','');
				$this->Secciones->SetTemplateArbolRama($id, '','');
			}
		}

		$this->Secciones->MostrarArbol();

		if (file_exists('../../inc/include/arboladmin.php')) {
			require('../../inc/include/arboladmin.php');
		} else {
			if ($echoactivate) {

				echo '<script type="text/javascript">
				<!--
				var Tree = new Array;';
				$ri = 0;
				foreach($this->Secciones->rama as $raiz=>$rama) {
					if ($raiz=="root") {
						foreach($rama as $padre=>$hijo) {
							if (	$hijo->m_id_tiposeccion==$GLOBALS['_ID_ROOT_TYPE_SECTION']
								&& ( 	(	$user_is_cg && $hijo->m_id_tiposeccion==$GLOBALS['_ID_SYSTEM_TYPE_SECTION'] )
										|| $hijo->m_id_tiposeccion!=$GLOBALS['_ID_SYSTEM_TYPE_SECTION']
									)
								) {
								echo 'Tree['.$ri.']  = "'.$hijo->m_id.'|0|'.$hijo->m_nombre.'|#|";';
								$ri++;
							}
						}
					}	else {
						foreach($rama as $padre=>$hijo) {
							if ( $hijo->m_id_tiposeccion!=$GLOBALS['_ID_ROOT_TYPE_SECTION'] && $hijo->m_id_tiposeccion!=$GLOBALS['_ID_SYSTEM_TYPE_SECTION']) {
								if ($onlynavigate) {
									$url = "javascript:navegarseccion('".$hijo->m_id."');";
									$extra = "";
								} else {
									$url = "javascript:consultarseccion('".$hijo->m_id."','".$hijo->m_id_tiposeccion."');";
									$extra = "";
									$extra.= "<a href=\\\"javascript:nuevaseccionhija(".$hijo->m_id.",3);\\\"><img src=\\\"../../inc/images/agregarmini.gif\\\" border=\\\"0\\\"  alt=\\\"Add\\\" title=\\\"Add\\\"></a>";
									$extra.= "<a href=\\\"javascript:modificarseccion(".$hijo->m_id.");\\\"><img src=\\\"../../inc/images/editarmini.gif\\\"  border=\\\"0\\\"  alt=\\\"Edit\\\" title=\\\"Edit\\\"></a>";
									$extra.= "<a href=\\\"javascript:borrarseccion(".$hijo->m_id.");\\\"><img src=\\\"../../inc/images/borrarmini.gif\\\"  border=\\\"0\\\"  alt=\\\"Delete\\\" title=\\\"Delete\\\"></a>";
									$extra.= "<a href=\\\"javascript:upseccion(".$hijo->m_id.");\\\"><img src=\\\"../../inc/images/upmini.gif\\\"  border=\\\"0\\\"  alt=\\\"Up\\\" title=\\\"Up\\\"></a>";
									$extra.= "<a href=\\\"javascript:downseccion(".$hijo->m_id.");\\\"><img src=\\\"../../inc/images/downmini.gif\\\"  border=\\\"0\\\"  alt=\\\"Down\\\" title=\\\"Down\\\"></a>";
								}
							} elseif ($hijo->m_id_tiposeccion==$GLOBALS['_ID_ROOT_TYPE_SECTION']) {
								$url = "#";
								if ($onlynavigate) {
									$extra = "";
								} else {
									$extra = "<a href=\\\"javascript:nuevaseccionhija(".$hijo->m_id.");\\\"><img src=\\\"../../inc/images/agregarmini.gif\\\"  border=\\\"0\\\"></a>";
								}
							} else {
								$url = "#";
								$extra = "";
							}
							if ( $hijo->m_id_tiposeccion!=$GLOBALS['_ID_SYSTEM_TYPE_SECTION'] ) {
								echo 'Tree['.$ri.']  = "'.$hijo->m_id.'|'.$hijo->m_id_seccion.'|'.$hijo->m_nombre.'|'.$url.'|'.$extra.'";';
								$ri++;
							}
						}
					}
				}
				echo '//-->
				</script>
				<div class="tree">
				<script type="text/javascript">
				<!--
					document.sitename = "'.$GLOBALS['_TITLE_'].'";
					createTree(Tree);
				//-->
				</script>
				</div>
				';
			}
		}
	}

	//-----------------------------------------------------
	//esto es para el navegador de contenidos...no confundir
	function NavegadorTree() {
		global $idcontenido, $_contenido_, $_tipocontenido_, $_seccion_, $_field_;
		global $_debug_, $CLang;
		//echo '<base target="centro">';

		echo '<form name="formnavegadortree" action="../../principal/admin/navegadortree.php" target="navegadorleave" method="POST">';

		// nodeId | parentNodeId | nodeName | nodeUrl
		$this->MostrarArbol(true,true);

		echo '
			<input name="_field_" type="hidden" value="'.$_field_.'">
			<input name="_contenido_" type="hidden" value="'.$_contenido_.'">
			<input name="_tipocontenido_" type="hidden" value="'.$_tipocontenido_.'">
			<input name="_seccion_" type="hidden" value="'.$_seccion_.'">

			<input name="_e_ID_SECCION" type="hidden"  value="">
			<input name="_f_ID_SECCION" type="hidden"  value="">
		  	<input name="_e_ID_TIPOSECCION" type="hidden"  value="">
			<input name="_consulta_" type="hidden"  value="si">
			<input name="_accion_" type="hidden"  value="">
			<input name="_seleccion_" type="hidden"  value="">
			<input name="_debug_" type="hidden" value="'.$_debug_.'">
			<input name="_borrar_" type="hidden" value="no">
			<input name="_cancelar_" type="hidden" value="no">
			<input name="_modificar_" type="hidden" value="si">
			<input name="_ordenar_" type="hidden" value="">
			<input name="_nuevo_" type="hidden" value="no">
			<input name="_primario_ID" value="" type="hidden">
			';
		echo '</form>';


	}

	//-----------------------------------------------------

	function NavegadorLeave() {
		global $_seccion_, $_contenido_, $_tipocontenido_, $_field_;

		//campos generales
		global $_f_ID_TIPOCONTENIDO, $_consulta_, $_orden_, $_debug_, $CLang, $_f_ID_SECCION;

		$spl = split ( '\|', $_tipocontenido_);


		$ramaseccion = $this->Secciones->GetPathSeccion($_f_ID_SECCION,""," > ");

		//Imprimimos la seccion:
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="'.$GLOBALS['_COLOR_BG'].'"><tr>';
		echo '<td height="15"><span class="modulo_admin_usuario">'.$ramaseccion.'</span></td>';
		echo '</tr><tr>';
		echo '<td height="2" bgcolor="#000000"><img src="../../inc/images/spacer.gif" width="100" height="2" border="0"></td>';
		echo '</td>';
		echo '</tr></table>';


		$temp = '<table width="100%"  border="0" cellpadding="0" cellspacing="2" bgcolor="'.$GLOBALS['_COLOR_BG'].'">
		<tr>
		<td width="10" valign="top"><input type="checkbox" name="cb_*IDCONTENIDO*"></td>
		<td valign="top">
		<table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#000000">
			<tr>
				<td width="100" bgcolor="'.$GLOBALS['_COLOR_BG'].'" align="left" valign="middle"><span class="modulo_admin_tipoficha">*IDTIPOCONTENIDO:FICHA_TIPO*</span></td>
				<td colspan="1" bgcolor="#EFEFEF" align="left" valign="middle"><a href="javascript:seleccionarcontenido(*IDCONTENIDO*,\''.$_field_.'\');"><span class="MAD_TIT">*TITULO*</span></a></td>
				<td bgcolor="'.$GLOBALS['_COLOR_BG'].'" align="right" width="10" valign="middle"><a href="javascript:seleccionarcontenido(*IDCONTENIDO*,\''.$_field_.'\');"><span class="MAD_TIT">'.$CLang->m_Words['SELECT'].'</span></a></td>
			</tr>
		</table>
		</td>
		</tr>
		</table>';


		$this->SetTemplates();


		//FILTRADO
		if ($_consulta_=='si') {
			//$this->Contenidos->m_tcontenidos->debug = 'si';
			$this->Contenidos->m_tcontenidos->LimpiarSQL();
			$tcfiltro = $or = "";
			foreach($spl as $idtc) {
				if ($idtc!="") {
					$_f_ID_TIPOCONTENIDO = $idtc;
					$tcfiltro.= $or."contenidos.ID_TIPOCONTENIDO=".$idtc;
					$or = " OR ";
				}
			}
			$tcfiltro = "(".$tcfiltro.")";
			$this->Contenidos->m_tcontenidos->FiltrarSQL('ID_SECCION','/*SPECIAL*/'.$tcfiltro);
			echo $this->Contenidos->m_tcontenidos->SQL;
			$this->Contenidos->m_tcontenidos->OrdenSQL($_orden_);
			$this->Contenidos->m_tcontenidos->Open();
		}

		//RESULTADOS
		echo '<table width="75%" border="0" cellpadding="0" cellspacing="0">
					<tr>';
		if ($this->Contenidos->m_tcontenidos->nresultados>0) {
						echo '<td width="60%" valign="bottom"><span class="modulo_titulo">';
						echo $this->Contenidos->m_tcontenidos->nresultados.'</span></td>';
		} else {
						echo '<td width="60%" valign="bottom"><span class="modulo_titulo">'.$CLang->m_Messages['NORESULTS'].'</span></td>';
		}

		echo '<td align="right" valign="bottom">&nbsp;</td>';
		echo '<td  align="right" valign="bottom"><span class="modulo_titulo">'.$CLang->m_Words['ORDERBY'].'&nbsp;&nbsp;</span>';
		echo $this->Contenidos->m_tcontenidos->Ordenar($_orden_).'</td>';
		echo '</tr>';
		echo '<tr><td colspan="2" height="1"><img src="../images/spacer.gif" width="1" height="1"></td></tr>
				</table>';

		$this->Contenidos->MostrarResultadoCompleto();

		echo '</tr></table>';

	}


	//-----------------------------------------------------

	function AdministrarConfiguracion() {
		global $CLang, $_SESSION;

		if ( $GLOBALS['_ADMIN_TYPE']!='TREE' ) {
			$this->ConsultaHeader();
		}
		echo '<table border="0" cellpadding="8" cellspacing="0" width="100%" height="100%" class="MAD_CONF"><tr><td valign="top">';
		echo '<table border="0" cellpadding="8" cellspacing="0" width="100%">';
		/*echo '<tr><td valign="top" align="left"><span class="MAD_TIT">'.$CLang->m_Words['VISITED'].':&nbsp;&nbsp;'.$this->GetCounter().'</span></td></tr>';*/

if ($_SESSION['nivel'] == '0') {
		echo '<tr><td valign="top" align="left"><span class="MAD_TIT"><a href="../../principal/admin/usuarios.php?_f_NIVEL=0&_consulta_=si" target="centro" class="MAD_TIT">SUPER '.$CLang->Get('ADMINISTRATORS').'</a></span></td></tr>';
		echo '<tr><td valign="top" align="left"><span class="MAD_TIT"><a href="../../principal/admin/mantenimiento.php" target="centro"  class="MAD_TIT">'.$CLang->Get('MAINTENANCEFUNCTIONS').'</a></span></td></tr>';
		echo '<tr><td valign="top" align="left"><hr></td></tr>';
}

if ( $_SESSION['nivel'] == '1' || $_SESSION['nivel'] == '0' ) {
		echo '<tr><td valign="top" align="left"><span class="MAD_TIT"><a href="../../principal/admin/sistema.php" target="centro" class="MAD_TIT">'.$CLang->Get('CONFIGURATION').'</a></span></td></tr>';
		echo '<tr><td valign="top" align="left"><span class="MAD_TIT"><a href="../../principal/admin/usuarios.php?_f_NIVEL=4&_consulta_=si" target="centro" class="MAD_TIT">'.$CLang->Get('USERS').'</a></span></td></tr>';
		echo '<tr><td valign="top" align="left"><span class="MAD_TIT"><a href="../../principal/admin/usuarios.php?_f_NIVEL=1&_consulta_=si" target="centro" class="MAD_TIT">'.$CLang->Get('ADMINISTRATORS').'</a></span></td></tr>';
		echo '<tr><td valign="top" align="left"><span class="MAD_TIT"><a href="../../principal/admin/usuarios.php?_f_NIVEL=2&_consulta_=si" target="centro" class="MAD_TIT">DATA '.$CLang->Get('ADMINISTRATORS').'</a></span></td></tr>';
}	else {
		echo '<tr><td valign="top">Sin permisos suficientes.</td></tr>';
}
		//echo '<tr><td valign="top" align="left"><span class="MAD_TIT"><a href="../../principal/admin/usuarios.php?_f_NIVEL=4&_consulta_=si" target="centro"  class="MAD_TIT">Clients et inscrits à la News Letter</a></span></td></tr>';
		//echo '<tr><td valign="top" align="left"><span class="MAD_TIT"><a href="../../principal/admin/importation.php" target="centro"  class="MAD_TIT">'.$CLang->m_Words['IMPORT'].'</a></span></td></tr>';
		echo '</table>';
		echo '</td></tr></table>';
	}

	function AdministrarArbolSecciones() {
		global $_debug_, $CLang, $_e_ID_SECCION;

		//echo '<base target="centro">';
		$CRoot = $this->Secciones->GetRoot();
		if ($CRoot==null) echo 'err:'.$CRoot;

		$_e_ID_SECCION = $CRoot->m_id;

		echo '<table width="100%" height="100%" border="0" class="MAD_ARB">
				<tr>
					<td align="left" valign="top">';
		echo '<form name="formarbolseccion" action="modificarseccion.php" target="_self" method="POST">';

		if (file_exists('../../inc/include/filtrosadminarbolsecciones.php')) {
			echo '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="MAD_ARB_FILTROS">';
			echo ' <tr>
						<td valign="middle">';
			require('../../inc/include/filtrosadminarbolsecciones.php');
			echo ' 		</td>
					</tr>
					<tr>
						<td class="MAD_ARB_FILTROS_LINE"><img width="10" height="1" src="../../inc/images/spacer.gif" border="0"></td>
					</tr>
				</table>';
		}



		// nodeId | parentNodeId | nodeName | nodeUrl
		$this->MostrarArbol(false,true);

		echo '
			<input name="_e_ID_SECCION" type="hidden"  value="">
			<input name="_f_ID_SECCION" type="hidden"  value="">
			<input name="_f_ID_TIPOSECCION" type="hidden"  value="">
		  	<input name="_e_ID_TIPOSECCION" type="hidden"  value="">
			<input name="_consulta_" type="hidden"  value="si">
			<input name="_accion_" type="hidden"  value="">
			<input name="_seleccion_" type="hidden"  value="">
			<input name="_debug_" type="hidden" value="'.$_debug_.'">
			<input name="_borrar_" type="hidden" value="no">
			<input name="_cancelar_" type="hidden" value="no">
			<input name="_modificar_" type="hidden" value="si">
			<input name="_ordenar_" type="hidden" value="">
			<input name="_nuevo_" type="hidden" value="no">
			<input name="_primario_ID" value="" type="hidden">
			';
		echo '</form>';

		echo '</td></tr></table>';

	}

	function AdministrarUsuarios() {
		global $CLang;
		echo '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="MAD_USR">';
		echo '<tr><td valign="top" align="left"><span class="MAD_TIT">&nbsp;&nbsp;'.$CLang->m_Words['USERS'].'<br></span></td></tr>';
		echo '<tr><td valign="top" align="center"><span class="MAD_TXT"><a href="usuarios.php?_f_NIVEL=1&_consulta_=si" target="centro" class="MAD_TXT">'.$CLang->m_Words['ADMINISTRATORS'].'</a></span></td></tr>';
		//echo '<tr><td valign="top" align="center"><span class="MAD_TXT"><a href="usuarios.php?_f_NIVEL=4&_consulta_=si" target="centro">'.$CLang->m_Words['REGISTEREDUSERS'].'</a></span></td></tr>';
		echo '<tr><td valign="top" align="center"><span class="MAD_TXT"><a href="usuarios.php?_f_NIVEL=4&_consulta_=si" target="centro"  class="MAD_TXT">'.$CLang->m_Words['ADMINUSERS'].'</a></span></td></tr>';
		echo '</table>';
	}


	function Administrar() {
		if ( ($GLOBALS['_ADMIN_TYPE']=='TREE') || ($GLOBALS['_ADMIN_TYPE']=='LAPEL CONTENTTYPE') || ($GLOBALS['_ADMIN_TYPE']=='LAPEL SECTION')) {
			echo '<iframe id="centro" src="'.$GLOBALS['_ADMIN_STARTURL'].'" name="centro" scrolling="auto" height="100%" width="100%" frameborder="0" framespacing="0" name="centro">
						You need iframe support.
					</iframe>';
		} else {
			echo 'NO ADMIN TYPE';
		}
	}

	//------------------------------------------------------
	function ConsultaHeader() {
		global $_ADMIN_TYPE, $_ADMIN_ADMIN, $ramaseccion, $CLang, $_debug_;
		global $_desde_,$_hasta_,$_intervalo_,$_nresultados_,$_nxintervalo_,$_nintervalos_;

		if ( $_ADMIN_TYPE=='TREE' ) {

			$this->HeaderSection( $CLang->m_Words['QUERY'], $ramaseccion );

		} else {
			//one Lapel per each section...
			if ( $_ADMIN_TYPE=='LAPEL SECTION' ) $this->MostrarArbol(true,false);
			if ( $_ADMIN_TYPE=='LAPEL CONTENTTYPE' ) {
				$this->TiposContenidos->m_ttiposcontenidos->LimpiarSQL();
				$this->TiposContenidos->m_ttiposcontenidos->FiltrarSQL('ID','/*SPECIAL*/tiposcontenidos.ID<>'.$GLOBALS['_ID_VOID_TYPE_CARD'].' AND tiposcontenidos.ID<>'.$GLOBALS['_ID_SYSTEM_TYPE_CARD'], '0', "_superior_ID");
				$this->TiposContenidos->m_ttiposcontenidos->Open();
			}

			echo '<br><table width="100%" cellpadding="0" cellspacing="0" border="0" class="MAD_HEADER"><tr>';
				echo '<td><img src="../../inc/images/spacer.gif" width="10" height="29"></td>';
				echo '<td align="left" valign="top">';
					echo '<table cellpadding="0" cellspacing="0" border="0" ><tr>';
						echo '<td>';
						if (isset($_ADMIN_ADMIN) && $_ADMIN_ADMIN=="S" && $this->UsuarioAdmin->m_nivel<2 ) {
							echo '<table cellpadding="0" cellspacing="0" border="0" class="MAD_LAPEL"><tr><td colspan="3" class="MAD_LAPEL_BG_LN"><img src="../../inc/images/spacer.gif" width="7" height="1"></td></tr><tr>';
							echo '<td class="MAD_LAPEL_BG_LN"><img src="../../inc/images/spacer.gif" width="1" height="29"></td>';
							echo '<td class="MAD_LAPEL_BG" id="tdseccionsys" onmouseover="javascript:changeclass(\'tdseccionsys\',\'MAD_LAPEL_BG_SEL\');" onmouseout="javascript:changeclass(\'tdseccionsys\',\'MAD_LAPEL_BG\');" >&nbsp;&nbsp;<a title="consulta" href="../../principal/admin/adminconfiguracion.php"  class="MAD_TIT"><span class="MAD_TIT">'.$CLang->m_Words['ADMINISTRATION'].'</span></a>&nbsp;&nbsp;</td>';
							echo '<td class="MAD_LAPEL_BG_LN"><img src="../../inc/images/spacer.gif" width="1" height="29"></td>';
							echo '</tr></table>';
						}
						echo '</td>';
						echo '<td><img src="../../inc/images/spacer.gif" width="10" height="29"></td>';
						$ri=0;
						if ( $_ADMIN_TYPE=='LAPEL SECTION' ) {
							foreach($this->Secciones->rama as $raiz=>$rama) {
								if ($raiz!="root") {
									foreach($rama as $padre=>$hijo) {
										if ( $hijo->m_id_tiposeccion!=$GLOBALS['_ID_ROOT_TYPE_SECTION'] && $hijo->m_id_tiposeccion!=$GLOBALS['_ID_SYSTEM_TYPE_SECTION']) {
											$ri++;
											//$url = "javascript:consultar(".$hijo->m_id.");";
											$url = "consulta.php?_f_ID_SECCION=".$hijo->m_id."&_f_ID_TIPOSECCION=".$hijo->m_id_tiposeccion."&_consulta_=si";
											echo '<td><table cellpadding="0" cellspacing="0" border="0"  class="MAD_LAPEL"><tr><td colspan="3" class="MAD_LAPEL_BG_LN"><img src="../../inc/images/spacer.gif" width="5" height="1"></td></tr><tr>';
											echo '<td class="MAD_LAPEL_BG_LN"><img src="../../inc/images/spacer.gif" width="1" height="29"></td>';
											echo '<td class="MAD_LAPEL_BG" id="tdseccion'.$ri.'" onmouseover="javascript:changeclass(\'tdseccion'.$ri.'\',\'MAD_LAPEL_BG_SEL\');" onmouseout="javascript:changeclass(\'tdseccion'.$ri.'\',\'MAD_LAPEL_BG\');" >&nbsp;&nbsp;<a title="go" href="'.$url.'"  class="MAD_TIT"><span class="MAD_TIT">';
											echo $hijo->m_nombre;
											echo '</span></a>&nbsp;&nbsp;</td>';
											echo '<td class="MAD_LAPEL_BG_LN"><img src="../../inc/images/spacer.gif" width="1" height="29"></td>';
											echo '</tr></table></td>';
											echo '<td><img src="../../inc/images/spacer.gif" width="10" height="29"></td>';
										}
									}
								}
							}
						} else if ($_ADMIN_TYPE=='LAPEL CONTENTTYPE') {
							if ( $this->TiposContenidos->m_ttiposcontenidos->nresultados>0 ) {
									while($_row_ = $this->TiposContenidos->m_ttiposcontenidos->Fetch($this->TiposContenidos->m_ttiposcontenidos->resultados) ) {
										$tipocontenido = new CTipoContenido( $_row_ );
										$ri++;
										//$url = "javascript:consultartipocontenido(".$tipocontenido->m_id.");";
										$url = "consulta.php?_f_ID_TIPOCONTENIDO=".$tipocontenido->m_id."&_consulta_=si";
										echo '<td><table cellpadding="0" cellspacing="0" border="0"  class="MAD_LAPEL"><tr><td colspan="3" class="MAD_LAPEL_BG_LN"><img src="../../inc/images/spacer.gif" width="5" height="1"></td></tr><tr>';
										echo '<td class="MAD_LAPEL_BG_LN"><img src="../../inc/images/spacer.gif" width="1" height="29"></td>';
										echo '<td class="MAD_LAPEL_BG" id="tdseccion'.$ri.'" onmouseover="javascript:changeclass(\'tdseccion'.$ri.'\',\'MAD_LAPEL_BG_SEL\');" onmouseout="javascript:changeclass(\'tdseccion'.$ri.'\',\'MAD_LAPEL_BG\');" >&nbsp;&nbsp;<a title="go" href="'.$url.'"  class="MAD_TIT"><span class="MAD_TIT">';
										echo $tipocontenido->m_descripcion;
										echo '</span></a>&nbsp;&nbsp;</td>';
										echo '<td class="MAD_LAPEL_BG_LN"><img src="../../inc/images/spacer.gif" width="1" height="29"></td>';
										echo '</tr></table></td>';
										echo '<td><img src="../../inc/images/spacer.gif" width="10" height="29"></td>';
									}
							}

						}
					echo '</tr></table>';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
			echo '	<td colspan="2" height="1" class="MAD_HEADER_BG_LN"><img src="../../inc/images/spacer.gif" width="100" height="1" border="0"></td>';
			echo '</tr></table>';
		}

	}

	//-----------------------------------------------------

	function ConsultaFiltrosEspeciales() {
		if (file_exists('../../inc/include/filtrosespeciales.php')) {
			require('../../inc/include/filtrosespeciales.php');
		}
	}

	//------------------------------------------------------

	function ConsultaFiltros() {
		global $CLang, $_debug_;

		global $_DIR_SITEABS;
		global $_f_ID_TIPOCONTENIDO, $_combo_ESTADOPEDIDO, $_buscartexto_;
		global $_tf_FECHAALTA, $_f_FECHAALTA, $_tf_FECHAALTA2, $_f_FECHAALTA2;
		global $_f_ID_SECCION, $_f_ID_TIPOSECCION, $ramaseccion;
		global $_intervalo_,$_nxintervalo_,$_nresultados_;


		Debug("_f_ID_TIPOSECCION : ".$_f_ID_TIPOSECCION);
		Debug("_f_ID_SECCION : ".$_f_ID_SECCION);

		//Para filtrar automaticamente contenidos del dia de hoy (ensaladasportenas.com.ar)
		//if ($_f_FECHAALTA=='') $_f_FECHAALTA = date("Y-m-d");
		//if ($_f_FECHAALTA2=='') $_f_FECHAALTA2 = date("Y-m-d",strtotime("+1 day"));

		$ramaseccion = $this->Secciones->GetPathSeccion($_f_ID_SECCION,""," > ");

		$this->ConsultaHeader();

		//por ahora para todos los contenidos...
		echo '<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td>';
		echo '<table width="100%" cellspacing="0" cellpadding="4" border="0" class="MAD_CONS">
			<tr>';
		//TABLA FILTROS
		echo '	<td id="tdadd" align="left"  class="MAD_CONS_FILTERS"  valign="middle" nowrap><a href="javascript:nuevo()"><span class="modulo_admin_consulta_nuevaficha">'.$CLang->m_Words['ADDCARD'].'</span><img src="../../inc/images/agregar.gif" align="absmiddle" width="14" height="17" border="0" alt="'.$CLang->m_Words['ADDCARD'].'" hspace="4" onMouseOver="javascript:showimg(\'../../inc/images/agregar_over.gif\');" onMouseOut="javascript:showimg(\'../../inc/images/agregar.gif\');"></a></td>';
		echo '	<td align="left" width="2" class="MAD_CONS_FILTERS" valign="middle" >';
		if (file_exists('../../inc/include/filtrosadmin.php')) {
			require('../../inc/include/filtrosadmin.php');
		}
		echo '	</td>';
		echo '	<td align="right" valign="bottom" width="2" class="MAD_CONS_FILTERS">
				<span class="MAD_CONS_FILTERS">'.$CLang->m_Words['TEXTTOSEARCH'].'<br></span>';
		echo '		<input name="_buscartexto_" type="text" value="'.$_buscartexto_.'" size="20">';
		echo '	</td>';
		$_tf_FECHAALTA = "_superior_FECHAALTA";
		$_tf_FECHAALTA2 = "_inferior_FECHAALTA";
		echo '	<td align="right" valign="middle" valign="bottom"  class="MAD_CONS_FILTERS">';
		echo '		<table border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td valign="bottom" >&nbsp;'.$CLang->m_Words['FROM'].'&nbsp;</td>
							<td valign="bottom" >
								<span class="MAD_CONS_FILTERS">'.$CLang->m_Words['DATESTR'].'</span>
							<br>
								<input id="_f_FECHAALTA" name="_f_FECHAALTA" type="text" size="13" value="'.$_f_FECHAALTA.'">
								<a href="javascript:NewCal(\'_f_FECHAALTA\',\'yyyymmdd\',true,24)"><img src="../../inc/images/cal.gif" width="16" height="16" border="0" alt="Pick a date"  align="absmiddle"></a>
							</td>
							<td valign="bottom">&nbsp;'.$CLang->m_Words['TO'].'&nbsp;</td>
							<td valign="bottom" >
								<span class="MAD_CONS_FILTERS">'.$CLang->m_Words['DATESTR'].'</span>
								<br>
								<input id="_f_FECHAALTA2" name="_f_FECHAALTA2" type="text" size="13" value="'.$_f_FECHAALTA2.'"></span>
								<a href="javascript:NewCal(\'_f_FECHAALTA2\',\'yyyymmdd\',true,24)"><img src="../../inc/images/cal.gif" width="16" height="16" border="0" alt="Pick a date" align="absmiddle"></a>
							</td>
						</tr>
					</table>
				</td>
				<td id="tdsearch" valign="middle" class="MAD_CONS_FILTERS">&nbsp;&nbsp;
					<a href="javascript:consultar();" class="MAD_CONS_FILTERS">'.$CLang->m_Words['SEARCH'].'<img src="'.$_DIR_SITEABS.'/inc/images/search_16.png" align="absmiddle" border="0" alt="'.$CLang->m_Words['ADDCARD'].'" hspace="4"></a>&nbsp;&nbsp;
				</td>
			</tr>
			</table>';
		echo '</td>
			</tr>
			<tr>
				<td width="1" class="MAD_HEADER_BG_LN"><img src="../../inc/images/spacer.gif" width="100" height="1" border="0"></td>
			</tr>
			<tr>
				<td>';
		$this->ConsultaFiltrosEspeciales();
		echo '</td>
			</tr>
			</table><br>';

		$this->Secciones->m_tsecciones->FiltrarCampo('ID_TIPOSECCION','','escondido');

		echo '
			<input name="_consulta_" type="hidden"  value="si">
			<input name="_accion_" type="hidden"  value="">
			<input name="_seleccion_" type="hidden"  value="">
			<input name="_debug_" type="hidden" value="'.$_debug_.'">
			<input name="_ordenar_" type="hidden" value="">
			<input name="_borrar_" type="hidden" value="no">
			<input name="_cancelar_" type="hidden" value="no">
			<input name="_modificar_" type="hidden" value="si">
			<input name="_nuevo_" type="hidden" value="no">
			<input name="_primario_ID" value="" type="hidden">
		 	<input name="_intervalo_" type="hidden" value="'.$_intervalo_.'">
			<input name="_nxintervalo_" type="hidden" value="'.$_nxintervalo_.'">
			<input name="_nresultados_" type="hidden" value="'.$_nresultados_.'">
			<input name="_rd_" type="hidden"  value="'.rand().'">
			';

	}

	//-----------------------------------------------------

	function ConsultaDetalles() {

		global $_f_ID_TIPOCONTENIDO;


	}

	//------------------------------------------------------

	function ConsultaResultados() {
		//campos generales
		global $_f_ID_TIPOCONTENIDO, $_consulta_, $_buscartexto_, $_orden_, $_debug_;
		global $CLang, $_f_ID_SECCION;

		//campos de filtros customizados
		//global $_combo_ESTADOPEDIDO;
		//global $_fcombo_EMPRESA;
		global $_f_FECHAALTA, $_f_FECHAALTA2;
		global $_desde_,$_hasta_,$_intervalo_,$_nresultados_,$_nxintervalo_,$_nintervalos_;

		$this->SetTemplates();

		//FILTRADO

		if ($_consulta_=='si') {
			//$this->Contenidos->m_tcontenidos->debug = 'si';


			$this->ConsultaDetalles();

			$this->Contenidos->m_tcontenidos->LimpiarSQL();

			if ( $this->AdminPermisos["ROL_USER_ACCESS"]==false ) {
				$this->Contenidos->m_tcontenidos->FiltrarSQL('ID_USUARIO_CREADOR','', $this->UsuarioAdmin->m_id);
			}


			//TEXTO
			if ($_buscartexto_!="") {
				$_buscartexto_x_ = str_replace(" ","%",$_buscartexto_);
				$filtrotexto = "/*SPECIAL*/ (contenidos.TITULO LIKE '%".$_buscartexto_x_."%'";
				$filtrotexto.= " OR contenidos.COPETE LIKE '%".$_buscartexto_x_."%'";
				$filtrotexto.= " OR contenidos.CUERPO LIKE '%".$_buscartexto_x_."%'";
				$filtrotexto.=")";
				$this->Contenidos->m_tcontenidos->FiltrarSQL('ID',$filtrotexto,'0','_superior_ID');
			}

			//FECHAS

			if ($_f_FECHAALTA!='' && $_f_FECHAALTA2=='') {
				$this->Contenidos->m_tcontenidos->FiltrarSQL('FECHAALTA');
			} else if ($_f_FECHAALTA=='' && $_f_FECHAALTA2!='') {
				$_f_FECHAALTA = date("Y-m-d",strtotime("-1800 day"));
				$this->Contenidos->m_tcontenidos->FiltrarSQL('FECHAALTA','/*SPECIAL*/ contenidos.FECHAALTA < \''.$_f_FECHAALTA2.'\'');
			} else if ($_f_FECHAALTA2!='' && $_f_FECHAALTA!='') {
				$this->Contenidos->m_tcontenidos->FiltrarSQL('FECHAALTA','/*SPECIAL*/ contenidos.FECHAALTA < \''.$_f_FECHAALTA2.'\'');
			}


			if ($_f_ID_SECCION!='') {
				$this->Contenidos->m_tcontenidos->FiltrarSQL('ID_SECCION');
			}
			if ($_f_ID_TIPOCONTENIDO!='') {
				$this->Contenidos->m_tcontenidos->FiltrarSQL('ID_TIPOCONTENIDO');
			}
			if ($_orden_=='') {
				//seleccionamos el primer indice definido para el usuario
				foreach($this->Contenidos->m_tcontenidos->indices as $indice) {
					$_orden_= $indice['indice'];
					break;
				}
			}
			$this->Contenidos->m_tcontenidos->OrdenSQL($_orden_);
			if (!isset($GLOBALS['_default_nxintervalo_'])) $GLOBALS['_default_nxintervalo_'] = 'max';
			if ($_nxintervalo_=='') $_nxintervalo_ = $GLOBALS['_default_nxintervalo_'];
			if ($_intervalo_=='') $_intervalo_ = 1;

			//RESULTADOS
			if ( $_nresultados_=='' || !isset($_nresultados_)) {
				$this->Contenidos->m_tcontenidos->Count();
				$_nresultados_ = $this->Contenidos->m_tcontenidos->nresultados;
				if ($_nxintervalo_=='max') $nint=$_nresultados_;
				else $nint=$_nxintervalo_;
			}

			if ($_nresultados_>0) {
				$_desde_ = ($_intervalo_ - 1) * $nint + 1;
				$_hasta_ = min( ($_desde_+$nint-1) , $_nresultados_);
				$_nintervalos_ = ceil( $_nresultados_ / $nint);
				$this->Contenidos->m_tcontenidos->LimiteSQL( ($_desde_-1), $nint );
				$this->Contenidos->m_tcontenidos->Open();
			}

		}

		Debug( $this->Contenidos->m_tcontenidos->SQL );

		if (is_object($this->UsuarioAdmin)) {
			if ($this->UsuarioAdmin->m_nick=="cg_admin") {
				echo '<div id="boton_showsql"><span style="display: block; padding: 2px; background-color: #DDD; font-weight: bold; border: solid 1px #000;" onclick="javascript:togglediv(\'showsql\');">MOSTRAR SQL</span>';
				echo '<div id="showsql" style="display: none; position: relative;float: left; width: 100%; height: auto;"><textarea rows="12">';
				echo $this->Contenidos->m_tcontenidos->SQL;
				echo '</textarea></div>';
				echo '</div>';
			}
		}

		//RESULTADOS

		$this->ConsultaResultadosHeader();

		$this->Contenidos->MostrarResultadoConsulta();

		$this->ConsultaResultadosFooter();

	}

	//-----------------------------------------------------
	function ConsultaResultadosHeader($_tabla_="",$jsintervalcall="") {
		global $_consulta_;
		global $_desde_,$_hasta_,$_intervalo_,$_nresultados_,$_nxintervalo_,$_nintervalos_;
		global $seccionencabezado;

		global $_orden_;
		global $CLang;

		if ($_tabla_=="") $_tabla_ = $GLOBALS['tabla'];
		if ($jsintervalcall=="") $jsintervalcall = 'consultarintervalo';


		echo '<table width="100%" cellspacing="0" cellpadding="0"><tr><td align="center">';

		if (($_consulta_=='si') || ($_nresultados_>0)) {
			echo '<table cellpadding="0" cellspacing="0" width="100%" class="MAD_CONS_RES">';
			echo '<tr><td align="left" class="MAD_CONS_RES_HD">';
			if ($_nresultados_>0) {
				echo '<span class="MAD_CONS_RES">'.$_desde_.'&nbsp;'.$CLang->m_Words['TO'].'&nbsp;'.$_hasta_.'&nbsp;&nbsp;&nbsp;'.$CLang->m_Words['OF'].'&nbsp;'.$_nresultados_.'&nbsp;'.$CLang->m_Words['RESULTS'].'</span>';
				echo '</td>';
				echo '<td align="right" class="MAD_CONS_RES">';
				echo $CLang->m_Words['ITEMSPERPAGE'].'&nbsp;&nbsp;';
				echo '<select name="selectnxintervalo" onchange="javascript:'.$jsintervalcall.'(1)">';
				echo '<option value="10" '.($_nxintervalo_==10? "selected" : "").'>10</option>
					<option value="20" '.($_nxintervalo_==20? "selected" : "").'>20</option>
					<option value="30" '.($_nxintervalo_==30? "selected" : "").'>30</option>
					<option value="50" '.($_nxintervalo_==50? "selected" : "").'>50</option>
					<option value="100" '.($_nxintervalo_==100? "selected" : "").'>100</option>
					<option value="500" '.($_nxintervalo_==500? "selected" : "").'>500</option>
					<option value="1000" '.($_nxintervalo_==1000? "selected" : "").'>1000</option>
					<option value="2000" '.($_nxintervalo_==2000? "selected" : "").'>2000</option>
					<option value="max" '.(($_nxintervalo_=='max' || $_nxintervalo_==$_nresultados_)? "selected" : "").'>'.$CLang->m_Words['ALL'].'</option></select>';
				echo '&nbsp;&nbsp;'.$CLang->Get('ORDERBY').'&nbsp;';
				$_tabla_->Ordenar($_orden_);
			} else {
				echo '<span class="MAD_CONS_RES_NO">'.$CLang->m_Words['NORESULTS'].'</span>';
			}
			echo '</td></tr></table>';
		}
	}

	//-----------------------------------------------------

	function ConsultaResultadosFooter($_tabla_="",$jsintervalcall="") {
		global $_consulta_;
		global $_desde_,$_hasta_,$_intervalo_,$_nresultados_,$_nxintervalo_,$_nintervalos_;
		global $seccionencabezado;
		global $CLang;

		if ($_tabla_=="") $_tabla_ = $GLOBALS['tabla'];
		if ($jsintervalcall=="") $jsintervalcall = 'consultarintervalo';

		if (($_consulta_=='si') || ($_nresultados_>0)) {

		echo	'<div class="resultados">
					<div class="selectall selector"><input id="idselectall" name="selectall"  type="checkbox" onclick="javascript:seleccionartodo(\'idselectall\');">'.$CLang->m_Words["SELECTALL"].'</div>';

		if (file_exists('../../inc/include/funcionesespeciales.php')) require('../../inc/include/funcionesespeciales.php');
		/*
		echo '		<div class="deleteall"><a href="javascript:borrarseleccion(\''.$_tabla_->nombre.'\');">'.$CLang->m_Words["DELETEALL"].'<img src="../../inc/images/borrar.gif" alt="X" border="0"></a></div>
				</div>';
		*/

		//PAGES
			echo '<br><table border="0" cellpadding="0" cellspacing="0" width="90%"  class="MAD_CONS_RES">';
			echo '<tr><td align="center"  class="MAD_CONS_RES"><span class="MAD_CONS_RES">'.$CLang->m_Words['RESULTSPAGES'].':</span>';

			if ($_intervalo_>1)
				echo '<a href="javascript:'.$jsintervalcall.'('.($_intervalo_-1).');" class="MAD_CONS_RES">'.$CLang->m_Words['PREVIOUS'].'</a><span class="MAD_CONS_RES"> </span>';

			for($e=max(1,$_intervalo_-20);$e<=min($_nintervalos_,$_intervalo_+20);$e++) {
				if ($e==$_intervalo_)
					echo '<span class="MAD_CONS_RES">  '.$e.'&nbsp;&nbsp;</span>';
				else
					echo '<span class="MAD_CONS_RES">  </span><a href="javascript:'.$jsintervalcall.'('.($e).');" class="MAD_CONS_RES">'.$e.'</a><span class="MAD_CONS_RES">  </span>';
			}

			if ($_intervalo_<$_nintervalos_)
				echo '<span class="MAD_CONS_RES">  </span>
				<a href="javascript:'.$jsintervalcall.'('.($_intervalo_+1).');" class="MAD_CONS_RES">'.$CLang->m_Words['NEXT'].'</a>';

			echo'</td></tr>';
			echo '</table>';
		}
		echo '</td></tr></table>';

	}

	//-----------------------------------------------------

	function EditarUsuario() {
		global $_debug_, $CLang, $_f_ID_TIPOCONTENIDO, $_f_NIVEL;
		global $_accion_, $_primario_ID, $_modificar_, $_borrar_, $_nuevo_;
		global $_intervalo_,$_nresultados_,$_nxintervalo_,$_nintervalos_;
		global $_e_ID_USUARIO_CREADOR, $_e_NIVEL;

		$permission = false;

		//EDICION
		if ($_modificar_=='si') {

			//$this->Usuarios->campos['NICK']['editable'] = 'no';
			//$this->Usuarios->campos['PASSWORD']['editable'] = 'no';
			$this->Usuarios->m_tusuarios->Edicion($_primario_ID);
			if ($_f_NIVEL==4) $_seccion_ = ' > '.$CLang->m_Words['MODIFYINGUSER'];
			if ($_f_NIVEL==1  || $_f_NIVEL==2) $_seccion_ = ' > '.$CLang->m_Words['MODIFYINGADMIN'];
			//if ( $_SESSION['idusuario']==$_e_ID_USUARIO_CREADOR || $_SESSION['nivel']==0) $permission = true;
			$permission = true;

		} else if ($_borrar_=='si') {

			$this->Usuarios->m_tusuarios->Edicion($_primario_ID);
			if ($_f_NIVEL==4) $_seccion_ = ' > '.$CLang->m_Words['DELETINGUSER'];
			if ($_f_NIVEL==1  || $_f_NIVEL==2) $_seccion_ = ' > '.$CLang->m_Words['DELETINGADMIN'];
			//if ( $_SESSION['idusuario']==$_e_ID_USUARIO_CREADOR || $_SESSION['nivel']==0) $permission = true;
			$permission = true;

		} else if ($_nuevo_=='si') {

			$_e_ID_USUARIO_CREADOR = $_SESSION['idusuario'];
			$this->Usuarios->m_tusuarios->Nuevo();
			if ($_f_NIVEL==4) $_seccion_ = ' > '.$CLang->m_Words['ADDINGUSER'];
			if ($_f_NIVEL==1 || $_f_NIVEL==2) $_seccion_ = ' > '.$CLang->m_Words['ADDINGADMIN'];
			$_e_NIVEL = $_f_NIVEL;
			$permission = true;

		}

		$ramaseccion = "&nbsp;";

		//Imprimimos la seccion:
		$this->HeaderSection( $_seccion_,  $ramaseccion );


		echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="MAD_EDIT"><tr><td>';

		$this->ConfirmRibbon('confirmar()',$permission);

		if ($_borrar_=='si' && $permission) {
			echo '<span class="MAD_WARNING">'.$CLang->m_Words['RECORD_DELETION_WARNING'].'</span>';
		}

		echo '
						<!--CAMPOS-->
			<form name="confirmar" method="post" action="confirmarusuario.php">';
		$this->Usuarios->SetTemplateEdicion( $_e_NIVEL );

		$editstr = $this->Usuarios->Edit( $_e_NIVEL, $CLang);
		echo $editstr;

		$this->ConfirmRibbon('confirmar()',$permission);

		if ( strpos( $editstr, "_e_NIVEL" ) === false ) {
			echo '<input name="_e_NIVEL" type="hidden" value="'.$_e_NIVEL.'">';
		}

		echo '
			<input name="_f_NIVEL" type="hidden" value="'.$_f_NIVEL.'">
			<input name="_f_ID_TIPOCONTENIDO" type="hidden" value="'.$_f_ID_TIPOCONTENIDO.'">
			<input name="_primario_ID" type="hidden" value="'.$_primario_ID.'">
			<input name="_modificar_" type="hidden" value="'.$_modificar_.'">
			<input name="_borrar_" type="hidden" value="'.$_borrar_.'">
			<input name="_cancelar_" type="hidden" value="no">
			<input name="_nuevo_" type="hidden" value="'.$_nuevo_.'">
			<input name="_admin_" type="hidden" value="'.$_admin_.'">
			<input name="_debug_" type="hidden" value="'.$_debug_.'">
			<input name="_intervalo_" type="hidden" value="'.$_intervalo_.'">
			<input name="_nxintervalo_" type="hidden" value="'.$_nxintervalo_.'">
			<input name="_nresultados_" type="hidden" value="'.$_nresultados_.'">
		</form>
		';

		echo '</td></tr></table>';

	}

	//------------------------------------------------------

	function ConfirmarUsuario() {

		global $CLang;
		global $_accion_, $_primario_ID, $_modificar_, $_borrar_, $_nuevo_, $_cancelar_, $_debug_;
		global $_seleccion_, $_error_, $_errores_, $_afectados_, $_admin_, $_onload_;
		global $camposmod, $_e_NIVEL;


		if (($_e_NIVEL != 0 && $_e_NIVEL != 1 && $_e_NIVEL != 2) && ($_e_NIVEL !=4)) $_e_NIVEL = 4;


		$_exito_ = true;

		//$_debug_ = 'si';
		//ACCIONES

		if ($_accion_=="borrarseleccion") {//BORRAR SELECCION

			//en el campo
			$SELS = split(",",$_seleccion_);
			foreach($SELS as $k) {
				$_primario_ID = $k;
				$_exito_ = $this->Usuarios->Eliminar($_primario_ID);
				//borrar todos los logs relacionados???
			}

		} else {//SINO MODO NORMAL


		if ( ($_cancelar_ == 'no')  && ($_borrar_=='no')) $_error_ = $this->Usuarios->m_tusuarios->Verificar();//verifica y completa un valor: $errores , y listo

		if (!$_error_) {

			if ($_borrar_=='si') {

				$_exito_ = $this->Usuarios->Eliminar($_primario_ID);
				if (!$_exito_) {
					ShowError( $this->Usuarios->PopAllErrorsFullStr());
				}

			} elseif ($_nuevo_=='si') {

				global $_e_PASSMD5;
				global $_e_PASSKEY;
				global $_e_PASSWORD;
				global $_e_PASSWORD_confirm;

				if ($_e_PASSWORD!="" && ($_e_PASSWORD==$_e_PASSWORD_confirm)) {
					$_e_PASSMD5 = md5($_e_PASSWORD);
					$_e_PASSKEY = $this->Usuarios->Crypt( $_e_PASSWORD );
				}

				$_exito_ = $this->Usuarios->m_tusuarios->Insertar();

				$_primario_ID = mysql_insert_id($this->Usuarios->m_tusuarios->CONN);

			} elseif ($_modificar_=='si') {

				global $_e_PASSMD5;
				global $_e_PASSKEY;
				global $_e_PASSWORD;
				global $_e_PASSWORD_confirm;
				if ($_e_PASSWORD!="" && ($_e_PASSWORD==$_e_PASSWORD_confirm)) {
					$_e_PASSMD5 = md5($_e_PASSWORD);
					$_e_PASSKEY = $this->Usuarios->Crypt( $_e_PASSWORD );
				} else {
					$this->Usuarios->m_tusuarios->campos['PASSWORD']['editable'] = 'no';
					$this->Usuarios->m_tusuarios->campos['PASSMD5']['editable'] = 'no';
					$this->Usuarios->m_tusuarios->campos['PASSKEY']['editable'] = 'no';
				}
				//$_exito_ = $this->Usuarios->m_tusuarios->Modificar();
				$Usuario = new CUsuario();
				if (!is_numeric($Usuario->m_id_contenido)) $Usuario->m_id_contenido = 0;
				//echo "pass:".$Usuario->m_password." idc:".$Usuario->m_id_contenido;
				$_exito_ = $this->Usuarios->ActualizarUsuario( $Usuario, $Usuario->m_password );
				//echo "exito:".$_exito_;
				if (!$_exito_) {
					ShowError( $this->Usuarios->PopAllErrorsFullStr());
				}
			} elseif ($_cancelar_=='si') {
				$_exito_ = true;
				$this->Usuarios->m_tusuarios->exito = $CLang->m_Words['CANCELLED'];
			} else {
				$_exito_ = false;
				$this->Usuarios->m_tusuarios->exito = "ERROR: no se definió ninguna acción";
			}

		}

		}//FIN BORRAR SELECCION

		//$_debug_ = 'si';

		if (!$_error_) {

			if (($_exito_) and ($_debug_!='si') && !DebugOn()) {
				if ($_admin_!='si') $_onload_="javascript:document.consultar.submit();";
				else $_onload_="javascript:admin();";
			}

			if ($_exito_) {
				$this->Usuarios->exito = '<span class="navegador1">'.$this->Usuarios->exito.'</span>';
			} else {
				$this->Usuarios->m_tusuarios->exito = '<span class="error">'.$this->Usuarios->exito.'</span>';
				$this->Usuarios->m_tusuarios->exito.= '<br><a href="javascript:document.consultar.submit();">OK</a>';
			}
		} else {
			$_errores_ = $this->Usuarios->m_tusuarios->ImprimirErrores($camposmod);
			$this->Usuarios->m_tusuarios->exito = '<a href="javascript:document.consultar.submit();">OK</a>';
		}

	}
	//----

	function ConfirmarUsuarioResultado() {

		global $_errores_, $_errorimg_;
		global $_orden_, $_debug_, $_exito_, $_error_;
		global $_f_NIVEL;
		global $_e_NIVEL;

		$_f_NIVEL = $_e_NIVEL;

		echo $_errores_;
		echo $this->Usuarios->m_tusuarios->exito;
		echo '<br><br>
		<form name="consultar" method="post" action="usuarios.php">
		<div style="position:absolute;display:none;">';
		echo '
		</div>
		<input name="_f_NIVEL" type="hidden"  value="'.$_f_NIVEL.'">
		<input name="_consulta_" type="hidden"  value="si">
		<input name="_debug_" type="hidden" value="'.$_debug_.'">
		<input name="_admin_" type="hidden" value="'.$_admin_.'">
		</form>	';

	}

	//------------------------------------------------------

	function Editar() {
		global $_accion_, $_seleccion_, $_modificar_, $_borrar_, $_nuevo_, $_seccion_;
		global $_admin_, $_orden_, $_debug_, $CLang, $CMultiLang;
		global $_intervalo_,$_nresultados_,$_nxintervalo_,$_nintervalos_;

		global $_usuariosecciones_;
		global $_primario_ID;
		global $_f_ID_SECCION;
		global $_f_ID_TIPOCONTENIDO;
		global $_f_ID_TIPOSECCION;

		global $_e_ID_TIPOCONTENIDO;
		global $_e_ID_USUARIO_CREADOR;
		global $_e_ID_USUARIO_MODIFICADOR;
		global $_e_ID_SECCION;
		global $_e_ORDEN;

		global $_buscartexto_;
		global $_f_FECHAALTA;
		global $_tf_FECHAALTA;
		global $_f_FECHAALTA2;
		global $_fcombo_EMPRESA;
		global $_combo_ESTADOPEDIDO;


		$permission = false;
		//EDICION
		//aca deberia buscar el id correspondiente a este contenido pero: del idioma correspondiente...
		if ($_modificar_=='si') {
			$this->Contenidos->m_tcontenidos->Edicion($_primario_ID);
			if (!is_numeric($_e_ID_CONTENIDO)) $_e_ID_CONTENIDO = 1;
			$CEdit = new CContenido();
			$CEdit->m_id = $_primario_ID;
			$_accion_ = "modificar";
			$_seccion_ = ' > '.$CLang->m_Words['MODIFYINGCARD'];
			//if ( $_SESSION['idusuario']==$_e_ID_USUARIO_CREADOR || $_SESSION['nivel']==0) $permission = true;
			//if ($_e_ID_TIPOCONTENIDO==FICHA_SISTEMA && $_SESSION['nivel']<=1)
			$permission = true;
		} else if ($_borrar_=='si') {
			$this->Contenidos->m_tcontenidos->Edicion($_primario_ID);
			$CEdit = new CContenido();
			$CEdit->m_id = $_primario_ID;
			$_accion_ = "borrar";
			$_e_ID_USUARIO_MODIFICADOR = $_SESSION['idusuario'];
			$_seccion_ = ' > '.$CLang->m_Words['DELETINGCARD'];
			//if ( $_SESSION['idusuario']==$_e_ID_USUARIO_CREADOR || $_SESSION['nivel']==0)
			$permission = true;
		} else if ($_nuevo_=='si') {
			$this->Contenidos->m_tcontenidos->Nuevo();
			if (!is_numeric($_e_ID_CONTENIDO)) $_e_ID_CONTENIDO = 1;
			$CEdit = new CContenido();
			$_accion_ = "nuevo";
			if ($_primario_ID>0) $this->Contenidos->m_tcontenidos->Edicion($_primario_ID);
			if ($_f_ID_TIPOCONTENIDO!="") $_e_ID_TIPOCONTENIDO = $_f_ID_TIPOCONTENIDO;
			if ($_f_ID_SECCION!="") $_e_ID_SECCION = $_f_ID_SECCION;
			$_e_ID_USUARIO_CREADOR = $_SESSION['idusuario'];
			$_e_ID_USUARIO_MODIFICADOR = $_SESSION['idusuario'];
			$_seccion_ = ' > '.$CLang->m_Words['ADDINGCARD'];
			$permission = true;
		}


		$ramaseccion = $this->Secciones->GetPathSeccion($_e_ID_SECCION,""," > ")." >>>> TIPO: ".$this->TiposContenidos->GetTipo($_e_ID_TIPOCONTENIDO)."";
		$CSec = $this->Secciones->GetSeccion($_e_ID_SECCION);
		$_f_ID_TIPOSECCION = $CSec->m_id_tiposeccion;
		$this->HeaderSection( $_seccion_, $ramaseccion);

		echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="MAD_EDIT"><tr><td>';

		if ($_borrar_=='si' && $permission) {
					echo '<span class="MAD_EDIT_WARNING">'.$CLang->m_Messages['RECORD_DELETION_WARNING'].'<br></span>';
		}

		echo '<!--CAMPOS-->
			<form name="confirmar" id="confirmar" method="post"  enctype="multipart/form-data" action="confirmar.php">';


		if ($CMultiLang->Activo()) {
			echo $CMultiLang->Banderas();
		}

		$this->ConfirmRibbon('confirmar()',$permission);

		$this->SetTemplates( $_e_ID_TIPOCONTENIDO );

		$resstr = $this->Contenidos->Edit( $_e_ID_TIPOCONTENIDO, $CLang, $CMultiLang );

		if (strpos( $resstr, "_e_ID_SECCION") === false ) {
			$idseccion_campo = true;
		} else $idseccion_campo = false;

		if (strpos( $resstr, "_e_ID_CONTENIDO") === false ) {
			$id_contenido_campo = true;
		} else $id_contenido_campo = false;


		$resstr = $this->TiposContenidos->EditarDetalles( $CEdit, $_accion_, $resstr );

		echo $resstr;

		echo '
		<input name="_e_ID_USUARIO_CREADOR" type="hidden" value="'.$_e_ID_USUARIO_CREADOR.'">
		<input name="_e_ID_USUARIO_MODIFICADOR" type="hidden" value="'.$_e_ID_USUARIO_MODIFICADOR.'">
		<input name="_e_ID_TIPOCONTENIDO" type="hidden" value="'.$_e_ID_TIPOCONTENIDO.'">';

		if ($idseccion_campo) echo '<input name="_e_ID_SECCION" type="hidden" value="'.$_e_ID_SECCION.'">';
		if ($id_contenido_campo) echo '<input name="_e_ID_CONTENIDO" type="hidden" value="'.$_e_ID_CONTENIDO.'">';

		echo '<input name="_e_PRINCIPAL" type="hidden" value="N">
		<input name="_e_ORDEN" type="hidden" value="'.$_e_ORDEN.'">
		';

		$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
		$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_SECCION','','escondido');
		//$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_SECCION','/*SPECIAL*/secciones.ID IN ('.$_usuariosecciones_.')','escondido');
		$this->Secciones->m_tsecciones->FiltrarCampo('ID_TIPOSECCION','','escondido');
		$this->Contenidos->m_tcontenidos->Ordenar($_orden_,'escondido');

		$this->ConfirmRibbon('confirmar()',$permission);

		echo '
		<input name="_primario_ID" type="hidden" value="'.$_primario_ID.'">
		<input name="_modificar_" type="hidden" value="'.$_modificar_.'">
		<input name="_borrar_" type="hidden" value="'.$_borrar_.'">
		<input name="_cancelar_" type="hidden" value="no">
		<input name="_nuevo_" type="hidden" value="'.$_nuevo_.'">
		<input name="_admin_" type="hidden" value="'.$_admin_.'">
		<input name="_debug_" type="hidden" value="'.$_debug_.'">
		<input name="_buscartexto_" type="hidden"  value="'.$_buscartexto_.'">
		<input name="_f_FECHAALTA" type="hidden"  value="'.$_f_FECHAALTA.'">
		<input name="_tf_FECHAALTA" type="hidden"  value="'.$_tf_FECHAALTA.'">
		<input name="_f_FECHAALTA2" type="hidden"  value="'.$_f_FECHAALTA2.'">
		<input name="_intervalo_" type="hidden" value="'.$_intervalo_.'">
		<input name="_nxintervalo_" type="hidden" value="'.$_nxintervalo_.'">
		<input name="_nresultados_" type="hidden" value="'.$_nresultados_.'">
		</form>
		';

		echo '</td></tr></table>';

		$CMultiLang->Esconder();

	}


	//-----------------------------------------------------
	function EditarSeccion() {
		global $CLang, $_accion_, $_seleccion_, $_modificar_, $_ordenar_, $_borrar_, $_nuevo_, $_seccion_, $_admin_;
		global $_orden_, $_debug_, $CMultiLang;

		global $_usuariosecciones_, $_primario_ID, $_f_ID_SECCION, $_f_ID_TIPOSECCION;

		global $_e_ID_TIPOSECCION, $_e_ID_USUARIO_CREADOR, $_e_ID_SECCION, $_e_ID_CONTENIDO, $_e_PROFUNDIDAD;
		global $_e_RAMA, $_e_ORDEN, $_e_NOMBRE, $_e_ML_NOMBRE, $_e_DESCRIPCION, $_e_ML_DESCRIPCION;

		$permission = false;
		//EDICION
		if ($_modificar_=='si' || $_borrar_=='si' || $_ordenar_=='up' || $_ordenar_=='down') {
			$this->Secciones->m_tsecciones->Edicion($_primario_ID);
			//if ( $_SESSION['idusuario']==$_e_ID_USUARIO_CREADOR || $_SESSION['nivel']==0) $permission = true;
			$permission = true;
		} else if ($_nuevo_=='si') {
			$_e_ID_USUARIO_CREADOR = $_SESSION['idusuario'];
			$this->Secciones->m_tsecciones->Nuevo();
			$permission = true;
		}

		$this->TiposSecciones->SetTemplateEdicion( $_e_ID_TIPOSECCION );

		echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="MAD_EDIT"><tr><td>';

		if ($_borrar_=='si' && $permission) {
					echo '<span class="MAD_EDIT_WARNING">'.$CLang->m_Messages['RECORD_DELETION_WARNING'].'</span>';
		}

		if ($CMultiLang->Activo()) {
			echo $CMultiLang->Banderas();
		}

		echo '<form name="confirmar" method="post" action="confirmarseccion.php">';

		if ($_ordenar_=='') {

			$resstr = $this->Secciones->Edit( $_e_ID_TIPOSECCION, $CLang, $CMultiLang );

			echo $resstr;

			$this->ConfirmRibbon('confirmarseccion()',$permission);

		} else {
			echo '<input name="_e_NOMBRE" type="hidden" value="'.$_e_NOMBRE.'">';
			echo '<input name="_e_ML_NOMBRE" type="hidden" value="'.$_e_ML_NOMBRE.'">';
			echo '<input name="_e_DESCRIPCION" type="hidden" value="'.$_e_DESCRIPCION.'">';
			echo '<input name="_e_ML_DESCRIPCION" type="hidden" value="'.$_e_ML_DESCRIPCION.'">';
		}

		if (strpos( $resstr, "_e_ID_SECCION") === false ) {
			$idseccion_campo = true;
		} else $idseccion_campo = false;

		if (strpos( $resstr, "_e_RAMA") === false ) {
			$rama_campo = true;
		} else $rama_campo = false;

		echo '<input name="_e_ID_USUARIO_CREADOR" type="hidden" value="'.$_e_ID_USUARIO_CREADOR.'">';
		echo '<input name="_e_ID_TIPOSECCION" type="hidden" value="'.$_e_ID_TIPOSECCION.'">';
		if ($idseccion_campo) echo '<input name="_e_ID_SECCION" type="hidden" value="'.$_e_ID_SECCION.'">';
		echo '<input name="_e_ID_CONTENIDO" type="hidden" value="'.$_e_ID_CONTENIDO.'">';
		echo '<input name="_e_PROFUNDIDAD" type="hidden" value="'.$_e_PROFUNDIDAD.'">';
		if ($rama_campo) echo '<input name="_e_RAMA" type="hidden" value="'.$_e_RAMA.'">';
		echo '<input name="_e_ORDEN" type="hidden" value="'.$_e_ORDEN.'">';


		echo '
		<input name="_primario_ID" type="hidden" value="'.$_primario_ID.'">
		<input name="_ordenar_" type="hidden" value="'.$_ordenar_.'">
		<input name="_modificar_" type="hidden" value="'.$_modificar_.'">
		<input name="_borrar_" type="hidden" value="'.$_borrar_.'">
		<input name="_cancelar_" type="hidden" value="no">
		<input name="_nuevo_" type="hidden" value="'.$_nuevo_.'">
		<input name="_admin_" type="hidden" value="'.$_admin_.'">
		<input name="_debug_" type="hidden" value="'.$_debug_.'">
		</form>
		';

		echo '</td></tr></table>';

		if ($_ordenar_!='') {
			echo '<script> confirmarseccion(); </script>';

		}

		$CMultiLang->Esconder();

	}

	//-----------------------------------------------------
	function Confirmar() {
		global $CLang;
		global $_accion_;
		global $_seleccion_;

		global $_fields_error_;
		global $_errores_;

		global $_afectados_;
		global $_debug_;
		global $_nuevo_;
		global $_modificar_;
		global $_borrar_;
		global $_cancelar_;
		global $_ordenar_;


		global $_primario_ID;
		global $_e_ORDEN;
		global $_e_ID_SECCION;
		global $_f_ID_TIPOSECCION;
		global $_f_ID_TIPOCONTENIDO;
		global $_admin_;
		global $_onload_;
		global $camposmod;
		global $_exito_;

		Debug("CAdmin::Confirmar()");

		$_exito_ = true;
		$_fields_error_ = false;
		//$_debug_ = 'si';

		if ($_accion_=="borrarseleccion") {//BORRAR SELECCION
			//en el campo

			Debug("CAdmin::Confirmar(): _accion_: ".$_accion_." _primario_ID:".$_primario_ID." f_idtipocont : ".$_f_ID_TIPOCONTENIDO);
			$SELS = split(",",$_seleccion_);

			foreach($SELS as $k) {
				$_primario_ID = $k;
				$CEdit = $this->Contenidos->GetContenido($_primario_ID);
				$_exito_ = $this->Contenidos->Eliminar($_primario_ID, true, true, false); //no reordenamos
			}

			$this->Contenidos->OrdenarContenido( $_e_ID_SECCION , 0, "", $_f_ID_TIPOCONTENIDO );

		} elseif ($_accion_=="deshabilitar") {
			$SELS = split(",",$_seleccion_);
			foreach($SELS as $k) {
				$_primario_ID = $k;
				$_exito_ = $this->Contenidos->Deshabilitar($_primario_ID);
			}

		} elseif ($_accion_=="habilitar") {
			$SELS = split(",",$_seleccion_);
			foreach($SELS as $k) {
				$_primario_ID = $k;
				$_exito_ = $this->Contenidos->Habilitar($_primario_ID);
			}

		} elseif ($_accion_!="") {
			if (file_exists('../../inc/include/funcionesespecialesconfirmar.php')) {
				require('../../inc/include/funcionesespecialesconfirmar.php');
			}
		} else	{//SINO MODO NORMAL

		if ( ($_cancelar_ == 'no')  && ($_borrar_=='no') && ($_ordenar_=='')) $_fields_error_ = $this->Contenidos->m_tcontenidos->Verificar();//verifica y completa un valor: $errores , y listo

		if (!$_fields_error_) {

			if ($_borrar_=='si') {
				$_exito_ = $this->Contenidos->Eliminar( $_primario_ID, true, true );
			} elseif ($_nuevo_=='si') {

				$CNuevo = new CContenido("");
				//$CNuevo->m_orden = $this->Contenidos->Count($_e_ID_SECCION) + 1;
				$CNuevo->m_orden = 0;
				$CNuevo->m_id_usuario_creador = $this->UsuarioAdmin->m_id;

				if ( $this->UsuarioAdmin->m_nivel>1 && $this->AdminPermisos["ROL_APPROVAL"]==false) {
					$CNuevo->m_baja = 'V';
				}


				$CNuevo = $this->Contenidos->CrearContenidoCompleto( "", $CNuevo, false /*confirmar detalles desde los globales*/, true );
				$_exito_ = is_object($CNuevo);
				$_primario_ID = $CNuevo->m_id;

			} elseif ($_modificar_=='si') {

				$CEdit = new CContenido();
				$CEdit->m_id = $_primario_ID;
				$CEdit->m_id_usuario_modificador = $this->UsuarioAdmin->m_id;

				if ( $this->UsuarioAdmin->m_nivel>1 && $this->AdminPermisos["ROL_APPROVAL"]==false) {
					$CEdit->m_baja = 'V';
				}

				$_exito_ = $this->Contenidos->Actualizar( $CEdit );

			} elseif ($_cancelar_=='si') {

				$_exito_ = true;
				$this->Contenidos->m_tcontenidos->exito = $CLang->m_Words['CANCELLED'];

			} elseif ($_ordenar_!='') {

				$_exito_ = true;
				Debug("CAdmin::Confirmar(): _accion_: ".$_accion_." _primario_ID:".$_primario_ID);
				if (is_numeric($_primario_ID)) {
					$CC = $this->Contenidos->GetContenido($_primario_ID);
				} else {
					$_primario_ID = substr( $_primario_ID, 1,strlen($_primario_ID)-2);
					if (is_numeric($_primario_ID)) {
						$CC = $this->Contenidos->GetContenido($_primario_ID);
					} else $CC = null;
				}

				if ($CC!=null)	{
					Debug("CAdmin::Confirmar > ordenando primario_id es :".$_primario_ID);
					$_exito_ = $this->Contenidos->OrdenarContenido( $CC->m_id_seccion , $CC->m_id, $_ordenar_, $CC->m_id_tipocontenido );
				} else {
					$_exito_ = false;
					DebugError("CAdmin::Confirmar > primario_id es :".$_primario_ID);
				}

			} else {
				$_exito_ = false;
				$this->Contenidos->m_tcontenidos->exito = $CLang->m_ErrorMessages['NOACTIONDEFINED'];
			}

		} else $_exito_ = $_fields_error_;

		}//FIN BORRAR SELECCION

		//$_debug_ = 'si';


		if ($_exito_) {

			Debug("sin errores");

			//===================
			// POST PROCESAMIENTO
			//===================
			if ($_exito_) {
				$_exito_ = $this->PostProcess();
				if (!$_exito_) {
					ShowError('Post process failed ['.$_exito_.']');
				}
			}

			//===================
			// REDIRECCIONAMIENTO
			//===================
			if (($_exito_) and ($_debug_!='si') && !DebugOn()) {
				if ($_admin_!='si') {
					$_onload_="javascript:document.consultar.submit();";
				}
				else $_onload_="javascript:admin();";
			}

			if ($_exito_) {
				/*
				if ($_e_ID_SECCION=="") {
					Debug("Recuperando _e_ID_SECCION:".$_e_ID_SECCION);
					$CC = $this->Contenidos->GetContenido($_primario_ID);
					$CS = $this->Secciones->GetSeccion($CC->m_id_seccion);
					$_e_ID_SECCION = $CS->m_id;
					$_f_ID_TIPOSECCION = $CS->m_id_tiposeccion;
				}
				*/
				$this->Contenidos->m_tcontenidos->exito = '<span class="navegador1">'.$this->Contenidos->m_tcontenidos->exito.'</span>';
			} else {
				DebugError("Error");
			}
		} else {
			DebugError("CAdmin::Errores de confirmación de acción: ".$_accion_);
			//DebugError( $this->Contenidos->PopErrorsCascade() );
			if ($_error_) $_errores_ = $this->Contenidos->m_tcontenidos->ImprimirErrores($camposmod);
		}

	}

	function PostProcess() {
		global $_exito_;

		if (file_exists('../../inc/include/adminpostprocess.php')) {
			require('../../inc/include/adminpostprocess.php');
		}

		return $_exito_;

	}

	//-----------------------------------------------------

	function ConfirmarResultado() {

		global $CLang;

		global $_intervalo_,$_nresultados_,$_nxintervalo_,$_nintervalos_;
		global $_errores_;
		global $_errorimg_;
		global $_usuariosecciones_;
		global $_orden_;
		global $_debug_;
		global $_exito_;
		global $_error_;
		global $_e_ID_TIPOCONTENIDO;
		global $_e_ID_SECCION;
		global $_f_ID_TIPOSECCION;

		global $_f_FECHAALTA;
		global $_tf_FECHAALTA;
		global $_f_FECHAALTA2;
		global $_fcombo_EMPRESA;
		global $_combo_ESTADOPEDIDO;
		global $_buscartexto_;

		global $_onload_;

		Debug("CAdmin::ConfirmarResultado()");

		if (!$_exito_) {
			if ($_errores_!="") ShowError( $_errores_ );
			ShowError( $this->Contenidos->PopErrorsCascade() );
		}

		ShowMessage( '<a href="javascript:volver();">'.$CLang->Get("GOBACK").'</a>' );
		if ($_debug_=='si') ShowMessage( '<a href="javascript:document.consultar.submit();">Submit</a>' );

		Debug("CAdmin::ConfirmarResultado() > _f_ID_TIPOSECCION:".$_f_ID_TIPOSECCION);

		echo '<br><br>
		<form name="consultar" method="post" action="consulta.php">
		<div style="position:absolute;display:none;">';

		$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
		$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_SECCION','','escondido');
		$this->Secciones->m_tsecciones->FiltrarCampo('ID_TIPOSECCION','','escondido');
		//$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_SECCION','/*SPECIAL*/secciones.ID IN ('.$_usuariosecciones_.')','escondido');
		$this->Contenidos->m_tcontenidos->Ordenar($_orden_);

		echo '
		</div>
		<input name="_consulta_" type="hidden"  value="si">
		<input name="_buscartexto_" type="hidden"  value="'.$_buscartexto_.'">
		<input name="_f_FECHAALTA" type="hidden"  value="'.$_f_FECHAALTA.'">
		<input name="_tf_FECHAALTA" type="hidden"  value="'.$_tf_FECHAALTA.'">
		<input name="_f_FECHAALTA2" type="hidden"  value="'.$_f_FECHAALTA2.'">
		<input name="_debug_" type="hidden" value="'.$_debug_.'">
		<input name="_admin_" type="hidden" value="'.$_admin_.'">
		<input name="_intervalo_" type="hidden" value="'.$_intervalo_.'">
		<input name="_nxintervalo_" type="hidden" value="'.$_nxintervalo_.'">
		<input name="_nresultados_" type="hidden" value="'.$_nresultados_.'">
		</form>	';
	}


	//-----------------------------------------------------
	function ConfirmarSeccion() {
		global $CLang;
		global $_accion_;
		global $_seleccion_;

		global $_error_;
		global $_errores_;

		global $_afectados_;
		global $_debug_;
		global $_nuevo_;
		global $_modificar_;
		global $_borrar_;
		global $_cancelar_;
		global $_ordenar_;


		global $_primario_ID;
		global $_admin_;
		global $_onload_;
		global $camposmod;

		global $_e_ID_SECCION,$_e_ORDEN,$_e_RAMA,$_e_PROFUNDIDAD,$_e_ID_SECCION_ANTERIOR;

		$Padre = $this->Secciones->GetSeccion($_e_ID_SECCION);
		$Hijos = $this->Secciones->GetSeccionHijos($_e_ID_SECCION);
		$_e_PROFUNDIDAD = $Padre->m_profundidad + 1;

		if ( ($_cancelar_ == 'no')  && ($_borrar_=='no')) $_error_ = $this->Secciones->m_tsecciones->Verificar();//verifica y completa un valor: $errores , y listo

		if (!$_error_) {
			//BORRAR//
			if ($_borrar_=='si') {

				$this->Contenidos->m_tcontenidos->LimpiarSQL();
				$this->Contenidos->m_tcontenidos->SQL = 'SELECT * FROM contenidos WHERE ID<>1 AND ID_SECCION='.$_primario_ID;
				$this->Contenidos->m_tcontenidos->Open();
				if ($this->Contenidos->m_tcontenidos->nresultados>0) {
					$_exito_ = false;
					$this->Secciones->m_tsecciones->exito = $CLang->m_Messages['SECTIONISNOTEMPTY'];
				} else {
					$_exito_ = $this->Secciones->m_tsecciones->Borrar();
					if ($_exito_) $_exito_ = $this->Secciones->DesasignarGruposSecciones($_primario_ID);
					if ($_exito_) {
						//des relacionar
		        		global $_trelaciones_;
		        		$_trelaciones_->LimpiarSQL();
		        		$_trelaciones_->SQL = 'DELETE FROM relaciones WHERE ID_SECCION='.$_primario_ID;
		        		$_exito_ = $_trelaciones_->EjecutaSQL();

		        		$_trelaciones_->LimpiarSQL();
		        		$_trelaciones_->SQL = 'DELETE FROM relaciones WHERE ID_SECCION_REL='.$_primario_ID;
		        		$_exito_ = $_trelaciones_->EjecutaSQL();
					}
				}

			//NUEVO//
			} elseif ($_nuevo_=='si') {

				$_e_ORDEN = count($Hijos)+1;
				$_e_RAMA = $Padre->m_rama.".".$_e_ORDEN;
				$_exito_ = $this->Secciones->m_tsecciones->Insertar();
				$_primario_ID = mysql_insert_id($this->Secciones->m_tsecciones->CONN);
				$this->Secciones->OrdenarRama($_e_ID_SECCION);
				if ($_exito_) $this->Secciones->AsignarGruposSecciones( $_e_ID_SECCION, $_primario_ID );

			//MODIFICAR//
			} elseif ($_modificar_=='si') {
				//$_e_ORDEN = count($Hijos)+1;	//no va porque ahora se puede cambiar el orden, y no depende
				//de la posicion anterior...
				$_e_RAMA = $Padre->m_rama.".".$_e_ORDEN; //esto si va, porque actualiza el campo RAMA, util en el futuro
				$_exito_ = $this->Secciones->m_tsecciones->Modificar();
				$this->Secciones->OrdenarRama($_e_ID_SECCION);
			//ORDENAR//
			} elseif ($_ordenar_!='') {
				$_exito_ = true;
				$this->Secciones->OrdenarSeccion( $_e_ID_SECCION , $_primario_ID, $_ordenar_ );

			} elseif ($_cancelar_=='si') {

				$_exito_ = true;
				$this->Secciones->m_tsecciones->exito = $CLang->m_Words['CANCELLED'];

			} else {
				$_exito_ = false;
				$this->Secciones->m_tsecciones->exito = $CLang->m_ErrorMessages['NOACTIONDEFINED'];
			}

		}


		if (!$_error_) {

			if (($_exito_) and ($_debug_!='si')) {
				if ($_admin_!='si') $_onload_="javascript:document.consultar.submit();";
				else $_onload_="javascript:admin();";
			}

			if ($_exito_) {
				$_errorimg_ = '<img src="../../inc/images/ingresado.gif" border="0">';
				$this->Secciones->m_tsecciones->exito = '<span class="navegador1">'.$this->Secciones->m_tsecciones->exito.'</span>';
			} else {
				$_errorimg_ = '<img src="../../inc/images/error.gif" border="0">';
				$this->Secciones->m_tsecciones->exito = '<span class="error">'.$this->Secciones->m_tsecciones->exito.'</span>';
				$this->Secciones->m_tsecciones->exito.= '<br><a href="javascript:document.consultar.submit();">OK</a>';
			}
		} else {
			$_errorimg_ = '<img src="../../inc/images/error.gif" border="0">';
			$_errores_ = $this->Secciones->m_tsecciones->ImprimirErrores($camposmod);
			$this->Secciones->m_tsecciones->exito = '<a href="javascript:document.consultar.submit();">OK</a>';
		}

	}

	//-----------------------------------------------------
	function ConfirmarSeccionResultado() {

		global $_errores_;
		global $_errorimg_;

		global $_orden_;
		global $_debug_;
		global $_exito_;
		global $_error_;

		echo $_errores_;
		echo '<span class="modulo_admin_error">'.$this->Secciones->m_tsecciones->exito.'</span>';
		echo '<br><br>
		<form name="consultar" method="post" action="adminarbolsecciones.php">
		<div style="position:absolute;display:none;">';
		echo '
		</div>
		<input name="_consulta_" type="hidden"  value="si">
		<input name="_debug_" type="hidden" value="'.$_debug_.'">
		<input name="_admin_" type="hidden" value="'.$_admin_.'">
		</form>	';

	}

	//-----------------------------------------------------

	//consulta de usuarios..
	function ModuloUsuarios() {
		global $_debug_;
		global $CLang;
		global $_orden_;
		global $_accion_;
		global $_seleccion_;
		global $_f_NIVEL;
		global $_desde_,$_hasta_,$_intervalo_,$_nresultados_,$_nxintervalo_,$_nintervalos_;
		global $_buscartexto_;
		global $_DIR_SITEABS;

		$_seccion_ = " > ".$CLang->m_Words['QUERY']." ".$CLang->m_Words['USERS'];
		$ramaseccion = $_f_NIVEL;

		if($_f_NIVEL==4) {
			$adduser = $CLang->m_Words['ADDUSER'];
		} else if($_f_NIVEL==2) {
			$adduser = $CLang->m_Words['ADDADMIN']." (DATA) ";
		} else if($_f_NIVEL==1) {
			$adduser = $CLang->m_Words['ADDADMIN'];
		} else if($_f_NIVEL==0) {
			$adduser = $CLang->m_Words['ADDADMIN']." (SUPER) ";
		}


		$this->ConsultaHeader();

		//ACCIONES ESPECIALES
		if ($_accion_=='listmails') {
			echo '<textarea cols="80" rows="40" class="modulo_admin_campo">';
			$this->Usuarios->m_tusuarios->SetTemplateResultado('*usuarios.NOMBRE* *usuarios.APELLIDO* <*usuarios.MAIL*>');
			//aqui va la funcion de listar mails...
			$SELS = split(",",$_seleccion_);
			$sep = "";
			foreach($SELS as $k) {
				$this->Usuarios->m_tusuarios->LimpiarSQL();
				$this->Usuarios->m_tusuarios->FiltrarSQL( 'ID', '', $k);
				$this->Usuarios->m_tusuarios->FiltrarSQL('NIVEL','',$_f_NIVEL);
				$this->Usuarios->m_tusuarios->Open();
				if ($this->Usuarios->m_tusuarios->nresultados>0) {
					$sep=='' ? $sep = ",\n" : print($sep);
					$this->Usuarios->m_tusuarios->ImprimirResultados("");
				}

			}
			echo '</textarea>';
			return;
		}

		if ($_orden_=='') {
			//seleccionamos el primer indice definido (ver deftabla.php)
			foreach($this->Usuarios->m_tusuarios->indices as $indice) {
				$_orden_= $indice['indice'];
				break;
			}
		}

		$this->Usuarios->SetTemplateConsulta($_f_NIVEL);
		$CLang->Translate($this->Usuarios->m_templatesconsulta[$_f_NIVEL]);
		$this->Usuarios->m_tusuarios->SetTemplateResultado( $this->Usuarios->m_templatesconsulta[$_f_NIVEL] );
		//$this->Usuarios->debug = "si";


		echo '<form name="consultar" action="usuarios.php" method="post" target="_self">';

		echo '	<input name="_consulta_" type="hidden"  value="si">
			<input name="_accion_" type="hidden"  value="">
			<input name="_seleccion_" type="hidden"  value="">
			<input name="_debug_" type="hidden" value="'.$_debug_.'">
			<input name="_borrar_" type="hidden" value="no">
			<input name="_cancelar_" type="hidden" value="no">
			<input name="_modificar_" type="hidden" value="si">
			<input name="_nuevo_" type="hidden" value="no">
			<input name="_primario_ID" value="" type="hidden">
			<input name="_intervalo_" type="hidden" value="'.$_intervalo_.'">
			<input name="_nxintervalo_" type="hidden" value="'.$_nxintervalo_.'">
			<input name="_nresultados_" type="hidden" value="'.$_nresultados_.'">
			<input name="_rd_" type="hidden"  value="'.rand().'">
		';
		//FILTROS
		echo '<table width="100%" cellspacing="0" cellpadding="0" bgcolor="'.$GLOBALS['_COLOR_BG'].'">
					<tr>
						<td>';

		echo '			<table width="100%" cellspacing="0" cellpadding="4">
							<tr>
								<td align="left" valign="top"><a href="javascript:nuevousuario()"><img hspace="3" src="../../inc/images/agregar.gif" align="absmiddle" width="14" height="17" border="0" alt="'.$CLang->m_Words['ADDUSER'].'" onMouseOver="javascript:showimg(\'../../inc/images/agregar_over.gif\');" onMouseOut="javascript:showimg(\'../../inc/images/agregar.gif\');"><span class="modulo_admin_consulta_nuevousuario">'.$adduser.'</span></a>
								</td>';
		echo '	<td align="right" valign="bottom" width="200" class="MAD_CONS_FILTERS">';
		if (file_exists('../../inc/include/filtrosadminusuarios.php')) {
			require('../../inc/include/filtrosadminusuarios.php');
		} else {
			$this->Usuarios->m_tusuarios->LimpiarSQL();
		}
		echo '</td>';
		echo '	<td align="right" valign="bottom" width="2" class="MAD_CONS_FILTERS">
				<span class="MAD_CONS_FILTERS">'.$CLang->m_Words['TEXTTOSEARCH'].'<br></span>';
		echo '		<input name="_buscartexto_" type="text" value="'.$_buscartexto_.'" size="20">';
		echo '	</td>';
		echo '	<td id="tdsearch" valign="bottom" class="MAD_CONS_FILTERS">&nbsp;&nbsp;
					<a href="javascript:consultarusuarios();" class="MAD_CONS_FILTERS">'.$CLang->m_Words['SEARCH'].'<img src="'.$_DIR_SITEABS.'/inc/images/search_16.png" align="absbottom" border="0" alt="'.$CLang->m_Words['SEARCH'].'" title="'.$CLang->m_Words['SEARCH'].'" hspace="4"></a>&nbsp;&nbsp;
				</td>
			</tr>
			</table>';

		echo '		</td>
					</tr>
					<tr>
						<td bgcolor="#000000"><img src="../../inc/images/spacer.gif" width="100" height="1" border="0"></td>
					</tr>
					<tr>
						<td align="center" valign="top"><br>
			';


		if ($_f_NIVEL!=0 && $_f_NIVEL!=1 && $_f_NIVEL!=2 && $_f_NIVEL!=4) $_f_NIVEL = 4;
		if ( 	($_f_NIVEL == 0)
					|| ($_f_NIVEL == 1)
					|| ($_f_NIVEL == 2)
					|| ($_f_NIVEL == 3)
					|| ($_f_NIVEL == 4)
				) {
			$special = "";
			if ($_buscartexto_!="") {
				$mots = explode(" ",$_buscartexto_);
				$special = "/*SPECIAL*/";
				$nd = "";
				foreach($mots as $mot) {
				 	$special.= $nd." (usuarios.NOMBRE LIKE '%".$mot."%' OR usuarios.APELLIDO LIKE '%".$mot."%' OR usuarios.NICK LIKE '%".$mot."%' OR usuarios.DIRECCION LIKE '%".$mot."%') ";
				 	$nd = " OR ";
				}
			}
			$this->Usuarios->m_tusuarios->FiltrarSQL('NIVEL',$special,$_f_NIVEL);
			$this->Usuarios->m_tusuarios->OrdenSQL($_orden_);

			if ($_nxintervalo_=='') $_nxintervalo_ = 30;
			if ($_intervalo_=='') $_intervalo_ = 1;

			//RESULTADOS
			if ( $_nresultados_=='' || !isset($_nresultados_) || $_nresultados_==0) {
				$this->Usuarios->m_tusuarios->Count();
				$_nresultados_ = $this->Usuarios->m_tusuarios->nresultados;
				if ($_nxintervalo_=='max') $_nxintervalo_ = $_nresultados_;
			}

			if ($_nresultados_>0) {
				$_desde_ = ($_intervalo_ - 1) * $_nxintervalo_ + 1;
				$_hasta_ = min( ($_desde_+$_nxintervalo_-1) , $_nresultados_);
				$_nintervalos_ = ceil( $_nresultados_ / $_nxintervalo_);
				$this->Usuarios->m_tusuarios->LimiteSQL( ($_desde_-1), $_nxintervalo_ );
				$this->Usuarios->m_tusuarios->Open();

			}
		}


		//RESULTADOS
		$this->ConsultaResultadosHeader( $this->Usuarios->m_tusuarios, 'usuariosintervalo' );

		//$tool_menu_listado = $TR->Navegacion( $tool_menu_listado, array("nxpage_selector"=>false) );

		if ($this->Usuarios->m_tusuarios->nresultados>0) {
			//$this->Usuarios->m_tusuarios->ImprimirResultados("");

			while($r = $this->Usuarios->m_tusuarios->Fetch()) {

				$CUser = new CUsuario($r);

				$str = $this->Usuarios->TextoConsulta( $CUser );

				if ( $CUser->m_id_contenido>1 ) {

					$Ficha = $this->Contenidos->GetContenidoCompleto( $CUser->m_id_contenido );
					if (is_object($Ficha)) {
						$str = $this->TiposContenidos->TextoCompleto( $Ficha, $str );
					}

				}

				echo $str;
			}

		}

		$this->ConsultaResultadosFooter( $this->Usuarios->m_tusuarios, 'usuariosintervalo' );

		echo '<input name="_f_NIVEL" type="hidden"  value="'.$_f_NIVEL.'">';

		echo '</td></tr></table>';
		echo '</form>';


	}

	//-----------------------------------------------------

	function ModuloSistema() {
		//MUESTRA EL CONTENIDO DE LAS VARIABLES DEL SISTEMA:
		//EJ: Banner de la home
		//	 Destacado de la home
		//	 Colores
		//	 Texto que aparece en la home
		//	 Formato de fecha...etc etc

		//PSEUDO CODIGO
		//busca el codigo correspondiente al contenido del tipo FICHA_SISTEMA
		//y lo muestra para modificarlo....
		global $_accion_;
		global $_seleccion_;
		global $_modificar_;
		global $_borrar_;
		global $_nuevo_;
		global $_seccion_;
		global $_admin_;
		global $_orden_;
		global $_debug_;
		global $_consulta_;

		global $_usuariosecciones_;
		global $_primario_ID;
		global $_f_ID_SECCION;
		global $_f_ID_TIPOCONTENIDO;

		global $_e_ID_TIPOCONTENIDO;
		global $_e_ID_USUARIO_CREADOR;
		global $_e_ID_SECCION;

		global $_tf_FECHAALTA;
		global $_f_FECHAALTA;
		global $_tf_FECHAALTA2;
		global $_f_FECHAALTA2;

		global $_f_ID_TIPOSECCION;

		$_e_ID_CONTENIDO = 1;
		$_e_ID_USUARIO_CREADOR = $_SESSION['idusuario'];
			/*
		//busca el codigo correspondiente al contenido del tipo FICHA_SISTEMA
		$this->Secciones->m_tsecciones->LimpiarSQL();
		$this->Secciones->m_tsecciones->FiltrarSQL('ID_TIPOSECCION','',$GLOBALS['_ID_SYSTEM_TYPE_SECTION']);
		$this->Secciones->m_tsecciones->Open();

		$_modificar_ = 'si';
		$_borrar_ = 'no';
		$_nuevo_ = 'no';


		if ($this->Secciones->m_tsecciones->nresultados==1) {

			$row = $this->Secciones->m_tsecciones->Fetch($this->Secciones->m_tsecciones->resultados);

			$_primario_ID = $this->Contenidos->GetIdsContenidos( $row['secciones.ID'], 'contenidos.ID_TIPOCONTENIDO='.$GLOBALS['_ID_SYSTEM_TYPE_CARD'] );

		} else {

			echo '<div class="error">SYSTEM SECTION not present! </div>';

		}
		//echo "GLOBALS['_ID_SYSTEM_TYPE_SECTION'] : ".$GLOBALS['_ID_SYSTEM_TYPE_SECTION']." seccion id: ".$row['secciones.ID']." nombre:".$row['secciones.NOMBRE']."  contenido id:".$_primario_ID;

		$this->Editar();
		*/
		$_modificar_ = 'no';
		$_borrar_ = 'no';
		$_nuevo_ = 'no';
		$_consulta_ = 'si';

		$_f_ID_TIPOCONTENIDO = FICHA_SISTEMA;
		$_f_ID_TIPOSECCION = $GLOBALS['_ID_SYSTEM_TYPE_SECTION'];
		$_f_ID_SECCION = 1;
		$_tf_FECHAALTA = '';
		$_f_FECHAALTA = '';
		$_tf_FECHAALTA2 = '';
		$_f_FECHAALTA2 = '';

		echo '<form name="consultar" action="consulta.php" method="post" target="_self">';
		$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_SECCION','','escondido');
		$this->Contenidos->m_tcontenidos->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
		$this->ConsultaFiltros();
		$this->ConsultaResultados();
		echo '</form>';

	}

	//-----------------------------------------------------

	function ModuloMantenimiento() {
		global $CLang;
		global $_accion_;

		if ($_accion_=="sincronizartodo_iterativo") {

	  	if (file_exists('../../inc/modules/ModuloMantenimiento.php')) {
				require('../../inc/modules/ModuloMantenimiento.php');
			}

		} else {
			$this->ConsultaHeader();

		echo '<div id="pagebook" class="pagebook">
		<div class="tabcontrol">
			<div id="sqlsupport" class="tab tab-on" onclick="javascript:PageOn(\'pagebook\',\'sqlsupport\');">Sentencias SQL de mantenimiento</div>
			<div id="sqlminiadmin" class="tab" onclick="javascript:PageOn(\'pagebook\',\'sqlminiadmin\');">SQL mini admin</div>
			<div id="mantenimiento" class="tab" onclick="javascript:PageOn(\'pagebook\',\'mantenimiento\');">DB Sync</div>
		</div>
		<div class="pagescontrol">
			';


		echo '<div id="sqlsupport" class="page page-on">

<span>reasignar secciones huerfanas:</span>
<textarea>
update secciones set id_seccion=1 where id_seccion NOT IN (select id from secciones)
</textarea>


<span>purgar detalles:</span>
<textarea>
delete from detalles where detalles.id_contenido not in (select id from contenidos)
</textarea>

<span>purgar relaciones (contenidos que referencian):</span>
<textarea>
delete from relaciones where relaciones.id_contenido<>0 and relaciones.id_contenido not in (select id from contenidos)
</textarea>

<span>purgar relaciones (contenidos que son referenciados):</span>
<textarea>
delete from relaciones where relaciones.id_contenido_rel<>0 and relaciones.id_contenido_rel not in (select id from contenidos)
</textarea>

<span>purgar relaciones (secciones que referencian):</span>
<textarea>
delete from relaciones where relaciones.id_seccion<>0 and relaciones.id_seccion not in (select id from secciones)
</textarea>

<span>purgar relaciones (secciones que son referenciadas):</span>
<textarea>
delete from relaciones where relaciones.id_seccion_rel<>0 and relaciones.id_seccion_rel not in (select id from secciones)
</textarea>


<span>chequear subcontenidos sin referencia:</span>
<textarea>
select * from contenidos where contenidos.id_contenido not in (select id from contenidos)
</textarea>

<span>reasignar contenidos sin autoria (creador o modificador):</span>
<textarea>
update contenidos set id_usuario_modificador=1,id_usuario_creador=1 where id_usuario_creador not in (select id from usuarios) or id_usuario_modificador not in (select id from usuarios)
</textarea>

<span>reasignar fecha de actualizacion de contenidos no validas:</span>
<textarea>
update contenidos set actualizacion=NOW() where actualizacion = \'00-00-0000 00:00\'
</textarea>

<span>reasignar fechas de alta y baja a fecha valida:</span>
<textarea>
update contenidos set fechaalta=NOW(),fechabaja=NOW() where fechaalta = \'00-00-0000 00:00\' or fechabaja=\'00-00-0000 00:00\'
</textarea>

<span>reasignar fechas de eventos no validas:</span>
<textarea>
update contenidos set fechaevento=NOW() where fechaevento = \'00-00-0000 00:00\'
</textarea>
</div>
		';

		//if (DebugOn()) {
			echo '<div id="sqlminiadmin" class="page">';
			echo '<iframe width="100%" height="70%" frameborder="1" src="../../administracion/admin/phpminiadmin.php">you need iframes</iframe>';
			echo '</div>';
		//}

		if (file_exists('../../inc/modules/ModuloMantenimiento.php')) {
			echo '<div id="mantenimiento" class="page">';
			require('../../inc/modules/ModuloMantenimiento.php');
			echo '</div>';
		}

		echo '</div>'; ///fin pages control
		echo '</div>'; ///fin pageook

		}

	}

	//-----------------------------------------------------

	function ModuloEstadisticas() {
		echo "estadisticas";
	}

}

if (file_exists('../../inc/include/CAdminExtended.php')) {
	require '../../inc/include/CAdminExtended.php';
}

global $Admin;

if (!defined("DNK_SITE") and !defined("Admin")) {
  	define( "DNK_SITE", "OK" );
  	$Admin = new CAdmin($_tsecciones_,$_ttipossecciones_,$_tcontenidos_,$_ttiposcontenidos_,$_tarchivos_,$_ttiposarchivos_,$_tdetalles_,$_ttiposdetalles_,$_tusuarios_,$_tlogs_,$_trelaciones_, $_ttiposrelaciones_);
}

$Admin->IniciarSesion();


?>
