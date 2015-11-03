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
<title><?=$CLang->m_Words['ADMINISTRATION']?> --- </title>
<? require "../../inc/include/style.php"; ?>
<? require "../../inc/include/scripts.php"; ?>
</head>
<body>
<? $Admin->AdministrarConfiguracion(); ?>
</body>
</html>