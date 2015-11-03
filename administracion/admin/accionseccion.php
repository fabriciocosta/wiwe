<?Php

/**
 * ESTE MODULO INTEGRA LA FUNCIONALIDAD DEL ALTA Y MODIFICACION DE UNA SECCION
 * 
 * 
 */

global $__modulo__;
global $_accion_;
global $_idseccion_;

$__modulo__ = "config";

require "../../inc/include/deftabla.php";

/**
 * 
 * NO CREAMOS EL OBJETO DEL SITIO ENTERO; NO ES NECESARIO
 * 
 * SOLO LOS OBJETOS DE LAS SECCIONES
 * 
 */

$TiposSecciones = new CTiposSecciones($_ttipossecciones_);
$Secciones = new CSecciones($_tsecciones_,$TiposSecciones);		


if ($_accion_=="edit" || $_accion_=="new") {
	if ($_accion_=="edit") {
		$Seccion = $Secciones->GetSeccion($_idseccion_);
	} else {
		$Seccion = new CSeccion();
		$Seccion->m_id_seccion = $_idseccion_;
		$Seccion->m_id_usuario_creador = 1;
		$Seccion->m_id_usuario_modificador = 1;
		$Seccion->m_orden = 0;

	}	
	
	$Padre = $Secciones->GetSeccion( $Seccion->m_id_seccion );
	$Hijos = $Secciones->GetSeccionHijos( $Seccion->m_id_seccion );
	
	$Seccion->ToGlobals();
	
	$_e_PROFUNDIDAD = $Padre->m_profundidad + 1;

?>
<form name="form_editar_seccion" method="get" action="accionseccion.php">

<input type="hidden" value="confirm<?=$_accion_?>" id="_accion_" name="_accion_">

<input name="_primario_ID" type="hidden" value="<?=$Seccion->m_id?>">


<input name="_e_ID_USUARIO_CREADOR" type="hidden" value="<?=$_e_ID_USUARIO_CREADOR?>">
<input name="_e_ID_CONTENIDO" type="hidden" value="<?=$_e_ID_CONTENIDO?>">
<input name="_e_PROFUNDIDAD" type="hidden" value="<?=$_e_PROFUNDIDAD?>">
<input name="_e_RAMA" type="hidden" value="<?=$_e_RAMA?>">
<input name="_e_ORDEN" type="hidden" value="<?=$_e_ORDEN?>">


<table cellpadding="0" cellspacing="1" bgcolor="#e31780">
	<tr>
		<td>
			<table cellpadding="5" cellspacing="0" bgcolor="#FFFFFF">
				<tr>
					<td align="right"  class="conf_field"><?=htmlentities( $CLang->m_Words['SECTION'] )?></td>
					<td align="left" class="conf_input"><? $_tsecciones_->EditarCampo('ID_SECCION'); ?></td>
				</tr>
				<tr>
					<td align="right"  class="conf_field"><?=htmlentities( $CLang->m_Words['SECTIONTYPE'] )?></td>
					<td align="left" class="conf_input"><? $_tsecciones_->EditarCampo('ID_TIPOSECCION'); ?></td>
				</tr>
				<tr>
					<td align="right"  class="conf_field"><?=htmlentities( $CLang->m_Words['SECTIONNAME'] )?></td>
					<td align="left" class="conf_input"><? $_tsecciones_->EditarCampo('NOMBRE');  $_tsecciones_->EditarCampo('ML_NOMBRE');?></td>
				</tr>
				<tr>
					<td align="right"  class="conf_field"><?=htmlentities( $CLang->m_Words['SECTIONDESCRIPTION'] )?></td>
					<td align="left" class="conf_input"><? $_tsecciones_->EditarCampo('DESCRIPCION'); $_tsecciones_->EditarCampo('ML_DESCRIPCION');?></td>
				</tr>
				<tr>
					<td align="right"  class="conf_field"><?=htmlentities( $CLang->m_Words['FOLDER'] )?></td>
					<td align="left" class="conf_input"><? $_tsecciones_->EditarCampo('CARPETA');?></td>
				</tr>
				<tr>
					<td align="right"  class="conf_field"><?=htmlentities( $CLang->m_Words['CATEGORY'] )?></td>
					<td align="left" class="conf_input"><? $_tsecciones_->EditarCampo('CATEGORIA');?></td>
				</tr>								
				<tr>
					<td align="right"  class="conf_field"><?=htmlentities( $CLang->m_Words['CARDVERIFIED'] )?></td>
					<td align="left" class="conf_input"><? $_tsecciones_->EditarCampo('BAJA');?></td>
				</tr>				
				<tr>
					<td align="left"  class="conf_field"><a href="javascript:conf_cancelseccion();"><?=$CLang->Get('CANCEL')?></a></td>
					<td align="right" class="conf_input"><a href="javascript:conf_okseccion(<?=$Seccion->m_id_seccion?>);"><?=$CLang->Get('OK')?></a></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<?
} else if ($_accion_=="delete") {
	
	$Seccion = $Secciones->GetSeccion($_idseccion_);
?>
<form name="form_editar_seccion" method="get" action="accionseccion.php">

<input type="hidden" value="confirm<?=$_accion_?>" id="_accion_" name="_accion_">

<input name="_primario_ID" type="hidden" value="<?=$Seccion->m_id?>">

<table cellpadding="0" cellspacing="1" bgcolor="#e31780">
	<tr>
		<td>
			<table cellpadding="5" cellspacing="0" bgcolor="#FFFFFF">
				<tr>
					<td align="right"  class="conf_field"><?=$CLang->m_Messages['RECORD_DELETION_WARNING']?></td>
				</tr>
				<tr>
					<td align="left"  class="conf_field"><a href="javascript:conf_cancelseccion();"><?=$CLang->m_Words['CANCEL']?></a></td>
					<td align="right" class="conf_input"><a href="javascript:conf_okseccion(<?=$Seccion->m_id_seccion?>);"><?=$CLang->m_Words['OK']?></a></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<?} else { 

	if ($_accion_=="confirmedit") {
		$Seccion = new CSeccion();
		
		if (!$Secciones->Actualizar($Seccion)) { $mensaje = "ERROR> ".$Secciones->m_tsecciones->exito; $_exito_ = false;}
		else { $Secciones->OrdenarRama($Seccion->m_id_seccion); $_exito_ = true; }

	} else if ( $_accion_=="confirmnew") {
		$Seccion = new CSeccion();
		
		if (!$Secciones->Insertar($Seccion)) { $mensaje = "ERROR".$Secciones->m_tsecciones->exito; $_exito_ = false; } 
		else {
			//POST PROCESS for SECTIONS CONSISTENCY
			$_exito_ = true;
			$_primario_ID = mysql_insert_id( $Secciones->m_tsecciones->CONN );
			$Secciones->OrdenarRama($Seccion->m_id_seccion);
			$Secciones->AsignarGruposSecciones( $Seccion->m_id_seccion, $_primario_ID );			
		}

	} else if ($_accion_=="confirmdelete") {
		$_tcontenidos_->LimpiarSQL();
		$_tcontenidos_->SQL = 'SELECT * FROM contenidos WHERE ID<>1 AND ID_SECCION='.$_primario_ID;
		$_tcontenidos_->Open();
		if ($_tcontenidos_->nresultados>0) {
			$_exito_ = false;
			$Secciones->m_tsecciones->exito = $CLang->m_Messages['SECTIONISNOTEMPTY'];
		} else {		
			$_exito_ = $Secciones->m_tsecciones->Borrar();
			if ($_exito_) $_exito_ = $Secciones->DesasignarGruposSecciones($_primario_ID);
		}
		
		$mensaje = $Secciones->m_tsecciones->exito;
	}
?>

<?if ($_exito_) {?>
<div id="div_message" style="display:none;">
<?} else { ?>
<div id="div_message" style="display:inline;">
<?} ?>
	<form name="form_editar_seccion" method="get" action="accionseccion.php">
<input type="hidden" value="" id="_accion_" name="_accion_">
<table cellpadding="0" cellspacing="1" bgcolor="#e31780">
	<tr>
		<td>
			<table cellpadding="5" cellspacing="0" bgcolor="#FFFFFF">
				<tr>
					<td align="right"  class="conf_field">
					<?=$mensaje?> <?=$_accion_?>
					<br><br>
					<a href="javascript:window.location.reload();">RELOAD</a> <a href="javascript:hidediv('div_message');">OK</a></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
</div>
<?

} 
?>

