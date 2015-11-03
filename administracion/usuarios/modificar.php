<?Php
// ********************************
//             TABLA 
// ********************************
require "deftabla.php";
require '../include/bloqueoheader.php';
if ($_logueado_=='verdadero') {
?>
<html>
<head><title>Edición <?=$tabla->nombre?></title>
<?
require "../include/style.php";
require "../include/scripts.php";

	if ($_modificar_=='si') {
		$tabla->Edicion(${'_primario_'.$tabla->primario});
		$_seccion_ = ' > MODIFICANDO '.strtoupper($tabla->nombre);
	} else if ($_borrar_=='si') {
		$tabla->Edicion(${'_primario_'.$tabla->primario});
		$_seccion_ = ' > BORRANDO '.strtoupper($tabla->nombre);					
	} elseif ($_nuevo_=='si') {
		$tabla->Nuevo();
		$_seccion_ = ' > AGREGANDO '.strtoupper($tabla->nombre);										
	}

?>
</head>
<body marginheight="0" marginwidth="0">
<? include "../include/pageheader.php";?>
<?include "../include/navegador.php";?>
<!--EDICION DE CAMPOS -->
<? include "../include/modificarheader.php";?>
<? include "../include/camposheader.php";?>
				<?
				if ($_borrar_=='si') {
					echo '<span class="error">ATENCION - ATENCION -ATENCION <br>¿Confirma que quiere borrar este registro?<br></span>';
				}
				?>
				<!--CAMPOS-->
			<form name="confirmar" method="post" action="confirmar.php">				
				<table border="0" cellpadding="0" cellspacing="10">
					<tr>
						<td colspan="1"><?$tabla->EditarCampo('NICK');?></td>
						<td colspan="1"><?$tabla->EditarCampo('PASSWORD');?></td>						
						<td rowspan="12" valign="bottom"><? include "../include/okcancel.php";?></td>
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('NIVEL');?></td>
					</tr>
					<tr>
						<td colspan="1"><?$tabla->EditarCampo('NOMBRE');?></td>
						<td colspan="1"><?$tabla->EditarCampo('APELLIDO');?></td>						
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('MAIL');?></td>												
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('PAIS');?></td>												
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('CIUDAD');?></td>						
					</tr>					
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('DIRECCION');?></td>						
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('PISO');?></td>						
					</tr>					
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('EMPRESA');?></td>						
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('OFICINA');?></td>						
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('PAGINA');?></td>						
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('TELEFONO');?></td>						
					</tr>					
				</table>
			<!--FIN CAMPOS-->
<? include "../include/camposfooter.php";?>
<!--FIN EDICION DE CAMPOS -->
<? include "../include/modificarfooter.php";?>
<div style="position:absolute;display:none;">
<? 
	$tabla->FiltrarSQL('NICK','','escondido');
	$tabla->FiltrarSQL('NOMBRE','','escondido');
	$tabla->FiltrarSQL('APELLIDO','','escondido');
	$tabla->FiltrarSQL('MAIL','','escondido');
	$tabla->Ordenar($_orden_);	
?>
</div>
<input name="_primario_<?=$tabla->primario?>" type="hidden" value="<?=${'_primario_'.$tabla->primario}?>">
<input name="_modificar_" type="hidden" value="<?=$_modificar_?>">
<input name="_borrar_" type="hidden" value="<?=$_borrar_?>">
<input name="_cancelar_" type="hidden" value="no">
<input name="_nuevo_" type="hidden" value="<?=$_nuevo_?>">
<input name="_admin_" type="hidden" value="<?=$_admin_?>">
<input name="_debug_" type="hidden" value="<?=$_debug_?>">
<input name="_usuario_" type="hidden" value="<?=$_usuario_?>">
<input name="_usuariologs_" type="hidden" value="<?=$_usuariologs_?>">
</form>
<? include "../include/pagefooter.php";?>
</body>
</html>
<?
} else { include '../include/bloqueofooter.php'; }
?>