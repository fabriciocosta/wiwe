<?
/*BLOQUEO*/
require '../include/DinamikAdmin.php';

require '../include/bloqueoheader.php';
if ( $_SESSION['idusuario'] > 0 ) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Administrador</title>
<script>
</script>
<?
require "../include/style.php";
require "../include/scripts.php";
?>
</head>
<body marginheight="0" marginwidth="0">
<div align="center">
<? include "../include/pageheader.php"; ?>
<?
$_seccion_ = '';
include "../include/navegador.php";



include "../include/pagefooter.php"; ?>
</div>
</body>
</html>
<?
} else { include '../include/bloqueofooter.php'; }
?>
