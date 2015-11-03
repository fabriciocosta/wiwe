<?Php
//======================================================================
//
//			consulta.php
//			archivo generico para mostrar contenidos de una seccion
//			version: 1.0.2 (23/09/2006)
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
<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td bgcolor="<?=$GLOBALS['_COLOR_BG']?>" valign="top">
<!-- SECCION CONSULTA -->
<form name="consultar" action="consulta.php" method="post" target="_self">
			<!--FILTROS-->
<? $Admin->ConsultaFiltros(); ?>
			<!--FIN FILTROS-->

<!-- SECCION RESULTADO -->
<? $Admin->ConsultaResultados(); ?>
<!-- FIN SECCION RESULTADO -->
</form>
		</td>
	</tr>
</table>
<!-- FIN SECCION CONSULTA -->
</body>
</html>