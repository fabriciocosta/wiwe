<?Php

header('Content-Type: text/html; charset=iso-8859-1');


require "../../inc/include/deftabla.php";//modulo de definicion de las tablas

$__modulo__ = "request";

//AQUI CONTRUIMOS EL OBJETO PRINCIPAL DEL SITIO, AL QUE LE ASIGNAMOS LAS TABLAS YA CREADAS
if (!defined("DNK_SITE") and !defined("Sitio")) {
  	define("DNK_SITE","OK");
  	$Sitio = new CSitioExtended($_tsecciones_,$_ttipossecciones_,$_tcontenidos_,$_ttiposcontenidos_,$_tarchivos_,$_ttiposarchivos_,$_tdetalles_,$_ttiposdetalles_,$_tlogs_);
  	$Sitio->Inicializar();  
}

global $_tcontenidos_;
global $text;
global $tipoid;
global $divid;

function replace_x( $texto) {
	
	return str_replace(array(" "),array("-"),trim($texto));
	
}

function replace_y( $texto) {
	
	return str_replace( "-"," ",trim($texto));
	
}

function remove_accents($string)
{
  return str_replace( array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'), array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y'), $string);
} 

$text = trim($text);

if ($text=="") return "";

$__lang__ = "";

$cn = 0;

$TABLE = "";
$idset = "";
$textset = "";

//$tipoid
$_template_ = "";
$_template_file_ = "../../inc/templates/".$Sitio->TiposContenidos->m_Int2StrArray[$tipoid].".ajax.html";

if (file_exists($_template_file_)) {
	$_template_ = implode('', file($_template_file_));
}


$TABLE.='<table width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="white"><tr><td valign="top" style="padding-left:5px;">';

if ( strlen( $text ) >= 3) {
	
	//*********************************************************
	//	CONTENTS
	//*********************************************************
	
	$TABLE.= '<table  width="100%" cellpadding="0" cellspacing="0" bgcolor="white">';
	$_tcontenidos_->LimpiarSQL();
	if ($__lang__=="") {
		$_tcontenidos_->FiltrarSQL('ID_TIPOCONTENIDO',"/*SPECIAL*/ ( contenidos.TITULO LIKE '".$text."%' OR contenidos.TITULO LIKE '".replace_x($text)."%')", $tipoid );
		$_tcontenidos_->LimiteSQL(0,10);
		$_tcontenidos_->OrdenSQL('CONVERT(contenidos.TITULO USING utf8) ASC');
	} else {
		if ($text!="") {
			$_tcontenidos_->FiltrarSQL('ID_TIPOCONTENIDO',"/*SPECIAL*/ ( contenidos.ML_TITULO LIKE '%<".$__lang__.">".$text."%' OR contenidos.ML_TITULO LIKE '%<".$__lang__.">".replace_x($text)."%')", $tipoid);
		} else $_tcontenidos_->FiltrarSQL('ID_TIPOCONTENIDO',"",$tipoid);
		//$_tcontenidos_->OrdenSQL("CONVERT( SUBSTRING_INDEX(contenidos.ML_TITULO,'<".$__lang__.">',-1) USING utf8) ASC COLLATE utf8_general_ci");
		$_tcontenidos_->LimiteSQL(0,10);
		$_tcontenidos_->OrdenSQL("CONVERT( SUBSTRING_INDEX(contenidos.ML_TITULO,'<".$__lang__.">',-1) USING utf8)");
	}
	
	$_tcontenidos_->Open();
	//echo $_tcontenidos_->SQL;
	if ($_tcontenidos_->nresultados>0) {
		while ($r = $_tcontenidos_->Fetch()) {
			$Content = new CContenido($r);
			$__lang__== "" ? $Content->m_titulo = $Content->m_titulo : $Content->m_titulo = $Sitio->Contenidos->m_tcontenidos->TextoML( $Content->m_ml_titulo, $__lang__);
			$cn++;
			($cn%2==0)? $cl = "#F0F0F0" : $cl = "#F9F9F9";
			$titre = $Content->m_titulo;
			$ln = strlen($text);				
			if ($ln>0) {
				$tit = "<b>".htmlentities(substr( $titre, 0, $ln ))."</b>".htmlentities(substr($titre,$ln));
			} else {
				$tit = htmlentities($Content->m_titulo);
			}
			if ($idset!="") {$idset.="|"; $textset.= "|"; }
			$idset.= $Content->m_id;
			$textset.= $Content->m_titulo;
			
			if ($_template_=="") {
				$TABLE.= '
				<tr><td height="20" bgcolor="'.$cl.'"><div id="'.$divid.$Content->m_id.'" style="border-width:1px;" 
				onclick="javascript:'.$divid.'_manager.ContentClick('.$Content->m_id.',\''.trim(addslashes($Content->m_titulo)).'\');"  
				onmouseover="javascript:'.$divid.'_manager.ContentOver('.$Content->m_id.');"  
				onmouseout="javascript:'.$divid.'_manager.ContentOut('.$Content->m_id.');"
				><span style="font-size:11px;">'.$tit.'</span></div></td></tr>';
			} else {
				$Sitio->TiposContenidos->GetCompleto($Content);
				$TABLE.= '
				<tr><td height="20" bgcolor="'.$cl.'"><div id="'.$divid.$Content->m_id.'" style="border-width:1px;" 
				onclick="javascript:'.$divid.'_manager.ContentClick('.$Content->m_id.',\''.trim(addslashes($Content->m_titulo)).'\');"  
				onmouseover="javascript:'.$divid.'_manager.ContentOver('.$Content->m_id.');"  
				onmouseout="javascript:'.$divid.'_manager.ContentOut('.$Content->m_id.');"
				><span style="font-size:11px;">'.$tit.$Sitio->TiposContenidos->TextoCompleto( $Content, $_template_ ).'</span></div></td></tr>';
			}
		}
	}	
	
	//$_tcontenidos_->QuitarReferencias();
	
}


{
	$TABLE.= '</table>';
	//$TABLE.= '</td></tr></table>';
	$TABLE.='<input id="'.$divid.'_nrows" name="'.$divid.'_nrows" value="'.$cn.'" type="hidden">';
	$TABLE.='<input id="'.$divid.'_idset" name="'.$divid.'_idset" value="'.$idset.'" type="hidden">';
	$TABLE.='<input id="'.$divid.'_textset" name="'.$divid.'_textset" value="'.$textset.'" type="hidden">';
	
}


$HEIGHT = min(array( 30+$cn*20 , 10*20 ));

echo  '<div style="overflow:auto; width: 220px; height: '.$HEIGHT.'px;" >';
echo $TABLE;
echo "[".$text.",".$tipoid.",".$cn."]";
echo '</div>';
?>