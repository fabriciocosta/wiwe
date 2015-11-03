<?Php
//======================================================================
//
//			index.php LOGIN -> ADMINISTRADOR
//			archivo generico para mostrar contenidos de una seccion
//			version: 1.0.1 (18/04/2003)
//======================================================================
$__modulo__ = "admin";
global $_DIR_SITEABS;
?>

<?Php require "../../inc/include/deftabla.php"; ?>
<html>
<head>
<title><?=$CLang->m_Words['LOGIN']?></title>
<? require "../../inc/include/style.php"; ?>
<? require "../../inc/include/scripts.php"; ?>
</head>
<body>
<? //require "../../inc/include/adminheader.php"; ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="<?=$_COLOR_BG?>">
			<tr>
				<td align="left" valign="top" width="236" height="36" bgcolor="<?=$_COLOR_BG?>">
					<table width="236" border="0" cellspacing="0" cellpadding="0" height="36">
						<tr height="36">
							<td align="right" valign="top" height="36"><img src="../../inc/images/adminlogo.jpg" alt="" height="39" border="0"></td>
						</tr> 
					</table>
				</td>
			</tr>
			<tr height="2">
				<td colspan="3" bgcolor="black" height="2"><img src="../../inc/images/spacer.gif" alt="" height="2" width="10" border="0"></td>
			</tr>			
			<tr>
				<td align="center" valign="middle"><br><br>
						
<form name="formlogin" method="POST" action="<?=$_DIR_SITEABS?>/principal/admin/admin.php">
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td colspan="3" align="center"><span class="modulo_admin_login">&nbsp;</span></td>
			</tr>
			<tr>
				<td colspan="3" align="center"><span class="modulo_admin_loginerror"><? if (isset($_errorlogueo_)) echo $_errorlogueo_;?></span></td>
			</tr>
			<tr>
				<td colspan="3" align="center"><span class="modulo_admin_login"><?=$CLang->m_Words['LOGIN']?></span></td>
			</tr>
			<tr>
				<td colspan="3" align="center"><img src="../../inc/images/spacer.gif" width="14" height="14"></td>
			</tr>			
			<tr>
				<td align="right"><span class="modulo_admin_login"><?=$CLang->m_Words['ADMINISTRATOR']?></span></td>
				<td width="14"><img src="../../inc/images/spacer.gif" width="14" height="1"></td>
				<td align="left"><input type="text" name="_usuario_" value=""></td>
			</tr>
			<tr>
				<td colspan="3" align="center"><img src="../../inc/images/spacer.gif" width="14" height="14"></td>
			</tr>
			<tr>
				<td align="right"><span class="modulo_admin_login"><?=$CLang->m_Words['PASSWORD']?></span></td>
				<td width="14"><img src="../../inc/images/spacer.gif" width="14" height="1"></td>
				<td align="left"><input type="password" name="_password_" value=""></td>
			</tr>
			<tr>
				<td colspan="3" align="center"><img src="../../inc/images/spacer.gif" width="14" height="14"></td>
			</tr>			
			<tr>
				<td colspan="3" align="center"><input type="submit" value="<?=$CLang->m_Words['ENTER']?>"><!--<img onclick="javascript:document.formlogin.submit();" src="../../inc/images/botonentrar.png" alt="" width="51" height="20" border="0">--></td>
			</tr>			
		</table>
</form>

</td>
</tr>
</table>

<? //require "../../inc/include/adminfooter.php"; ?>
</body>
</html>

