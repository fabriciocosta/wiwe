<?php

global $_DIR_SITEABS;

if ( $GLOBALS['_ADMIN_TYPE']=='TREE' ) {
?>		
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="MAD_HEADER" height="100%">
			<tr>
				<td align="left" valign="top" width="236">
					<table width="336" border="0" cellspacing="0" cellpadding="0" height="100%">
						<tr height="36">
							<td align="left" valign="top" height="36"><img src="../../inc/images/adminlogo.jpg" alt="" border="0"></td>
						</tr>
						<tr height="2">
							<td  class="MAD_HEADER_BG_LN" height="2"><img src="../../inc/images/spacer.gif" alt="" height="2" width="10" border="0"></td>
						</tr>
						<tr height="15">
							<td height="15"  class="MAD_HEADER_BG_BAND"><span class="MAD_HEADER_TIT">&nbsp;<?if ($_SESSION['user']!="") echo $CLang->m_Words['USER'].'&nbsp;:&nbsp;'.$_SESSION['user'].'  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../principal/admin/"><span class="modulo_admin_logout">'.$CLang->m_Words['LOGOUT'].'</span></a>';?></span></td>
						</tr>
						<tr height="2">
							<td class="MAD_HEADER_BG_LN" height="2"><img src="../../inc/images/spacer.gif" alt="" height="2" width="10" border="0"></td>
						</tr>
						<tr height="2">
							<td  height="20">								
								<table width="336" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td id="tdadmin1" align="center" valign="middle" onmouseover="javascript:changebgcolor('tdadmin1','<?=$_COLOR_FG?>');" onmouseout="javascript:changebgcolor('tdadmin1','<?=$_COLOR_BG?>');" ><a title="<?=$CLang->m_Words['ADMINISTRATION']?>" href="../../principal/admin/adminconfiguracion.php" target="toc"><span class="modulo_admin_titulo"><?=$CLang->m_Words['ADMINISTRATION']?></span></a></td>
										<td align="center" valign="middle" width="1" bgcolor="black"><img src="./../inc/images/spacer.gif" alt="" height="20" width="1" border="0"></td>
										<td id="tdadmin2" align="center" valign="middle" onmouseover="javascript:changebgcolor('tdadmin2','<?=$_COLOR_FG?>');" onmouseout="javascript:changebgcolor('tdadmin2','<?=$_COLOR_BG?>');" ><a title="<?=$CLang->m_Words['SECTIONSTREE']?>" href="../../principal/admin/adminarbolsecciones.php" target="toc"><span class="modulo_admin_titulo"><?=$CLang->m_Words['SECTIONSTREE']?></span></a></td>
									</tr>
								</table>
							</td>
						</tr>			
						<tr> 
							<td align="center" valign="middle"  height="1" bgcolor="black"><img src="../../inc/images/spacer.gif" alt="" height="1" width="336" border="0"></td>
						</tr>									
						<tr>
							<td align="left" valign="top">
								<iframe name="toc" scrolling="auto" height="100%" width="336" frameborder="0" framespacing="0" id='toc' src="adminarbolsecciones.php">You need iframe support.</iframe>
							</td>
						</tr>
					</table>
				</td>
				<td bgcolor="black" width="1"><img src="../../inc/images/spacer.gif" alt="" height="1" width="1" border="0"></td>
				<td align="left" valign="top">				
<?} else {?>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="<?=$_COLOR_BG?>">
			<tr>
				<td align="left" valign="top" width="236" height="36" bgcolor="<?=$_COLOR_BG?>">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" height="36">
						<tr height="36">
							<td width="5" align="left" valign="bottom" height="36"><img src="../../inc/images/adminlogo.jpg" alt="" height="39" border="0"></td>
						</tr> 
					</table>
				</td>				
			</tr>
			<tr height="2">
				<td bgcolor="black" height="2"><img src="../../inc/images/spacer.gif" alt="" height="2" width="10" border="0"></td>
			</tr>			
			<tr height="15">
				<td height="15" bgcolor="black"><span class="modulo_admin_usuario">&nbsp;<?if ($_SESSION['user']!="") echo $CLang->m_Words['USER'].':'.$_SESSION['user'].'  <a href="../../principal/admin/"><span class="modulo_admin_logout">'.$CLang->m_Words['LOGOUT'].'</span></a>';?></span></td>
			</tr>
			<tr>
				<td align="center" valign="middle">
<?}?>