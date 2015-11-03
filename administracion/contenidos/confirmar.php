<?Php
// ********************************
//             CONFIRMAR ABM 
// ********************************

require "deftabla.php";
require '../include/bloqueoheader.php';
if ($_logueado_=='verdadero') {
?>
<?
	if ( $_cancelar_ == 'no' ) $_error_ = $tabla->Verificar();//verifica y completa un valor: $errores , y listo

	if (!$_error_) {
		if ($_borrar_=='si') {
			$_exito_ = $tabla->Borrar();
			//nos fijamos si alguna seccion tiene este contenido como principal y lo ponemos en 1
			$_tsecciones_->LimpiarSQL();
			$_tsecciones_->SQL = 'UPDATE secciones SET secciones.ID_CONTENIDO=1 WHERE secciones.ID_CONTENIDO='.$_primario_ID;			
			$_exito_ = $_tsecciones_->EjecutaSQL();
			$tabla->exito.=mysql_error($_tsecciones_->CONN);
			$_afectados_ = mysql_affected_rows($tabla->CONN);
			$tabla->exito.= $_afectados_;
		} elseif ($_nuevo_=='si') {
			$_exito_ = $tabla->Insertar();
			${'_primario_'.$tabla->primario} = mysql_insert_id($tabla->CONN);
			if ( ($_e_PRINCIPAL == 'S') and $_exito_ and ($_nuevo_=='si' or $_modificar_=='si')) {
					//se asignó este contenido como principal
					//todos los otros contenidos de la misma seccion se fijan en PRINCIPAL='N'
					$_tcontenidos_->LimpiarSQL();
					$_tcontenidos_->SQL = "UPDATE contenidos SET PRINCIPAL='N' WHERE ID_SECCION=".$_e_ID_SECCION." AND ID NOT IN (".$_primario_ID.")";
					$_error_ = !$_tcontenidos_->EjecutaSQL();
					$tabla->exito.=mysql_error($_tcontenidos_->CONN);
					
					//ahora fijamos el id_contenido de la seccion
					$_tsecciones_->LimpiarSQL();
					$_tsecciones_->SQL = "UPDATE secciones SET ID_CONTENIDO=".$_primario_ID." WHERE ID=".$_e_ID_SECCION;
					$_error_ = !$_tsecciones_->EjecutaSQL();
					$tabla->exito.=mysql_error($_tsecciones_->CONN);		
			}			
		} elseif ($_modificar_=='si') {
			$_exito_ = $tabla->Modificar();
			if ( ($_e_PRINCIPAL == 'S') and $_exito_ and ($_nuevo_=='si' or $_modificar_=='si')) {
					//se asignó este contenido como principal
					//todos los otros contenidos de la misma seccion se fijan en PRINCIPAL='N'
					$_tcontenidos_->LimpiarSQL();
					$_tcontenidos_->SQL = "UPDATE contenidos SET PRINCIPAL='N' WHERE ID_SECCION=".$_e_ID_SECCION." AND ID NOT IN (".$_primario_ID.")";
					$_error_ = !$_tcontenidos_->EjecutaSQL();
					$tabla->exito.=mysql_error($_tcontenidos_->CONN);
					
					//ahora fijamos el id_contenido de la seccion
					$_tsecciones_->LimpiarSQL();
					$_tsecciones_->SQL = "UPDATE secciones SET ID_CONTENIDO=".$_primario_ID." WHERE ID=".$_e_ID_SECCION;
					$_error_ = !$_tsecciones_->EjecutaSQL();
					$tabla->exito.=mysql_error($_tsecciones_->CONN);		
			}			
		} elseif ($_cancelar_=='si') {
			$_exito_ = true;
			$tabla->exito = 'Cancelado!!!';
		} else {
			echo "ERROR: no se definió ninguna acción";
		}			
		
		if ($_exito_) {
		//<!--DETALLES -->
			require "confirmarDetalles.php";
		//<!--FIN DETALLES -->			
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
<title>confirmando acción</title>
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