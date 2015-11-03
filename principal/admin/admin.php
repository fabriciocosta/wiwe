<?Php
//======================================================================
//
//			administrar.php
//			archivo generico para mostrar contenidos de una seccion
//			version: 1.0.1 (18/04/2003)
//======================================================================
$__modulo__ = "admin";

require "../../inc/include/deftabla.php";
require "../../inc/core/CAdmin.php";

?>
<html>
<head>
<title><?=$CLang->m_Words['ADMINISTRATION']?> --- </title>
<?
?>
<? require "../../inc/include/style.php"; ?>
<? require "../../inc/include/scripts.php"; ?>
</head>
<body>
<? require "../../inc/include/adminheader.php"; ?>
<? $Admin->Administrar(); ?>
<? require "../../inc/include/adminfooter.php"; ?>
</body>
</html>

