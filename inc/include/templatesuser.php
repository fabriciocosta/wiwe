<?php

	$this->TiposContenidos->SetTemplateEdicionUsuario( FICHE_PRODUIT );

	$this->TiposDetalles->SetTemplateEdicion( PRODUIT_TARIF1DAY,  '<tr><td bgcolor="#FFFFFF"><div align="center"><span class="style3">{PERIODFROM}</span></div></td>
														<td bgcolor="#FFFFFF"><div align="center"><span class="style3">{PERIODTO}</span></div></td>
														<td bgcolor="#FFFFFF"><div align="center"><span class="style3">{TARIF}</span></div></td>
														<td bgcolor="#FFFFFF"><div align="center"><span class="style3">{CURRENCY}</span></div></td></tr>', 
						'<table border="0" width="264">
			             <tr>
			                <td bgcolor="#330000" width="82"><div class="style22" align="center">{FROM}</div></td>
			                <td bgcolor="#330000" width="82"><div class="style22" align="center">{TO}</div></td>
			                <td bgcolor="#330000" width="29"><div class="style22" align="center">{RATE}</div></td>
			                <td bgcolor="#330000" width="53"><div class="style22" align="center">{CURRENCY}</div></td>
			              </tr>', '</table>');
	
	//echo $this->TiposDetalles->m_templatesedicion[PRODUIT_TARIF1DAY]["templateheader"];
	$this->TiposDetalles->m_templatesedicion[PRODUIT_TARIF1WEEK] = $this->TiposDetalles->m_templatesedicion[PRODUIT_TARIF1DAY];
	$this->TiposDetalles->m_templatesedicion[PRODUIT_TARIF1MONTH] = $this->TiposDetalles->m_templatesedicion[PRODUIT_TARIF1DAY];

?>