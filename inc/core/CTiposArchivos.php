<?php

/**
 * class CTiposArchivos
 *
 * @version 28/11/2003
 * @copyright 2003 
 **/

 /*
 define("DNK_ARCHIVO_BINARIO",1);
define("DNK_ARCHIVO_DOCUMENTACION",2);
define("DNK_ARCHIVO_IMAGEN",3);
define("DNK_ARCHIVO_ANIMACION",4);
define("DNK_ARCHIVO_PELICULA",5);
define("DNK_ARCHIVO_SONIDO",6);
define("DNK_ARCHIVO_STREAM",7);
 */
 
class CTiposArchivos {

	var $m_ttiposarchivos;//tabla tiposcontenidos
	
	function CTiposArchivos($__ttiposarchivos__) {
				
		$this->m_ttiposarchivos = $__ttiposarchivos__;
		
	}
	
	function Set($__ttiposarchivos__) {

		$this->m_ttiposarchivos = $__ttiposarchivos__;

	}

	
	function MostrarColapsado($__CArchivo__) {
		
		switch($__CArchivo__->m_id_tipoarchivo) {
			case DNK_ARCHIVO_IMAGEN:
				echo '<tr><td><span class="archivo_colapsado">';
				echo '<img src="../../inc/images/tiposarchivos/imagen.gif" hspace="4">';								
				echo '<a href="home.php?_seccion_='.$__CArchivo__->m_id_seccion.'&_archivo_='.$__CArchivo__->m_id.'">';
				echo $__CArchivo__->m_nombre;
				echo '</a></span></td></tr>';
				echo '<tr><td height="5"><img src="../../inc/images/spacer.gif" height="5" border="0"></td></tr>';					
				break;

			default:
				echo '<tr><td><span class="archivo_colapsado">';
				echo '<img src="../../inc/images/tiposarchivos/normal.gif" hspace="4">';								
				echo '<a href="home.php?_seccion_='.$__CArchivo__->m_id_seccion.'&_archivo_='.$__CArchivo__->m_id.'">';
				echo $__CArchivo__->m_nombre;
				echo '</a></span></td></tr>';
				echo '<tr><td height="5"><img src="../../inc/images/spacer.gif" height="5" border="0"></td></tr>';					
				break;
				
		}
		
	}

	function MostrarResumen($__CArchivo__) {
		
		switch($__CArchivo__->m_id_tipoarchivo) {
		
			default:
				echo '<tr><td><span class="archivo_resumen">';
				echo '<img src="../../inc/images/tiposarchivos/normal.gif" hspace="4">';								
				echo '<a href="home.php?_seccion_='.$__CArchivo__->m_id_seccion.'&_archivo_='.$__CArchivo__->m_id.'">';
				echo $__CArchivo__->m_nombre;
				echo '</a></span></td></tr>';
				echo '<tr><td><span class="archivo_resumen_descripcion">';
				echo $__CArchivo__->m_descripcion;
				echo '</td></tr>';					
				break;
				
		}
		
	}


	function MostrarCompleto($__CArchivo__) {
		
		switch($__CArchivo__->m_id_tipoarchivo) {
		
			default:
				echo '<tr><td>&nbsp;</td></tr>';
				echo '<tr><td><span class="archivo_nombre">';
				echo '<a href="'.$__CArchivo__->m_url.'" target="_blank">';
				echo $__CArchivo__->m_nombre;
				echo '</a>';
				echo '</span></td></tr>';
				echo '<tr><td>&nbsp;</td></tr>';
				echo '<tr><td><span class="archivo_descripcion">'.$__CArchivo__->m_descripcion.'</span><td></tr>';
				echo '<tr><td>&nbsp;</td></tr>';
				break;
				
		}
		
	}
	
	
} 
?>