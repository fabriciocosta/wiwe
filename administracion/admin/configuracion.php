<?
if ($_accion_=="set") {
	
	$Error = "";
	
	$archive =  file("../../inc/include/config.php");
	
	$counted = count ($archive);
	//echo "count:$counted<br>";
	
	for($i=0;$i<$counted;$i++) {
		$line = $archive[$i];
		$lineSP = split ("=", $line);
		
		$check = trim($lineSP[0]);
		//trim(substr(trim($lineSP[0]),1,strlen(trim($lineSP[0]))-1));
		if ($check == "\$_DOCTYPE_") $idoctype = $i;
		if ($check == "\$_TITLE_") $ititle = $i;
		if ($check == "\$_WWW_") $iwww = $i;		
		if ($check == "\$_TIPODB_") $itipodb = $i;
		if ($check == "\$_DB_") $idb = $i;
		if ($check == "\$_SERVIDOR_") $iservidor = $i;
		if ($check == "\$_USUARIO_") $iusuario = $i;
		if ($check == "\$_CONTRASENA_") $icontrasena = $i;
		if ($check == "\$_FTPSERVER_") $iftpserver = $i;
		if ($check == "\$_FTPUSUARIO_") $iftpusuario = $i;
		if ($check == "\$_FTPCONTRASENA_") $iftpcontrasena = $i;
		if ($check == "\$_DOCROOT_") $idocroot = $i;
		if ($check == "\$_SITEROOT_") $isiteroot = $i;
		if ($check == "\$_DIR_ADMABS") $idiradmabs = $i;
		if ($check == "\$_DIR_ADMREL") $idiradmrel = $i;
		if ($check == "\$_DIR_SITEABS") $idirsiteabs = $i;
		if ($check == "\$_DIR_SITEREL") $idirsiterel = $i;
		if ($check == "\$_DIR_FTPREL") $idirftprel = $i;
		if ($check == "\$_DIR_ARCH") $idirarch = $i;
		if ($check == "\$_DIR_SECCIONES") $idirsecciones = $i;
		if ($check == "\$_DIR_TMP") $idirtmp = $i;		
		
		if ($check == "\$_COLOR_BG") $icolorbg = $i;
		if ($check == "\$_COLOR_FG") $icolorfg = $i;
		if ($check == "\$_COLOR_INK") $icolorink = $i;
		if ($check == "\$_COLOR_INKHL") $icolorinkhl = $i;
		if ($check == "\$_COLOR_BOX") $icolorbox = $i;
		if ($check == "\$_COLOR_BOXHL") $icolorboxhl = $i;
		if ($check == "\$_PASSWORD_VERSION") $ipasswordversion = $i;
		if ($check == "\$_LANG_") $ilang = $i;
		if ($check == "\$_ADMIN_TYPE") $iadmintype = $i;
		if ($check == "\$_ADMIN_COPETE") $iadmincopete = $i;
		if ($check == "\$_ADMIN_CUERPO") $iadmincuerpo = $i;
		if ($check == "\$_ADMIN_EVENTO") $iadminevento = $i;
		if ($check == "\$_ADMIN_ADMIN") $iadminadmin = $i;
		if ($check == "\$_ADMIN_STARTURL") $iadminstarturl = $i;
		if ($check == "\$_ADMIN_STARTURL_ARBOL") $iadminstarturlarbol = $i;
		if ($check == "\$_SITIO_SESION") $isitiosesion = $i;
		
		//echo $check."| offset:".$i."<br>";
	}

	/*
	$_DB_ = "uv0377_relax";
	$_SERVIDOR_ = "localhost";
	$_USUARIO_ = "uv0377";
	$_CONTRASENA_ = "mudar712cano";
	$_FTPSERVER_ = "ftp.destinorelax.com";
	$_FTPUSUARIO_ = "uv0377";
	$_FTPCONTRASENA_ = "mudar712cano";
	
	$_DOCROOT_ = $_SERVER['DOCUMENT_ROOT'];
	$_SITEROOT_ = $_DOCROOT_;
	
	$_DIR_ADMABS = "http://www.destinorelax.com/administracion";
	$_DIR_ADMREL = "/administracion";
	$_DIR_SITEABS = "http://www.destinorelax.com";
	$_DIR_SITEREL = "";
	$_DIR_FTPREL = "public_html";
	
	$_DIR_ARCH = "/archivos";
	$_DIR_SECCIONES = "/secciones";
	$_DIR_TMP = "/tmp";
	*/
	if (isset($_DOCTYPE_new)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_DOCTYPE_ = $_DOCTYPE_new;
	   	$archive[$idoctype] = "\$_DOCTYPE_='".$_DOCTYPE_new."';\n";   	
	}	
	if (isset($_TITLE_new)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_TITLE_ = $_TITLE_new;
	   	$archive[$ititle] = "\$_TITLE_='".$_TITLE_new."';\n";   	
	}	
	if (isset($_WWW_new)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_WWW_ = $_WWW_new;
	   	$archive[$iwww] = "\$_WWW_='".$_WWW_new."';\n";   	
	}	
	if (isset($_TIPODB_new)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_TIPODB_ = $_TIPODB_new;
	   	$archive[$itipodb] = "\$_TIPODB_='".$_TIPODB_new."';\n";   	
	}	
	if (isset($_DB_new)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_DB_ = $_DB_new;
	   	$archive[$idb] = "\$_DB_='".$_DB_new."';\n";   	
	}	
	if (isset($_SERVIDOR_new)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_SERVIDOR_ = $_SERVIDOR_new;
	   	$archive[$iservidor] = "\$_SERVIDOR_='".$_SERVIDOR_new."';\n";   	
	}
	if (isset($_USUARIO_new)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_USUARIO_ = $_USUARIO_new;
	   	$archive[$iusuario] = "\$_USUARIO_='".$_USUARIO_new."';\n";   	
	}
	if (isset($_CONTRASENA_new)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_CONTRASENA_ = $_CONTRASENA_new;
	   	$archive[$icontrasena] = "\$_CONTRASENA_='".$_CONTRASENA_new."';\n";   	
	}
	if (isset($_FTPSERVER_new)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_FTPSERVER_ = $_FTPSERVER_new;
	   	$archive[$iftpserver] = "\$_FTPSERVER_='".$_FTPSERVER_new."';\n";   	
	}
	if (isset($_FTPUSUARIO_new)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_FTPUSUARIO_ = $_FTPUSUARIO_new;
	   	$archive[$iftpusuario] = "\$_FTPUSUARIO_='".$_FTPUSUARIO_new."';\n";   	
	}
	if (isset($_FTPCONTRASENA_new)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_FTPCONTRASENA_ = $_FTPCONTRASENA_new;
	   	$archive[$iftpcontrasena] = "\$_FTPCONTRASENA_='".$_FTPCONTRASENA_new."';\n";   	
	}

		
	if (isset($_DIR_ADMABSnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_DIR_ADMABS = $_DIR_ADMABSnew;
	   	$archive[$idiradmabs] = "\$_DIR_ADMABS='".$_DIR_ADMABSnew."';\n";   	
	}
	if (isset($_DIR_ADMRELnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_DIR_ADMREL = $_DIR_ADMRELnew;
	   	$archive[$idiradmrel] = "\$_DIR_ADMREL='".$_DIR_ADMRELnew."';\n";   	
	}
	if (isset($_DIR_SITEABSnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_DIR_SITEABS = $_DIR_SITEABSnew;
	   	$archive[$idirsiteabs] = "\$_DIR_SITEABS='".$_DIR_SITEABSnew."';\n";   	
	}
	if (isset($_DIR_SITERELnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_DIR_SITEREL = $_DIR_SITERELnew;
	   	$archive[$idirsiterel] = "\$_DIR_SITEREL='".$_DIR_SITERELnew."';\n";   	
	}
	if (isset($_DIR_FTPRELnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_DIR_FTPREL = $_DIR_FTPRELnew;
	   	$archive[$idirftprel] = "\$_DIR_FTPREL='".$_DIR_FTPRELnew."';\n";   	
	}
	
	if (isset($_DIR_ARCHnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_DIR_ARCH = $_DIR_ARCHnew;
	   	$archive[$idirarch] = "\$_DIR_ARCH='".$_DIR_ARCHnew."';\n";   	
	}
	if (isset($_DIR_SECCIONESnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_DIR_SECCIONES = $_DIR_SECCIONESnew;
	   	$archive[$idirsecciones] = "\$_DIR_SECCIONES='".$_DIR_SECCIONESnew."';\n";   	
	}
	if (isset($_DIR_TMPnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_DIR_TMP = $_DIR_TMPnew;
	   	$archive[$idirtmp] = "\$_DIR_TMP='".$_DIR_TMPnew."';\n";   	
	}
	
	if (isset($_COLOR_BGnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_COLOR_BG = $_COLOR_BGnew;
	   	$archive[$icolorbg] = "\$_COLOR_BG='".$_COLOR_BGnew."';\n";   	
	}	

	if (isset($_COLOR_FGnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_COLOR_FG = $_COLOR_FGnew;
	   	$archive[$icolorfg] = "\$_COLOR_FG='".$_COLOR_FGnew."';\n";   	
	}
	
	if (isset($_COLOR_INKnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_COLOR_INK = $_COLOR_INKnew;
	   	$archive[$icolorink] = "\$_COLOR_INK='".$_COLOR_INKnew."';\n";   	
	}
	
	if (isset($_COLOR_INKHLnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_COLOR_INKHL = $_COLOR_INKHLnew;
	   	$archive[$icolorinkhl] = "\$_COLOR_INKHL='".$_COLOR_INKHLnew."';\n";   	
	}	

	if (isset($_COLOR_BOXnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_COLOR_BOX = $_COLOR_BOXnew;
	   	$archive[$icolorbox] = "\$_COLOR_BOX='".$_COLOR_BOXnew."';\n";   	
	}	
	
	if (isset($_COLOR_BOXHLnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_COLOR_BOXHL = $_COLOR_BOXHLnew;
	   	$archive[$icolorboxhl] = "\$_COLOR_BOXHL='".$_COLOR_BOXHLnew."';\n";   	
	}	
	
	if (isset($_PASSWORD_VERSIONnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_PASSWORD_VERSION = $_PASSWORD_VERSIONnew;
	   	$archive[$ipasswordversion] = "\$_PASSWORD_VERSION='".$_PASSWORD_VERSIONnew."';\n";   	
	}	

	if (isset($_LANG_new)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_LANG_ = $_LANG_new;
	   	$archive[$ilang] = "\$_LANG_='".$_LANG_new."';\n";   	
	}	
	

	if (isset($_ADMIN_TYPEnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_ADMIN_TYPE = $_ADMIN_TYPEnew;
	   	$archive[$iadmintype] = "\$_ADMIN_TYPE='".$_ADMIN_TYPEnew."';\n";   	
	}	

	if (isset($_ADMIN_COPETEnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_ADMIN_COPETE = $_ADMIN_COPETEnew;
	   	$archive[$iadmincopete] = "\$_ADMIN_COPETE='".$_ADMIN_COPETEnew."';\n";   	
	}	

	if (isset($_ADMIN_CUERPOnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_ADMIN_CUERPO = $_ADMIN_CUERPOnew;
	   	$archive[$iadmincuerpo] = "\$_ADMIN_CUERPO='".$_ADMIN_CUERPOnew."';\n";   	
	}	

	if (isset($_ADMIN_EVENTOnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_ADMIN_EVENTO = $_ADMIN_EVENTOnew;
	   	$archive[$iadminevento] = "\$_ADMIN_EVENTO='".$_ADMIN_EVENTOnew."';\n";   	
	}	

	if (isset($_ADMIN_ADMINnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_ADMIN_ADMIN = $_ADMIN_ADMINnew;
	   	$archive[$iadminadmin] = "\$_ADMIN_ADMIN='".$_ADMIN_ADMINnew."';\n";   	
	}

	if (isset($_ADMIN_STARTURLnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_ADMIN_STARTURL = $_ADMIN_STARTURLnew;
	   	$archive[$iadminstarturl] = "\$_ADMIN_STARTURL='".$_ADMIN_STARTURLnew."';\n";   	
	}	

	if (isset($_ADMIN_STARTURL_ARBOLnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_ADMIN_STARTURL_ARBOL = $_ADMIN_STARTURL_ARBOLnew;
	   	$archive[$iadminstarturlarbol] = "\$_ADMIN_STARTURL_ARBOL='".$_ADMIN_STARTURL_ARBOLnew."';\n";   	
	}
	
	if (isset($_SITIO_SESIONnew)) {
		//echo "setting Videostorage:$videostorage<br>";
		$_SITIO_SESION = $_SITIO_SESIONnew;
	   	$archive[$isitiosesion] = "\$_SITIO_SESION='".$_SITIO_SESIONnew."';\n";   	
	}		
	
	$fp = fopen("../../inc/include/config.php","w+");
	for($i=0;$i<$counted;$i++) {
		$line = $archive[$i];
		fputs($fp,$line);
	}
	fclose($fp);
	
}
?>
<div id="div_configuracion" name="div_configuracion" style="display:inline;">

<form action="../admin/admin.php" method="post" name="formsetup">
<input type="hidden" value="set" name="_accion_">
<table cellpadding="7" cellspacing="0" border="0" width="100%">
	<tr>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="right" colspan="2"  class="conf_field conf_field_gral"><?=$CLang->m_Words['DATABASEACCESS']?></td>
				</tr>
				<tr>
					<td align="right"  class="conf_field"><span class="setup"><?=$CLang->m_Words['DATABASETYPE']?></span></td>
					<td align="left" class="conf_input"><input type="text" name="_TIPODB_new" value="<?=$_TIPODB_?>" size="12"></td>
				</tr>									
				<tr>
					<td align="right"  class="conf_field"><span class="setup"><?=$CLang->m_Words['DATABASENAME']?></span></td>
					<td align="left" class="conf_input"><input type="text" name="_DB_new" value="<?=$_DB_?>" size="12"></td>
				</tr>
				<tr>
					<td align="right"  class="conf_field"><span class="setup"><?=$CLang->m_Words['DATABASESERVER']?></span></td>
					<td align="left" class="conf_input"><input type="text" name="_SERVIDOR_new" value="<?=$_SERVIDOR_?>" size="12"></td>
				</tr>		
				<tr>
					<td align="right"  class="conf_field"><span class="setup"><?=$CLang->m_Words['DATABASEUSER']?></span></td>
					<td align="left" class="conf_input"><input type="text" name="_USUARIO_new" value="<?=$_USUARIO_?>" size="12"></td>
				</tr>
				<tr>
					<td align="right"  class="conf_field"><span class="setup"><?=$CLang->m_Words['PASSWORD']?></span></td>
					<td align="left" class="conf_input"><input type="password" name="_CONTRASENA_new" value="<?=$_CONTRASENA_?>" size="12"></td>
				</tr>				
			</table>
		</td>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="right" colspan="2"  class="conf_field conf_field_gral"><?=$CLang->m_Words['FTPACCESS']?></td>
				</tr>			
				<tr>
					<td align="right"  class="conf_field"><span class="setup"><?=$CLang->m_Words['FTPSERVER']?></span></td>
					<td align="left" class="conf_input"><input type="text" name="_FTPSERVER_new" value="<?=$_FTPSERVER_?>" size="12"></td>
				</tr>		
				<tr>
					<td align="right"  class="conf_field"><span class="setup"><?=$CLang->m_Words['FTPUSER']?></span></td>
					<td align="left" class="conf_input"><input type="text" name="_FTPUSUARIO_new" value="<?=$_FTPUSUARIO_?>" size="12"></td>
				</tr>
				<tr>
					<td align="right"  class="conf_field"><span class="setup"><?=$CLang->m_Words['FTPPASSWORD']?></span></td>
					<td align="left" class="conf_input"><input type="password" name="_FTPCONTRASENA_new" value="<?=$_FTPCONTRASENA_?>" size="12"></td>
				</tr>
			</table>				
		</td>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="right" colspan="2"  class="conf_field conf_field_gral"><?=$CLang->m_Words['SITEACCESS']?></td>
				</tr>								
				<tr>
					<td align="right"  class="conf_field"><span class="setup"><?=$CLang->m_Words['DIR_ADMABS']?></span></td>
					<td align="left" class="conf_input"><input type="text" name="_DIR_ADMABSnew" value="<?=$_DIR_ADMABS?>" size="42"></td>
				</tr>
				<tr>
					<td align="right"  class="conf_field"><span class="setup"><?=$CLang->m_Words['DIR_ADMREL']?></span></td>
					<td align="left" class="conf_input"><input type="text" name="_DIR_ADMRELnew" value="<?=$_DIR_ADMREL?>" size="42"></td>
				</tr>
				<tr>
					<td align="right"  class="conf_field"><span class="setup"><?=$CLang->m_Words['DIR_SITEABS']?></span></td>
					<td align="left" class="conf_input"><input type="text" name="_DIR_SITEABSnew" value="<?=$_DIR_SITEABS?>" size="42"></td>
				</tr>
				<tr>
					<td align="right"  class="conf_field"><span class="setup"><?=$CLang->m_Words['DIR_SITEREL']?></span></td>
					<td align="left" class="conf_input"><input type="text" name="_DIR_SITERELnew" value="<?=$_DIR_SITEREL?>" size="42"></td>
				</tr>
				<tr>
					<td align="right"  class="conf_field"><span class="setup"><?=$CLang->m_Words['DIR_FTPREL']?></span></td>
					<td align="left" class="conf_input"><input type="text" name="_DIR_FTPRELnew" value="<?=$_DIR_FTPREL?>" size="42"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<table cellpadding="7" cellspacing="0" border="0" width="100%">
	<tr>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="right" colspan="2"  class="conf_field conf_field_gral"><?=$CLang->m_Words['DIRACCESS']?></td>
				</tr>
				<tr>
					<td align="right"  class="conf_field"><span class="setup"><?=$CLang->m_Words['DOCTYPE']?></span></td>
					<td align="left" class="conf_input"><textarea rows="2" cols="40" name="_DOCTYPE_new"><?=$_DOCTYPE_?></textarea></td>
				</tr>							
				<tr>
					<td align="right"  class="conf_field"><span class="setup"><?=$CLang->m_Words['DOCROOT']?></span></td>
					<td align="left" class="conf_input"><?=$_DOCROOT_?></td>
				</tr>
				<tr>
					<td align="right"  class="conf_field"><span class="setup"><?=$CLang->m_Words['SITEROOT']?></span></td>
					<td align="left" class="conf_input"><?=$_SITEROOT_?></td>
				</tr>			
				<tr>
					<td align="right" class="conf_field"><span class="setup"><?=$CLang->m_Words['MYSQL_PASSWORD_VERSION']?></span></td>
					<td align="left" class="conf_input"><select name="_PASSWORD_VERSIONnew"><option value="PASSWORD" <? if ($_PASSWORD_VERSION=="PASSWORD") echo "selected";?>>PASSWORD</option><option value="OLD_PASSWORD" <? if ($_PASSWORD_VERSION=="OLD_PASSWORD") echo "selected";?>>OLD_PASSWORD</option></select></td>
				</tr>
				<tr>
					<td align="right" class="conf_field"><span class="setup"><?=$CLang->m_Words['LANG']?></span></td>
					<td align="left" class="conf_input"><select name="_LANG_new"><option value="SP" <? if ($_LANG_=="SP") echo "selected";?>>SP</option><option value="EN" <? if ($_LANG_=="EN") echo "selected";?>>EN</option><option value="FR" <? if ($_LANG_=="FR") echo "selected";?>>FR</option></select></td>
				</tr>
				<tr>
					<td align="right" class="conf_field"><span class="setup"><?=$CLang->m_Words['ADMIN_STARTURL']?></span></td>
					<td align="left" class="conf_input"><input type="text" name="_ADMIN_STARTURLnew" value="<?=$_ADMIN_STARTURL?>" size="42"></td>
				</tr>
				<tr>
					<td align="right" class="conf_field"><span class="setup"><?=$CLang->m_Words['ADMIN_STARTURL_ARBOL']?></span></td>
					<td align="left" class="conf_input"><input type="text" name="_ADMIN_STARTURL_ARBOLnew" value="<?=$_ADMIN_STARTURL_ARBOL?>" size="42"></td>
				</tr>
			</table>
		</td>
		<td valign="top" align="right">
		<?
	
if ($_accion_=="set") {

	//Ahora trataremos de inicializar al DB:
	$Messages = "";
	$link = mysql_connect($_SERVIDOR_new, $_USUARIO_new,$_CONTRASENA_new);
	if (!$link) {
   		$Messages.= $CLang->m_Words['ERROR'].' Could not connect: ' . mysql_error().'<br>';
   		$Error.= mysql_error();
	} else {
		$Messages.= 'Connected successfully';
		if (!mysql_select_db($_DB_new,$link)) {
			//try create
			if (!mysql_query("CREATE DATABASE ".$_DB_new."",$link)) {
				$Messages.="DB couldn't be created ". mysql_error()."<br>";
				$Error.= mysql_error();
			} else {
				$Messages.="DB creation success!<br>";
			}
		}
			
		if (mysql_select_db($_DB_new,$link)) {
			$Messages.= "Database ".$_DB_." founded!<br>";			
			$query = mysql_query( "SHOW TABLES FROM ".$_DB_new, $link);
			if (!query) {
				$Messages.= $CLang->m_Words['ERROR']." Couldn't show tables from ".$_DB_new.'<br>';
				$Error.= $CLang->m_Words['ERROR'];
			} else {		
				$tmatch = false;	
				if (mysql_num_rows($query)>0) {	
					while ( $qrow = mysql_fetch_row($query)) {
						if ( in_array( $qrow[0], array("usuarios","contenidos","secciones","tiposcontenidos","tipossecciones","tiposdetalles","detalles","grupossecciones","gruposusuarios","archivos","grupos","logs","logusuarios","tiposarchivos"))) {
							$Messages.= "Warning: table match ... ";
							$tmatch = true;
						}
						$Messages.= $qrow[0]."<br>";				
					}
				}
				
				if (!$tmatch) {
					$Messages.= $CLang->m_Words['SETUP']." Database ".$_DB_new." is ready for wiwe!<br>";
					$Messages.= "Trying to create database tables structure and data<br>";
					//CREACION DE LAS TABLAS
					require "create.php";
					
				}
			}
		}
		mysql_close($link);
	}
	
	echo '<div style="width:350px;height:160px;overflow:auto;font-family:Arial;font-size:10px;" ><table><tr><td valign="top">'.$Messages.'</td><td valign="top">
	<a href="#" onclick="javascript:window.location.reload();">Reload</a></td></tr></table>
	</div>';
	if ($Error=="") echo "<script> //window.location.href = 'admin.php'; </script>";
}
?>
		</td>		
	</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign="top" align="center">
			<input type="submit" name="submit" value="<?=$CLang->m_Words['SAVE']?>">
			<br><iframe width="100%" height="300" frameborder="1" src="../admin/phpminiadmin.php">you need iframes</iframe>
		</td>
	</tr>
</table>	
</form>


</div>