<?

/*=================================*/
/*		DOC TYPE	  			   */
/*=================================*/
global $__modulo__;
if ($__modulo__!="admin" && $__modulo__!="phpminiadmin") {
	$_DOCTYPE_ = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
}



/*=================================*/
/*		BASE DE DATOS  			   */
/*=================================*/
$_TITLE_='WiWe';
$_WWW_='WiWe';
$_TIPODB_='mysql';
$_DB_='wiwe';
$_SERVIDOR_='localhost';
$_USUARIO_='root';
$_CONTRASENA_='';
$_FTPSERVER_='';
$_FTPUSUARIO_='';
$_FTPCONTRASENA_='';

/*=================================*/
/*			DIRECTORIOS 		   */
/*=================================*/

$_DIR_ADMABS='/administracion';
$_DIR_ADMREL='/administracion';
$_DIR_SITEABS='';
$_DIR_SITEREL='';
$_DIR_FTPREL='';

$_DIR_ARCH='/archivos';
$_DIR_ARCH_IMG = '/imagen';
$_DIR_ARCH_DOC = '/documentacion';
$_DIR_SECCIONES='/secciones';
$_DIR_TMP='/tmp';

$_DOCROOT_ = $_SERVER['DOCUMENT_ROOT'];
$_SITEROOT_ = $_DOCROOT_.$_DIR_SITEREL;

/*=================================*/
/*			CONSTANTES 			   */
/*=================================*/

$_COLOR_BG='#FFFFFF';
$_COLOR_FG='#FFFFFF';
$_COLOR_INK='#000000';
$_COLOR_INKHL='#000000';
$_COLOR_BOX='#FFFFFF';
$_COLOR_BOXHL='#CCCCCC';
$_PASSWORD_VERSION='OLD_PASSWORD';
//LANG: SP-EN-FR-PO
if (!isset($_LANG_)) {
$_LANG_='SP';
}
$_ADMIN_TYPE='TREE';
$_ADMIN_COPETE='F#1#F#2#F#6#';
$_ADMIN_CUERPO='F#1#F#2#F#3#F#4#F#6#';
$_ADMIN_EVENTO='F#1#F#2#F#3#F#6#';
$_ADMIN_ADMIN='N';
$_ADMIN_STARTURL='';
$_ADMIN_STARTURL_ARBOL='';
$_SITIO_SESION='auto';
?>
