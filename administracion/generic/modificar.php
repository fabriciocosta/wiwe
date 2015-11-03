<?Php
// ********************************
//             TABLA 
// ********************************
require "deftabla.php";
?>
<html>
<head><title>Edición <?=$tabla->nombre?></title>
<?
require "../include/style.php";
require "../include/scripts.php";

if (!($_filtrando_=='si')) {
	if ($_modificar_=='si') {
		$tabla->Edicion(${'_primario_'.$tabla->primario});
		$_seccion_ = ' > MODIFICANDO '.strtoupper($tabla->nombre);
	} else if ($_borrar_=='si') {
		$tabla->Edicion(${'_primario_'.$tabla->primario});
		$_seccion_ = ' > BORRANDO '.strtoupper($tabla->nombre);					
	} elseif ($_nuevo_=='si') {
		$tabla->Nuevo();
		$_seccion_ = ' > AGREGANDO '.strtoupper($tabla->nombre);										
	} else {
		$_seccion_ = ' > INGRESANDO '.strtoupper($tabla->nombre);										
	}
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
						<td><?	$tabla->Combo('hereda','hemisferio','Hemisferio','HEMISFERIO','ID','DESCRIPCION');?></td>
						<td><?	$tabla->Combo('hereda','continente','Continente','CONTINENTES','ID','DESCRIPCION');?></td>
						<td><?	$tabla->EditarCampo('CAMPO1','LOOKUP.ID_CONTINENTE='.${'_fcomboe_'.'continente'}.' AND LOOKUP.ID_HEMISFERIO='.${'_fcomboe_'.'hemisferio'});?></td>
						<td rowspan="2" valign="bottom"><? include "../include/okcancel.php";?></td>
					</tr>
					<tr>
						<td><?	$tabla->EditarCampo('CAMPO2');?></td>
						<td><?	$tabla->EditarCampo('CAMPO3');?></td>
						<td></td>											
					</tr>
				</table>
			<!--FIN CAMPOS-->
<? include "../include/camposfooter.php";?>
<!--FIN EDICION DE CAMPOS -->
<? include "../include/modificarfooter.php";?>
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
<input name="_primario_<?=$tabla->primario?>" type="hidden" value="<?=${'_primario_'.$tabla->primario}?>">
<input name="_modificar_" type="hidden" value="<?=$_modificar_?>">
<input name="_borrar_" type="hidden" value="<?=$_borrar_?>">
<input name="_nuevo_" type="hidden" value="<?=$_nuevo_?>">
<input name="_admin_" type="hidden" value="<?=$_admin_?>">
<input name="_filtrando_" type="hidden" value="<?=$_filtrando_?>">
<input name="_debug_" type="hidden" value="<?=$_debug_?>">
</form>
<? include "../include/pagefooter.php";?>
</body>
</html>
