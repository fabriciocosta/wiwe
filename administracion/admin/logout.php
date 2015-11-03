<?
require '../include/DinamikAdmin.php';
require '../admin/deftabla.php';

session_start();

$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (isset($_COOKIE[session_name()])) {
   setcookie(session_name(), '', time()-42000, '/');
}			
session_destroy();

/*
$tadminlog->LimpiarSQL();
$tadminlog->SQL= "DELETE FROM logusuarios WHERE NICK_USUARIO='".$_usuario_."' AND LOGS=".$_usuariologs_;
$tadminlog->EjecutaSQL();
*/
?>
<html>
<title>Login out.</title>
<meta http-equiv="refresh" content="0;url='../login/login.php';">
<body>
Redireccionando...
</body>
</html>