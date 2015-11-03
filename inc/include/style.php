<?Php
/*CSS STYLE*/
?>

<link href="<?=$_DIR_SITEABS?>/inc/css/calendar.css" rel="stylesheet" media="screen">
<link href="<?=$_DIR_SITEABS?>/inc/css/general.css" rel="stylesheet" media="screen">
<link href="<?=$_DIR_SITEABS?>/inc/css/lightbox.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="<?=$_DIR_SITEABS?>/inc/css/nivo-slider.css" type="text/css" media="screen" />
<link href="<?=$_DIR_SITEABS?>/inc/css/jquery.treeview.css" rel="stylesheet" media="screen">

<?
global $__modulo__;

if ($__modulo__!="admin") {
?>	
	<link type="text/css" rel="stylesheet" href="<?=$_DIR_SITEABS?>/inc/css/site.css"/>
<?
} else {
?>
<link type="text/css" rel="stylesheet" href="<?=$_DIR_SITEABS?>/inc/css/admin.css"/>
<?
}

///DEBUG STYLE SHOW DEBUG MESSAGES
if ( DebugOn() ) echo '<style> .debugdetails, .errordetails {	display: block !important; } </style>';

?>
<link href="<?=$_DIR_SITEABS?>/inc/css/jquery-ui-1.8.22.custom.css" rel="stylesheet">
<style>
.ui-helper-hidden-accessible {
	clip: inherit !important;
}
</style>