<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td align="left" valign="bottom" width="124">RESULTADOS</td>
<? if ($tabla->nresultados>0) { ?>
		<td width="60%" valign="bottom"><span class="resultados"><?=$tabla->nresultados?></span></td>
<?
} else {
?>
		<td width="60%" valign="bottom"><span class="resultadosninguno">NINGUNO</span></td>
<?
}
?>
<td align="right" valign="bottom">&nbsp;</td><td  align="right" valign="bottom"><span class="navegador2">ordenar por&nbsp;&nbsp;</span><?
	$tabla->Ordenar($_orden_);
?></td>
	</tr>
	<tr>
		<td colspan="3" height="1"><img src="../images/spacer.gif" width="1" height="1"></td>
	</tr>
</table>
