<?Php
// ********************************
//             CONFIRMAR ABM 
// ********************************
echo $HTTP_POST_FILES['_archivo_ARCHIVO']['tmp_name'];


require "deftabla.php";
require '../include/bloqueoheader.php';
if ($_logueado_=='verdadero') {
?>
<?
	$_e_URL = '_ERROR_';
	$_midir_ = $_SERVER['DOCUMENT_ROOT'];
	
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
				$_urlviejo_ = $row['archivos.URL'];
				//si es una imagen, fijarse si tiene un thumbnail /thm/archivo.jpg
				//y borrarlo
				$_urlthm_ = dirname($_urlviejo_)."/thm/".basename($_urlviejo_);
				if (is_file($_midir_.$_urlthm_)) {
					//lo borramos
					rename($_midir_.$_urlthm_,'/tmp/borrar'.$_primario_ID.'thm.tmp');
				}
			}
			rename($_midir_.$_urlviejo_,'/tmp/borrar'.$_primario_ID.'.tmp');
			$_exito_ = $tabla->Borrar();			
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
			$_seccionnombre_= "seccion".$row['secciones.ID']."_".$row['secciones.CARPETA'];			
			//ahora copiamos el archivo donde le corresponda			
			if (is_uploaded_file($_FILES["_archivo_ARCHIVO"]["tmp_name"])) {				
			
				if ($_e_GUARDAR_SECCION=='S') $_urlnuevo_ = '/secciones/'.$_seccionnombre_.'/'.$_FILES["_archivo_ARCHIVO"]["name"];
				if ($_e_GUARDAR_SECCION=='N') $_urlnuevo_ = '/archivos/'.$_tipoarchivocarpeta_.'/'.$_FILES["_archivo_ARCHIVO"]["name"];
				//chmod('/tmp/'.$_FILES["_archivo_ARCHIVO"]["tmp_name"], 0777);
				//OJO: si el archivo ya existe....
				if (is_file($_midir_.$_urlnuevo_)) {
					//abort!!!abort!!!
					$_exito_ = false;
					$tabla->exito = 'EL archivo '.$_urlnuevo_.' ya existe!!!! Cambie el nombre del archivo que quiere subir o borre el archivo existente.';
				} else {
					$_e_URL = $_urlnuevo_;
					$_exito_ = $tabla->Insertar();
				}
				
				if (($_exito_) and rename($_FILES["_archivo_ARCHIVO"]["tmp_name"],$_midir_.'/tmp/archivo'.$_primario_ID)) {
					//generamos el thumbnail si es una imagen
					//thumbnail($_midir_.$_urlnuevo_,242,$_midir_.dirname($_urlnuevo_)."/thm",basename($_urlnuevo_));
					if (rename_ftp('/public_html'.$_urlnuevo_,'/public_html'.'/tmp/archivo'.$_primario_ID)) {
						chmod_ftp('/public_html'.$_urlnuevo_);
						thumbnail($_midir_.$_urlnuevo_,121,$_midir_.dirname($_urlnuevo_)."/thm",basename($_urlnuevo_));												
						$tabla->exito = "Correcto: se guardó el archivo: ".$_urlnuevo_;
					}
				} else {
					$_exito_= false;					
					$tabla->exito.= "No se pudo renombrar el archivo a: ".$_midir_.$_urlnuevo_;
				}				
			} else {
				$tabla->exito = "No se pudo subir el archivo... Intente otra vez...";
			}		
		} elseif ($_modificar_=='si') {
			$tabla->LimpiarSQL();
			$_f_ID = $_primario_ID;
			$tabla->FiltrarSQL('ID');//para sacar el URL
			$tabla->Open();
			$row = $tabla->Fetch($tabla->resultados);
			$_urlviejo_ = $row['archivos.URL'];
			echo "urlviejo: ".$_urlviejo_. " nombre: ".$_FILES["_archivo_ARCHIVO"]["name"];
			
			if (is_uploaded_file($_FILES["_archivo_ARCHIVO"]["tmp_name"])) {
				//delete($_midir_.$_urlviejo_);
				if ($_e_GUARDAR_SECCION=='S') $_urlnuevo_ = '/secciones/seccion'.$row['secciones.ID']."_".$row['secciones.CARPETA']."/".$_FILES["_archivo_ARCHIVO"]["name"];
				if ($_e_GUARDAR_SECCION=='N') $_urlnuevo_ = '/archivos/'.$row['tiposarchivos.CARPETA']."/".$_FILES["_archivo_ARCHIVO"]["name"];
				
				//chmod('/tmp/'.$_FILES["_archivo_ARCHIVO"]["tmp_name"], 0777);				
				
				if ($_urlviejo_!=$_urlnuevo_) {//borramos el archivo anterior
				
					rename($_midir_.$_urlviejo_,'/tmp/borrar'.$_primario_ID.'.tmp');
					$_urlthm_ = dirname($_urlviejo_)."/thm/".basename($_urlviejo_);
				
					if (is_file($_midir_.$_urlthm_)) {
						//lo borramos
						rename($_midir_.$_urlthm_,'/tmp/borrar'.$_primario_ID.'thm.tmp');
					}
				}
				
				if (rename($_FILES["_archivo_ARCHIVO"]["tmp_name"],$_midir_.'/tmp/archivo'.$_primario_ID)) {
					//generamos el thumbnail si es una imagen
					if (rename_ftp('/public_html'.$_urlnuevo_,'/public_html'.'/tmp/archivo'.$_primario_ID)) {
						chmod_ftp('/public_html'.$_urlnuevo_);
						thumbnail($_midir_.$_urlnuevo_,121,$_midir_.dirname($_urlnuevo_)."/thm",basename($_urlnuevo_));						
						$tabla->exito = "Correcto: se guardó el archivo: ".$_midir_.$_urlnuevo_;						
						$_e_URL = $_urlnuevo_;
						$_exito_ = $tabla->Modificar();
					}
				} else {
					$_exito_= false;
					$tabla->exito.= "No se pudo renombrar el archivo a: ".$_urlnuevo_;
				}
			} else {
				$tabla->exito = "No se subió un archivo...";
				$_e_URL = $_urlviejo_;
				//chmod($_midir_.$_urlviejo_, 0777);
			}
			
			echo "urlnuevo: ".$_urlnuevo_;
			
			
			
		} elseif ($_cancelar_=='si') {
			$_exito_ = true;
			$tabla->exito = 'Cancelado!!!';			
		} else {
			echo "ERROR: no se definió ninguna acción";
		}
		//paramos para debuguear
		$_pausa_ = true;			
		if (($_exito_) and ($_debug_!='si' or !$_pausa_)) { if ($_admin_!='si') $_onload_="javascript:consultar();";  else $_onload_="javascript:admin();"; }
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