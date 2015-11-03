<?Php
// ****************************************************
//             CONSULTA DE TABLA GENERICA
//					MODELO BASE 
// ****************************************************

require "deftabla.php";
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
								<tr><td><span class="navegador3">ELIJA LOS FILTROS PARA LA CONSULTA DE GENERICA</span></td></tr>
								<tr><td><img src="../images/spacer.gif" border="0" width="1" height="5"></td></tr>
								<tr><td colspan="2" bgcolor="#C0C0C0"><img src="../images/spacer.gif" border="0" width="1" height="2"></span></td>
</tr></table></td>
					</tr>
					<tr>
						<td><? $tabla->FiltrarCampo('CAMPO1','LOOKUP.ID_CONTINENTE='.${'_fcombo_continente'}.' AND LOOKUP.ID_HEMISFERIO='.${'_fcombo_hemisferio'}); ?></td>
						<td><?	$tabla->Combo('','hemisferio','Hemisferio','HEMISFERIO','ID','DESCRIPCION');?></td>
						<td><?	$tabla->Combo('','continente','Continente','CONTINENTES','ID','DESCRIPCION');?></td>
						<td rowspan="2" valign="bottom"><a href="javascript:consultar();"><img src="../images/buscar.gif" alt="" width="50" height="25" border="0" onMouseDown="javascript:showimg('../images/buscar_down.gif');" onMouseOut="javascript:showimg('../images/buscar.gif');"></a></td>
					</tr>
					<tr>
						<td><? $tabla->FiltrarCampo('CAMPO2'); ?></td>						
						<td colspan="2"><? $tabla->FiltrarCampo('CAMPO3'); ?></td>
					</tr>
				</table>
			<!--FIN FILTROS-->
<input name="_consulta_" type="hidden"  value="si">
<input name="_debug_" type="hidden" value="<?=$_debug_?>">
<input name="_borrar_" type="hidden" value="no">
<input name="_modificar_" type="hidden" value="si">
<input name="_nuevo_" type="hidden" value="no">
<input name="_primario_<?=$tabla->primario?>" value="" type="hidden">
<!-- FIN SECCION FILTRO -->
<? include "../include/filtrofooter.php";?>

<!-- SECCION CONSULTA -->
<? 
	if ($_consulta_=='si') {
		$tabla->LimpiarSQL();
		$tabla->FiltrarSQL('CAMPO1','LOOKUP.ID_CONTINENTE='.${'_fcombo_continente'}.' AND LOOKUP.ID_HEMISFERIO='.${'_fcombo_hemisferio'});
		$tabla->FiltrarSQL('CAMPO2');
		$tabla->FiltrarSQL('CAMPO3');
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
