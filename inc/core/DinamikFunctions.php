<?php

/**
 * DinamikFunctions
 *
 * @version $Id$
 * @copyright 2003 Fabricio Costa
  **/

ini_set( 'display_errors',true);
error_reporting(E_ERROR|E_PARSE);
//error_reporting(E_ALL);

global $_debug_;
global $__modulo__;
global $debugon;
global $cg_admin_debug;
$debugon = "";
$cg_admin_debug = false;

$ip = getenv("REMOTE_ADDR") ;
if ( ($ip=="127.0.0.1" && $__modulo__!="config") || $ip=="186.136.125.119" || $ip=="190.195.3.78") {
	//error_reporting(E_ALL);
	//$debugon = true;
}

//$debugon = true;


function TextCounter( $_id_, $min='', $max='' ) {
	
	if (!is_numeric($max)) return "";
	
	$_title_ = "Caracteres restantes ";
	return '
		<div class="ficha_text_counter"> '.$_title_.'
		      <input id="'.$_id_.'_restantes" readonly="readonly" type="text" name="restantes" size="3" maxlength="3" value="'.$max.'"  align="right" />
		    ({MAX} '.$max.')</div>
			
			<script>
		textCounter( \''.$_id_.'\',\''.$_id_.'_restantes\', '.$max.' );
		$("#'.$_id_.'").keydown(function() {
			textCounter( \''.$_id_.'\',\''.$_id_.'_restantes\', '.$max.' )
		});
		</script>
	';
	
}


function UI_DatePicker( $id ) {

	$dtp = '
	<script>
		$(function() {
		$( "#'.$id.'" ).datepicker({
			inline: true,
			dayNames: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
			dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
			monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
			monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dec"],
			dateFormat: "yy-mm-dd",
		});
	});
	</script>
';

	return $dtp;
}

function UI_DateTimePicker( $id ) {

	$dtp = '
	<script>
	$(function() {
	$( "#'.$id.'" ).datetimepicker({
	inline: true,
	dayNames: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
	dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
	monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
	monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dec"],
	dateFormat: "yy-mm-dd",
		
	timeFormat: "hh:mm",
	showSecond: false,
	showMillisec: false,
	timeOnlyTitle: "Hora",
	timeText: "Hora",
	hourText: "Hora",
	minuteText: "Minutos",
	secondText: "Segundos",
	millisecText: "Milisegundos",
	currentText: "Ahora",
	closeText: "Cerrar",
	ampm: false
});
});
</script>
';

	return $dtp;
}


function UI_TimePicker( $id ) {

	$dtp = '

	<script>
	$(function() {
	$( "#'.$id.'" ).timepicker({
	inline: true,
		
	timeFormat: "hh:mm",
	showSecond: false,
	showMillisec: false,
	timeOnlyTitle: "Hora",
	timeText: "Hora",
	hourText: "Hora",
	minuteText: "Minutos",
	secondText: "Segundos",
	millisecText: "Milisegundos",
	currentText: "Ahora",
	closeText: "Cerrar",
	ampm: false
});
});
</script>

';

	return $dtp;
}

function CloseHtml( $html_str ) {
	

	 require_once "../../inc/core/htmlfixer.class.php";
	 
	 $HTML = new HtmlFixer();
	 if (is_object($HTML))
		 $html_str = $HTML->getFixedHtml($html_str);

		return $html_str;
}

function FormatLink( $link, $domain="" ) {
	
	$link = trim($link);
	
	if ( substr( $link, 0, 4 )=="http"
			|| substr( $link, 0, 5 )=="https"
			||	substr( $link, 0, 3 )=="ftp"
			||	substr( $link, 0, 4 )=="sftp" ) {
		
		$link = $link;
		
	} else if (substr( $link, 0, 3 )=="www") {
		$link = "http://".$link;
	} else if ($domain!="" && $link!="") {
		if (is_numeric(strpos($link,$domain))) {
			return $link;
		} else {
			$link = "http://".$domain."/".$link;
		}
	} 
	
	return $link;
	
}

function Autocomplete( $idunico, $inputtext, $tabla /*contenidos,usuarios,secciones*/, $sql, $sqlcount) {

	$str = ' 	<div id="div_searchterm_autocomplete_'.$idunico.'"></div>
					<div id="div_searchterm_autocomplete_'.$idunico.'loader"></div>';
	$str.= "   <input 
							
						id=\"autocomplete_".$idunico."\"

						onblur=\"javascript:searchterm_onblur('autocomplete_".$idunico."','".$inputtext."');setTimeout(' hidediv(\'div_searchterm_autocomplete_".$idunico."\')', 1000 );\"
	        	onkeypress=\"preventSubmit(event);\"
	        	onkeyup=\"setTimeout('searchterm_add_autocomplete2( \''+event.keyCode+'\',\'autocomplete_".$idunico."\',\'text\',\'".$tabla."\',\'".$sql."\',\'".$sqlcount."\',\'0\',\'\')',300);\"

						onfocus=\"javascript:searchterm_onfocus('autocomplete_".$idunico."','".$inputtext."');\"	        	
	        	type=\"text\"        
	        	value=\"".$inputtext."\" 
	        	autocomplete=\"off\"
	        	>";
	$str.= "    <input type=\"hidden\" value=\"\"
						id=\"autocomplete_".$idunico."_idx\"
						size=\"4\"						
						>";

	return $str;
}


function SearchAutocomplete( $idunico, 
$inputtext, 
$tipocontenido ) {

	
   $str = "    <input type=\"text\" 
						   class=\"text autocomplete_tema\" 
						   id=\"".$idunico."\" 
						   name=\"".$idunico."\"
						   value=\"".$inputtext."\" 
						   size=\"15\"  
						   autocomplete=\"off\">";
   
   $str.= "     <span class=\"minibutton_clear agregar-categoria\" 
       						 onclick=\"javascript:searchterm_add('".$idunico."','text','".$tipocontenido."','','','','');\">+</span>
        	";
        

        
   $str.= '     
   <div id="div_searchterm_'.$idunico.'" class="searchterm" style="display:none;"></div>
   <div id="div_searchterm_'.$idunico.'loader" class="loader" style="display:none;"><img src="/wiwe/inc/imgrrcc/loader.gif"></div>';
        
	
	return $str;
}



function Redirection( $codigo, &$redirecciones, $custom="" ) {
		
	$redirection = "";
	
	if (isset($redirecciones[$codigo])) {
	
		Debug("Redirection: codigo :".$codigo." => ".$redirecciones[$codigo]);
		if ($redirecciones[$codigo][0]=="[PREVIOUS_URL]"  && $custom=="") {
			$custom = $_SERVER['HTTP_REFERER'];			
		}
		
		if ($custom=="") {
			$custom = $redirecciones[$codigo][0];
		}		
		if ($custom!="") {
			ShowMessage("Cargando...:");
			$redirection = "<script>
						function redirection() {
								window.location.href='".$custom."';
						}
						
						window.onload = setTimeout( 'redirection()', '".$redirecciones[$codigo][1]."' );
						</script>";		
						
		} else {
			$message = $redirecciones[$codigo][2];
			if ($message!="") {
				ShowMessage($message);
				$redirection = "";
			}
		}
	} else {
		DebugError("Redirection: código no existe : ".$codigo);
	}
	return $redirection;
}

function PanelEdit( $_cID_ ) {
	
		global $Sitio;
		global $CLang;
		
		if (is_object($Sitio)) {
		if (is_object($Sitio->Usuarios->GetSesionUsuario()) ) {
			$UserAdmin = $Sitio->Usuarios->GetSesionUsuario();
			if ( $UserAdmin->m_nivel<=1 ) {
				if ( ModuloInstalled("panel") ) {
					return '<div class="useradminlogged">
						<a href="/panel/_accion_=modificar&_cID_='.$_cID_.'" class="inputbutton">'.$CLang->Get("EDIT").'</a>
						<a href="/panel/_accion_=borrar&_cID_='.$_cID_.'" class="inputbutton">'.$CLang->Get("DELETE").'</a>
						</div>';
				}
			}
		}
		}
		return "";
	
}

function ModuloInstalled( $namemod ) {
	$ex = true;
	$ex&= file_exists( "../../inc/modules/"."Modulo".str_replace( " ","",ucwords( strtolower( $namemod ) ) ).".php" );
	$ex&= file_exists( "../../principal/home/".strtolower( str_replace(" ","_",$namemod) ).".php" );
	return $ex;
}

function ShowMenu( &$items , $echo = true ) {
	
	$nitem = 0;
 	$menu = "";
 	
 	foreach($items as $mod=>$values) {
 		 		
 		if ($values["visible"]) {
 			
 			$menu = str_replace( " last-item","", $menu );
 			
 			if ( ($nitem%2)== 0) $class_item = "even-item";
 			else  $class_item = "odd-item";
 			
 			if ($nitem==0) $class_item.= " first-item";
 			
 			$class_item.= " last-item";
 			
 			$menu.= '<div id="menuitem" class="'.$class_item.'">
 						<div id="item_'.$mod.'" class="mitem'.$values["selected"].'">
 							<a href="'.$values["link"].'">'.$values["title"].'</a>
 						</div>
 						<div id="item_'.$mod.$values["selected"].'" class="mitembord">
 						</div>
 				</div>';
 			
 			$nitem++;
 		}
 	}
 	
 	if ($echo) echo $menu;
 	else return $menu;
}

function DebugOn() {
	global $debugon;
	if ($debugon=="") $debugon = DebugIsOn();
	return $debugon;
}

function DebugIsOn() {
	if ( isset($GLOBALS['Sitio']) ) {
		global $Sitio;
		if (is_object($Sitio->Usuarios->GetSesionUsuario())) {
			$UserAdmin = $Sitio->Usuarios->GetSesionUsuario();
			if ($UserAdmin->m_nick=="cg_admin") {
				//return true;
			}
		}

	} else if ( isset($GLOBALS['Admin']) ) {
		global $Admin;
		if (is_object($Admin->UsuarioAdmin)) {
			if ($Admin->UsuarioAdmin->m_nick=="cg_admin") {
				global $cg_admin_debug;
				return $cg_admin_debug;
			}
		}

	}
	return false;
}

function Debug( $debudgdetails ) {
	if (DebugOn()) echo '<div id="div_debug" class="debugdetails"><span>'.$debudgdetails.'</span></div>';
}

function DebugError( $debudgdetails ) {
	
	if (DebugOn()) echo '<div  id="div_debug" class="errordetails"><span>'.$debudgdetails.'</span></div>';
}

function ShowError( $msg_error, $echo = true ) {
	$res = '<div class="showerror"><span>'.$msg_error.'</span></div>';
	
	if ($echo) echo $res;
	else return $res;	
}

function LogError( $msg ) {
	
	$fhandle = fopen( "../../tmp/wiwe.log", "a+");
	if ($fhandle) {
		fwrite($fhandle, $msg."\n");
	}
	fclose($fhandle);
	
}
function ShowMessage( $msg, $echo = true, $close = false ) {
	//$res = '<div class="showmessage"><span>'.$msg.'</span></div>';
	if ($close==false) $closeb = '<a href="#" class="close" data-dismiss="alert">&times;</a>';

    $res = '<div class="alert alert-warning">
        '.$msg.'
    </div>';


	if ($echo) echo $res;
	else return $res;
}

function TiposParsing( $sql_string, $tabla="" ) {
	
	global $_TIPOS_;
	
	if ($tabla=="") {
		foreach( $_TIPOS_ as $TABLA=>$TIPOS) {
			foreach($TIPOS as $CLAVE=>$ID) {
				$sql_string = str_replace( $CLAVE, $ID, $sql_string );					
			}
		}
	} else {
		$TIPOS = $_TIPOS_[$tabla];
		foreach($TIPOS as $CLAVE=>$ID) {
			$sql_string = str_replace( $CLAVE, $ID, $sql_string );					
		}
	}
	return $sql_string;
}

function TextoML( $_contenidoml_, $lang='' ) {
	global $__lang__;
	
	//tratamos de tomar el que esta elegido por default
	if (trim($lang)=='') $lang = $__lang__;
	
	if ( trim($lang)!="" ) {
		$a = strpos( $_contenidoml_,"<".$lang.">")+strlen("<".$lang.">");
		$b = strpos( $_contenidoml_,"</".$lang.">");
		
		if ($a!=false and $b!=false) {
			
			if ($b>$a) $_ret_ = substr($_contenidoml_,$a,($b-$a));
			else $_ret_='';
			
		} else $_ret_='';	
	} else $_ret_ = $_contenidoml_;
	
	return $_ret_;	
}

/**
 * Muestra un bloque con contenidos de una seccion con links al modulo correspondiente.
 *
 * @param String|Number $__seccion__
 * @param Number $__tipocontenido__
 * @param String $__modulo__
 * @param String $__orden__
 * @param Number $__limite__
 * @param String $__template__
 * @param String $__clase__
 */
function ShowBlock( $__seccion__, $__tipocontenido__, $__modulo__, $__orden__="RAND()", $__limite__=5, $__template__="", $__clase__="" ) {
	
			global $Sitio;
			if (is_numeric($__seccion__)) {
				$Seccion = $Sitio->Secciones->GetSeccion( $__seccion__ );
				if ($Seccion!=null) {
					$_nombre_ = $Seccion->Nombre();
				} else $_nombre_ = "el ID:".$__seccion__." no corresponde a una sección.";
			} else {
				$Seccion = $Sitio->Secciones->GetSeccionByName( $__seccion__ );
				if ($Seccion!=null) {
					$_nombre_ = $Seccion->Nombre();
				} else $_nombre_ = $__seccion__;
			}
			
			if ($__clase__=="") $__clase__ = "block-".strtolower($__modulo__);
			
			echo 		'
			<div id="'.$__clase__.'" class="'.$__clase__.'">
				<h2>'.$_nombre_.'</h2>
				<div class="contenidos">';
				
			//echo '<span class="titulares_footer"><a href="/'.strtolower( $__modulo__ ).'" class="titulares_footer">'.$Seccion->Nombre().'</a></span>';
			//echo '<span class="textos_footer">';	
	
				$t = $Sitio->Contenidos->m_tcontenidos;
        
				$t->LimpiarSQL();
        $t->FiltrarSQL('ID_SECCION','', $Seccion->m_id );          
        $t->OrdenSQL($__orden__);
        $t->LimiteSQL('0',$__limite__);
        $t->Open();
        
        if ($__template__=="") {
        	$__template__ = '<a title="*TITULO*" href="/'.$__modulo__.'/*IDCONTENIDO*">*TITULO*</a>';
        }
        
        $Sitio->Contenidos->MostrarResultadoColapsado('
        <div class="item item-'.$__modulo__.' [POSITION-CLASS]">
        	'.$__template__.'
        </div>');	
        
      echo '</div>';
      
    echo '</div>';
	
}


///GOOGLEMAP:
class CGmaps {
	var $m_ie;
	var $m_ll;
	var $m_spn;
	var $t;
	var $z;
	var $output;
	var $s;
	
	var $m_f;
	var $m_geocode;
	var $m_q;
	var $m_sspn;
	var $m_sll;
	var $m_hl;
	
	var $m_cid;
	var $m_cid_lat;
	var $m_cid_long;
	var $m_cid_id;
	
	
	function CGmaps() {
		
		$this->m_ie = "UTF8";
		$this->m_ll = "0.0,0.0";
		$this->m_spn = "0.0,0.0";
		$this->m_t = "";
		$this->m_z = "14";
		$this->m_output = "embed";
		$this->m_s = "AARTsJqzARj-Z8VnW5pkPMLMmZbqrJcYpw";
		
		$this->m_f = "";
		$this->m_geocode = "";
		$this->m_q = "";
		$this->m_sspn = "";
		$this->m_sll = "";
		$this->m_hl = "";
		
	}
	
	function ParseVar( &$var, $txtdata, $parseinit, $parsefinish ) {		
		$exmaps = explode( $parseinit, $txtdata );
		if (count($exmaps)>1) {
			$var = substr( $exmaps[1],0,strpos( $exmaps[1], $parsefinish));
		}	
	}
	
	function Parse( $gmaps ) {
		$this->ParseVar( $this->m_f, $gmaps, "f=", "&" );
		$this->ParseVar( $this->m_hl, $gmaps, "&hl=", "&" );
		$this->ParseVar( $this->m_geocode, $gmaps, "&geocode=", "&" );
		$this->ParseVar( $this->m_q, $gmaps, "&q=", "&" );
		$this->ParseVar( $this->m_sspn, $gmaps, "&sspn=", "&" );
		$this->ParseVar( $this->m_sll, $gmaps, "&sll=", "&" );
		
		$this->ParseVar( $this->m_cid, $gmaps, "&cid=", "&" );
		if ($this->m_cid!="") {
			$excodes = explode(",",$this->m_cid);
			if (count($excodes)>2) {
				$this->m_cid_lat = $excodes[0] / 1000000;
				$this->m_cid_long = $excodes[1] / 1000000;
				$this->m_cid_id = $excodes[2] / 1000000;
			}		
			
			//$this->m_sll = $this->m_cid_lat.",".$this->m_cid_long;
			//$this->m_sll = $this->m_cid;			
		}
		
		$this->ParseVar( $this->m_ll, $gmaps, "&ll=", "&" );
		$this->ParseVar( $this->m_t, $gmaps, "&t=", "&" );
		$this->ParseVar( $this->m_spn, $gmaps, "&spn=", "&" );
		$this->ParseVar( $this->m_z, $gmaps, "&z=", "&" );
		if ($this->m_z=="") $this->m_z=17;
		$this->ParseVar( $this->m_s, $gmaps, "&s=", "\"" );
	}
	
	function Valid() {
		
		$valid = true;
		$valid = (($this->m_sll!="0.0,0.0" && $this->m_sll!="") && ($this->m_sspn!="0.0,0.0" && $this->m_sspn!="")) || (($this->m_ll!="0.0,0.0" && $this->m_ll!="") && ($this->m_spn!="0.0,0.0" && $this->m_spn!=""));
		if ($this->m_f=="q") {
			$valid = $valid && ($this->m_q!="");
		} if ($this->m_f=="d") {
			
		}
		return $valid;
	}
	
	function Generate( $txtdata = "", $m_sll="", $m_t="", $m_z="", $m_q="") {
		if ($txtdata!="") $this->Parse( html_entity_decode($txtdata) );
		if ($m_sll!="") {
			$this->m_sll = $m_sll;
			$this->m_z = $m_z;
			$this->m_t = $m_t;
			$this->m_q = $m_q;
		}
/*
		if (!$this->Valid()) return '<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="#"></iframe>';
	
		$linkmaps = 'http://maps.google.com/?';
		
		$linkmaps.= 'ie='.$this->m_ie;
		$linkmaps.= '&hl='.$this->m_hl;
		
		if ($this->m_f!="") {
			$linkmaps.= '&f='.$this->m_f;
			$linkmaps.= '&geocode='.$this->m_geocode;
			$linkmaps.= '&q='.$this->m_q;
			$linkmaps.= '&sspn='.$this->m_sspn;
			$linkmaps.= '&sll='.$this->m_sll;
		}
		
		
		$linkmaps.= '&ll='.$this->m_ll;			
		$linkmaps.= '&spn='.$this->m_spn;
		$linkmaps.= '&t='.$this->m_t;
		$linkmaps.= '&z='.$this->m_z;
		$linkmaps.= '&output='.$this->m_output;
		$linkmaps.= '&s='.$this->m_s;
		$finalgmaps = '<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$linkmaps.'"></iframe>';
		return $finalgmaps;
		
		*/
		if ($m_sll=="") if (!$this->Valid()) return '<!--invalid: f:'.$this->m_f.' q:'.$this->m_q.' sll:'.$this->m_sll." ll:".$this->m_ll." sspn:".$this->m_sspn." spn:".$this->m_spn." z:".$this->m_z."-->";
		//if (!$this->Valid()) return '';
		
		if ($this->m_sll=="0.0,0.0" || $this->m_sll=="") $this->m_sll = $this->m_ll;  
		
		/*
		if ( $this->m_cid!="" ) {
			$finalgmaps = "<iframe></iframe>";
		} else {
		*/
		if ($this->m_q!="") $geocode = 'geocoder = new GClientGeocoder();
					geocoder.getLocations("'.utf8_decode(urldecode($this->m_q)).'", addAddressToMap);';
		
		if ($this->m_t!="") {
			if ($this->m_t=="h") $maptype  = "map.setMapType(G_HYBRID_MAP);";
			if ($this->m_t=="p") $maptype  = "map.setMapType(G_PHYSICAL_MAP);";
			if ($this->m_t=="s") $maptype  = "map.setMapType(G_SATELLITE_MAP);";
		}// else $maptype  = "map.setMapType(G_HYBRID_MAP);";
		
		if ($m_sll=="") {
			
			return '<iframe width="425" height="350" frameborder="0" src="/inc/core/getmap.php?m_sll='.$this->m_sll.'&m_t='.$this->m_t.'&m_q='.$this->m_q.'&m_z='.$this->m_z.'"></iframe>';
		
		} else {
			
		$finalgmaps = '<script>
			var map = null;
			var geocoder = null;
			var maptypecontrol = null;
			var zoomcontrol = null;
		
			function initialize() { 
				if (GBrowserIsCompatible()) { 
					map = new GMap2(document.getElementById("divmaplocation"));
					map.setCenter(new GLatLng('.$this->m_sll.'), '.$this->m_z.');
					
					maptypecontrol = new GMapTypeControl();
					map.addControl(maptypecontrol);
					'.$maptype.'
					zoomcontrol = new GLargeMapControl();
					map.addControl(zoomcontrol);

					'.$geocode.'
				}
			}
			
			function showAddress(address) {
		      if (geocoder) {
		        geocoder.getLatLng(
		          address,
		          function(point) {
		            if (!point) {
		              alert(address + " not found");
		            } else {
		              map.setCenter(point, '.$this->m_z.');
		              var marker = new GMarker(point);
		              map.addOverlay(marker);
		              marker.openInfoWindowHtml(address);
		            }
		          }
		        );
		      }
		    }
		    
		    function addAddressToMap(response) {
		      map.clearOverlays();
		      if (!response || response.Status.code != 200) {
		        /*alert("Sorry, we were unable to geocode that address:"+response.Status.code);*/
		      } else {
		        place = response.Placemark[0];
		        point = new GLatLng(place.Point.coordinates[1],
		                            place.Point.coordinates[0]);
		        marker = new GMarker(point);
		        map.addOverlay(marker);
		        marker.openInfoWindowHtml(place.address + \'<br>\' +
		          \'<b>Country code:</b> \' + place.AddressDetails.Country.CountryNameCode);
		      }
		    }
		    		    
		    
			function showLocation( address ) {
				geocoder.getLocations( address, addAddressToMap);
			}
			
		</script>';
		
		return str_replace( array("\n","\r"), array("",""), $finalgmaps);
		}
	}
	
}

if (!function_exists("money_format")) {
	function money_format($format, $number)
{
    $regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'.
              '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
    if (setlocale(LC_MONETARY, 0) == 'C') {
        setlocale(LC_MONETARY, '');
    }
    $locale = localeconv();
    preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
    foreach ($matches as $fmatch) {
        $value = floatval($number);
        $flags = array(
            'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ?
                           $match[1] : ' ',
            'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,
            'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?
                           $match[0] : '+',
            'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,
            'isleft'    => preg_match('/\-/', $fmatch[1]) > 0
        );
        $width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
        $left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
        $right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
        $conversion = $fmatch[5];

        $positive = true;
        if ($value < 0) {
            $positive = false;
            $value  *= -1;
        }
        $letter = $positive ? 'p' : 'n';

        $prefix = $suffix = $cprefix = $csuffix = $signal = '';

        $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
        switch (true) {
            case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':
                $prefix = $signal;
                break;
            case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':
                $suffix = $signal;
                break;
            case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':
                $cprefix = $signal;
                break;
            case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':
                $csuffix = $signal;
                break;
            case $flags['usesignal'] == '(':
            case $locale["{$letter}_sign_posn"] == 0:
                $prefix = '(';
                $suffix = ')';
                break;
        }
        if (!$flags['nosimbol']) {
            $currency = $cprefix .
                        ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) .
                        $csuffix;
        } else {
            $currency = '';
        }
        $space  = $locale["{$letter}_sep_by_space"] ? ' ' : '';

        $value = number_format($value, $right, $locale['mon_decimal_point'],
                 $flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
        $value = @explode($locale['mon_decimal_point'], $value);

        $n = strlen($prefix) + strlen($currency) + strlen($value[0]);
        if ($left > 0 && $left > $n) {
            $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
        }
        $value = implode($locale['mon_decimal_point'], $value);
        if ($locale["{$letter}_cs_precedes"]) {
            $value = $prefix . $currency . $space . $value . $suffix;
        } else {
            $value = $prefix . $value . $space . $currency . $suffix;
        }
        if ($width > 0) {
            $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?
                     STR_PAD_RIGHT : STR_PAD_LEFT);
        }

        $format = str_replace($fmatch[0], $value, $format);
    }
    return $format;
} 
}

function ReformatLinks(&$__template__) {
	global $_DIR_SITEABS;
	$__template__ = str_replace(array('"../..','"/inc','"/archivos'),array('"'.$_DIR_SITEABS,'"'.$_DIR_SITEABS.'/inc','"'.$_DIR_SITEABS.'/archivos'),$__template__);
}


function ProcessProduct() {
		
		global $_products_;
		global $orderproducts;	
		global $nproducts;
		global $_accion_;
		global $_cant_;
		global $_cID_;
		global $_idstr_;
		
		$orderproducts = array();
		$nproducts = 0;
		
		
		if (isset($_products_)) {			
		
			if ($_products_!="") {
				$xproducts = explode("|",$_products_);		
				foreach($xproducts as $product) {
					$pr = explode(":",$product);
					if(is_numeric($pr[0])) {
						$id = $pr[0];
					} else {
						$expr = explode("__",$pr[0]);
						$id = $expr[0];
						$opciones = $expr[1];
					}					
					$np = $pr[1];
					if ($_accion_=="removeproduct" && $_idstr_==$id."#".$opciones) {
						//remove, do nothing
					} else if(isset($orderproducts[$id."#".$opciones])) {//si esta repetido , lo agrega..
						if(isset($np)) {
							$orderproducts[$id."#".$opciones]['cantidad']+= $np;							
						}
					} else {
						$orderproducts[$id."#".$opciones] = array('id'=>$id,'cantidad'=>$np,'opciones'=>$opciones);
					}
				}
				//reordenamos los id|cant reagrupandolos por id's,
				//generando el nuevo string de _products_
				$_products_ = "";
				$sep="";
				$nproducts = 0;		
				foreach($orderproducts as $idstr=>$arr) {
					$id = $arr['id'];
					$opciones = $arr['opciones'];
					
					if ($_accion_=="changeproduct" && $_idstr_==$idstr) {	
						//modificar
						$orderproducts[$idstr]['cantidad'] = $_cant_;
						$_products_.= $sep.$arr['id']."__".$arr['opciones'].":".$_cant_;
						$sep = "|";
						$nproducts+=$_cant_;
					} else {
						$_products_.= $sep.$arr['id']."__".$arr['opciones'].":".$arr['cantidad'];
						$sep = "|";
						$nproducts+=$arr['cantidad'];
					}			
				}
				$_SESSION['products'] = $_products_;				
			}
		} else {			
			$_products_ = "";
			$_SESSION['products'] = "";
		}		
	
	}

function rel2abs($rel, $base)
{
    /* return if already absolute URL */
    if (parse_url($rel, PHP_URL_SCHEME) != '') return $rel;
    /* queries and anchors */
    if ($rel[0]=='#' || $rel[0]=='?') return $base.$rel;
    /* parse base URL and convert to local variables:
       $scheme, $host, $path */
    extract(parse_url($base));
    /* remove non-directory element from path */
    $path = preg_replace('#/[^/]*$#', '', $path);
    /* destroy path if relative url points to root */
    if ($rel[0] == '/') $path = '';
    /* dirty absolute URL */
    $abs = "$host$path/$rel";
    /* replace '//' or '/./' or '/foo/../' with '/' */
    $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
    for($n=1; $n>0; $abs=preg_replace($re, '/', $abs, -1, $n)) {}
   
    /* absolute URL is ready! */
    return $scheme.'://'.$abs;
}
function wiwe_dir_siteabs( $filename ) {
	
	global $_DIR_SITEABS;
	
	if (substr($filename,0,strlen($_DIR_SITEABS))==$_DIR_SITEABS) {
		return $filename;
	}
	
	$_slash = "";
	if (substr($filename,0,6)=="../../") {
		$_slash = "/";
		$filename = str_replace("../../","",$filename);
	} else if (substr($filename,0,1)!="/") {
		$_slash = "/";
	}
	
	if (substr($filename,0,5)=="/wiwe") {
		return str_replace("/wiwe",$_DIR_SITEABS,$filename);
	}
	
	return $_DIR_SITEABS.$_slash.$filename;
}

function replace_flat($str) {
	
	return str_replace( array("&","ç",'"',"'","ä","ë","ï","ö","ü","á","é","í","ó","ú","à","è","ì","ò","ù","â","ê","î","ô","û"), 
										array(" ","c"," "," ","a","e","i","o","u","a","e","i","o","u","a","e","i","o","u","a","e","i","o","u"), strtolower($str));
	
}

function alphawithaccents( $text ) {
	
	return ereg_replace("[^A-Za-zäëïöüáéíóúàèìòùâêîôûÄËÏÖÜÁÉÍÓÚÀÈÌÒÙÂÊÎÔÛñÑ[:space:]]", "", html_entity_decode($text));
	
}

function clean($text) {
	
	return preg_replace("/^[^a-z0-9]?(.*?)[^a-z0-9]?$/i", "$1", $text);
}

function uniquecode( $__str__ ) {
		$ext = substr ($__str__, -4,4);
		if (strtolower($ext)=="jpeg") {
			$ext = substr ($__str__, -5,5);
		}
		if ((strtolower($ext)==".jpeg") || (strtolower($ext)==".jpg")) {
			return "_".md5($__str__.date("d-m-Y H:i:s")).".jpg";	    
		} else if (strtolower($ext)==".gif") {
		    return "_".md5($__str__.date("d-m-Y H:i:s")).".gif";			
		} else {
			return "_".md5($__str__.date("d-m-Y H:i:s")).$ext;
		}
		
	
}

function urlsafe_b64encode($string)
{
  $data = base64_encode($string);
  $data = str_replace(array('+','/','='),array('-','_','.'),$data);
  return $data;
}
function urlsafe_b64decode($string)
{
  $data = str_replace(array('-','_','.'),array('+','/','='),$string);
  $mod4 = strlen($data) % 4;
  if ($mod4) {
    $data .= substr('====', $mod4);
  }
  return base64_decode($data);
}
  
function wwrap( $text, $start, $length, $tridots="...") {
	$words = explode(" ",$text);
	$count = 0;
	$final = "";
	foreach($words as $word) {
		$count+= strlen(" ".$word);
		if ($count<=$length) $final.=" ".$word;
		else break;		
	}
	if ($length<strlen($text)) return $final.$tridots;
	else return $text;
} 
 
function editor_process($source,$classlink="",$target="_blank") {
	
	//BOLD ITALIC
	$source = str_replace("\n","<br>",$source);
	$source = str_replace(array("!#","#!"),array("<strong>","</strong>"),$source);
	$source = str_replace(array("|#","#|"),array("<i>","</i>"),$source);
	$source = str_replace(array("_#","#_"),array("<u>","</u>"),$source);
	
	//LINKS
	
	//MAILS
	$conti = true;	
	while($conti==true) {
		$s1 = strpos( $source,'!@');
		$s2 = strpos( $source,'@!');
		if ( ($s1===false) || ($s2===false) ) { 
			$conti=false;
		} else {
			$mm = substr( $source, $s1, ( ($s2 - $s1) + 2) );
			$mm2 = substr( $source, $s1+2, ( ($s2 - $s1) - 2) );
			$ilnk = explode(" ",$mm2);
			$source = str_replace( $mm, '<a href="mailto:'.$ilnk[0].'" class="'.$classlink.'">'.$ilnk[1].'</a>', $source );
		}
	}		
	
	//LINKS
	$conti = true;	
	while($conti==true) {
		$s1 = strpos( $source,'!@');
		$s2 = strpos( $source,'@!');
		if ( ($s1===false) || ($s2===false) ) { 
			$conti=false;
		} else {
			$mm = substr( $source, $s1, ( ($s2 - $s1) + 2) );
			$mm2 = substr( $source, $s1+2, ( ($s2 - $s1) - 2) );
			$ilnk = explode(" ",$mm2);
			$source = str_replace( $mm, '<a href="'.$ilnk[0].'" class="'.$classlink.'" target="'.$target.'">'.$ilnk[1].'</a>', $source );
		}
	}
		
	return $source;
}
 
function replace_accents($str) {
  $str = htmlentities($str);
  $str = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde);/','$1',$str);
  return html_entity_decode($str);
}


function replace_specials($str) {
   global $export;
   $search  = array ('ç', 'á', 'é', 'í', 'ó', 'ú', 'ã', 'õ', 'à', 'è', 'ì', 'ò', 'ù','ä', 'ë', 'ï', 'ö', 'ü','Ã','Õ');
   $replace = array ('c', 'a', 'e', 'i', 'o', 'u', 'a', 'o', 'a', 'e', 'i', 'o', 'u','a', 'e', 'i', 'o', 'u','A','O');
   $export    = str_replace($search, $replace, $str);
   return $export; 
}
 
function getCode() {

	$r = rand(7380,66429);
	
	$x1 = $r / (81*81);
	$r1 = $r % (81*81);
	$x2 = $r1 / (81*9);
	$r2 = $r1 % (81*9);
	$x3 = $r2 / (81);
	$r3 = $r2 % (81);
	$x4 = $r3 / 9;
	$x5 = $r3 % 9;
	
	return floor($x1*10000 + $x2*1000 + $x3*100 + $x4*10 + $x5);
		
}

function getCode6() {

	$digs = 6;
	$rD = array();
	$xD = array();
	$cd = 0;
	
	$rD[0] = mt_rand(0,pow(9,6));	
	//echo "r".$rD[0]."<br>";
	for($i=$digs;$i>=1;$i--) {
		$xD[$digs-$i+1] = floor($rD[$digs-$i] / pow(9,($i-1)));
		$rD[$digs-$i+1] = $rD[$digs-$i] % pow(9,($i-1));
		//echo "x".($digs-$i).":".$xD[$digs-$i+1];
		//echo "r".($digs-$i).":".$rD[$digs-$i+1]."<br>";
		$cd+= ($xD[$digs-$i+1] + 1 ) * pow(10,($i-1));
	}
	//$xD[$digs] = $rD[$digs-1];//innecesario
	return $cd;	
		
}

function tmp_to_local( $src, $dest ) {
	
	global $_FTPSERVER_;
	global $_FTPUSUARIO_;
	global $_FTPCONTRASENA_;
	
	global $_DIR_FTPREL;
	
	if (!rename( $src, $dest )) {
		echo "failed: src:".$src." dst:".$dest;
		$conn_id = ftp_connect($_FTPSERVER_, "21"); 
	    $login = $_FTPUSUARIO_;
	    $password = $_FTPCONTRASENA_;
	    $login_result = ftp_login($conn_id, $login, $password);
	    if ($login_result) {				    
	    ftp_pasv($conn_id, true); 
	    $upload=ftp_put($conn_id, $_DIR_FTPREL.$dest, $src, FTP_BINARY);
	    ftp_close($conn_id);
	      if (!$upload) 
	      { 
	       echo "FTP upload failed";
	       return false; 
	      } 
	      else 
	      {
	      	echo "OK";
	      	return true;
	      }
	    } else {
	    	echo "FTP login failed";
	    	return false;
	    }
	} else return true;
	
	return false;
}

function mkdir_ftp($dir) {
	if ( function_exists(ftp_connect) && $GLOBALS['_FTPENABLED_']) {
		
    $ftp_server = $GLOBALS["_FTPSERVER_"];
	$ftp_user_name = $GLOBALS["_FTPUSUARIO_"];
	$ftp_user_pass = $GLOBALS["_FTPCONTRASENA_"];
	$ftp_dir = $GLOBALS["_DIR_FTPREL"];	
	$conn_id = ftp_connect($ftp_server); 
	if (!$conn_id) {
        echo "FTP connection has failed!";
        //echo "Attempted to connect to $ftp_server for user $ftp_user_name"; 
        return false;	    
	}

	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 

	if (!$login_result) { 
        echo "FTP login has failed!";
        //echo "Attempted to connect to $ftp_server for user $ftp_user_name"; 
		ftp_close($conn_id);
        return false;
	}
	
	if(ftp_mkdir($conn_id,$ftp_dir.$dir)) {
		ftp_site($conn_id,"CHMOD +775 ".$ftp_dir.$dir);
		ftp_close($conn_id);
		return true;
	} else {
		ftp_close($conn_id);
		return false;
	}	
	} else {
		return mkdir($GLOBALS["_SITEROOT_"].$dir, 0755);
	}
}

function chmod_ftp($file) {
	
	if ( function_exists(ftp_connect)  && $GLOBALS['_FTPENABLED_']) {
		
    $ftp_server = $GLOBALS["_FTPSERVER_"];
	$ftp_user_name = $GLOBALS["_FTPUSUARIO_"];
	$ftp_user_pass = $GLOBALS["_FTPCONTRASENA_"];
	$ftp_dir = $GLOBALS["_DIR_FTPREL"];
	$conn_id = ftp_connect($ftp_server); 
	if (!$conn_id) {
        echo "FTP connection has failed!";
        //echo "Attempted to connect to $ftp_server for user $ftp_user_name"; 
        return false;	    
	}

	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 

	if (!$login_result) { 
        echo "FTP login has failed!";
		ftp_close($conn_id);
        //echo "Attempted to connect to $ftp_server for user $ftp_user_name"; 
        return false;
	}

	if (ftp_site($conn_id,"CHMOD +775 ".$ftp_dir.$file)) {
		ftp_close($conn_id);
		return true;
	} else {
		echo "FTP CHMOD ERROR: ".$ftp_dir.$file;
		ftp_close($conn_id);
		return false;
	}
	
	} else {
		return chmod($GLOBALS["_SITEROOT_"].$file, 0775);
		return true;
	}	
	
}

function rename_ftp($filed,$files) {
	global $_debug_;
	
	if ( function_exists(ftp_connect)  && $GLOBALS['_FTPENABLED_']  ) {
		
    $ftp_server = $GLOBALS["_FTPSERVER_"];
		$ftp_user_name = $GLOBALS["_FTPUSUARIO_"];
		$ftp_user_pass = $GLOBALS["_FTPCONTRASENA_"];
		$ftp_dir = $GLOBALS["_DIR_FTPREL"];
		$conn_id = ftp_connect($ftp_server); 
		if (!$conn_id) {
	        ShowError("FTP connection has failed!");
	        //echo "Attempted to connect to $ftp_server for user $ftp_user_name"; 
	        return false;	    
		}
	
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 
	
		if (!$login_result) { 
	        echo "FTP login has failed!";
	        //echo "Attempted to connect to $ftp_server for user $ftp_user_name"; 
			ftp_close($conn_id);
	        return false;
		}
		//ftp_put($conn_id, $filed, $files, FTP_BINARY);
		Debug("[ftp renaming:".$ftp_dir.$files." to ".$ftp_dir.$filed."]");
   	if (ftp_rename ($conn_id, $ftp_dir.$files, $ftp_dir.$filed)) {
			ftp_close($conn_id);
			return true;
   	} else {
			ShowError("rename_ftp (ftp version) > Couldn't rename file from :".$ftp_dir.$files." to: ".$ftp_dir.$filed);
			ftp_close($conn_id);
			return false;
		}
	
	} else {
		Debug("[ftp disabled! trying without it]");
		$fhd = fopen(  $GLOBALS["_SITEROOT_"].$filed, "w" );
		if (!$fhd) ShowError("rename_ftp (copy & unlink version) > Couldn't write in destination folder : ".$GLOBALS["_SITEROOT_"].$filed);
		if (copy($GLOBALS["_SITEROOT_"].$files, $GLOBALS["_SITEROOT_"].$filed)) {
			unlink($GLOBALS["_SITEROOT_"].$files);
			return true;
		} else {
			ShowError("rename_ftp (copy & unlink version) > Couldn't rename file from :".$GLOBALS["_SITEROOT_"].$files." to: ".$GLOBALS["_SITEROOT_"].$filed);
			return false;
		}
	}	
}

function delete_ftp($filed) {
	if ( function_exists(ftp_connect)  && $GLOBALS['_FTPENABLED_']) {
    $ftp_server = $GLOBALS["_FTPSERVER_"];
	$ftp_user_name = $GLOBALS["_FTPUSUARIO_"];
	$ftp_user_pass = $GLOBALS["_FTPCONTRASENA_"];
	$ftp_dir = $GLOBALS["_DIR_FTPREL"];
	$conn_id = ftp_connect($ftp_server); 
	if (!$conn_id) {
        echo "FTP connection has failed!";
        //echo "Attempted to connect to $ftp_server for user $ftp_user_name"; 
        return false;	    
	}

	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 

	if (!$login_result) { 
        echo "FTP login has failed!";
        //echo "Attempted to connect to $ftp_server for user $ftp_user_name"; 
		ftp_close($conn_id);
        return false;
	}
	//ftp_put($conn_id, $filed, $files, FTP_BINARY);
   	if (ftp_delete ($conn_id, $ftp_dir.$filed)) {
	   ftp_close($conn_id);
	   return true;
   	} else {
	   ftp_close($conn_id);
	   return false;
	}
	} else {
		return unlink($GLOBALS["_SITEROOT_"].$filed); 
	}	
}

 
/*

* SOBRE DIRECTORIOS

*/

 function DirSeccion($carpeta,$idseccion) {
	return $GLOBALS['_DIR_SECCIONES'].'/'.$carpeta.'_'.$idseccion;
 }


/*

* SOBRE FECHAS

*/


function Fecha($__mysqldate__,$__tipo__='') {

	//setlocale( LC_ALL , "Spanish");
	//es_AR ISO-8859-1
	$def = "";
		
	if (setlocale( LC_TIME, "Spanish")==FALSE) {		
		if (setlocale( LC_TIME, "es_AR.UTF8")==FALSE) {
			$def = "[no Spanish? or es_AR.UTF8] ";
		} 
	}
	
	//return $def.strftime( "%#d de %B del %Y", strtotime($__mysqldate__) );
	return $def.strftime( "%d/%m/%Y", strtotime($__mysqldate__) );
}

function Hora($__mysqldate__,$__tipo__='') {
	
	//$hh = substr($__mysqldate__,11,2);
	//$mm = substr($__mysqldate__,14,2);
	
	//return($hh.":".$mm." hs"); 
	if (setlocale( LC_TIME, "Spanish")==FALSE) {		
		if (setlocale( LC_TIME, "es_AR.UTF8")==FALSE) {
			$def = "[no Spanish? or es_AR.UTF8] ";
		} 
	}
	
	return $def.strftime( "%H:%M hs", strtotime($__mysqldate__) );
}

function DiaDeSemana($ts) {
	$w = date('w',$ts);
	switch($w) {
		case 0:
			return "Domingo";			
		case 1:
			return "Lunes";			
		case 2:
			return "Martes";			
		case 3:
			return "Miércoles";			
		case 4:
			return "Jueves";			
		case 5:
			return "Viernes";			
		case 6:
			return "Sábado";			
		default:
			break;
	}
}

/*

SOBRE THUMBNAILS 
*/
 
 	function thumbnail($droot,$name,$newwidth,$newdir,$thumbname,$newheight=0,$orientation=0) {
 		global $CLang;
 		$_tmp_ = $GLOBALS['_DIR_TMP'];
 		
 		if (!file_exists($droot.$name)) {
 			ShowError( "thumbnail() > ".$droot.$name." doesn't exists.'" );
 			return false;
 		}
 		
		$ext = substr ($name, -4,4);
		if (strtolower($ext)=="jpeg") {
			$ext = substr ($name, -5,5);
		}
		if ((strtolower($ext)==".jpeg") || (strtolower($ext)==".jpg")) {
		    $img = imagecreatefromjpeg($droot.$name); 
		} else if (strtolower($ext)==".gif") {
			$img = imagecreatefromgif($droot.$name);
		} else if (strtolower($ext)==".png") {
		    $img = imagecreatefrompng($droot.$name);			
		} else {
			//ShowError("thumbnail() > Format not recognized as valid image: ".$droot.$name);
			$errormsg = "{NOTRECOGNIZEDIMAGEFORMAT}";
			$CLang->Translate( $errormsg );
			ShowError( $errormsg );
			return false;
		}
		if ($img) {
			$originalwidth = imagesx($img); 
	    	$originalheight = imagesy($img);
	    	
	    	if ( $newwidth==0 && $newheight!=0) {
		    	$scale = $newheight / $originalheight;
		    	$newwidth = ceil($originalwidth*$scale);
		    	$croppedwidth = $originalwidth;
		    	$croppedheight = $originalheight;
		    	$croppedx = 0;
		    	$croppedy = 0;		    		
	    	} else 	
	    	if ($newheight!=0 && $orientation==0) {
		    	if ( ($originalwidth/$originalheight) > ($newwidth/$newheight) ) {	    		
		    		$croppedwidth = ceil($originalheight*($newwidth/$newheight));
		    		$croppedheight = $originalheight;
		    		$croppedx = ceil(($originalwidth - $croppedwidth) / 2);
		    		$croppedy = 0;
		    	} else {
		    		$croppedwidth = $originalwidth;
		    		$croppedheight = ceil($originalwidth*($newheight/$newwidth));
		    		$croppedx = 0;
		    		$croppedy = ceil(($originalheight - $croppedheight) / 2);
		    	}
			}  else if ($newheight==0 && $orientation==0) {				
		    	$scale = $newwidth / $originalwidth;
		    	$newheight = ceil($originalheight*$scale);
		    	$croppedwidth = $originalwidth;
		    	$croppedheight = $originalheight;
		    	$croppedx = 0;
		    	$croppedy = 0;		    	
		    } else if ($newheight==0 && $orientation==1) {
		    	if ($originalwidth>$originalheight) {
		    		$scale = $newwidth / $originalwidth;
			    	$newheight = ceil($originalheight*$scale);
			    	$croppedwidth = $originalwidth;
			    	$croppedheight = $originalheight;
			    	$croppedx = 0;
			    	$croppedy = 0;
		    	} else {
		    		$newheight = $newwidth;
		    		$scale = $newwidth / $originalheight;
			    	$newwidth = ceil($originalwidth*$scale);			    	
			    	$croppedwidth = $originalwidth;
			    	$croppedheight = $originalheight;
			    	$croppedx = 0;
			    	$croppedy = 0;
		    	}
		    }
		    
    		if ($croppedwidth*$croppedheight < $newwidth*$newheight) {
    			$newwidth = $croppedwidth;
    			$newheight = $croppedheight;
    		}
		    
		    $newimg = imagecreatetruecolor( $newwidth, $newheight); 
		    //resource dst_image, resource src_image, int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h )
		    imagecopyresampled( $newimg, $img, 0,0, $croppedx, $croppedy, $newwidth, $newheight, $croppedwidth, $croppedheight ); 
		    
			if ((strtolower($ext)==".jpeg") || (strtolower($ext)==".jpg")) {
				if (!is_dir($droot.$newdir)) {
					mkdir_ftp($newdir);
					chmod_ftp($newdir);
				}
				imagejpeg($newimg, $droot.$_tmp_."/".$thumbname, 75);
				if (!file_exists($droot.$_tmp_."/".$thumbname)) {
					ShowError("thumbnail() > Couldn't save result image to : ".$droot.$_tmp_."/".$thumbname);
				}
				rename_ftp($newdir."/".$thumbname , $_tmp_."/".$thumbname);
			} else if (strtolower($ext)==".gif") {
				if (!is_dir($droot.$newdir)) {
					mkdir_ftp($newdir);
					chmod_ftp($newdir);
				}		
				imagegif($newimg,$droot.$_tmp_."/".$thumbname);
				if (!file_exists($droot.$_tmp_."/".$thumbname)) {
					ShowError("thumbnail() > Couldn't save result image to : ".$droot.$_tmp_."/".$thumbname);
				}				
				rename_ftp($newdir."/".$thumbname , $_tmp_."/".$thumbname);			
			} else if (strtolower($ext)==".png") {
				if (!is_dir($droot.$newdir)) {
					mkdir_ftp($newdir);
					chmod_ftp($newdir);
				}		
				imagepng($newimg,$droot.$_tmp_."/".$thumbname);
				if (!file_exists($droot.$_tmp_."/".$thumbname)) {
					ShowError("thumbnail() > Couldn't save result image to : ".$droot.$_tmp_."/".$thumbname);
				}						
				rename_ftp($newdir."/".$thumbname , $_tmp_."/".$thumbname);			
			}
		    return true; 
		} else return false;
	}
	
	
	/**
	 *  XML FIELD:
	 * 
	 * <field_name type=text values=field_value/>
	 * 
	 * */
	
	class CXMLFieldDefinition {
		
		var $m_type;
		var $m_name;
		var $m_values;
		
		
		function CXMLFieldDefinition( $__name__, $__type__, $__values__="" ) {
			$this->m_type = $__type__;
			$this->m_name = $__name__;
			if ($__values__!="") {
				switch($this->m_type) {
					case "select":
						$this->m_values = explode( "|", $__values__ );
						break;
					case "checkbox":
						$this->m_values = explode( "|", $__values__ );
						break;
					case "checkboxsimple":
						$this->m_values = $__values__;
						break;
					case "text":
						$this->m_values = $__values__;
						break;
					case "number":
						$this->m_values = $__values__;
						break;
				}				
			}							
		}
		
		function Edit( $__label__, $__uservalues__="", $__template__="", $__script__="", $__cols__=3 ) {
			if ($__script__!="") {
				$__script__ = ' onChange="javascript:'.$__script__.'(\''.$__label__.$this->m_name.'\')" ';
			}
			if ($__template__=="") $resstr =  '<td>';
			if ($this->m_name!="") {
				switch( $this->m_type )	{
					case "select":
						$resstr.=  '<select name="'.$__label__.$this->m_name.'" '.$__script__.'>';
						foreach($this->m_values as $Value) {
							($__uservalues__!='' && $__uservalues__==$Value) ? $selected = "selected" : $selected="";							
							$resstr.=  '<option value="'.$Value.'" '.$selected.'>'.$Value.'</option>';
						}
						$resstr.=  '</select>';
						break;
												
					case "checkbox":
						$cn = 0;
						if ($__cols__!="") 
							$resstr.= '<table cellpadding="0" cellspacing="0">';						
						foreach($this->m_values as $Value) {
							$selected = "";
							if ($__uservalues__!='') {
								$SplitVals = explode( "\|", $__uservalues__ );
								$pos = false;
								if (is_array($SplitVals)) {
									foreach($SplitVals as $Val) {
										if ($Val==$Value) { $pos=1; break; }
									}
								}
								if (is_numeric($pos)) $selected = "checked"; else $selected="";
							}
							
							if ($__cols__!="") {
								$resstr.= '<td valign="top" align="left">';
								$cn++;
							}
							
							$resstr.= '<input '.$__script__.' name="'.$__label__.$this->m_name.'_'.WordsToVariable($Value).'" type="checkbox" '.$selected.'>&nbsp;'.trim($Value);

							if ($__cols__!="") {
								$resstr.= '</td>';
								if ( ( $cn % $__cols__ ) == 0 ) {
									$resstr.= '</tr><tr>';	
								}
							}
							
						}
						if ($__cols__!="") 
							$resstr.= '</table>';
						break;
						
					case "checkboxsimple":
						($__uservalues__!='' && $__uservalues__=="on") ? $selected = "selected" : $selected="";
						$resstr.=  '<input '.$__script__.' name="'.$__label__.$this->m_name.'" type="checkbox" '.$selected.'/>';					
						break;	
					case "text":
						($__uservalues__!='') ? $selected = $__uservalues__ : $selected = $this->m_values;
						$resstr.=  '<input '.$__script__.' name="'.$__label__.$this->m_name.'" type="text" value="'.$selected.'"  size="25" />';
						break;
					case "number":
						($__uservalues__!='') ? $selected = $__uservalues__ : $selected="";
						$resstr.=  '<input '.$__script__.' name="'.$__label__.$this->m_name.'" type="text" value="'.$selected.'" size="8"/>';					
						break;
				}
			}
			if ($__template__=="") $resstr.=  '</td>';
			return $resstr;			
		}
		
		function Confirm( $__label__ ) {
			$resstr = "";
			$FieldValues = "";
			if ($this->m_name!="") {
				
				$FieldInputName = $__label__.$this->m_name;
				
					switch( $this->m_type )	{
						case "select":
							foreach($this->m_values as $Value) {
								if ( $GLOBALS[$FieldInputName] == $Value) {
									$FieldValues = $Value;
									break;
								}
							}						
							break;
							
						case "checkbox":
							$or = "";										
							foreach($this->m_values as $Value) {
								$FieldInputNameVar = $FieldInputName."_".WordsToVariable($Value);	
								if ( $GLOBALS[$FieldInputNameVar] == "on") {
									$FieldValues.= $or.$Value;
									$or="|";									
								}														
							}						
							break;
							
						case "checkboxsimple":						
							( $GLOBALS[$FieldInputName] == "on" ) ?  $FieldValues = "on" : $FieldValues = "off";						
							break;	
						case "text":
							( $GLOBALS[$FieldInputName] == "" ) ?  $FieldValues = "" : $FieldValues = $GLOBALS[$FieldInputName];
							//if ($FieldValues=="") return $FieldValues;
							break;
						case "number":
							( $GLOBALS[$FieldInputName] == "" ) ?  $FieldValues = "" : $FieldValues = $GLOBALS[$FieldInputName];
							//if ($FieldValues=="" || $FieldValues=="0") return "";										
							break;
					}
					/*volvemos a chequear si el valor no esta vacio: 
					 * "Es condición que todos los valores editados de un registro (XMLRecord) no deberia tener campos vacios"
					 */
					if ($FieldValues!="")
						$resstr.= "<".$this->m_name." type=".$this->m_type." values=".$FieldValues."/>";
				
				
				
			}
			return $resstr;			
		}
		
		function Draw( $__uservalues__, $__template__="" ) {
			$resstr = "";
			if ($__template__=="") $resstr.=  '<td>';
			if ($this->m_name!="") {
				switch( $this->m_type )	{
					case "checkbox":
											
						foreach($this->m_values as $Value) {
							if ($__uservalues__!='') {
								$SplitVals = explode( "\|", $__uservalues__ );
								$pos = false;
								foreach($SplitVals as $Val) {
									if ($Val==$Value) { $pos=1; break; }
								}								
								if (is_numeric($pos)) $selected = "checked"; else $selected="";
							}
							$resstr.=  $Value.':<input name="'.$this->m_name.'_'.WordsToVariable($Value).'" type="checkbox" '.$selected.'>';							
						}				
						break;
						
					case "checkboxsimple":						
						($__uservalues__!='' && $__uservalues__=="on") ? $selected = "selected" : $selected="";
						$resstr.=  '<input name="'.$__label__.$this->m_name.'" type="checkbox" '.$selected.'>';												
						break;	
					case "text":
					case "number":
					case "select":
						$resstr.=  $__uservalues__;
						break;
				}
			}
			if ($__template__=="") $resstr.=  '</td>';
			return $resstr;			
		}
		
	};
	
	
	/**
	 * Define una entrada de varios campos
	 * campos: m_CFields
	 * 
	 * un record o registro:
	 * 
	 * <campo1 type=select values=opcion0|opcion1/>
	 * <campo2 type=text values=Un texto predeterminado/>
	 * <campo3 type=number values=1/>
	 * 
	 * */
	class CXMLRecordDefinition {
				
		var $m_CFields;
		var $m_newrecords;
		var $m_maxrecords;
		
		function CXMLRecordDefinition( &$__X2AData__ ) {
			$this->m_CFields = array();
			if (is_array($__X2AData__)) {
				foreach( $__X2AData__ as $fieldname=>$data ) {
					if ($fieldname=="newrecords") {
						$this->m_newrecords = $data['values'];
					} else if ($fieldname=="maxrecords") {
						$this->m_maxrecords = $data['values'];
					} else if (is_array($data)) {
						$this->m_CFields[$fieldname] = new CXMLFieldDefinition( $fieldname, $data['type'], $data['values'] );
					}	else {
						$this->m_CFields[$fieldname] = "undefined";
					}			
				}
			} else {
				ShowError("CXMLRecordDefinition::Constructor > invalid __X2AData__ : ".$__X2AData__);
			}
			if (count($this->m_CFields)==0) {
				ShowError("CXMLRecordDefinition::Constructor > no fields defined: ".$__X2AData__);			
			}
			
			Debug("CXMLRecordDefinition::Constructor : Fields <br>__X2AData__ = <pre>".print_r($__X2AData__,true)."</pre>
			<br> m_CFields = <pre>".print_r($this->m_CFields,true)."</pre>");			
		}
		
		/**
		 * Editar los datos XML de un campo
		 * 
		 * @param $__label__
		 * @param $__recorddata__
		 * @param $__template__
		 * @param $__script__ 
		 * */
		function Edit( $__label__, $__recorddata__="", $__template__="", $__script__="" ) {
			$__fieldvalues__ = "";
			
			Debug("CXMLRecordDefinition::Edit label: $__label__ template: <textarea>".$__template__."</textarea>");
						
			if ($__template__=="") 
				$editstr = "<tr id=\"##LABEL##\" >";								
			if (count($this->m_CFields)>0) {
				foreach( $this->m_CFields as $fieldname=>$CField ) {
					
					//Debug(" fieldname:$fieldname <pre>".print_r( $CField,true)."</pre>");
					
					if (is_array($__recorddata__)) {
						if (isset($__recorddata__[$fieldname])) {
							$__fieldvalues__ = $__recorddata__[$fieldname]['values'];
						} else {
							//ShowError("CXMLRecordDefinition::Edit no recorddata for '".$fieldname."'");
							//echo "<pre> label: ".$__label__."\n __recorddata__ = ";
							//print_r($__recorddata__);
							//echo "</pre>";
							$__fieldvalues__ = "";
						}
					} else $__fieldvalues__ = $CField->m_values; ///asigna el valor preterminado...
					
					if ($__template__=="")
						$editstr.= $CField->Edit( $__label__, $__fieldvalues__,'', $__script__ );
					else {
						$__template__ = str_replace("{".strtoupper($fieldname)."}", $CField->Edit( $__label__, $__fieldvalues__, $__template__, $__script__ ), $__template__);
						Debug("CXMLRecordDefinition::Edit template:$fieldname: <textarea>".$__template__."</textarea>");
					}
				}
			} else {
				ShowError( "CXMLRecordDefinition::Edit > no fields defined." );
			}

			
			
			if ($__template__=="") 
				$editstr.= "<td>##ACCION##</td>"."</tr>";
			else {
				$editstr.= $__template__ ;
			}
			
			$editstr = str_replace( 
							
							array(	"##LABEL##",
											"##ACCION##",
											"##CLASS##"), 
							
							array(	"label_".$__label__,
											"<a href=\"javascript:XMLEliminar( '##TABLE##', 'label_".$__label__."','##NRECORDS##');\">Eliminar</a>",
											"xmlrecord"
							), 
							
							$editstr );
							
							
			Debug("CXMLRecordDefinition::Edit editstr: <textarea>".$editstr."</textarea>");
			
			return $editstr;
		}
		
		function Confirm( $__label__ ) {
			$editstr =  "";
			foreach( $this->m_CFields as $fieldname=>$CField ) {
				$fieldconfirm = $CField->Confirm( $__label__ );
				if ($fieldconfirm) {
					$editstr.= $CField->Confirm( $__label__ );
				} else {
					$editstr = "";
					return $editstr;
				}	
			}
			return $editstr;
		}
		
		function Draw( $__recorddata__, $__template__="" ) {
			$editstr =  "";
			if ($__template__=="") 
				$editstr = "<tr>";
			
			foreach( $this->m_CFields as $fieldname=>$CField ) {
				$__fieldvalues__= "";
				if ($__recorddata__!="")
					if($__recorddata__[$fieldname]!="")
						$__fieldvalues__ = $__recorddata__[$fieldname]['values'];
				if ($__template__=="")
					$editstr.= $CField->Draw( $__fieldvalues__ );
				else
					$__template__ = str_replace("{".strtoupper($fieldname)."}", $CField->Draw( $__fieldvalues__, $__template__  ), $__template__);
			}
			if ($__template__=="") 
				$editstr.= "<tr>";
			else
				$editstr.= $__template__;
			return $editstr;
		}
		
	};
 
   function XData2Array( $_XData_ ) {
   		global $_X2AData_;
   		
   		$_X2AData_ = array();
   		
			$SplitFields = explode("/>", $_XData_ );
			foreach($SplitFields as $Field) {						
				if ($Field!="") {				
					$SplitXData = explode( "values=", $Field );
					$SplitField = explode( "type=", $SplitXData[0] );				
					$FieldName = trim( substr( $SplitField[0], 1) );
					$TypeName = trim( substr( $SplitField[1], 0) );					
					$_X2AData_[$FieldName] = array('type'=>$TypeName, 'values'=>$SplitXData[1]);							
				}
			}
			Debug('XData2Array:<pre>'.print_r( $_X2AData_,true).'</pre>');
			return $_X2AData_;		
   }
   
   function WordsToVariable( $words ) {
	 return str_replace(array(",","."," ","ñ","á","é","í","ó","ú","à","è","ì","ò","ù","â","ê","î","ô","û"),
	 					array("_","_","_","n","a","e","i","o","u","a","e","i","o","u","a","e","i","o","u"),
	 					strtolower($words));   
   }
   
   function FormatPrice( $price ) {
   	
   		return money_format('%.2n', $price);
   	
   }   
   
   function register_global_array( $superglobal)
	{
	foreach($superglobal as $varname => $value)
	{
	global $$varname;
	$$varname = $value;
	}
	}

	function register_globals($order = 'egpcs')
	{
	
	   
	    $order = explode("\r\n", trim(chunk_split($order, 1)));
	    foreach($order as $k)
	    {
	        switch(strtolower($k))
	        {
	            case 'e':    register_global_array($_ENV);        break;
	            case 'g':    register_global_array($_GET);        break;
	            case 'p':    register_global_array($_POST);        break;
	            case 'c':    register_global_array($_COOKIE);    break;
	            case 's':    register_global_array($_SERVER);    break;
	        }
	    }
	}
	
	register_globals();
?>
