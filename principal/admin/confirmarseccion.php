<?Php
//======================================================================
//
//			confirmar.php
//			archivo generico confirmar la edicion de un contenido
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
<? $Admin->ConfirmarSeccion();  ?>
<body onLoad="<?=$_onload_?>" marginheight="0" marginwidth="0">
<? //require "../../inc/include/adminheader.php"; ?>
<? 
	$Admin->ConfirmarSeccionResultado();
?>
<?// require "../../inc/include/adminfooter.php"; ?>
</body>
</html>