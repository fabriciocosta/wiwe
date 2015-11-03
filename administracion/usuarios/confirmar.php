<?Php
// ********************************
//             CONFIRMAR ABM 
// ********************************

require "deftabla.php";
require '../include/bloqueoheader.php';
if ($_logueado_=='verdadero') {
?>
<?
	echo $_p_PASSWORD;
	if ($_cancelar_=='no') $_error_ = $tabla->Verificar();//verifica y completa un valor: $errores , y listo

	if (!$_error_) {
		if ($_borrar_=='si') {
			$_exito_ = $tabla->Borrar();
			if ($_exito_) {
				//borro las asignaciones al grupo
				$_tgruposusuarios_->LimpiarSQL();
				$_tgruposusuarios_->SQL = 'DELETE FROM gruposusuarios WHERE gruposusuarios.ID_USUARIO='.$_primario_ID;
				$_exito_ = $_tgruposusuarios_->EjecutaSQL();
				//borro el grupo personalizado??? nop
			}
		} elseif ($_nuevo_=='si') {
			$_exito_ = $tabla->Insertar();
			$_idusuario_ = mysql_insert_id($tabla->CONN);
			if ($_exito_) {
				//damos de alta el grupo personalizado
				$_tgrupos_->LimpiarSQL();
				$_tgrupos_->SQL = "INSERT INTO grupos (GRUPO,DESCRIPCION) VALUES ('grupo_".$_e_NICK."','Grupo de acceso personalizado: ".$_e_NICK."')";
				$_exito_ = $_tgrupos_->EjecutaSQL();
				//lo integramos al grupo personalizado
				if ($_exito_) {
					$_idgrupo_ = mysql_insert_id($_tgrupos_->CONN);
					$_tgruposusuarios_->LimpiarSQL();
					$_tgruposusuarios_->SQL = "INSERT INTO gruposusuarios (ID_GRUPO,ID_USUARIO) VALUES (".$_idgrupo_.",".$_idusuario_.")";
					$_exito_ = $_tgruposusuarios_->EjecutaSQL();
				}
			}
		} elseif ($_modificar_=='si') {
			$_tusuarios_->LimpiarSQL();
			$_tusuarios_->SQL = 'SELECT usuarios.NICK FROM usuarios WHERE usuarios.ID='.$_primario_ID;
			$_tusuarios_->Open();
			if ( $_tusuarios_->nresultados > 0 ) {
				$row = $_tusuarios_->Fetch($_tusuarios_->resultados);
			}
			$_nickanterior_ = $row['usuarios.NICK'];
			$_exito_ = $tabla->Modificar();
			if (($_exito_) and ($_nickanterior_!=$_e_NICK)) {
				//si se modifico el nombre cambia el grupo_personalizado
				$_tgrupos_->LimpiarSQL();
				$_tgrupos_->SQL = "UPDATE grupos SET grupos.GRUPO='grupo_".$_e_NICK."' WHERE grupos.GRUPO='grupo_".$_nickanterior_."'";
				$_exito_ = $_tgrupos_->EjecutaSQL();
			}			
		} elseif ($_cancelar_=='si') {
			$_exito_ = true;
			$tabla->exito = 'Cancelado!!!';			
		} else {
			echo "ERROR: no se definió ninguna acción";
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
	$tabla->FiltrarSQL('ID_TIPOARCHIVO','','escondido');
	$tabla->FiltrarSQL('ID_SECCION','','escondido');
	$tabla->FiltrarSQL('NOMBRE','','escondido');
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
