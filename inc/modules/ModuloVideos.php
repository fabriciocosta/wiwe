<?php
global $_seccion_;
global $CLang;

global $texto;

$this->Sistema('SISTEMA_VIDEOS', $texto);
?>
<a name="head"></a>
<div class="container">
    
<table cellpadding="0" cellspacing="0" border="0" width="95%">
	<tr>
		<td style="text-align:justify;"><br>
		<span class="text_white" ><?=$texto?></span>
		<br>

<table width="100%" border="0" cellspacing="0" cellpadding="15">
  <tr>
    <td align="center">
		
<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/UI2-gF9iyzQ&hl=es&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/UI2-gF9iyzQ&hl=es&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object>
<br><br>

<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/J0NjFeKE7Qo&hl=es&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/J0NjFeKE7Qo&hl=es&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object>
<br><br>

<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/RjjN1siRqgA&hl=es&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/RjjN1siRqgA&hl=es&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object>
<br><br>
		
		<object height="321"
 width="400"><param name="allowfullscreen"
 value="true"><param name="allowscriptaccess"
 value="always"><param name="movie"
 value="http://vimeo.com/moogaloop.swf?clip_id=3084865&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1">
                        <embed
 src="http://vimeo.com/moogaloop.swf?clip_id=3084865&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1"
 type="application/x-shockwave-flash" allowfullscreen="true"
 allowscriptaccess="always" height="321" width="400"></object>
 
 <br><br>
         
 <param
 name="allowfullscreen" value="true"><param
 name="allowscriptaccess" value="always"><param
 name="movie"
 value="http://vimeo.com/moogaloop.swf?clip_id=3617517&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1">
                        <embed
 src="http://vimeo.com/moogaloop.swf?clip_id=3617517&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1"
 type="application/x-shockwave-flash" allowfullscreen="true"
 allowscriptaccess="always" height="321" width="400"></object>
 
	
		
		</td>
	</tr>		
</table>
		
		</td>
	</tr>		
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="15">
  <tr>
    <td align="center"><img src="/images/galeria/Photo-0075.jpg" alt="" width="1280" height="1024" id="fotog" /></td>
  </tr>

  <tr>
    <td align="center"><img src="/images/galeria/imagen.asp.jpg" alt="" width="620" height="465" id="fotog" /></td>
  </tr>
  <tr>
    <td align="center"><img src="/images/galeria/narciso2008.jpg" alt="" width="640" height="480" id="fotog" /></td>
  </tr>
  <tr>
    <td align="center"><img src="/images/galeria/Photo-0006.jpg" alt="" width="1280" height="1024" id="fotog" /></td>
  </tr>

  <tr>
    <td align="center"><img src="/images/galeria/IMG_1743.JPG" alt="" width="1600" height="1200" id="fotog" /></td>
  </tr>
  <tr>
    <td align="center"><img src="/images/galeria/Photo-0004.jpg" alt="" width="1280" height="1024" id="fotog" /></td>
  </tr>
  <tr>
    <td align="center"><img src="/images/galeria/contemplacion.jpg" alt="" width="378" id="fotog2" /></td>
  </tr>

  <tr>
    <td align="center"><img src="/images/galeria/sinestesia0.jpg" alt="" width="800" height="600" id="fotog" /></td>
  </tr>
  
  <tr>
    <td align="center">&nbsp;</td>

  </tr>
</table>


<?

$this->InicializarTemplatesCompletos();

$this->Contenidos->m_tcontenidos->LimpiarSQL();
$this->Contenidos->m_tcontenidos->FiltrarSQL('ID_TIPOCONTENIDO','',FICHA_VIDEO);
//$this->Contenidos->m_tcontenidos->OrdenSQL();
$this->Contenidos->m_tcontenidos->Open();

$cn = 0;
echo '<table width="100%" border="0" cellpadding="0"  cellspacing="0">';
if ( $this->Contenidos->m_tcontenidos->nresultados>0) {
	echo '<tr>';
	
	while($rrr = $this->Contenidos->m_tcontenidos->Fetch($this->Contenidos->m_tcontenidos->resultados)) {
		
		$Partner = new CContenido($rrr);
		echo "<td>";
		$this->TiposContenidos->MostrarCompleto($Partner);
		echo "</td>";
		$cn++;
		
		if ( ( $cn % 2 ) == 0 ) {
			echo "</tr><tr>";
		}		
	}
	
	if ( ( $cn % 2 ) == 0 ) {
		echo "</tr>";
	} else {
		echo "<td></td></tr>";
	}
}
echo '</table>';

?>

</div>
