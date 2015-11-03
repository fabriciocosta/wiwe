<?
$__modulo__ = "admin";

//AQUI CONTRUIMOS EL OBJETO PRINCIPAL DEL SITIO, AL QUE LE ASIGNAMOS LAS TABLAS YA CREADAS
require "../../inc/include/deftabla.php"; 
require "../../inc/core/CAdmin.php";

?>
<html>
<head>
<title><?=$_TITLE_?></title>
<? require "../../inc/include/scripts.php";//los scripts mas comunes?>
<? require "../../inc/include/style.php";?>

</head>
<!-- PAGINA --><?
global $_id_contenido_,$_id_tipocontenido_;
global $_id_detalle_,$_id_tipodetalle_;
$Admin->SetTemplates();
$Admin->Detalles->SubContentBrowse( $Admin->Contenidos, $_id_contenido_, $_id_detalle_, $_id_tipodetalle_ );
?><!-- FIN PAGINA -->
</html>