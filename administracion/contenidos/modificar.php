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

require "../include/scriptseditor.php";

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
			<form name="confirmar" method="post" enctype="multipart/form-data" action="confirmar.php">				
				<table border="0" cellpadding="0" cellspacing="10">
					<tr>
						<td><?$tabla->EditarCampo('ID_TIPOCONTENIDO','','onChange="javascript:modificartipocontenido();"');?></td>						
						<td><?$tabla->EditarCampo('PRINCIPAL');?></td>						
					</tr>
					<tr>
						<td><?$tabla->EditarCampo('ID_USUARIO_CREADOR');?></td>
						<td><?$tabla->EditarCampo('ID_USUARIO_MODIFICADOR');?></td>
					</tr>					
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('ID_CONTENIDO');?></td>						
					</tr>
					<tr>
						<td colspan="2">
<!--DETALLES -->
						<? require "modificarDetalles.php"; ?>
<!--FIN DETALLES -->						
						</td>
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('ID_SECCION','/*SPECIAL*/secciones.ID IN ('.$_usuariosecciones_.')');?></td>
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('TITULO');?></td>
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('ML_TITULO');?></td>
					</tr>					
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('COPETE');?></td>
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('ML_COPETE');?></td>
					</tr>					
					<tr>
						<td colspan="2">
						<table>
							<tr>
								<td><?$tabla->EditarCampo('CUERPO');?></td>
							</tr>
							<tr>
								<td><?$tabla->EditarCampo('ML_CUERPO');?></td>
							</tr>							
						</table>
						</td>
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('BAJA');?></td>
					</tr>					
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('FECHAEVENTO');?></td>
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('FECHAALTA');?></td>
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('FECHABAJA');?></td>
					</tr>					
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('AUTOR');?></td>
					</tr>
					<tr>
						<td colspan="2" valign="bottom"><? include "../include/okcancel.php";?></td>
					</tr>
				</table>
			<!--FIN CAMPOS-->			
<? include "../include/camposfooter.php";?>
<!--FIN EDICION DE CAMPOS -->
<? include "../include/modificarfooter.php";?>
<div style="position:absolute;display:none;">
<? 
	$tabla->FiltrarCampo('ID_TIPOCONTENIDO','','escondido');
	$tabla->FiltrarCampo('ID_USUARIO_CREADOR','','escondido');
	$tabla->FiltrarCampo('ID_USUARIO_MODIFICADOR','','escondido');
	$tabla->FiltrarCampo('ID_SECCION','/*SPECIAL*/secciones.ID IN ('.$_usuariosecciones_.')','escondido');
	$tabla->FiltrarCampo('TITULO','','escondido');
	$tabla->FiltrarCampo('ML_TITULO','','escondido');
	$tabla->FiltrarCampo('COPETE','','escondido');
	$tabla->FiltrarCampo('ML_COPETE','','escondido');
	$tabla->FiltrarCampo('CUERPO','','escondido');
	$tabla->FiltrarCampo('ML_CUERPO','','escondido');
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
<?
//echo '<script>changetoIframeEditor(document.forms[0]._e_CUERPO);</script>';
?>