<?Php
// ********************************
//             CONFIRMAR ABM 
// ********************************

require "deftabla.php";
require '../include/bloqueoheader.php';
if ($_logueado_=='verdadero') {
?>
<?
	if ($_cancelar_=='no') $_error_ = $tabla->Verificar();//verifica y completa un valor: $errores , y listo

	if (!$_error_) {
		if ($_borrar_=='si') {
			//SI SE BORRA UN GRUPO SE BORRAN TODAS LAS ASIGNACIONES
			$_exito_ = $tabla->Borrar();
			if ($_exito_) {
				//borramos las secciones asociadas.
				$_tgrupossecciones_->LimpiarSQL();
				$_tgrupossecciones_->SQL = 'DELETE FROM grupossecciones WHERE ID_GRUPO='.$_primario_ID;
				$_exito_ = $_tgrupossecciones_->EjecutaSQL();
				//borramos los usuarios asociados.
				$_tgruposusuarios_->LimpiarSQL();
				$_tgruposusuarios_->SQL = 'DELETE FROM gruposusuarios WHERE ID_GRUPO='.$_primario_ID;
				$_exito_ = $_tgruposusuarios_->EjecutaSQL();
			}
		} elseif ($_nuevo_=='si') {
			$_exito_ = $tabla->Insertar();
		} elseif ($_modificar_=='si') {
			$_exito_ = $tabla->Modificar();
		} elseif ($_cancelar_=='si') {
			$_exito_ = true;
			$tabla->exito = 'Cancelado!!!';
		} else {
			echo "ERROR: no se defini� ninguna acci�n";
		}			
		if (($_exito_) and ($_debug_!='si')) { if ($_admin_!='si') $_onload_="javascript:consultar();";  else $_onload_="javascript:admin();"; }
		if ($_exito_) { $_errorimg_ = '<img src="../images/ingresado.gif" border="0">'; $tabla->exito = '<span class="navegador1">'.$tabla->exito.'</span>';
		} else { 
			$_errorimg_ = '<img src="../images/error.gif" border="0">'; $tabla->exito = '<span class="error">'.$tabla->exito.'</span>'; 
			$tabla->exito.= '<br><a href="javascript:volver();"><img src="../images/ok.gif" alt="" width="50" height="25" border="0" onMouseOver="javascript:showimg(\'../images/ok_down.gif\');" onMouseOut="javascript:showimg(\'../images/ok.gif\');"></a>';
		}
	} else {
		$_errorimg_ = '<img src="../images/error.gif" border="0">';
		$_errores_ = $tabla->ImprimirErrores($camposmod);
		$tabla->exito = '<a href="javascript:volver();"><img src="../images/ok.gif" alt="" width="50" height="25" border="0" onMouseOver="javascript:showimg(\'../images/ok_down.gif\');" onMouseOut="javascript:showimg(\'../images/ok.gif\');"></a>';
	}
?>

<html>
<head>
<title>confirmando acci�n</title>
<?
require "../include/style.php"; 
require "../include/scripts.php";
?>
</head>
<body onLoad="<?=$_onload_?>" marginheight="0" marginwidth="0">
<?include "../include/pageheader.php";?>
<?include "../include/navegador.php";?>
<!-- SECCION CONFIRMACION -->
<?include "../include/confirmarheader.php";?>
<?=$_errores_?>
<?=$tabla->exito;?>
<br><br>
<form name="consultar" method="post" action="consulta.php">
<div style="position:absolute;display:none;">
<? 
	$tabla->FiltrarSQL('GRUPO','','escondido');
	$tabla->FiltrarSQL('PERMISOS_MIEMBROS','','escondido');
	$tabla->FiltrarSQL('PERMISOS_USUARIOS','','escondido');
	$tabla->FiltrarSQL('DESCRIPCION','','escondido');
	$tabla->Ordenar($_orden_);
?>
</div>
<input name="_consulta_" type="hidden"  value="si">
<input name="_debug_" type="hidden" value="<?=$_debug_?>">
<input name="_admin_" type="hidden" value="<?=$_admin_?>">
<input name="_usuario_" type="hidden" value="<?=$_usuario_?>">
<input name="_usuariologs_" type="hidden" value="<?=$_usuariologs_?>">
</form>
<?include "../include/confirmarfooter.php";?>
<?include "../include/pagefooter.php";?>
</body></html>
<?
} else { include '../include/bloqueofooter.php'; }
?>