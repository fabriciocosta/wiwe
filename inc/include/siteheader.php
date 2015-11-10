<!-- SITEHEADER -->
<?Php

global $_DIR_SITEABS;
global $__modulo__;

?>

<?Php
if ($__lang__!="") $ln = ".".strtolower($__lang__);
?>
<body class="<?=$__modulo__?>">


<!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/" title="Moldeo - www.moldeo.org <?=getenv("REMOTE_ADDR")?>"><img src="<?=$_DIR_SITEABS?>/inc/moldeo/moldeo.logo.transparent.png" height="40" border="0"/></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="/features#head" title="Features/Caracter&iacute;sticas">Features</a>
                    </li>
                    <li>
                        <a href="/downloads#head" title="Downloads/Descargas">Downloads</a>
                    </li>
                    <li>
                        <a href="/work#head" title="Work/Obras">Work</a>
                    </li>
					<!--
                    <li>
						<a href="/event#head" title="Event/Eventos">Event</a>
                    </li>
                    <li>
						<a href="/gallery#head" title="Gallery/Galer&iacute;a">Gallery</a>
                    </li>
					-->
                    <li>
						<a href="/documentacion#head" title="Documentation/Documentaci&oacute;n">Documentation</a>
                    </li>
                    <li>
						<a href="/community#head" title="Community/Comunidad">Community</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
    
<?php 

if ($__modulo__=="home") {

	$novedades = $Sitio->Contenidos->GetContenidos( FICHA_NOVEDAD, "", "", "contenidos.ORDEN ASC" );
	$slidenumber = 0;
	$indicators = "";
	$imgs = "";
	$firstclass = " active";
	
	foreach($novedades as $kid=>$cid) {
		
		$Novedad = $Sitio->Contenidos->GetContenidoCompleto($cid);
		
		$link = $Novedad->m_detalles["NOVEDAD_LINK"]->m_detalle;
		$imagen = trim($Novedad->m_detalles["NOVEDAD_IMAGEN"]->m_detalle);
		$titulonovedad = trim($Novedad->Titulo());
		$resumennovedad = trim($Novedad->Copete());
		//echo $titulonovedad;
		if ($imagen!="") {
			//$slidercontent = '<img src="'.$Novedad->m_detalles["NOVEDAD_IMAGEN"]->m_detalle.'" alt="'.$Novedad->Titulo().'" title="'.$Novedad->Titulo().'"/>';
			//if ($link!="") $link = '<a href="'.$link.'">'.$slidercontent.'</a>'."\n";
			$sliderindicator = "\n".'<li data-target="#myCarousel" data-slide-to="'.$slidenumber.'" class="'.$firstclass.'"></li>';
			$indicators.= $sliderindicator;
			$slidercontent = '<div class="item'.$firstclass.'">
								<div class="fill" 
										style="background-image:url(\''.$Novedad->m_detalles["NOVEDAD_IMAGEN"]->m_detalle.'\');"></div>
                <div class="carousel-caption">
                    <h2>'.$titulonovedad.'</h2>
                    <h4>'.$resumennovedad.'</h4>
                </div>
            </div>';
			$firstclass = "";
			$slidenumber+= 1;
			$imgs.= $slidercontent;
		}
	}


?>

<header id="myCarousel" class="carousel slide">
        <!-- Indicators -->
        <ol class="carousel-indicators"><?=$indicators?></ol>

        <!-- Wrapper for Slides -->
        <div class="carousel-inner"><?=$imgs?></div>

        <!-- Controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="icon-prev"></span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="icon-next"></span>
        </a>
</header>

<?
} else {
?>
<a name="head"></a><br><br><br><br>
<?
}
?>
<div class="debugdetails" style="position:absolute;left:0px;top:0px;"><a href="#" onclick="javascript:toggleDivAll('div_debug');">hide/show debug</a></div>
<!-- <div id="sitebody">
 -->
<!-- 
<div id="sitecontainer"><div id="siteheader"><div id="siteinfo"><?=date("d/m/Y h:i")?></div>
<div id="siteuserinfo"><?=$Sitio->Usuarios->ShowLogin("","","&nbsp;|&nbsp;")?><?=$CMultiLang->ShowLangOptions()?></div>
	<div id="sitelogo"><a href="/" title="Moldeo - www.moldeo.org <?=getenv("REMOTE_ADDR")?>"><img src="<?=$_DIR_SITEABS?>/inc/moldeo/moldeo.logo.transparent.png" height="90" border="0"/></a></div>
</div>

-->
    
    
<!-- <div id="sitemenu">-->
<?

if ($__modulo__!="home") {
		
?>	
	<div id="section_header">
		<div class="menu_section_header_up" id="section_header_top"><img width="100%" height="7" border="0" src="/inc/imgmoldeo/section_header_up_back.png"></div>
		<div class="menu_section_header_mid" id="section_header_middle"><div class="section_header_title"><?=ucwords( $__modulo__ )?></div></div>
		<div class="menu_section_header_down" id="section_header_bottom"></div>
		<div id="section_header_description"><div class="section_description"></div></div>
	</div>
	
<?
}
?>
<!-- </div>-->

<div class="module">
<!-- <div id="sitemodule"> -->