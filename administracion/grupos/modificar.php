<?Php
// ********************************
//             TABLA 
// ********************************
require "deftabla.php";
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
					//muestro todas las secciones asociadas!!
					$_tgrupossecciones_->LimpiarSQL();
					$_tgrupossecciones_->SQL = 'SELECT * FROM grupossecciones WHERE ID_GRUPO='.$_primario_ID;
					$_tgrupossecciones_->Open();
					if ($_tgrupossecciones_->nresultados>0) echo "<br>HAY ".$_tgrupossecciones_->nresultados." SECCION(ES) CON PERMISOS PARA ESTE GRUPO";
					$_tgruposusuarios_->LimpiarSQL();
					$_tgruposusuarios_->SQL = 'SELECT * FROM gruposusuarios WHERE ID_GRUPO='.$_primario_ID;
					$_tgruposusuarios_->Open();
					if ($_tgruposusuarios_->nresultados>0) echo "<br>HAY ".$_tgruposusuarios_->nresultados." USUARIO(S) CON PERMISOS DENTRO DE ESTE GRUPO<br>Est� seguro?<br>";
				}
				?>
				<!--CAMPOS-->
			<form name="confirmar" method="post" action="confirmar.php">				
				<table border="0" cellpadding="0" cellspacing="10">
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('GRUPO');?></td>
						<td rowspan="4" valign="bottom"><? include "../include/okcancel.php";?></td>
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('PERMISOS_MIEMBROS');?></td>
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('PERMISOS_USUARIOS');?></td>
					</tr>
					<tr>
						<td colspan="2"><?$tabla->EditarCampo('DESCRIPCION');?></td>						
					</tr>
				</table>
			<!--FIN CAMPOS-->
<? include "../include/camposfooter.php";?>
<!--FIN EDICION DE CAMPOS -->
<? include "../include/modificarfooter.php";?>
<div style="position:absolute;display:none;">
<? 
	$tabla->FiltrarSQL('GRUPO','','escondido');
	$tabla->FiltrarSQL('PERMISOS_MIEMBROS','','escondido');
	$tabla->FiltrarSQL('PERMISOS_USUARIOS','','escondido');
	$tabla->FiltrarSQL('DESCRIPCION','','escondido');
	$tabla->Ordenar($_orden_);	
?>
</div>
<input name="_primario_<?=$tabla->primario?>" type="hidden" value="<?=${'_primario_'.$tabla->primario}?>">
<input name="_cancelar_" type="hidden" value="no">
<input name="_modificar_" type="hidden" value="<?=$_modificar_?>">
<input name="_borrar_" type="hidden" value="<?=$_borrar_?>">
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
