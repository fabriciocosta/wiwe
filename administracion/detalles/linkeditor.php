<?Php
// ****************************************************
//             CONSULTA DE TABLA GENERICA
//					MODELO BASE 
// ****************************************************

require "deftabla.php";
require "../admin/deftabla.php";

?><html>
<head><title>Selector <?=$tabla->nombre?></title>
<?
require "../include/style.php";
?>
<script>
	var linktexto;
	function insertar() {
		linktexto = '<a href=\'http://'+document.selector.linkv.value+'\' target=\'_blank\'>'+document.selector.linkv.value+'</a>';
		window.opener.insertarlink('<?=$_campo_?>');
	}
</script>
</head>
<body>
<form name="selector" method="post" action="">
<table width="95%" bgcolor="#000000" cellpadding="0" cellspacing="1">
<tr>
<td>
<table width="100%" bgcolor="#FFFFFF" cellpadding="2" cellspacing="0">
<tr>
	<td bgcolor="#000000"><span class="titulo" style="color:#FFFFFF">Ingrese el link y presione insertar.</span></td>
</tr>
<tr>
<td>
<input type="text" value="www.computaciongrafica.com" size="100" name="linkv">
</td>
</tr>
<td>
<a href="javascript:insertar();">Insertar</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</form>
</body>
