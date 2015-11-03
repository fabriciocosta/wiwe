<?Php

error_reporting(E_ALL);
require '../include/DinamikAdmin.php';

global $_DIR_ADMABS;
global $_DIR_SITEABS;
?>

<html>
<head>
	<title>Login</title>
	<link href="<?=$_DIR_SITEABS?>/inc/css/general.css" rel="stylesheet" media="screen">
	<script>
	function login() {
		document.formlog._logueo_.value = 'logueando';
		document.formlog.submit();
	}
	</script>
</head>
<body bgcolor="#FFFFFF" marginheight="0" marginwidth="0" leftmargin="0" bottommargin="0" rightmargin="0" topmargin="0">
<form name="formlog" method="post" action="<?=$_DIR_ADMABS?>/admin/admin.php" >
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" valign="top"><br><br>
		<table width="400" height="200" border="0" cellspacing="2" cellpadding="0" bgcolor="#000000">
			<tr>
				<td colspan="3" align="center"><span class="logintitle"><?=$CLang->m_Words['SETUP']?></span></td>
			</tr>
			<tr>
			<td align="center" >
				<table width="400" height="200" border="0" cellspacing="2" cellpadding="2" bgcolor="<?=$_COLOR_BG?>">
					<tr>
						<td align="center"><br>
						<img src="<?=$_DIR_ADMABS?>/images/config_logo.jpg" alt="" border="0">		
						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td colspan="3" align="center"><span class="modulo_admin_login">&nbsp;</span></td>
							</tr>
							<tr>
								<td colspan="3" align="center"><span class="modulo_admin_loginerror"><? if (isset($_errorlogueo_)) echo $_errorlogueo_;?></span></td>
							</tr>
							<tr>
								<td colspan="3" align="center">&nbsp;</td>
							</tr>			
							<tr>
								<td align="right"><span class="modulo_admin_login"><?=$CLang->m_Users['USERNICK']?></span></td>
								<td width="14"><img src="<?=$_DIR_ADMABS?>/images/spacer.gif" width="14" height="1"></td>
								<td align="left"><input type="text" name="_usuario_" value=""></td>
							</tr>
							<tr>
								<td colspan="3" align="center">&nbsp;</td>
							</tr>
							<tr>
								<td align="right"><span class="modulo_admin_login"><?=$CLang->m_Words['PASSWORD']?></span></td>
								<td width="14"><img src="<?=$_DIR_ADMABS?>/images/spacer.gif" width="14" height="1"></td>
								<td align="left"><input type="password" name="_password_" value=""></td>
							</tr>
							<tr>
								<td colspan="3" align="center">&nbsp;</td>
							</tr>			
							<tr>
								<td colspan="3" align="center"><input type="submit" name="submit" value="Entrar"><!--<input type="image" onmouseout="javascript:showimg('../../inc/images/botonentrar.png');" onmousedown="javascript:showimg('../../inc/images/botonentrar_down.png');" src="../../inc/images/botonentrar.png"  name="submit" value="">--><!--<img onclick="javascript:login();" onmouseout="javascript:showimg('../../inc/images/botonentrar.png');" onmousedown="javascript:showimg('../../inc/images/botonentrar_down.png');" src="../../inc/images/botonentrar.png" alt="" width="51" height="20" border="0">--></td>
							</tr>			
						</table>
						</td>
					</tr>
					<?php					
					if (file_exists('setup.php')) {				
					?>
					<tr>
						<td align="center">
							<a href="setup.php" class="modulo_admin_login"><?=$CLang->m_Words['SETUP']?></a>
						</td>
					</tr>
					<?	
					}
					?>
				</table>
				<input type="hidden" name="_logueo_" value="">
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
</body>
</html>
