<?
header('Content-Type: text/html; charset=iso-8859-1');

$__modulo__ = "searchterm";
		
require "../../inc/include/deftabla.php";//modulo de definicion de las tablas

//AQUI CONTRUIMOS EL OBJETO PRINCIPAL DEL SITIO, AL QUE LE ASIGNAMOS LAS TABLAS YA CREADAS
if (!defined("DNK_SITE") and !defined("Sitio")) {
  	define("DNK_SITE","OK");
  	$Sitio = new CSitioExtended($_tsecciones_,$_ttipossecciones_,$_tcontenidos_,$_ttiposcontenidos_,$_tarchivos_,$_ttiposarchivos_,$_tdetalles_,$_ttiposdetalles_,$_tlogs_,$_tusuarios_);
  	$Sitio->Inicializar();    	
}

//$Sitio->Detalles->GalleryBrowse( $_id_detalle_, $_id_tipodetalle_ );
global $term;
global $terms;
global $id_input_field;
global $type_input_field;

global $tipodetalle;
global $accion; //add or update
global $tiporelacion; //por seccion o por contenido....
global $id_contenido_seccion_usuario; //id del contenido o la seccion de donde surge la relacion
global $sql; //definido dentro del tipo de relacion o tipo de detalle, por ahora lo hacemos dentro del tipo de detalle
global $sqlcount;
global $id_contenido_seccion_rel; //id del contenido o la seccion a RELCIONAR!!!!
global $idtodelete;
global $hide;
global $last_callback;

$sql = str_replace("SELECCIONAR","SELECT",$sql);
$sqlcount = str_replace("SELECCIONAR","SELECT",$sqlcount);

/*
 En un futuro proximo:
	en el tipo de detalle deberia definirse simplemente el id del tipo de relacion ???
	luego dentro del tipo de relacion deberia definirse los queries.... por ahora lo podemos hacer dentro del tipo de detalle...
	este otro punto puede ser mejor para crear relaciones fuera de cualquier ficha...
	es bueno q se defina un tipo de relacion? sin asociarse a un tipo de contenido o tipo de seccion? no es necesario....?
	lo interesante seria q el concepto de tipo de detalle pueda asignarse tanto a un usuario, como a una seccion, en definitiva
	que si un tipo de detalle puede asociarse a un usuario o bien a una seccion estos campos se encuentre dentro de la tabla tiposdetalles
	ID_SECCION.... mmmm complicado....
 */
/*

echo "tiporelacion:".$tiporelacion;
echo "<br>accion:".$accion;
echo "<br>id_contenido_seccion_rel:".$id_contenido_seccion_rel;
echo "<br>id_contenido_seccion:".$id_contenido_seccion;
echo "<br>sql:".$sql;
echo "<br>sql:".$sqlcount;
*/
$resstr = '';

global $sql_exc;
global $sqlcount_exc;
global $sql_avoid;
global $sqlcount_avoid;
global $sql_avoid_exc;
global $sqlcount_avoid_exc;

global $style;


global $debugon;
//$debugon = true;

	
//echo '<pre>'.print_r($tipospl).'</pre>';
if ( trim($sql) == "" ) {
	$sql = trim($tipospl[1]);
	$sqlcount = trim($tipospl[2]);
}

$id_contenido_seccion = 0;

//($tiporelacion=='contenidos') ? $idtiporelacion = 1 : $idtiporelacion = 2 ;
$sql  = TiposParsing($sql);
$sqlcount  = TiposParsing($sqlcount);

$sql_avoid = str_replace("SELECT","SELECCIONAR",$sql);
$sqlcount_avoid = str_replace("SELECT","SELECCIONAR",$sqlcount);

if ($id_contenido_seccion_usuario=="") $id_contenido_seccion_usuario="-1";

global $notinids_a;

global $terms_ex;
global $lastterm;

if ( $accion == "addautocomplete" ) {
		
		if ($type_input_field=="text") $terms_ex = explode(",",$terms);
		if (count($terms_ex)>1) $lastterm = trim($terms_ex[count($terms_ex)-1]);
		else $lastterm = $terms;
		
		//echo " lasterm: ".$lastterm;
		//echo " terms: ".$terms;
		//echo count($terms_ex);
		
		if ( strlen($lastterm) > 0 ) {
			$accion = "add";
			if ($tiporelacion == 'contenidos') {
				$sql_exc = str_replace( "where","where contenidos.TITULO LIKE '".$lastterm."%' AND ",strtolower($sql));
				$sqlcount_exc = str_replace( "where","where contenidos.TITULO LIKE '".$lastterm."%' AND ",strtolower($sqlcount));
				$sql_exc.= " LIMIT 0, 10 ";
				$sqlcount_exc.= " LIMIT 0, 10 ";
			} else if ($tiporelacion == 'secciones') {
				$sql_exc = str_replace( "where","where secciones.NOMBRE LIKE '".$lastterm."%' AND ",strtolower($sql));
				$sqlcount_exc = str_replace( "where","where secciones.NOMBRE LIKE '".$lastterm."%' AND ",strtolower($sqlcount));
				$sql_exc.= " LIMIT 0, 10 ";
				$sqlcount_exc.= " LIMIT 0, 10 ";				
			}
		}
} else {
	
	$sql_exc = $sql;		
	$sqlcount_exc = $sqlcount;
		
}


