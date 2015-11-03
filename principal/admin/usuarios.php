<?Php
//======================================================================
//
//			consulta.php
//			archivo generico para mostrar contenidos de una seccion
//			version: 1.0.1 (18/04/2003)
//======================================================================
$__modulo__ = "admin";

require "../../inc/include/deftabla.php"; 
require "../../inc/core/CAdmin.php";
?>
<html>
<head>
<title>Administración --- </title>
<? require "../../inc/include/style.php"; ?>
<? require "../../inc/include/scripts.php"; ?>
</head>
<body>
<form name="consultar" method="post" action="usuarios.php">
<? //require "../../inc/include/adminheader.php"; ?>
<!-- SECCION USUARIOS-->
<? $Admin->ModuloUsuarios(); ?>
<!-- FIN SECCION USUARIOS -->
<? //require "../../inc/include/adminfooter.php"; ?>
</form>
</body>
</html>