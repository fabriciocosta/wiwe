<?Php
// ********************************
//             CONFIRMAR ABM 
// ********************************
//echo $HTTP_POST_FILES['_archivo_ARCHIVO']['tmp_name'];

require "deftabla.php";
require '../include/bloqueoheader.php';
if ($_logueado_=='verdadero') {
?>
<?
	//if ($_e_EXTERNO=='N') $_e_URL = '_ERROR_';
	$_midir_ = $_SERVER['SITE_ROOT'];
	
	if ($_cancelar_=='no') $_error_ = $tabla->Verificar();//verifica y completa un valor: $errores , y listo

	if (!$_error_) {
		if ($_borrar_=='si') {
			//tomo el URL
			$tabla->LimpiarSQL();
			$_f_ID = $_primario_ID;
			$tabla->FiltrarSQL('ID');
			$tabla->Open();
			if ($tabla->nresultados>0) {
				$row = $tabla->Fetch($tabla->resultados);
							
				if ($row['archivos.EXTERNO']=="N") {					
					//*********ARCHIVO LOCAL*************//
					$_urlviejo_ = $row['archivos.URL'];
					$_exito_ = borrar_thm($_urlviejo_);
					if ($_exito_) {//existia el thm y se borro, o no existia
						$_exito_ = delete_ftp($_urlviejo_);
						if ($_exito_) {
							$_exito_ = $tabla->Borrar();
						} else {
							$tabla->exito = "No se pudo eliminar el archivo ".$_urlviejo_;
						}
					} else {
						$tabla->exito = "No se pudo eliminar el archivo ".$_urlthm_;						
					}				
				} else {
					//*********ARCHIVO EXTERNO*************//
					$_exito_ = $tabla->Borrar();
				}				
			}
		
		} elseif ($_nuevo_=='si') {
			//buscamosnombnre del tipodearchivo, TIPO
			$_ttiposarchivos_->LimpiarSQL();
			$_ttiposarchivos_->SQL='SELECT tiposarchivos.ID,tiposarchivos.TIPO,tiposarchivos.CARPETA FROM tiposarchivos WHERE tiposarchivos.ID='.$_e_ID_TIPOARCHIVO;
			$_ttiposarchivos_->Open();
			$row = $_ttiposarchivos_->Fetch($_ttiposarchivos_->resultados);
			$_tipoarchivocarpeta_= $row['tiposarchivos.CARPETA'];
			//buscamos el nombre de la seccion
			$_tsecciones_->LimpiarSQL();
			$_tsecciones_->SQL='SELECT secciones.ID,secciones.CARPETA FROM secciones WHERE secciones.ID='.$_e_ID_SECCION;
			$_tsecciones_->Open();
			$row = $_tsecciones_->Fetch($_tsecciones_->resultados);
			$_seccionnombre_= DirSeccion($row['secciones.CARPETA'],$row['secciones.ID']);			
			//ahora copiamos el archivo donde le corresponda
			
			if ($_e_EXTERNO=='N') {
			
				//*********ARCHIVO LOCAL*************//

				if (is_uploaded_file($_FILES["_archivo_ARCHIVO"]["tmp_name"])) {				
				
					//*********SE SUBIO UN ARCHIVO*************//
					
					if ($_e_GUARDAR_SECCION=='S') $_urlnuevo_ = $_seccionnombre_.'/arch/'.$_FILES["_archivo_ARCHIVO"]["name"];
					if ($_e_GUARDAR_SECCION=='N') $_urlnuevo_ = '/archivos/'.$_tipoarchivocarpeta_.'/'.$_FILES["_archivo_ARCHIVO"]["name"];
					
					//OJO: si el archivo ya existe....					
					if (is_file($_midir_.$_urlnuevo_)) {
						/*ERROR*/
						$_exito_ = false;
						$tabla->exito = 'El archivo '.$_urlnuevo_.' ya existe!!!! Cambie el nombre del archivo que quiere subir o borre el archivo existente.';
					} else {
						$_e_URL = $_urlnuevo_;
						$_exito_ = $tabla->Insertar();
					}
					
					if (($_exito_) and rename($_FILES["_archivo_ARCHIVO"]["tmp_name"],$_midir_.'/tmp/archivo'.$_primario_ID)) {
						$_exito_ = (!is_dir($_midir_.$_seccionnombre_."/arch")) ? mkdir_ftp($_seccionnombre_."/arch") : true;
						if ($_exito_) {
						
							/*RENOMBRO EL ARCHIVO*/
							
							if (rename_ftp($_urlnuevo_,'/tmp/archivo'.$_primario_ID)) {
								chmod_ftp($_urlnuevo_);
								thumbnail($_midir_,$_urlnuevo_,121,dirname($_urlnuevo_)."/thm",basename($_urlnuevo_));												
								$tabla->exito = "Correcto: se guardó el archivo: ".$_urlnuevo_;
							}
						} else {
							/*ERROR*/
							$tabla->exito = "No se pudo crear la carpeta de destino";						
						}
					} else {
						/*ERROR*/
						$_exito_= false;					
						$tabla->exito.= "No se pudo renombrar el archivo a: ".$_midir_.$_urlnuevo_;
					}				
				} else {
					/*ERROR*/
					$tabla->exito = "No se pudo subir el archivo... Intente otra vez...";
				}		
			} else {
				$_exito_ = $tabla->Insertar();
			}
		} elseif ($_modificar_=='si') {
			$tabla->LimpiarSQL();
			$_f_ID = $_primario_ID;
			$tabla->FiltrarSQL('ID');//para sacar el URL
			$tabla->Open();
			$row = $tabla->Fetch($tabla->resultados);
			$_urlviejo_ = $row['archivos.URL'];
			$_viejoexterno_ = $row['archivos.EXTERNO'];
			echo "urlviejo: ".$_urlviejo_. " nombre: ".$_FILES["_archivo_ARCHIVO"]["name"];
			//buscamos el nombre de la seccion
			$_tsecciones_->LimpiarSQL();
			$_tsecciones_->SQL='SELECT secciones.ID,secciones.CARPETA FROM secciones WHERE secciones.ID='.$_e_ID_SECCION;
			$_tsecciones_->Open();
			$rowsec = $_tsecciones_->Fetch($_tsecciones_->resultados);
			$_seccionnombre_= DirSeccion($rowsec['secciones.CARPETA'],$rowsec['secciones.ID']);		
			//sino, capaz que solo lo tenemos que mover
			if ($_e_GUARDAR_SECCION=='S') $_urlnuevo_ = $_seccionnombre_."/arch/".$_FILES["_archivo_ARCHIVO"]["name"];
			if ($_e_GUARDAR_SECCION=='N') $_urlnuevo_ = '/archivos/'.$row['tiposarchivos.CARPETA']."/".$_FILES["_archivo_ARCHIVO"]["name"];
			
			if ($_e_EXTERNO=='N') {
				//*********ARCHIVO LOCAL***********//

				//====CARPETA ARCH=====//
				if ($_e_GUARDAR_SECCION=='S') $_exito_ = (!is_dir($_midir_.$_seccionnombre_."/arch")) ? mkdir_ftp($_seccionnombre_."/arch") : true;
				else $_exito_ = true;//no importa

				if ($_exito_) {
					//*****NO SE SUBIO NINGUN ARCHIVO*****//	    
					if (!$_FILES["_archivo_ARCHIVO"]["name"]) {//nos fijamos si se hizo un cambio de seccion
						echo "no hay archivo a subir; ";					
						$_urlnuevo_ = $_seccionnombre_."/arch/".basename($_urlviejo_);
						if ($row['archivos.ID_SECCION']!=$rowsec['secciones.ID']) {//se cambio de seccion
							echo "cambio de seccion: probamos mover;";					
							//lo movemos entonces
							if (is_file($_midir_.$_urlviejo_)) {//si el archivo existe, a moverlo
								$_exito_ = rename_ftp($_urlnuevo_,$_urlviejo_);
								if ($_exito_) {//si lo movimos
									$tabla->exito = "Correcto: se guardó el archivo: ".$_midir_.$_urlnuevo_;						
									$_e_URL = $_urlnuevo_;
									$_exito_ = $tabla->Modificar();
								} else {
	
									$tabla->exito = "No se pudo cambiar de seccion: ".$_midir_.$_urlnuevo_;
								}
							} else {
								$_e_URL = $_urlnuevo_;
								$_exito_ = $tabla->Modificar();
							}
						} else {
							$_e_URL = $_urlnuevo_;
							$_exito_ = $tabla->Modificar();
						}
					//*****SE SUBIO ARCHIVO*****//	    
					} elseif (is_uploaded_file($_FILES["_archivo_ARCHIVO"]["tmp_name"])) {
		
						if ($_urlviejo_!=$_urlnuevo_) {//borramos el archivo anterior, si existe y no lo logramos abortamos
								$_exito_ = borrar_thm($_urlviejo_);
							if (is_file($_midir_.$_urlviejo_) and $_exito_) {
								$_exito_ = delete_ftp($_urlviejo_);
								$_exito_ ? $tabla->exito.= 'borrado '.$_urlviejo_ : $tabla->exito.= 'no pudimos borrar '.$_urlviejo_;
							} else $_exito_ = true;//seguimos, no hay archivo a borrar
						}
						
						if ($_exito_ and rename($_FILES["_archivo_ARCHIVO"]["tmp_name"],$_midir_.'/tmp/archivo'.$_primario_ID)) {//nos deshicimos del archivo anterior, ahora tratamos de posicionar el nuevo que tenemos
							//generamos el thumbnail si es una imagen
							if (rename_ftp($_urlnuevo_,'/tmp/archivo'.$_primario_ID)) {
								chmod_ftp($_urlnuevo_);
								thumbnail($_midir_,$_urlnuevo_,121,dirname($_urlnuevo_)."/thm",basename($_urlnuevo_));
								$tabla->exito = "Correcto: se guardó el archivo: ".$_midir_.$_urlnuevo_;
								$_e_URL = $_urlnuevo_;
								$_exito_ = $tabla->Modificar();
							}
						} else {
							/*ERROR*/
							$_exito_= false;
							$tabla->exito.= "No se pudo renombrar el archivo a: ".$_urlnuevo_;
						}					
					}									
				} else {
					/*ERROR*/
					$tabla->exito = "No se pudo crear la carpeta de destino /arch";
				}				
				echo "urlnuevo: ".$_urlnuevo_;
				
			} elseif (($_viejoexterno_=='S' && $_e_EXTERNO == 'S')) {			
				//*********ARCHIVO EXTERNO >>>>> ARCHIVO EXTERNO***********//
				$_exito_ = $tabla->Modificar();		
			} elseif (($_viejoexterno_=='N' && $_e_EXTERNO == 'S')) {						
				//*********ARCHIVO EXTERNO >>>>> ARCHIVO INTERNO***********//			
				$_exito_ = borrar_thm($_urlviejo_);//continuo
				if ($_exito_) { 
					if (is_file($_midir_.$_urlviejo_)) {
						if (delete_ftp($_urlviejo_)) {				
							$_exito_ = $tabla->Modificar();
							$tabla->exito = "Archivo anterior borrado: ".$_urlviejo_;
						} else {
							$_exito_ = false;
							$tabla->exito = "No se pudo eliminar el archivo anterior: ".$_urlviejo_;
						}			
					} else {
						$_exito_ = $tabla->Modificar();				
					}
				} else {
					$tabla->exito = "No se pudo borrar el archivo: ".$_urlthm_;	
				}
			}
			
		} elseif ($_cancelar_=='si') {
			$_exito_ = true;
			$tabla->exito = 'Cancelado!!!';			
		} else {
			$_exito_ = false;
			$tabla->exito =  "ERROR: no se definió ninguna acción";
		}
		//paramos para debuguear
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
	$tabla->FiltrarCampo('ID_TIPOARCHIVO','','escondido');
	$tabla->FiltrarCampo('ID_SECCION','/*SPECIAL*/secciones.ID IN ('.$_usuariosecciones_.')','escondido');
	$tabla->FiltrarCampo('URL','','escondido');
	$tabla->FiltrarCampo('NOMBRE','','escondido');
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