<?Php
// ********************************
//             TABLA 
// ********************************
require "deftabla.php";
//$tabla->debug = 'si';
require '../include/bloqueoheader.php';
if ($_logueado_=='verdadero') {
?>
<html>
<head><title>Edici�n <?=$tabla->nombre?></title>
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
					echo '<span class="error">ATENCION - ATENCION -ATENCION <br>�Confirma que quiere borrar este registro?<br></span>';
				}
				?>
				<!--CAMPOS-->
			<form name="confirmar" method="post" action="confirmar.php">				
				<table border="0" cellpadding="0" cellspacing="10">
					<tr>
						<td><?$tabla->EditarCampo('ID_SECCION');?></td>
						<td><?$tabla->EditarCampo('CATEGORIA');?></td>						
						<td rowspan="5" valign="bottom"><? include "../include/okcancel.php";?></td>
					</tr>
					<tr>
						<td><?$tabla->EditarCampo('ID_TIPOSECCION');?></td>
					</tr>
					<tr>
						<td><?$tabla->EditarCampo('ID_CONTENIDO');?></td>
					</tr>					
					<tr>
						<td><?$tabla->EditarCampo('ORDEN');?></td>
					</tr>							
					<tr>
						<td><?$tabla->EditarCampo('RAMA');?></td>
					</tr>						
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('NOMBRE');?></td>
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('CARPETA');?></td>
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('DESCRIPCION');?></td>
					</tr>					
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('BAJA');?></td>
					</tr>					
				</table>
			<!--FIN CAMPOS-->
<? include "../include/camposfooter.php";?>
<!--FIN EDICION DE CAMPOS -->
<? include "../include/modificarfooter.php";?>
<div style="position:absolute;display:none;">
<? 
	$tabla->FiltrarCampo('ID_SECCION','/*SPECIAL*/secciones.ID IN ('.$_usuariosecciones_.')','escondido');
	$tabla->FiltrarCampo('NOMBRE','','escondido');
	$tabla->FiltrarCampo('CARPETA','','escondido');		
	$tabla->FiltrarCampo('DESCRIPCION','','escondido');
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