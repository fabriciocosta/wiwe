<?	

//require_once "../../inc/include/deftabla.php";
/*require_once "../../inc/core/CAdmin.php";

if (!defined("DNK_SITE") and !defined("Admin")) {
  	
	define("DNK_SITE","OK");
  	
  	$Admin = new CAdmin($_tsecciones_,$_ttipossecciones_,$_tcontenidos_,$_ttiposcontenidos_,$_tarchivos_,$_ttiposarchivos_,$_tdetalles_,$_ttiposdetalles_,$_tusuarios_,$_tlogs_);
  	
}
*/
		require_once "../../inc/include/deftabla.php";		
		//require "../../inc/core/Dinamik.php";
		
 		$TiposDetalles = new CTiposDetalles($_ttiposdetalles_);		
		$Detalles = new CDetalles($_tdetalles_,$TiposDetalles);		
		$TiposContenidos = new CTiposContenidos($_ttiposcontenidos_,$Detalles);		
		$Contenidos = new CContenidos($_tcontenidos_,$TiposContenidos);
		
?>
<html>
<head>
<title>Funciones</title>
<? require_once "../../inc/include/scripts.php"; ?>
<style>
body,td,span,div,input,table,font,br {
	font-size: 11px;
	font-family: Arial;
	vertical-align: middle;
	text-align: left;
}
</style>
</head>
<body>

<?=$CLang->m_Words["FUNCTIONS"]?>
<br>

<form action="" method="post" name="formsetup">
<input type="hidden" value="execute_my_function" name="_accion_">
<input type="hidden" value="funciones" name="_mod_">

<table>
	<tr>
			<td  valign="bottom"><?
			error_reporting(E_ERROR);
			
			//$_tcontenidos_->Combo( "", "COUNTRY","Country", "contenidos", "ID", "TITULO", "", "", "", "contenidos.TITULO", "contenidos.ID_TIPOCONTENIDO=".FICHE_COUNTRY );
			
			?></td>
			<td valign="bottom"><?
		
			?>
			<td valign="bottom"><button type="submit">Execute My Function</button></td>
	</tr>
</table>
<?

if ( $_accion_ == "execute_my_function" ) {


	
}

?>
</form>
</body>
</html>