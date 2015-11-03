<?php
global $_seccion_;
global $CLang;
global $__lang__;
global $_csistema_;
global $en;
if ($_csistema_==null) $this->GetVariablesSistema();
$detalles = $_csistema_->m_detalles;

$__lang__=='' ? $texteconditions = $_csistema_->m_detalles['SISTEMA_ABOUT']->m_txtdata : $texteconditions = $this->Secciones->m_tsecciones->TextoML($_csistema_->m_detalles['SISTEMA_ABOUT']->m_ml_txtdata, $__lang__ );

?>
<table width="995" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" border="0"><tr><td valign="top">
			<tr> 
				<td valign="top" align="left" style="padding-left:10px;" width="285">
					<img src="../../inc/images/fd2.jpg" width="235" height="541" border="0">
				</td>
				<td valign="top" align="left" style="padding-left:10px;padding-right:10px;" width="680">

					<p ><?=$texteconditions?></p>	
				</td>
			</tr>
		</table>

