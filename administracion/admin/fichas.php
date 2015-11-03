<?




?>
<div id="div_fichas" name="div_fichas" style="display:none;">


<table cellpadding="0" cellspacing="0" width="100%">
	<tr>	
		<td>
			<table>
				<tr>
					<td align="right" class="conf_field conf_field_gral"><span class="conf_tablas"><?=strtoupper( $CLang->m_Words['CONTENTTYPES'])?></span></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['NEW']?> <?=$CLang->m_Words['CONTENTTYPE']?><a href="../tiposcontenidos/modificar.php?_nuevo_=si&_admin_=si&_random_=<?=rand()?>"><u>[++]</u></a></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['QUERY']?> <a href="../tiposcontenidos/consulta.php?_random_=<?=rand()?>"><u>[>>]</u></a></td>
				<tr>
			</table>
		</td>
		<td>
			<table>
				<tr>
					<td align="right" class="conf_field conf_field_gral"><span class="conf_tablas"><?=strtoupper( $CLang->m_Words['CONTENTS'])?></span></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['NEW']?> <?=$CLang->m_Words['CONTENT']?><a href="../contenidos/modificar.php?_nuevo_=si&_admin_=si&_random_=<?=rand()?>"><u>[++]</u></a></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['QUERY']?> <a href="../contenidos/consulta.php?_random_=<?=rand()?>"><u>[>>]</u></a></td>
				<tr>
			</table>

		</td>		
	</tr>
	<tr>	
		<td>
			<table>
				<tr>
					<td align="right" class="conf_field conf_field_gral"><span class="conf_tablas"><?=strtoupper( $CLang->m_Words['DETAILTYPES'])?></span></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['NEW']?> <?=$CLang->m_Words['DETAILTYPE']?><a href="../tiposdetalles/modificar.php?_nuevo_=si&_admin_=si&_random_=<?=rand()?>"><u>[++]</u></a></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['QUERY']?> <a href="../tiposdetalles/consulta.php?_random_=<?=rand()?>"><u>[>>]</u></a></td>
				<tr>
			</table>
		</td>
		<td>
			<table>
				<tr>
					<td align="right" class="conf_field conf_field_gral"><span class="conf_tablas"><?=strtoupper( $CLang->m_Words['DETAILS'])?></span></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['NEW']?> <?=$CLang->m_Words['DETAIL']?><a href="../detalles/modificar.php?_nuevo_=si&_admin_=si&_random_=<?=rand()?>"><u>[++]</u></a></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['QUERY']?> <a href="../detalles/consulta.php?_random_=<?=rand()?>"><u>[>>]</u></a></td>
				<tr>
			</table>

		</td>		
	</tr>	
</table>

	
</div>