<?Php
// ********************************
//             CONFIRMAR ABM 
// ********************************

//version 4.0 : agregado creacion de templates automatica: 29/08/2006

require "deftabla.php";
require '../include/bloqueoheader.php';
if ($_logueado_=='verdadero') {
	
?>
<?
	
	/*FIN ARREGLOS*/
	
	if ($_cancelar_=='no') $_error_ = $tabla->Verificar();//verifica y completa un valor: $errores , y listo

	if (!$_error_) {
		if ($_borrar_=='si') {
			//OJO, si la accion es borrar, entonces no puede haber contenido:
			$_tcontenidos_->LimpiarSQL();
			$_tcontenidos_->SQL = 'SELECT * FROM contenidos WHERE ID_TIPOCONTENIDO='.$_primario_ID;
			$_tcontenidos_->Open();
			if ($_tcontenidos_->nresultados>0) {
				$_exito_ = false;
				$tabla->exito = 'No se puede borrar el tipo de contenido ya que tiene contenidos asociados a el.<br> Sólo está permitido borrar un tipodecontenido sin usar.';
			} else {		
				$_exito_ = $tabla->Borrar();
				if ($_exito_) {					
					$_ttiposdetalles_->LimpiarSQL();
					$_ttiposdetalles_->SQL = 'DELETE FROM tiposdetalles WHERE ID_TIPOCONTENIDO='.$_primario_ID;
					$_exito_ = $_ttiposdetalles_->EjecutaSQL();
					if (!$_exito_) $tabla->exito = 'No se puede borrar.';
				}
			}
		} elseif ($_nuevo_=='si') {

			$_exito_ = $tabla->Insertar();
			
		} elseif ($_modificar_=='si') {

			$_exito_ = $tabla->Modificar();

		} elseif ($_cancelar_=='si') {
			$_exito_ = true;
			$tabla->exito = 'Cancelado!!!';
		} else {
			echo "ERROR: no se definió ninguna acción";
			$_exito_ = false;
			return false;
		}	

			if ( $_exito_ && ($_nuevo_=='si' || $_modificar_=='si') ) {
				
				global $CMultiLang;
				global $sobreescribir_template;
				
				echo "sobreescribir:[".$sobreescribir_template."]";
							
				
				$tpl_colapsado = file_get_contents("../templates/tpl_colapsado.html");
				$tpl_resumen = file_get_contents("../templates/tpl_resumen.html");
				$tpl_completo = file_get_contents("../templates/tpl_completo.html");
				
				/**
				 * correcion para los detalles
				 */
				$tpl_colapsado = str_replace("{FICHA}",str_replace("FICHA_","",$_e_TIPO),$tpl_colapsado);
				$tpl_resumen = str_replace("{FICHA}",str_replace("FICHA_","",$_e_TIPO),$tpl_resumen);
				$tpl_completo = str_replace("{FICHA}",str_replace("FICHA_","",$_e_TIPO),$tpl_completo);
				
				//creamos el archivo correspondiente a los templates
				$nombre_template_colapsado = "../../inc/templates/".$_e_TIPO.".colapsado.html";
				$nombre_template_resumen = "../../inc/templates/".$_e_TIPO.".resumen.html";
				$nombre_template_completo = "../../inc/templates/".$_e_TIPO.".completo.html";

				function CreateTemplate( $nombre, $template ) {
					global $sobreescribir_template;
					if (!file_exists($nombre) || $sobreescribir_template=='on') { 
						$rec = fopen( $nombre, 'w+');
						if ($rec) {
							fwrite($rec,$template); 
							fclose($rec);
							return true;
						} else {
							echo '<div class="error">ERROR: couldnt open file: '.$nombre.'</div>';
							return false;
						} 
					} else return true;
					
					
				}
				
				if (!CreateTemplate($nombre_template_colapsado,$tpl_colapsado)) { $_exito_ = false; }
				if (!CreateTemplate($nombre_template_resumen,$tpl_resumen)) { $_exito_ = false; }
				if (!CreateTemplate($nombre_template_completo,$tpl_completo)) { $_exito_ = false; }
				
				foreach($CMultiLang->m_arraylangs as $langue=>$code) {
					if (trim($code)!="") {
						$nombre_template_colapsado = "../../inc/templates/".$_e_TIPO.".colapsado.".$code.".html";
						$nombre_template_resumen = "../../inc/templates/".$_e_TIPO.".resumen.".$code.".html";
						$nombre_template_completo = "../../inc/templates/".$_e_TIPO.".completo.".$code.".html";				
						if (!CreateTemplate($nombre_template_colapsado,$tpl_colapsado)) { $_exito_ = false; }
						if (!CreateTemplate($nombre_template_resumen,$tpl_resumen)) { $_exito_ = false; }
						if (!CreateTemplate($nombre_template_completo,$tpl_completo)) { $_exito_ = false; }
																	
					}
				}				
			}
		

				
		$_pausa_ = false;			
		if (($_exito_) and ($_debug_!='si') and !$_pausa_) { if ($_admin_!='si') $_onload_="javascript:consultar();";  else $_onload_="javascript:admin();"; }
		if ($_exito_) { $_errorimg_ = '<img src="../images/ingresado.gif" border="0">'; $tabla->exito = '<span class="navegador1">'.$tabla->exito.'</span>';
		} else { 
			$_errorimg_ = '<img src="../images/error.gif" border="0">'; $tabla->exito = '<span class="error">'.$tabla->exito.'</span>'; 
			$tabla->exito.= '<br><a href="javascript:volver();"><img src="../images/ok.gif" alt="" width="50" height="25" border="0" onMouseOver="javascript:showimg(\'../images/ok_down.gif\');" onMouseOut="javascript:showimg(\'../images/ok.gif\');"></a>';
		}
	} else {
		$_errorimg_ = '<img src="../images/error.gif" border="0">';
		$_errores_ = $tabla->ImprimirErrores();
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

	$tabla->FiltrarCampo('TIPO','','escondido');
	$tabla->FiltrarCampo('DESCRIPCION','','escondido');
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