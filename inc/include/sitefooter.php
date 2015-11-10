<!-- 
<!--</div>
<div id="sitefooter">
-->

        <? 
        
        global $contact;
        
		$Sitio->Sistema( "SISTEMA_CONTACTO", $contact );        
		
		function LinksSeccion( $__idseccion__, $__modulo__, $__orden__="" ) {
			
			global $Sitio;
			 
			$Seccion = $Sitio->Secciones->GetSeccion( $__idseccion__ );
			
			echo '<span class="titulares_footer"><a href="/'.strtolower( $__modulo__ ).'" class="titulares_footer">'.$Seccion->Nombre().'</a></span>';
			echo '<span class="textos_footer">';
			
			$t = $Sitio->Contenidos->m_tcontenidos;
			
	        $t->LimpiarSQL();
	        $t->FiltrarSQL('ID_SECCION','', $Seccion->m_id );          
	        if ($__orden__=="") $t->OrdenSQL('RAND()');
	        else $t->OrdenSQL($__orden__);
	        $t->LimiteSQL('0','7');
	        $t->Open();
	        $Sitio->Contenidos->MostrarResultadoColapsado('<div class="'.$__modulo__.'"><a class="titulo" title="*TITULO*" href="/'.$__modulo__.'#*IDCONTENIDO*">*TITULO*</a></div>');

	        echo '<a href="/'.strtolower( $__modulo__ ).'">++:...:++</a></span>';
			
		}        
?>

</div>
<?php
/**
<!-- 
<div id="bottommenu">
	 
		<? //investigacion?>
        <div id="investigacion" class="seccion"><?  LinksSeccion( 6, "noticias","FECHAEVENTO DESC" ) ?></div>       
        <? //desarrollos?>
        <div id="desarrollos" class="seccion"><? LinksSeccion( 5, "descargas","ORDEN ASC" ) ?></div>
        <? //productos ?>
        <div id="productos" class="seccion"><? LinksSeccion( 10, "tutoriales","ORDEN ASC" )?></div>
        <? //tecnologias ?>
        <div id="tecnologias" class="seccion"><?LinksSeccion( 13, "desarrollo","ORDEN ASC" )?></div>
        
        <div id="contact" class="seccion"><span class="titulares_footer"><?=$GLOBALS['CLang']->Get('CONTACT')?></span><br />
        <span class="textos_footer">
          <? 
          
          	echo $contact;

          	//$Sitio->Contenidos->MostrarColapsadosPorTipo( 6, FICHA_COMUNIDAD, 5, -1, '<div class="comunidad">*COPETE*</div>','RAND()');

          ?>    
         </span>
         </div>
</div>-->
*/
?>

<!-- 
</div>
</div>
</div>
 -->

<!-- Bootstrap Core JavaScript -->
<script type="text/javascript" src="<?=$_DIR_SITEABS?>/inc/js/bootstrap.min.js"></script>
	
<!-- Script to Activate the Carousel -->
<script>
    $('.carousel').carousel({
        interval: 5000 //changes the speed
    })
</script>
</body>
</html>
