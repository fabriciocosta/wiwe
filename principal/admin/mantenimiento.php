<?Php
//======================================================================
//
//			modificar.php
//			archivo generico para mostrar contenidos de una seccion
//			version: 2.0.1 (07/04/2005)
//======================================================================
$__modulo__ = "admin";

require "../../inc/include/deftabla.php"; 
require "../../inc/core/CAdmin.php";
?>
<html>
<head>
<title><?=$CLang->m_Words['ADMINISTRATION']?> --- </title>
<? require "../../inc/include/style.php"; ?>
<? require "../../inc/include/scripts.php"; ?>
</head>
<body>
<?// require "../../inc/include/adminheader.php"; ?>
<?
	$Admin->ModuloMantenimiento();
?>		
<?// require "../../inc/include/adminfooter.php"; ?>
</body>
</html>		
