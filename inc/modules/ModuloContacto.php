<?php

global $CLang;
global $accion;

global $_seccion_;
global $__lang__;
global $_csistema_;
global $en;
if ($_csistema_==null) $this->GetVariablesSistema();
$detalles = $_csistema_->m_detalles;

$__lang__=='' ? $textecontacts = $detalles['SISTEMA_CONTACTS']->m_txtdata : $textecontacts = $this->Secciones->m_tsecciones->TextoML($detalles['SISTEMA_CONTACTS']->m_ml_txtdata, $__lang__ );


$templatecontact = '<table class="contacto" width="364" border="0" cellpadding="3" cellspacing="1" bordercolor="0">
    <tr>
      <td colspan="2" class="boxname">*result*</td>
    </tr>
    <tr>
      <td width="182" class="boxname">'.$CLang->m_Words['NAME'].' #_nom_#</td>
      <td width="166" class="boxfield"><input value="*_nom_*" name="_nom_" type="text" id="_nom_" size="50" maxlength="50"></td>
    </tr>
    <tr>
      <td class="boxname">'.$CLang->m_Words['SUBJECT'].' #_subject_#</td>
      <td class="boxfield"><input value="*_subject_*" name="_subject_" type="text" id="_subject_" size="50" maxlength="50"></td>
    </tr>
    <tr>
      <td class="boxname">'.$CLang->m_Users['USEREMAIL'].' #_email_#</td>
      <td class="boxfield"><input value="*_email_*" name="_email_" type="text" id="_email_" size="50" maxlength="255"></td>
    </tr>
    <tr>
      <td class="boxname">'.$CLang->m_Words['MESSAGE'].' #_commentaire_# </td>
      <td class="boxfield"><textarea name="_commentaire_" id="_commentaire_" rows="10" cols="50">*_commentaire_*</textarea></td>
    </tr>    
    <tr>
      <td colspan="2" align="center" class="boxname"><input name="submit" type="submit" value="'.$CLang->m_Words['SEND'].'"></td>
    </tr>
  </table>';


		$variables = array(	'_nom_'=>$GLOBALS["_nom_"],
							'_email_'=>$GLOBALS["_email_"],
							'_subject_'=>$GLOBALS["_subject_"],
							'_commentaire_'=>$GLOBALS["_commentaire_"]);
		
		$mandatories = array(	'_nom_'=>'',
								'_email_'=>'',	
								'_subject_'=>'',
								'_commentaire_'=>'');
		
		$results = array(	'result'=>'',
							'errores'=>0 );
		
		if ($GLOBALS["submit"]!="") {
				$mailmessagetemplate = "{_nom_} : *_nom_*"."\n"."{_subject_}: *_subject_*,"."{_email_}: *_email_*,"."\n"."{_commentaire_} : *_commentaire_*"."\n";
			
				$this->SendMessage( $variables,
							$mandatories,
							$results,
							"*_nom_*",
							
							"La Sociedad Post",
							
							"fcosta@computaciongrafica.com",
							
							$mailmessagetemplate );
						
		}
		
		$this->UpdateMessage( $variables, $mandatories, $results, $templatecontact );
	
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50%" valign="top"  align="left" style="padding-left: 60px; padding-top: 35px;"><?=$textecontacts?></td>
		<td  width="50%" valign="top" align="center" style="padding-right: 60px; padding-top: 35px;">
			<form action="../../principal/home/<?=$en?>contacts.php" method="post" name="formcontacto" target="_self" id="formcontacto">
  				<?=$templatecontact?>
  				<input name="accion" type="hidden" value="confirm">
			</form>
		</td>
	</tr>
</table>

