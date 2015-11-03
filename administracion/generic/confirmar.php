<?Php
// ********************************
//             CONFIRMAR ABM 
// ********************************

require "deftabla.php";
?>
<?
	$_error_ = $tabla->Verificar();//verifica y completa un valor: $errores , y listo

	if (!$_error_) {
		if ($_borrar_=='si') {
			$_exito_ = $tabla->Borrar();	
		} elseif ($_nuevo_=='si') {
			$_exito_ = $tabla->Insertar();
		} elseif ($_modificar_=='si') {
			$_exito_ = $tabla->Modificar();
		} else {
			echo "ERROR: no se definió ninguna acción";
		}			
		if (($_exito_) and ($_debug_!='si')) { if ($_admin_!='si') $_onload_="javascript:consultar();";  else $_onload_="javascript:admin();"; }
		if ($_exito_) { $_errorimg_ = '<img src="../images/ingresado.gif" border="0">'; $tabla->exito = '<span class="navegador1">'.$tabla->exito.'</span>';}
		else { $_errorimg_ = '<img src="../images/error.gif" border="0">'; $tabla->exito = '<span class="error">'.$tabla->exito.'</span>'; }
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

	$tabla->Combo('','continente','Continente','CONTINENTES','ID','DESCRIPCION','','','','','','','escondido');
	$tabla->Combo('','hemisferio','Hemisferio','HEMISFERIO','ID','DESCRIPCION','','','','','','','escondido');
	$tabla->FiltrarCampo('CAMPO1','LOOKUP.ID_CONTINENTE='.${'_fcombo_'.'continente'}.' AND LOOKUP.ID_HEMISFERIO='.${'_fcombo_'.'hemisferio'},'escondido');
	$tabla->FiltrarCampo('CAMPO2');
	$tabla->FiltrarCampo('CAMPO3');
	$tabla->Ordenar($_orden_);
?>
</div>
<input name="_consulta_" type="hidden"  value="si">
<input name="_debug_" type="hidden" value="<?=$_debug_?>">
<input name="_admin_" type="hidden" value="<?=$_admin_?>">
</form>
<?include "../include/confirmarfooter.php";?>
<?include "../include/pagefooter.php";?>
</body></html>