<?Php
global $home;
global $contact;

global $_DIR_SITEABS;
global $CLang;


$this->Sistema( 'SISTEMA_TEXTO_HOME', $home );

	?>

<!-- 
	<div id="videohome">
			<a href="javascript:hidediv('videohome');" class="cerrar"><?=$CLang->Get("CLOSE")?></a>
			<a  
			 href="<?=$_DIR_SITEABS?>/inc/imgmoldeo/moldeo_reel.flv"  
			 
			 
			  
			 id="player" > 
		</a>
		<script>
			flowplayer( "player", "<?=$_DIR_SITEABS?>/inc/images/flowplayer-3.2.2.swf" );
		</script>
		
	</div>
	-->
<!-- 
<div id="slider">

	
	
</div>

<script type="text/javascript">
$(window).load(function() {
	/*$('#slider').nivoSlider();*/

	$('#slider').nivoSlider({
		effect:'random', //Specify sets like: 'fold,fade,sliceDown'
		slices:13,
		animSpeed:1500, //Slide transition speed
		pauseTime:7000,
		startSlide:0, //Set starting Slide (0 index)
		
		/*
		directionNav:true, //Next & Prev
		directionNavHide:true, //Only show on hover
		controlNav:false //1,2,3...
		*/
		/*controlNav:true, //1,2,3...*/
		controlNavThumbs:true, //Use thumbnails for Control Nav
      	controlNavThumbsFromRel:false, //Use image rel for thumbs
		controlNavThumbsSearch: 'imagen', //Replace this with...
		controlNavThumbsReplace: 'imagen/thm' //...this in thumb Image src
		/*
		keyboardNav:true, //Use left & right arrows
		pauseOnHover:true, //Stop animation while hovering
		manualAdvance:false, //Force manual transitions
		captionOpacity:0.8, //Universal caption opacity
		beforeChange: function(){},
		afterChange: function(){},
		slideshowEnd: function(){} //Triggers after all slides have been shown
		*/
	});

});
</script>
<?

  ?>      <table width="100%" border="0" cellpadding="4" cellspacing="4"><tr>
        <td width="40">&nbsp;</td>
        <td width="780" align="left"><span class="textos_bajada"><?echo $home; ?></span></td>
        <td width="40">&nbsp;</td>
      </tr></table>
  
-->

<div class="jumbotron">
  <h1>Moldeo 1.0 Beta</h1>
  <p><?echo $home; ?></p>
  <p><a class="btn btn-primary btn-lg" href="/descargas#head" role="button">Descargar</a></p>
</div>




