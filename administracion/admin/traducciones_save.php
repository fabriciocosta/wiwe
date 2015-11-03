<?Php

header('Content-Type: text/html; charset=iso-8859-1');


require "../../inc/include/deftabla.php";//modulo de definicion de las tablas

$__modulo__ = "config";

//AQUI CONTRUIMOS EL OBJETO PRINCIPAL DEL SITIO, AL QUE LE ASIGNAMOS LAS TABLAS YA CREADAS
if (!defined("DNK_SITE") and !defined("Sitio")) {
  	define("DNK_SITE","OK");
  	$Sitio = new CSitioExtended($_tsecciones_,$_ttipossecciones_,$_tcontenidos_,$_ttiposcontenidos_,$_tarchivos_,$_ttiposarchivos_,$_tdetalles_,$_ttiposdetalles_,$_tlogs_);
  	$Sitio->Inicializar();  
}


global $trans_group;
global $trans_code;
global $trans_lang;
global $trans_text;
global $trans_file;
global $cell_pos;
global $row_num;
/*
	echo "group:".$trans_group;
	echo "<br>code:".$trans_code;
	echo "<br>lang:".$trans_lang;
	echo "<br>text:".$trans_text;
	echo "<br>";
	*/

	$Lignes = file($trans_file);
			
	$ln = $Lignes[0];
	$lnx = explode( ";" , $ln );
	$coma = "";
	$headers = "";
	$codes = array();
	$filestring = "";

	//***********************************
	//  HEADERS
	//***********************************	
	for( $i = 0; $i < count($lnx); $i++) {
		$headers.= $coma.trim(  $lnx[$i]  );
		if ($i>=2) {
			$codes[trim(  $lnx[$i]  )] = $i;
			//echo "<br>".trim(  $lnx[$i]  )." > > ".$i;
		}
		$coma = ';';
	}
	
	$filestring.= $headers."\n";
	
	//***********************************
	//  CODES
	//***********************************	
	
	for( $cn = 1; $cn < count($Lignes); $cn++ ) {
	 	$ln = $Lignes[$cn];
	 	$lnx = explode( ";" , $ln );
	 	
	 	$group = strtoupper(trim($lnx[0]));
	 	$code = strtoupper(trim($lnx[1]));
	 	
	 	$coma = "";
	 	$row = "";
	 	$lnxi = 0;
	 	foreach($lnx as $value) {
	 		if ($lnxi==1 && $cell_pos==1 && $row_num==($cn-1) ) {
	 			$value = trim($trans_text);
	 		}
	 		if ($lnxi>=2) {
			 	if ( ($trans_group == $group) && ($code == $trans_code ) && 
			 		( $codes[trim($trans_lang)]==$lnxi )) {		 		
			 		$value = trim($trans_text);		 		
			 	} 		
	 		}
	 		$row.= $coma.trim($value);
	 		$coma = ';';
	 		//echo $coma.trim($value).":[".$lnxi."] "; 
	 		$lnxi++;
	 	}
	 	$filestring.= $row."\n";
	}
	
	//echo str_replace("\n","<br>",$filestring);
	
	
	$file = fopen ( $trans_file, "w");
	if ($file) {
		fwrite( $file, $filestring);
		fclose ( $file );
		echo "SAVE OK";
	} else echo "error writing file";
	
?>