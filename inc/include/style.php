<?Php
/*CSS STYLE*/
?>
<link href="<?=$_DIR_SITEABS?>/inc/css/calendar.css" rel="stylesheet" media="screen">
<link href="<?=$_DIR_SITEABS?>/inc/css/general.css" rel="stylesheet" media="screen">
<link href="<?=$_DIR_SITEABS?>/inc/css/lightbox.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="<?=$_DIR_SITEABS?>/inc/css/nivo-slider.css" type="text/css" media="screen" />


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
if ( DebugOn() ) echo '<style> .debugdetails, .errordetails {	display: block; } </style>';

?>

<link type="text/css" rel="stylesheet" href="<?=$_DIR_SITEABS?>/inc/css/bootstrap.min.css"/>
<link type="text/css" rel="stylesheet" href="<?=$_DIR_SITEABS?>/inc/css/full-slider.css"/>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-21354683-2']);
  _gaq.push(['_setDomainName', '.moldeo.org']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>