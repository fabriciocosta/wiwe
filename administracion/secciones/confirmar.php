<?Php
// ********************************
//             CONFIRMAR ABM 
// ********************************

require "deftabla.php";
//$tabla->debug = 'si';
require '../include/bloqueoheader.php';
if ($_logueado_=='verdadero') {
?>
<?
	/*ARREGLOS ANTES DE VERIFICAR: asignamos el valor de profundidad*/
	if ($_e_CATEGORIA=='N') {
		$tabla->SQL = 'SELECT secciones.PROFUNDIDAD FROM secciones WHERE secciones.ID='.$_e_ID_SECCION;
		$tabla->SQLCOUNT = 'SELECT COUNT(PROFUNDIDAD) FROM secciones WHERE ID='.$_e_ID_SECCION;
		$tabla->Open();
		if ($tabla->nresultados>0)  {
			$row = $tabla->Fetch($tabla->resultados);
			$tabla->campos['PROFUNDIDAD']['defecto'] = $row['secciones.PROFUNDIDAD'] + 1;
			$_e_PROFUNDIDAD = $tabla->campos['PROFUNDIDAD']['defecto'];
		}
	} elseif ($_e_CATEGORIA=='S') {
		$tabla->campos['PROFUNDIDAD']['defecto'] = '0';
		$_e_ID_SECCION = '0';//luego se cambia una vez insertado el registro
		$_e_PROFUNDIDAD = '0';
	}
	
	/*FIN ARREGLOS*/
	
	if ($_cancelar_=='no') $_error_ = $tabla->Verificar();//verifica y completa un valor: $errores , y listo

	if (!$_error_) {
		if ($_borrar_=='si') {
			//OJO, si la accion es borrar, entonces no puede haber contenido:
			$_tcontenidos_->LimpiarSQL();
			$_tcontenidos_->SQL = 'SELECT * FROM contenidos WHERE ID<>1 AND ID_SECCION='.$_primario_ID;
			$_tcontenidos_->Open();
			if ($_tcontenidos_->nresultados>0) {
				$_exito_ = false;
				$tabla->exito = 'No se puede borrar la sección ya que tiene contenidos asociados a ella.<br> Sólo está permitido borrar una sección vacía.';
			} else {		
				$_exito_ = $tabla->Borrar();
				if ($_exito_) {
					//borramos los permisos que tenia asociados.
					$_tgrupossecciones_->LimpiarSQL();
					$_tgrupossecciones_->SQL = 'DELETE FROM grupossecciones WHERE ID_SECCION='.$_primario_ID;
					$_exito_ = $_tgrupossecciones_->EjecutaSQL();
					if (!$_exito_) $tabla->exito = 'No se puede borrar.';
				}
			}
		} elseif ($_nuevo_=='si') {
			
			$_exito_ = $tabla->Insertar();
			/*ARREGLOS: por la categoria-modifico el registro que recien cree*/
			$_idseccion_ = mysql_insert_id($tabla->CONN);;
			if ($_exito_ and ($_e_CATEGORIA=='S')) {
				$_e_ID_SECCION = $_idseccion_;
				$_primario_ID = $_e_ID_SECCION;
				$_exito_ = $tabla->Modificar();
			}
			/*FIN ARREGLOS*/			

			if ($_exito_) {//si se insertó???
				//>>tomar de la seccion padre de los grupossecciones los id de grupos que la contienen  (conjunto de ID's)
				//>>(la linea anterior ya implica al grupo_usuario que lo creo) y listo				
				$_tgrupossecciones_->LimpiarSQL();				
				$_tgrupossecciones_->SQL = 'SELECT DISTINCT grupossecciones.ID_GRUPO FROM grupossecciones WHERE grupossecciones.ID_SECCION='.$_e_ID_SECCION;
				$_tgrupossecciones_->Open();
				$_gruposseccionesid_ = '';
				if ($_tgrupossecciones_->nresultados>0) {
					while ($row = $_tgrupossecciones_->Fetch($_tgrupossecciones_->resultados)) {
						$_gruposseccionesid_[$row['grupossecciones.ID_GRUPO']]='si';
					}
				}
				
				//>>generar registros nuevos en grupossecciones (por cada idgrupopadre) -> id_seccion = idnuevaseccion y id_grupo = idgrupopadre
				foreach($_gruposseccionesid_ as $_idgrupopadre_ => $_vale_) {
					if ($_vale_=='si') {
						$_tgrupossecciones_->LimpiarSQL();
						$_tgrupossecciones_->SQL = 'INSERT INTO grupossecciones (ID_GRUPO,ID_SECCION) ';
						$_tgrupossecciones_->SQL.= 'VALUES('.$_idgrupopadre_.','.$_idseccion_.')';
						$_tgrupossecciones_->EjecutaSQL();
					}
				}

				//generamos la carpeta!!! si no existe ya	
				if ($_e_ID_TIPOSECCION!=$_CONST_TIPOFORO) {//si no es un foro
					$_carpeta_ = DirSeccion($_e_CARPETA,$_idseccion_);
					if (is_dir($_SITEROOT_.$_carpeta_)) {
						///no hago nada
						echo "Carpeta ya existe...";
						chmod_ftp($_carpeta_);
					} else {
						echo "Creando carpeta...";
						mkdir_ftp($_carpeta_);
						if (is_dir($_SITEROOT_.$_carpeta_)) {
							echo "Se creó la carpeta";
							chmod_ftp($_carpeta_);
						}
	    			}				
				}
				/*FIN ARREGLOS*/
			}
			
		} elseif ($_modificar_=='si') {
			/*ARREGLOS: por la categoria*/
			if ($_e_CATEGORIA=='S') { 
				$_e_ID_SECCION = $_primario_ID;
			}			
			//generamos la carpeta!!! si no existe ya	
			$_idseccion_ = $_primario_ID;
			//REHACIENDO ESTA PARTE
			
			$_carpetanueva_ = DirSeccion($_e_CARPETA,$_idseccion_);
			
			$tabla->SQL = 'SELECT secciones.CARPETA FROM secciones WHERE secciones.ID='.$_idseccion_;
			$tabla->SQLCOUNT = 'SELECT COUNT(secciones.CARPETA) FROM secciones WHERE ID='.$_idseccion_;
			$tabla->Open();
			if ($tabla->nresultados>0)  {
				$row = $tabla->Fetch($tabla->resultados);
				$_carpeta_ = DirSeccion($_e_CARPETA,$_idseccion_);	
			}	
			if ($_carpeta_==$_carpetanueva_) {
				$_exito_ = $tabla->Modificar();
			} else {			    			
				if (is_dir($_SITEROOT_.$_carpeta_)) {
					///no hago nada
					echo "Carpeta ya existe...";
					chmod_ftp($_carpetanueva_);
					$_exito_ = $tabla->Modificar();
					$tabla->exito = "Carpeta ya existe...";
				} else {
					echo "Creando carpeta...";
					if (mkdir_ftp($_carpetanueva_)) {
						echo "Se creó la carpeta";						
						$_exito_ = $tabla->Modificar();
					}
				}				
			}
		/*FIN ARREGLOS*/
		} elseif ($_cancelar_=='si') {
			$_exito_ = true;
			$tabla->exito = 'Cancelado!!!';
		} else {
			echo "ERROR: no se definió ninguna acción";
		}	
		
				
		//$_pausa_ = true;			
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

	$tabla->FiltrarCampo('ID_SECCION','/*SPECIAL*/secciones.ID IN ('.$_usuariosecciones_.')','escondido');
	$tabla->FiltrarCampo('NOMBRE','','escondido');
	$tabla->FiltrarCampo('CARPETA','','escondido');	
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