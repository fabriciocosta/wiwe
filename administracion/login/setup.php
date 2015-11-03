<?Php
//version 4.1 11/08/2006
//version 4.0 18/07/2006
global $__modulo__;
global $__tables__;
global $_accion_;
global $link;

$__tables__ = array( 
							"usuarios"=>"not present",
							"contenidos"=>"not present",
							"secciones"=>"not present",
							"relaciones"=>"not present",
							"tiposcontenidos"=>"not present",
							"tipossecciones"=>"not present",
							"tiposdetalles"=>"not present",
							"tiposrelaciones"=>"not present",
							"detalles"=>"not present",
							"grupossecciones"=>"not present",
							"gruposusuarios"=>"not present",
							"archivos"=>"not present",
							"grupos"=>"not present",
							"logs"=>"not present",
							"logusuarios"=>"not present",
							"tiposarchivos"=>"not present"
						);

$__modulo__ = "config";

require "../../inc/core/DinamikFunctions.php";

if ($_accion_=="set") {

	$archive =  file("../../inc/include/config.php");
	
	$counted = count ($archive);
	//echo "count:$counted<br>";
	
	for($i=0;$i<$counted;$i++) {
		$line = $archive[$i];
		$lineSP = split ("=", $line);
		
		$check = trim($lineSP[0]);
		//trim(substr(trim($lineSP[0]),1,strlen(trim($lineSP[0]))-1));
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
		if ($check == "\$_DIR_ARCH_IMG") $idirimg = $i;
		if ($check == "\$_DIR_ARCH_DOC") $idirdoc = $i;
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
		
		//echo $check."| offset:"?$i."<br>";
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
	if (isset($_TITLE_)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$ititle] = "\$_TITLE_='".$_TITLE_."';\n";   	
	}	
	if (isset($_WWW_)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$iwww] = "\$_WWW_='".$_WWW_."';\n";   	
	}	
	if (isset($_TIPODB_)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$itipodb] = "\$_TIPODB_='".$_TIPODB_."';\n";   	
	}	
	if (isset($_DB_)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$idb] = "\$_DB_='".$_DB_."';\n";   	
	}	
	if (isset($_SERVIDOR_)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$iservidor] = "\$_SERVIDOR_='".$_SERVIDOR_."';\n";   	
	}
	if (isset($_USUARIO_)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$iusuario] = "\$_USUARIO_='".$_USUARIO_."';\n";   	
	}
	if (isset($_CONTRASENA_)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$icontrasena] = "\$_CONTRASENA_='".$_CONTRASENA_."';\n";   	
	}
	if (isset($_FTPSERVER_)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$iftpserver] = "\$_FTPSERVER_='".$_FTPSERVER_."';\n";   	
	}
	if (isset($_FTPUSUARIO_)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$iftpusuario] = "\$_FTPUSUARIO_='".$_FTPUSUARIO_."';\n";   	
	}
	if (isset($_FTPCONTRASENA_)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$iftpcontrasena] = "\$_FTPCONTRASENA_='".$_FTPCONTRASENA_."';\n";   	
	}

	/*
	if (isset($_DOCROOT_)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$idocroot] = "\$_DOCROOT_='".$_DOCROOT_."';\n";   	
	}
	if (isset($_SITEROOT_)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$isiteroot] = "\$_SITEROOT_='".$_SITEROOT_."';\n";   	
	}
		
*/
	if (isset($_DIR_ADMABS)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$idiradmabs] = "\$_DIR_ADMABS='".$_DIR_ADMABS."';\n";   	
	}
	if (isset($_DIR_ADMREL)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$idiradmrel] = "\$_DIR_ADMREL='".$_DIR_ADMREL."';\n";   	
	}
	if (isset($_DIR_SITEABS)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$idirsiteabs] = "\$_DIR_SITEABS='".$_DIR_SITEABS."';\n";   	
	}
	if (isset($_DIR_SITEREL)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$idirsiterel] = "\$_DIR_SITEREL='".$_DIR_SITEREL."';\n";   	
	}
	if (isset($_DIR_FTPREL)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$idirftprel] = "\$_DIR_FTPREL='".$_DIR_FTPREL."';\n";   	
	}
	
	$_DOCROOT_ = $_SERVER['DOCUMENT_ROOT'];
	$_SITEROOT_ = $_DOCROOT_.$_DIR_SITEREL;
	
	if (isset($_DIR_ARCH)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$idirarch] = "\$_DIR_ARCH='".$_DIR_ARCH."';\n";
	   	if (!is_dir($_SITEROOT_.$_DIR_ARCH)) {
	   		if (mkdir($_SITEROOT_.$_DIR_ARCH)) {
	   			echo "directory ".$_DIR_ARCH." created!!!";
	   		}
	   	}   	
	}
	if (isset($_DIR_ARCH_IMG)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$idirimg] = "\$_DIR_ARCH_IMG='".$_DIR_ARCH_IMG."';\n";
	   	if (!is_dir($_SITEROOT_.$_DIR_ARCH.$_DIR_ARCH_IMG)) {
	   		if (mkdir($_SITEROOT_.$_DIR_ARCH.$_DIR_ARCH_IMG)) {
	   			echo "directory ".$_DIR_ARCH.$_DIR_ARCH_IMG." created!!!";
	   		}
	   	}   	
	}
	if (isset($_DIR_ARCH_DOC)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$idirdoc] = "\$_DIR_ARCH_DOC='".$_DIR_ARCH_DOC."';\n";
	   	if (!is_dir($_SITEROOT_.$_DIR_ARCH.$_DIR_ARCH_DOC)) {
	   		if (mkdir($_SITEROOT_.$_DIR_ARCH.$_DIR_ARCH_DOC)) {
	   			echo "directory ".$_DIR_ARCH.$_DIR_ARCH_DOC." created!!!";
	   		}
	   	}   	
	}		
	if (isset($_DIR_SECCIONES)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$idirsecciones] = "\$_DIR_SECCIONES='".$_DIR_SECCIONES."';\n";
	   	if (!is_dir($_SITEROOT_.$_DIR_SECCIONES)) {
	   		if (mkdir($_SITEROOT_.$_DIR_SECCIONES)) {
	   			echo "directory ".$_DIR_SECCIONES." created!!!";
	   		}
	   	} 	   	   	
	}
	if (isset($_DIR_TMP)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$idirtmp] = "\$_DIR_TMP='".$_DIR_TMP."';\n";
	   	if (!is_dir($_SITEROOT_.$_DIR_TMP)) {
	   		if (mkdir($_SITEROOT_.$_DIR_TMP)) {
	   			echo "directory ".$_DIR_TMP." created!!!";
	   		}
	   	} 	   	  	
	}
	
	if (isset($_COLOR_BG)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$icolorbg] = "\$_COLOR_BG='".$_COLOR_BG."';\n";   	
	}	

	if (isset($_COLOR_FG)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$icolorfg] = "\$_COLOR_FG='".$_COLOR_FG."';\n";   	
	}
	
	if (isset($_COLOR_INK)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$icolorink] = "\$_COLOR_INK='".$_COLOR_INK."';\n";   	
	}
	
	if (isset($_COLOR_INKHL)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$icolorinkhl] = "\$_COLOR_INKHL='".$_COLOR_INKHL."';\n";   	
	}	

	if (isset($_COLOR_BOX)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$icolorbox] = "\$_COLOR_BOX='".$_COLOR_BOX."';\n";   	
	}	
	
	if (isset($_COLOR_BOXHL)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$icolorboxhl] = "\$_COLOR_BOXHL='".$_COLOR_BOXHL."';\n";   	
	}	
	
	if (isset($_PASSWORD_VERSION)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$ipasswordversion] = "\$_PASSWORD_VERSION='".$_PASSWORD_VERSION."';\n";   	
	}	

	if (isset($_LANG_)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$ilang] = "\$_LANG_='".$_LANG_."';\n";   	
	}	
	

	if (isset($_ADMIN_TYPE)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$iadmintype] = "\$_ADMIN_TYPE='".$_ADMIN_TYPE."';\n";   	
	}	

	if (isset($_ADMIN_COPETE)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$iadmincopete] = "\$_ADMIN_COPETE='".$_ADMIN_COPETE."';\n";   	
	}	

	if (isset($_ADMIN_CUERPO)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$iadmincuerpo] = "\$_ADMIN_CUERPO='".$_ADMIN_CUERPO."';\n";   	
	}	

	if (isset($_ADMIN_EVENTO)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$iadminevento] = "\$_ADMIN_EVENTO='".$_ADMIN_EVENTO."';\n";   	
	}	

	if (isset($_ADMIN_ADMIN)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$iadminadmin] = "\$_ADMIN_ADMIN='".$_ADMIN_ADMIN."';\n";   	
	}

	if (isset($_ADMIN_STARTURL)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$iadminstarturl] = "\$_ADMIN_STARTURL='".$_ADMIN_STARTURL."';\n";   	
	}	

	if (isset($_ADMIN_STARTURL_ARBOL)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$iadminstarturlarbol] = "\$_ADMIN_STARTURL_ARBOL='".$_ADMIN_STARTURL_ARBOL."';\n";   	
	}
	
	if (isset($_SITIO_SESION)) {
		//echo "setting Videostorage:$videostorage<br>";
	   	$archive[$isitiosesion] = "\$_SITIO_SESION='".$_SITIO_SESION."';\n";   	
	}		
	
	$fp = fopen("../../inc/include/config.php","w+");
	for($i=0;$i<$counted;$i++) {
		$line = $archive[$i];
		fputs($fp,$line);
	}
	fclose($fp);
	
	//Ahora trataremos de inicializar al DB:
	$Messages = "";
	$link = mysql_connect($_SERVIDOR_, $_USUARIO_,$_CONTRASENA_);
	if (!$link) {
   		$Messages.= ShowError( $CLang->m_Words['ERROR'].' Could not connect: ' . mysql_error(), false );
	} else {
		$Messages.= ShowMessage( 'Connected successfully', false );
		if (!mysql_select_db($_DB_,$link)) {
			//try create
			if (!mysql_query("CREATE DATABASE ".$_DB_."",$link)) {
				$Messages.= ShowError( "DB couldn't be created ". mysql_error() , true );
			} else {
				$Messages.= ShowMessage( "DB creation success!", false );
			}
		}
			
		if (mysql_select_db($_DB_,$link)) {
			$Messages.= ShowMessage( "Database ".$_DB_." founded!", false );			
			$query = mysql_query( "SHOW TABLES FROM ".$_DB_, $link);
			if (!query) {
				$Messages.= ShowError( $CLang->m_Words['ERROR']." Couldn't show tables from ".$_DB_, false );
			} else {	
				$tmatch = true;		
				if (mysql_num_rows($query)>0) {	
					while ( $qrow = mysql_fetch_row($query)) {
						$tabl = $qrow[0];
						if (isset( $__tables__[$tabl] )) {
							$__tables__[$tabl] = "present";
						}
					}
				}
				
				foreach($__tables__ as $name=>$presence) {
					if ($presence=="not present") {
						$tmatch = false;
						$Messages.= ShowError( "<div class=\"missing_table\">Missing table `$name`</div>", false );
					}
				}
				
				if (!$tmatch) {
					$Messages.= ShowError( "Tables are missing. Trying to create database tables structure and data", false );
					//CREACION DE LAS TABLAS
					require "create.php";
					require "update.php";
				} else {
					$Messages.= ShowMessage( $CLang->m_Words['SETUP']." Database ".$_DB_." is ready for wiwe! Updating....", false );
					//CREACION DE LAS TABLAS
					require "create.php";
					require "update.php";
					
				}
			}
		}
		mysql_close($link);
	}
	
	
