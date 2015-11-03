<?
/**
 * 
 * "C" => contenido => content
 * "S" => seccion => section
 * "D" => detalle => detail
 * "U" => usuario => user
 * "R" => relacion => relationship
 * 
 * "TC" => tipocontenido => type content
 * "TS" => tiposeccion => type section
 * "TD" => tipodetalle => type detail
 * "TR" => tiporelacion => type relationship
 * 
 * "RC" => relacion a un contenido (id)
 * "RS" => relacion a una seccuib (id) 
 * "RD" => relacion a un detalle (id)
 * "RR" => relacion a una relacion (id)
 * 
 * "RCx"=> relacion a multiples contenidos (ids)
 * "RSx"=> relacion a multiples secciones (ids) 
 * "RDx"=> relacion a multiples detalles (ids) 
 * "RRx"=> relacion a multiples relaciones (ids)
 *  
 * "RTCx"=> relacion a multiples tipos de contenidos (ids)
 * "RTSx"=> relacion a multiples tipos de secciones (ids) 
 * "RTDx"=> relacion a multiples tipos de detalles (ids) 
 * "RTRx"=> relacion a multiples tipos de relaciones (ids)
 * 
 * "T"=>"texto"
 * "B"=>"blob de texto"
 * "BH"=>"blob de texto html"
 * "E"=>"decimal exponencial"
 * "N"=>"entero"
 * "N"=>"entero"
 *  * */

$namemod = "panel";

$tiposcontenidos = array( 
					);

$tiposdetalles = array(
					);

/* tipos de detalles que se pueden customizar... */
$tiposdetalles_custom = array(

					);					
					
$contenidos = array();


$tipossecciones = array( 
				"Panel"=>array(
					"tipo"=>"SECCION_PANEL","descripcion"=>"Sección de contenidos creados desde el panel"
					)
				
				);

$secciones = array(
				"Panel"=>array( 
				"Panel de contenidos"=>array(
						"id_seccion"=>"1", 
						"id_tiposeccion"=>"SECCION_PANEL", 
						"nombre"=>"Panel", 
						"descripcion"=>"Sección del panel de contenidos", 
						"carpeta"=>"panel", 
						"categoria"=>"N", 
						"baja"=>"S" )
						)
				);

$relaciones = array();
$tiposrelaciones = array();

$moduloarchivos = array(
				"../modules/panel/inc/modules/ModuloPanel.php"=>"../../inc/modules/ModuloPanel.php",
				"../modules/panel/inc/css/panel.css"=>"../../inc/css/panel.css",
				"../modules/panel/inc/lang/panel.csv"=>"../../inc/lang/panel.csv",
				"../modules/panel/principal/home/panel.php"=>"../../principal/home/panel.php",
				"../modules/panel/inc/templates/CONTENIDO.panel.consulta.html"=>"../../inc/templates/CONTENIDO.panel.consulta.html",
				"../modules/panel/inc/templates/CONTENIDO.panel.edicion.html"=>"../../inc/templates/CONTENIDO.panel.edicion.html"
				);
				
				
?>