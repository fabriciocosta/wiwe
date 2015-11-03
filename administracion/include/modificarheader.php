<?
if ($tabla->nombre=="contenidos" ||
	$tabla->nombre=="tiposcontenidos" ||
	$tabla->nombre=="detalles" ||
	$tabla->nombre=="tiposdetalles"	) {
		?>
		<script>show_module('fichas');</script>	
	<?
} else if ($tabla->nombre=="secciones" ||
	$tabla->nombre=="tipossecciones") {
	?>
		<script>show_module('secciones');</script>	
	<?
} else if ($tabla->nombre=="usuarios" ||
	$tabla->nombre=="grupos" || 
	$tabla->nombre=="grupossecciones" || 
	$tabla->nombre=="gruposusuarios" ||
	$tabla->nombre=="logusuarios"
	) {
	?>
		<script>show_module('usuarios');</script>	
	<?
}
?>

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="15"><img src="../images/spacer.gif" width="10" height="10"></td>
	<td>
		<?if ($_borrar_=='si') {
		?>
		<table cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td><img src="../images/spacer.gif" width="1" height="15"></td>
			</tr>
			<tr>
				<td><span class="tablas">BORRANDO</span></td>
			</tr>
			<tr>
				<td><img src="../images/spacer.gif" width="1" height="5"></td>
			</tr>
		</table>
		<?
		} else {
		?>
		<table cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td><img src="../images/spacer.gif" width="1" height="15"></td>
			</tr>
			<tr>
				<td><span class="tablas"><?=$_seccion_?></span></td>
			</tr>
			<tr>
				<td><img src="../images/spacer.gif" width="1" height="5"></td>
			</tr>
		</table>
		<?
		}
		?>	
