<?Php
// ****************************************************
//             CONSULTA DE TABLA GENERICA
//					MODELO BASE 
// ****************************************************

require "deftabla.php";
require '../include/bloqueoheader.php';
if ($_logueado_=='verdadero') {
?><html>
<head><title>Consulta <?=$tabla->nombre?></title>
<?
require "../include/style.php";
require "../include/scripts.php";
?>
</head>
<body marginheight="0" marginwidth="0">
<? include "../include/pageheader.php";?>
<?$_seccion_ = " > CONSULTA ".strtoupper($tabla->nombre);
include "../include/navegador.php";?>
<!-- SECCION FILTRO -->
<? include "../include/consultaheader.php";?>
<? include "../include/filtroheader.php";?>
<form name="consultar" method="post" action="consulta.php">
			<!--FILTROS-->
				<table border="0" cellpadding="0" cellspacing="10">
					<tr>
						<td colspan="4">
							<table width="100%" cellpadding="0" cellspacing="0">
								<tr><td><span class="navegador3">ELIJA LOS FILTROS PARA LA CONSULTA DE <?=strtoupper($tabla->nombre)?></span></td></tr>
								<tr><td><img src="../images/spacer.gif" border="0" width="1" height="5"></td></tr>
								<tr><td colspan="2" bgcolor="#C0C0C0"><img src="../images/spacer.gif" border="0" width="1" height="2"></span></td>
</tr></table></td>
					</tr>
					<tr>
						<td colspan="2"><? $tabla->FiltrarCampo('GRUPO'); ?></td>
						<td rowspan="4" valign="bottom"><a href="javascript:consultar();"><img src="../images/buscar.gif" alt="" width="50" height="25" border="0" onMouseDown="javascript:showimg('../images/buscar_down.gif');" onMouseOut="javascript:showimg('../images/buscar.gif');"></a></td>
					</tr>
					<tr>
						<td colspan="2"><? $tabla->FiltrarCampo('PERMISOS_MIEMBROS'); ?></td>
					</tr>
					<tr>
						<td colspan="2"><? $tabla->FiltrarCampo('PERMISOS_USUARIOS'); ?></td>
					</tr>
					<tr>											
						<td colspan="2"><? $tabla->FiltrarCampo('DESCRIPCION'); ?></td>						
					</tr>					
				</table>
			<!--FIN FILTROS-->
<input name="_consulta_" type="hidden"  value="si">
<input name="_debug_" type="hidden" value="<?=$_debug_?>">
<input name="_borrar_" type="hidden" value="no">
<input name="_cancelar_" type="hidden" value="no">
<input name="_modificar_" type="hidden" value="si">
<input name="_nuevo_" type="hidden" value="no">
<input name="_primario_<?=$tabla->primario?>" value="" type="hidden">
<input name="_usuario_" type="hidden" value="<?=$_usuario_?>">
<input name="_usuariologs_" type="hidden" value="<?=$_usuariologs_?>">
<!-- FIN SECCION FILTRO -->
<? include "../include/filtrofooter.php";?>

<!-- SECCION CONSULTA -->
<? 
	if ($_consulta_=='si') {
		$tabla->LimpiarSQL();
		$tabla->FiltrarSQL('GRUPO');
		$tabla->FiltrarSQL('PERMISOS_MIEMBROS');
		$tabla->FiltrarSQL('PERMISOS_USUARIOS');
		$tabla->FiltrarSQL('DESCRIPCION');
		$tabla->OrdenSQL($_orden_);
		$tabla->Open();
	}
?>
<!-- SECCION RESULTADO -->
<br>
<? include "../include/resultados.php";?>
</form>


<!--REGISTROS-->
<table width="100%" border="1" cellpadding="0" cellspacing="0" bgcolor="#000000" bordercolor="#000000">	
	<tr>
		<td><? $tabla->ImprimirResultados(); ?></td>
	</tr>
</tr>
</table>
<br>
<!-- FIN SECCION RESULTADO -->

<!-- FIN SECCION CONSULTA -->
<? include "../include/consultafooter.php";?>
</body>
</html>
<?
} else { include '../include/bloqueofooter.php'; }
?>