if ( $accion == "get" ) {

	global $id;
	global $fields;
	global $details;
	
	error_reporting(E_ALL);
	
	//kkk kk  cccc	
	$CC = $Sitio->Contenidos->GetContenidoCompleto( $id );
	if (is_object($CC)) {
		$field_array = get_object_vars($CC);
		//echo "<pre>".print_r($field_array,true)."</pre>";
		
		$details_array = $CC->m_detalles;
		//echo "<pre>".print_r($CC->m_detalles,true)."</pre>";
		
		$fields = explode( "," , $fields );
		$details = explode( "," , $details );
		(is_array($fields))? $fields = $fields : $fields[0] = $fields;	
		(is_array($details))? $details = $details : $details[0] = $details;
	
		$coma = "";
		if (is_array($fields))
		foreach( $fields as $field ) {				
			$resstr.= $coma.$field."=".$field_array[$field]."";
			if ($resstr!="") $coma = "&";		 	
		}
		
		if (is_array($details))
		foreach( $details as $detail_field ) {				
			$resstr.= $coma.$detail_field."=".$details_array[$detail_field]->m_detalle."";
			if ($resstr!="") $coma = "&";		 	
		}
	} else {
		$resstr = "Error object ".$id." doesn't exists";
	}	 
	
	echo $resstr;	
	
} else if ($accion=="" || $accion=="add" ) {
		
	//=================================
	//ELIMINER LES CONTENTS DEJA INCLUS
	//=================================	
	
	/*
	$notinids = '';
	$coma = '';
	$_trelaciones_->LimpiarSQL();
	$_trelaciones_->FiltrarSQL('ID_TIPORELACION','',$idtiporelacion);
	$_trelaciones_->FiltrarSQL('ID_CONTENIDO','',$id_contenido_seccion);
	$_trelaciones_->Open();
	Debug("Not in sql:".$_trelaciones_->SQL); 
	
	while($row = $_trelaciones_->Fetch()) {
		if ($tiporelacion=='contenidos') $notinids.= $coma.$row['relaciones.ID_CONTENIDO_REL'];
		else $notinids.= $coma.$row['relaciones.ID_SECCION_REL'];
		$coma = ",";	
	}
	Debug("not in ids:".$notinids); 
	if ($notinids!="") {
		if ($tiporelacion=='contenidos') {
			$sql_exc = str_replace( "where ","where contenidos.ID NOT IN (".$notinids.") AND ", strtolower($sql) );		
			$sqlcount_exc = str_replace( "where ","where contenidos.ID NOT IN (".$notinids.") AND ", strtolower($sqlcount) );
		} else {
			//$sql_exc = str_replace( "where ","where secciones.ID NOT IN (".$notinids.") AND ", strtolower($sql) );		
			//$sqlcount_exc = str_replace( "where ","where secciones.ID NOT IN (".$notinids.") AND ", strtolower($sqlcount) );
			$sql_exc = strtolower($sql);
			$sqlcount_exc = strtolower($sqlcount);
			$notinids_a = explode(",",$notinids);	
		}
	} else {
		$sql_exc = strtolower($sql);
		$sqlcount_exc = strtolower($sqlcount);
	}
	//=================================
	//=================================
	
	//echo "CHOOSE<br>";
*/
	
	
	//$sql_avoid_exc = str_replace("SELECT","SELECCIONAR", $sql_exc);  // se vuelve a colocar "SELECCIONAR" para poder pasarlo
	//$sqlcount_avoid_exc = str_replace("SELECT","SELECCIONAR", $sqlcount_exc);
	
	if ($tiporelacion=='secciones') {
		$resstr.= '<SELECT class="select-searchterm" id="_searchterm_IDS_'.$id_input_field.'" name="_searchterm_IDS_'.$id_input_field.'" size="6" onchange="javascript:searchterm_confirmadd_2(\''.$id_input_field.'\',\''.$type_input_field.'\',\''.$tiporelacion.'\',\''.$id_contenido_seccion.'\', \''.$sql_avoid.'\',\''.$sqlcount_avoid.'\',\''.$hide.'\', 0, \'\', \'\' )">';
	}
	 
	if ($tiporelacion=='contenidos') {
	$resstr.= '<div class="select-searchterm" id="_searchterm_IDS_'.$id_input_field.'" name="_searchterm_IDS_'.$id_input_field.'" >';
	}
	$_tcontenidos_->LimpiarSQL();
	
	$_tcontenidos_->SQL = $sql_exc;
	$_tcontenidos_->SQLCOUNT = $sqlcount_exc;
	Debug("new select sql:".$_tcontenidos_->SQL);
	
	$_tcontenidos_->Open();
	$cc = 0;
	if ( $_tcontenidos_->nresultados>0 ) {
		while($_row_ = $_tcontenidos_->Fetch() ) {
			
			if ($tiporelacion=='contenidos') {
				
				$CC = new CContenido($_row_);
				$titulo = $CC->Titulo();
				if ($lastterm!="") {
						$titulo = str_ireplace( $lastterm, '<strong>'.$lastterm.'</strong>', $titulo );
				}
				$actualrec = "";
				if ($cc==0) $actualrec = "first-record";
				else if ($cc==$_tcontenidos_->nresultados-1) $actualrec = "last-record";
				( $cc%2 == 0 ) ?  $actualrec.= " even-record" : $actualrec.= " odd-record";
				$actualrec.= " ".$cc."-record";
				
				$recordid = $cc.'-record-'.$id_input_field;

				$other_fields  = "";
					
				//$resstr.= '<OPTION value="'.$CC->m_id.'" '.$sel.'>'.$CC->Titulo().'</OPTION>';
				$resstr.= '<a id="'.$recordid.'" class="record-link" href="#"
				onclick="javascript:return searchterm_confirmadd_2(\''.$id_input_field.'\',\''.$type_input_field.'\',\''.$tiporelacion.'\','.$id_contenido_seccion_usuario.', \''.$sql_avoid.'\',\''.$sqlcount_avoid.'\',\''.$hide.'\',\''.$CC->m_id.'\',\''.$CC->Titulo().'\', \'\' );">
				<div class="record '.$actualrec.'"><input type="hidden" value="'.$CC->m_id.'">'.$titulo.'</div></a>';
				$cc++;
			} else if ($tiporelacion=='secciones') {
				$CS = new CSeccion($_row_);
				$prof_str = str_repeat( " - ",$CS->m_profundidad );
				in_array( $CS->m_id, $notinids_a ) ? $disabled = "disabled" : $disabled = "";
				$resstr.= '<OPTION '.$disabled.' class="'.$disabled.' profundidad-'.$CS->m_profundidad.' seccion-'.$CS->m_id.'  tiposeccion-'.$CS->m_id_tiposeccion.'" value="'.$CS->m_id.'" '.$sel.'>'.$CS->Nombre().'</OPTION>';
			}
													
		}
	}
	if ($tiporelacion=='contenidos') {
		$resstr.= '</div>';
		$resstr.= '<input id="nresultados_'.$id_input_field.'" type="hidden" value="'.$_tcontenidos_->nresultados.'">';
		$resstr.= '<input id="iresultado_'.$id_input_field.'" type="hidden" value="0">';
	}
	 if ($tiporelacion=='secciones') {
	 	$resstr.=  '</SELECT>';
	 }
	
	
	
	//$resstr.= "ok terms:".$terms." idtipodetalle:".$idtipodetalle." tipodetalle_tipocampo:".$CTipoDetalle->m_tipocampo." sql:".$sql;
	echo $resstr;
	
} else if ($accion=="" || $accion=="confirmadd") {
	//echo "CONFIRMADD<br>";
	//echo "<br>id_contenido_seccion_rel:".$id_contenido_seccion_rel;
	//echo "<br>id_contenido_seccion:".$id_contenido_seccion;
	/*
	if ($tiporelacion=='contenidos') { 	
		if ( $_trelaciones_->InsertarRegistro(	array(
		'ID_TIPORELACION'=>$idtiporelacion,
		'ID_CONTENIDO'=>$id_contenido_seccion,
		'ID_SECCION'=>0,
		'ID_CONTENIDO_REL'=>$id_contenido_seccion_rel,
		'ID_SECCION_REL'=>0  ) ) ) {
			//echo "OK!!!";
		} else echo "ERROR!!!";
	} else {
		if ( $_trelaciones_->InsertarRegistro(	array(
		'ID_TIPORELACION'=>$idtiporelacion,
		'ID_CONTENIDO'=>$id_contenido_seccion,
		'ID_SECCION'=>0,
		'ID_CONTENIDO_REL'=>0,
		'ID_SECCION_REL'=>$id_contenido_seccion_rel  ) ) ) {
			//echo "OK!!!";
		} else echo "ERROR!!!";
		
	}
*/
  	$accion = "update";
}




?>
