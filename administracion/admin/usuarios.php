<?




?>
<div id="div_usuarios" name="div_usuarios" style="display:none;">


<table cellpadding="0" cellspacing="0" width="100%">
	<tr>	
		<td>
			<table>
				<tr>
					<td align="right" class="conf_field conf_field_gral"><span class="conf_tablas"><?=strtoupper( $CLang->m_Words['GROUPS'])?></span></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['NEW']?> <?=$CLang->m_Words['GROUP']?><a href="../grupos/modificar.php?_nuevo_=si&_admin_=si&_random_=<?=rand()?>"><u>[++]</u></a></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['QUERY']?> <a href="../grupos/consulta.php?_random_=<?=rand()?>"><u>[>>]</u></a></td>
				<tr>
			</table>
		</td>
		<td>
			<table>
				<tr>
					<td align="right" class="conf_field conf_field_gral"><span class="conf_tablas"><?=strtoupper( $CLang->m_Words['USERS'])?></span></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['NEW']?> <?=$CLang->m_Words['USER']?><a href="../usuarios/modificar.php?_nuevo_=si&_admin_=si&_random_=<?=rand()?>"><u>[++]</u></a></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['QUERY']?> <a href="../usuarios/consulta.php?_random_=<?=rand()?>"><u>[>>]</u></a></td>
				<tr>
			</table>

		</td>		
	</tr>
	<tr>	
		<td>
			<table>
				<tr>
					<td align="right" class="conf_field conf_field_gral"><span class="conf_tablas"><?=strtoupper( $CLang->m_Words['GROUPS']."/".$CLang->m_Words['USERS'])?></span></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['NEW']?> <?=$CLang->m_Words['GROUP']."/".$CLang->m_Words['USER']?><a href="../gruposusuarios/modificar.php?_nuevo_=si&_admin_=si&_random_=<?=rand()?>"><u>[++]</u></a></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['QUERY']?> <a href="../gruposusuarios/consulta.php?_random_=<?=rand()?>"><u>[>>]</u></a></td>
				<tr>
			</table>
		</td>
		<td>
			<table>
				<tr>
					<td align="right" class="conf_field conf_field_gral"><span class="conf_tablas"><?=strtoupper( $CLang->m_Words['GROUPS']."/".$CLang->m_Words['SECTIONS'])?></span></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['NEW']?> <?=$CLang->m_Words['GROUP']."/".$CLang->m_Words['SECTION']?><a href="../grupossecciones/modificar.php?_nuevo_=si&_admin_=si&_random_=<?=rand()?>"><u>[++]</u></a></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['QUERY']?> <a href="../grupossecciones/consulta.php?_random_=<?=rand()?>"><u>[>>]</u></a></td>
				<tr>
			</table>
		</td>
		
	</tr>	
	<tr>	
		<td>
			<table>
				<tr>
					<td align="right" class="conf_field conf_field_gral"><span class="conf_tablas"><?=strtoupper( $CLang->m_Words['USERSLOG'])?></span></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['NEW']?> <?=$CLang->m_Words['USERSLOG']?><a href="../logusuarios/modificar.php?_nuevo_=si&_admin_=si&_random_=<?=rand()?>"><u>[++]</u></a></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['QUERY']?> <a href="../logusuarios/consulta.php?_random_=<?=rand()?>"><u>[>>]</u></a></td>
				<tr>
			</table>
		</td>
		<td>
		</td>		
	</tr>	
</table>
		


</div>