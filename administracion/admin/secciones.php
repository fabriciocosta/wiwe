<?




?>
<div id="div_secciones" name="div_secciones" style="display:none;">


<table cellpadding="0" cellspacing="0" width="100%">
	<tr>	
		<td>
			<table>
				<tr>
					<td align="right" class="conf_field conf_field_gral"><span class="conf_tablas"><?=strtoupper( $CLang->m_Words['SECTIONTYPES'])?></span></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['NEW']?> <?=$CLang->m_Words['SECTIONTYPE']?><a href="../tipossecciones/modificar.php?_nuevo_=si&_admin_=si&_random_=<?=rand()?>"><u>[++]</u></a></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['QUERY']?> <a href="../tipossecciones/consulta.php?_random_=<?=rand()?>"><u>[>>]</u></a></td>
				<tr>
			</table>
		</td>
		<td>
			<table>
				<tr>
					<td align="right"  class="conf_field conf_field_gral"><span class="conf_tablas"><?=strtoupper( $CLang->m_Words['SECTIONS'])?></span></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['NEW']?> <?=$CLang->m_Words['SECTION']?><a href="../secciones/modificar.php?_nuevo_=si&_admin_=si&_random_=<?=rand()?>"><u>[++]</u></a></td>
				</tr>
				<tr>
					<td style="padding-left:50px"><?=$CLang->m_Words['QUERY']?> <a href="../secciones/consulta.php?_random_=<?=rand()?>"><u>[>>]</u></a></td>
				<tr>
			</table>

		</td>		
	</tr>
</table>

<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><iframe src="../admin/arbolsecciones.php" width="500" height="300"></iframe></td>
		<td><?=$CLang->m_Words['USERS']?> - <?=$CLang->m_Words['SECTIONSTREE']?><br><iframe src="../../principal/admin/adminarbolsecciones.php" width="200" height="300"></iframe></td>
	</tr>
</table>

 

<!--<div id="div_arbol_secciones" name="div_arbol_secciones" style="overflow:auto;height:200px;width:400px;">


</div> 
  -->			
			
</div>