<!-- NAVEGADOR -->
<?




?>

      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td class="conf_navup" height="14" valign="top">
            <table align="left" border="0" cellpadding="0"
 cellspacing="0" height="15" width="855">
              <tbody>
                <tr>
                  <td width="802" class="conf_user" align="left" valign="middle"><?=$CLang->m_Words['WELCOME']?>
                  <b><?=$_SESSION['user']?></b></td>
                  <td width="176"  class="conf_close"  align="right" valign="middle"><?=$CLang->m_Words['LOGOUT']?></td>
                  <td align="right" valign="middle" width="15"><a href="../admin/logout.php?_usuario_=<?=$_SESSION['user']?>&_random_=<?=rand()?>"><img
 alt="close" title="close" src="../images/conf_cerrar.png" height="15" border="0" width="15"></td>
                </tr>
              </tbody>
            </table>
            </td>
          </tr>
          <tr>
            <td class="conf_navdown"
 style="height: 558px; text-align: left; vertical-align: top;">
            <table style="text-align: left; width: 100%; height: 100%;"
 border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td style="height: 3px;">
                  <table style="height: 0px;" border="0" cellpadding="0"
 cellspacing="0" height="47" width="851">
                    <tbody>
                      <tr>
                        <td class="" height="0">
                        <table border="0" cellpadding="0"
 cellspacing="0" width="100%">
                          <tbody>
                            <tr>
                              <td
 class="conf_menuitem_left conf_menuitem_left_sel" id="td_configuracion_left" width="10">&nbsp;&nbsp;</td>
                              <td
 class="conf_menuitem_sel conf_menuitem"  id="td_configuracion_center" onclick="javascript:show_module('configuracion');"><?=$CLang->m_Words["CONFIGURATION"]?></td>
                              <td
 class="conf_menuitem_right conf_menuitem_right_sel"  id="td_configuracion_right" width="10">&nbsp;&nbsp;</td>
                            </tr>
                          </tbody>
                        </table>
                        </td>
                        <td height="0">
                        <table border="0" cellpadding="0"
 cellspacing="0" width="100%">
                          <tbody>
                            <tr>
                              <td class="conf_menuitem_left"  id="td_secciones_left" width="10">&nbsp;&nbsp;</td>
                              <td class="conf_menuitem"  id="td_secciones_center"  onclick="javascript:show_module('secciones');"><?=$CLang->m_Words["SECTIONS"]?></td>
                              <td class="conf_menuitem_right"  id="td_secciones_right" width="10">&nbsp;&nbsp;</td>
                            </tr>
                          </tbody>
                        </table>
                        </td>
                        <td class="" height="0">
                        <table border="0" cellpadding="0"
 cellspacing="0" width="100%">
                          <tbody>
                            <tr>
                              <td class="conf_menuitem_left"  id="td_fichas_left" width="10">&nbsp;&nbsp;</td>
                              <td class="conf_menuitem"  id="td_fichas_center" onclick="javascript:show_module('fichas');"><?=$CLang->m_Words["CARDS"]?></td>
                              <td class="conf_menuitem_right"  id="td_fichas_right" width="10">&nbsp;&nbsp;</td>
                            </tr>
                          </tbody>
                        </table>
                        </td>
                        <td class="" height="0">
                        <table border="0" cellpadding="0"
 cellspacing="0" width="100%">
                          <tbody>
                            <tr>
                              <td class="conf_menuitem_left"  id="td_usuarios_left" width="10">&nbsp;&nbsp;</td>
                              <td class="conf_menuitem" id="td_usuarios_center" onclick="javascript:show_module('usuarios');"><?=$CLang->m_Words["USERS"]?></td>
                              <td class="conf_menuitem_right" id="td_usuarios_right" width="10">&nbsp;&nbsp;</td>
                            </tr>
                          </tbody>
                        </table>
                        </td>
                        <td class="" height="0">
                        <table border="0" cellpadding="0"
 cellspacing="0" width="100%">
                          <tbody>
                            <tr>
                              <td class="conf_menuitem_left" id="td_templates_left" width="10">&nbsp;&nbsp;</td>
                              <td class="conf_menuitem"  id="td_templates_center"  onclick="javascript:show_module('templates');" ><?=$CLang->m_Words["TEMPLATES"]?></td>
                              <td class="conf_menuitem_right"  id="td_templates_right"  width="10">&nbsp;&nbsp;</td>
                            </tr>
                          </tbody>
                        </table>
                        </td>
                        <td class="" height="0">
                        <table border="0" cellpadding="0"
 cellspacing="0" width="100%">
                          <tbody>
                            <tr>
                              <td class="conf_menuitem_left" id="td_traducciones_left" width="10">&nbsp;&nbsp;</td>
                              <td class="conf_menuitem"  id="td_traducciones_center"  onclick="javascript:show_module('traducciones');" ><?=$CLang->m_Words["TRANSLATIONS"]?></td>
                              <td class="conf_menuitem_right"  id="td_traducciones_right"  width="10">&nbsp;&nbsp;</td>
                            </tr>
                          </tbody>
                        </table>
                        </td>
                        <td class="" height="0">
                        <table border="0" cellpadding="0"
 cellspacing="0" width="100%">
                          <tbody>
                            <tr>
                              <td class="conf_menuitem_left" id="td_modulos_left" width="10">&nbsp;&nbsp;</td>
                              <td class="conf_menuitem"  id="td_modulos_center"  onclick="javascript:show_module('modulos');" ><?=$CLang->m_Words["MODULES"]?></td>
                              <td class="conf_menuitem_right"  id="td_modulos_right"  width="10">&nbsp;&nbsp;</td>
                            </tr>
                          </tbody>
                        </table>
                        </td>
						<td class="" height="0">
                        <table border="0" cellpadding="0"
 cellspacing="0" width="100%">
                          <tbody>
                            <tr>
                              <td class="conf_menuitem_left" id="td_funciones_left" width="10">&nbsp;&nbsp;</td>
                              <td class="conf_menuitem"  id="td_funciones_center"  onclick="javascript:show_module('funciones');" ><?=$CLang->m_Words["FUNCTIONS"]?></td>
                              <td class="conf_menuitem_right"  id="td_funciones_right"  width="10">&nbsp;&nbsp;</td>
                            </tr>
                          </tbody>
                        </table>
                        </td>                        
                        <td height="0" width="525">&nbsp;</td>
                      </tr>
                    </tbody>
                  </table>
                  </td>
                </tr>
                <tr>
                  <td class="conf_framecenter" style="height: 581px;"
 valign="top">
<?

require_once "../admin/configuracion.php";
require_once "../admin/secciones.php";
require_once "../admin/fichas.php";
require_once "../admin/usuarios.php";
require_once "../admin/templates.php";
require_once "../admin/traducciones.php";
require_once "../admin/modulos.php";
require_once "../admin/funciones.php";

?>
<script>selecttab();</script>

<!--FIN NAVEGADOR-->
