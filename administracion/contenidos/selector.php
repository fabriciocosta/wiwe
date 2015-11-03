<?Php
// ****************************************************
//             CONSULTA DE TABLA GENERICA
//					MODELO BASE 
// ****************************************************
require "../include/tabla.php";
require "../admin/deftabla.php";

?><html>
<head><title>Selector <?=$tabla->nombre?></title>
<?
require "../include/style.php";
?>
<script>
	var nimagenes;
	function insertar(_align) {
		window.opener.insertarimagenes('<?=$_campo_?>',_align);
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
	<td bgcolor="#000000"><span class="titulo" style="color:#FFFFFF">Selector de imágenes</span></td>
</tr>
<tr>
<td>
<?

echo $_seccion_."<br>";
echo $_campo_."<br>";

$_midir_ = $_SERVER['DOCUMENT_ROOT'];
$nimagenes = 0;
$_tarchivos_->LimpiarSQL();
$_f_ID_SECCION = $_seccion_;
//$_f_ID_TIPOARCHIVO = 3;	//solo imagenes
$_tarchivos_->FiltrarSQL('ID_SECCION');
//$_tarchivos_->FiltrarSQL('ID_TIPOARCHIVO');			
$_tarchivos_->Open();
/*
$_tarchivos_->SQL = 'select archivos.ID,archivos.ID_SECCION,archivos.ARCHIVO,archivos.DESCRIPCION,archivos.URL FROM archivos WHERE archivos.ID_SECCION='.$_seccion_;
*/


if ($_tarchivos_->nresultados>0) {
	while ($row = $_tarchivos_->Fetch($_tarchivos_->resultados)) {
		$file = $row['archivos.URL'];
		$alttext = $row['archivos.DESCRIPCION'];
		$id = $row['archivos.ID'];
		$alttext = str_replace ( '"', "'", $alttext);
		if ($file!='.' and $file!='..' and (GetExtension($file)=='jpg' or GetExtension($file)=='gif' or GetExtension($file)=='swf')) {
			$tipo = GetExtension($file);
			if (GetExtension($file)=='swf') {
					$nimagenes++;
					echo '<table bgcolor="#FFFFFF"><tr><td>'.'<input type="hidden" name="img'.$nimagenes.'" value="'.$file.'">'.'<input type="hidden" name="tipo'.$nimagenes.'" value="'.$tipo.'">'.'<input type="hidden" name="alt'.$nimagenes.'" value="'.$alttext.'">'.'<input type="hidden" name="id'.$nimagenes.'" value="'.$id.'">'.'<input name="cb'.$nimagenes.'" type="checkbox" value="on" unchecked>'.'</td><td><a href="'.$file.'" target="_blank">'.basename($file).'</a></td><td><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.#version=5,0,30,0" height="100" width="100">
					<param name="movie" value="'.$file.'">
					<param name="quality" value="best">
					<param name="play" value="true">
					<embed  pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" src="'.$file.'" type="application/x-shockwave-flash" width="80" quality="best" play="true"> 
					</object></td><td>'.$alttext.'</td></tr></table>';
			} else {
				if ($size = getimagesize ($_midir_.$file)) {
					$nimagenes++;						
					echo '<table bgcolor="#FFFFFF"><tr><td>'.'<input type="hidden" name="img'.$nimagenes.'" value="'.$file.'">'.'<input type="hidden" name="tipo'.$nimagenes.'" value="'.$tipo.'">'.'<input type="hidden" name="alt'.$nimagenes.'" value="'.$alttext.'">'.'<input type="hidden" name="id'.$nimagenes.'" value="'.$id.'">'.'<input name="cb'.$nimagenes.'" type="checkbox" value="on" unchecked>'.'</td><td><a href="'.$file.'" target="_blank">'.basename($file).'</a></td><td><img width="80" src="'.$file.'" border="0"></td><td>'.$alttext.'</td></tr></table>';
				}
			}
		}
	}
} else {
		echo "no hay imagenes en la seccion.";
}
?>
<script>
nimagenes = <?=$nimagenes?>;
</script>
<a href="javascript://" onclick="javascript:insertar('left');">Insertar a la izquierda</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="javascript://" onclick="javascript:insertar('baseline');">Insertar con texto</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="javascript://" onclick="javascript:insertar('right');">Insertar a la derecha</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</form>
</body>