?>
<html>
<head>
<title><?=$CLang->m_Words['SETUP']?></title>
<?
require "../include/style.php";
?>
<script>
	function admin() {
		window.location.href = "../index.php";
	}	
</script>
</head>
<body>
<table width=100% border="0" cellpadding="12" cellspacing="2">
	<tr>
		<td align="center" valign="top">
			<span class="setuptitle"><?=$CLang->m_Words['SETUP']?></span>
		</td>
	</tr>
	<tr>
		<td align="center" valign="top">
			<span class="setupresult"><?=$Messages?><br></span>
		</td>		
	</tr>
	<tr>
		<td align="center" valign="top"><a href="javascript:admin();"><span class="button">Login</span></a></td>
	</tr>
</table>
</span>
</body>
</html>
<?

} else {

require '../../inc/include/config.php';
require '../../inc/core/CMultiLang.php';
require '../../inc/core/CLang.php';
require '../../inc/include/lang.php';

global $CLang;

if (!is_object($CLang)) {
	echo "Error in CLANG > NOT AN OBJECT";
}
?>
<html>
<head>
<title><?=$CLang->m_Words['SETUP']?></title>
<?
require "../include/style.php";
?>
</head>
<body>
	<form action="setup.php" method="post" name="formsetup">
		<table width="100%" height="100%" border="0" cellspace="0" cellpadding="0">
			<tr>
				<td align="center" valign="top"><br><br>
				<table width="400" height="200" border="0" cellspace="2" cellpadding="0" bgcolor="#000000">
					<tr>
						<td colspan="3" align="center"><span class="setuptitle"><?=$CLang->m_Words['SETUP']?></span></td>
					</tr>
					<tr>
					<td align="center" >
						<table width="400" height="200" border="0" cellspace="2" cellpadding="2" bgcolor="<?=$_COLOR_BG?>">
							<tr>
								<td align="center"><br>
								<img src="../../inc/images/adminlogo.jpg" alt="" border="0">		
								<table border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" align="center"><span class="login">&nbsp;</span></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><span class="loginerror"><? if (isset($_errorlogueo_)) echo $_errorlogueo_;?></span></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>			
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['SITENAME']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_TITLE_" value="<?=$_TITLE_?>" size="52"></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['SITEWWW']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_WWW_" value="<?=$_WWW_?>" size="52"></td>
									</tr>									
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['DATABASETYPE']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_TIPODB_" value="<?=$_TIPODB_?>" size="52"></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>									
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['DATABASENAME']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_DB_" value="<?=$_DB_?>" size="52"></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['DATABASESERVER']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_SERVIDOR_" value="<?=$_SERVIDOR_?>" size="52"></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>			
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['DATABASEUSER']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_USUARIO_" value="<?=$_USUARIO_?>" size="52"></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['PASSWORD']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="password" name="_CONTRASENA_" value="<?=$_CONTRASENA_?>" size="52"></td>
									</tr>
																							<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['FTPSERVER']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_FTPSERVER_" value="<?=$_FTPSERVER_?>" size="52"></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>			
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['FTPUSER']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_FTPUSUARIO_" value="<?=$_FTPUSUARIO_?>" size="52"></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['FTPPASSWORD']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="password" name="_FTPCONTRASENA_" value="<?=$_FTPCONTRASENA_?>" size="52"></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>	
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['DIR_ADMABS']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_DIR_ADMABS" value="<?=$_DIR_ADMABS?>" size="52"></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['DIR_ADMREL']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_DIR_ADMREL" value="<?=$_DIR_ADMREL?>" size="52"></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['DIR_SITEABS']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_DIR_SITEABS" value="<?=$_DIR_SITEABS?>" size="52"></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['DIR_SITEREL']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_DIR_SITEREL" value="<?=$_DIR_SITEREL?>" size="52"></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['DIR_FTPREL']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_DIR_FTPREL" value="<?=$_DIR_FTPREL?>" size="52"></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['DIR_ARCH']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_DIR_ARCH" value="<?=$_DIR_ARCH?>" size="52"></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['DIR_ARCH_IMG']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_DIR_IMG" value="<?=$_DIR_ARCH_IMG?>" size="52"></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['DIR_ARCH_DOC']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_DIR_DOC" value="<?=$_DIR_ARCH_DOC?>" size="52"></td>
									</tr>																		
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['DIR_SECTIONS']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_DIR_SECCIONES" value="<?=$_DIR_SECCIONES?>" size="39"></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>							
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['DIR_TMP']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_DIR_TMP" value="<?=$_DIR_TMP?>" size="52"></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>									
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['COLOR_BG']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_COLOR_BG" value="<?=$_COLOR_BG?>" size="52"></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['COLOR_FG']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_COLOR_FG" value="<?=$_COLOR_FG?>" size="52"></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>									
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['COLOR_INK']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_COLOR_INK" value="<?=$_COLOR_INK?>" size="52"></td>
									</tr>									
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>									
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['COLOR_INKHL']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_COLOR_INKHL" value="<?=$_COLOR_INKHL?>" size="52"></td>
									</tr>									
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['COLOR_BOX']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_COLOR_BOX" value="<?=$_COLOR_BOX?>" size="52"></td>
									</tr>									
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>									
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['COLOR_BOXHL']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_COLOR_BOXHL" value="<?=$_COLOR_BOXHL?>" size="52"></td>
									</tr>									
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['MYSQL_PASSWORD_VERSION']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><select name="_PASSWORD_VERSION"><option value="PASSWORD" <? if ($_PASSWORD_VERSION=="PASSWORD") echo "selected";?>>PASSWORD</option><option value="OLD_PASSWORD" <? if ($_PASSWORD_VERSION=="OLD_PASSWORD") echo "selected";?>>OLD_PASSWORD</option></select></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['LANG']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><select name="_LANG_"><option value="SP" <? if ($_LANG_=="SP") echo "selected";?>>SP</option><option value="EN" <? if ($_LANG_=="EN") echo "selected";?>>EN</option><option value="FR" <? if ($_LANG_=="FR") echo "selected";?>>FR</option></select></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>									
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['ADMIN_TYPE']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><select name="_ADMIN_TYPE"><option value="TREE" <? if ($_ADMIN_TYPE=="TREE") echo "selected";?>><?=$CLang->m_Words['ADMIN_TYPE_TREE']?></option><option value="LAPEL CONTENTTYPE" <? if ($_ADMIN_TYPE=="LAPEL CONTENTTYPE") echo "selected";?>><?=$CLang->m_Words['ADMIN_TYPE_LAPEL_CONTENTTYPE']?></option><option value="LAPEL SECTION" <? if ($_ADMIN_TYPE=="LAPEL SECTION") echo "selected";?>><?=$CLang->m_Words['ADMIN_TYPE_LAPEL_SECTION']?></option></select></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['ADMIN_COPETE']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_ADMIN_COPETE" value="<?=$_ADMIN_COPETE?>" size="52"></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['ADMIN_CUERPO']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_ADMIN_CUERPO" value="<?=$_ADMIN_CUERPO?>" size="52"></td>
									</tr>									
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['ADMIN_EVENTO']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_ADMIN_EVENTO" value="<?=$_ADMIN_EVENTO?>" size="52"></td>
									</tr>			
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['ADMINISTRATION']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><select name="_ADMIN_ADMIN"><option value="S" <? if ($_ADMIN_ADMIN=="S") echo "selected";?>><?=$CLang->m_Words['YES']?></option><option value="N" <? if ($_ADMIN_ADMIN=="N") echo "selected";?>><?=$CLang->m_Words['NO']?></option></select></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['ADMIN_STARTURL']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_ADMIN_STARTURL" value="<?=$_ADMIN_STARTURL?>" size="80"></td>
									</tr>
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['ADMIN_STARTURL_ARBOL']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_ADMIN_STARTURL_ARBOL" value="<?=$_ADMIN_STARTURL_ARBOL?>" size="80"></td>
									</tr>									
									<tr>
										<td align="right"><span class="setup"><?=$CLang->m_Words['SITIO_SESION']?></span></td>
										<td width="14">&nbsp;&nbsp;</td>
										<td align="left"><input type="text" name="_SITIO_SESION" value="<?=$_SITIO_SESION?>" size="52"></td>
									</tr>									
									<tr>
										<td colspan="3" align="center"><img src="../images/spacer.gif" width="14" height="14"></td>
									</tr>
									<tr>
										<td colspan="3" align="center"><input type="submit" name="submit" value="<?=$CLang->m_Words['OK']?>"><!--<input type="image" onmouseout="javascript:showimg('../../inc/images/botonentrar.png');" onmousedown="javascript:showimg('../../inc/images/botonentrar_down.png');" src="../../inc/images/botonentrar.png"  name="submit" value="">--><!--<img onclick="javascript:login();" onmouseout="javascript:showimg('../../inc/images/botonentrar.png');" onmousedown="javascript:showimg('../../inc/images/botonentrar_down.png');" src="../../inc/images/botonentrar.png" alt="" width="51" height="20" border="0">--></td>
									</tr>
								</table>
								</td>
							</tr>
						</table>
						<input type="hidden" name="_accion_" value="set">
						</tr>
					</table>
				</td>
			</tr>
		</table>	
	</form>
</body>
</html>
<?
}
?>