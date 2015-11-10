<?
header('Content-Type: text/html; charset=iso-8859-1');

$__modulo__ = "relaciones";

require "../../inc/include/deftabla.php";//modulo de definicion de las tablas

//error_reporting(E_ALL);

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
global $id_contenido_seccion; //id del contenido o la seccion de donde surge la relacion
global $sql; //definido dentro del tipo de relacion o tipo de detalle, por ahora lo hacemos dentro del tipo de detalle
global $sqlcount;
global $id_contenido_seccion_rel; //id del contenido o la seccion a RELCIONAR!!!!
global $idtodelete;
global $idtoorder;
global $hide;
global $last_callback;
global $order;

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

global $idtipodetalle;
global $sql_exc;
global $sqlcount_exc;
global $sql_avoid;
global $sqlcount_avoid;
global $sql_avoid_exc;
global $sqlcount_avoid_exc;

global $style;

$idtipodetalle = $Sitio->TiposDetalles->m_Str2IntArray[$tipodetalle];
$idtiporelacion = $idtipodetalle;

global $debugon;
//$debugon = true;


/*
$CTipoDetalle = $Sitio->TiposDetalles->GetTipoDetalle($idtipodetalle);
$tipospl = explode( "\n", $CTipoDetalle->m_txtdata );
*/

//($tiporelacion=='contenidos') ? $idtiporelacion = 1 : $idtiporelacion = 2 ;
$sql  = TiposParsing($sql);
$sqlcount  = TiposParsing($sqlcount);

$sql_avoid = str_replace("SELECT","SELECCIONAR",$sql);
$sqlcount_avoid = str_replace("SELECT","SELECCIONAR",$sqlcount);
	

global $notinids_a;

global $autocomplete_is_on;
$autocomplete_is_on = false;


//ShowMessage("accion:".$accion." sql:".$sql);
$sql = str_replace("ID_CONTENIDO_SECCION",$id_contenido_seccion,$sql);
$sqlcount = str_replace("ID_CONTENIDO_SECCION",$id_contenido_seccion,$sqlcount);

if ($accion=="addautocomplete") {
	
		/* separados por coma....
		 * 
		if ($type_input_field=="text") $terms_ex = explode(",",$terms);
		if (count($terms_ex)>1) $lastterm = trim($terms_ex[count($terms_ex)-1]);
		else $lastterm = $terms;
		*/
		$lastterm = $terms;
		$autocomplete_is_on = true;
		//echo " lasterm: ".$lastterm;
		//echo " terms: ".$terms;
		//echo count($terms_ex);
		
		if ( strlen($lastterm) > 2 ) {
			$accion = "add";
			if ($tiporelacion == 'contenidos') {
				$sql = str_replace( "where","where contenidos.TITULO LIKE '".$lastterm."%' AND ",strtolower($sql));
				$sqlcount = str_replace( "where","where contenidos.TITULO LIKE '".$lastterm."%' AND ",strtolower($sqlcount));
				$sql.= " LIMIT 0, 10 ";
				$sqlcount.= " LIMIT 0, 10 ";
				
			} else if ($tiporelacion == 'secciones') {
				$sql = str_replace( "where","where secciones.NOMBRE LIKE '".$lastterm."%' AND ",strtolower($sql));
				$sqlcount = str_replace( "where","where secciones.NOMBRE LIKE '".$lastterm."%' AND ",strtolower($sqlcount));
				$sql.= " LIMIT 0, 10 ";
				$sqlcount.= " LIMIT 0, 10 ";				
			}
		}	
	
}

if ($accion=="" || $accion=="add") {
		
	//=================================
	//ELIMINER LES CONTENTS DEJA INCLUS
	//=================================	
	$notinids = '';
	$coma = '';
	$_trelaciones_->LimpiarSQL();
	$_trelaciones_->FiltrarSQL('ID_TIPORELACION','',$idtiporelacion);
	$_trelaciones_->FiltrarSQL('ID_CONTENIDO','',$id_contenido_seccion);
	$_trelaciones_->Open();
	Debug("Not in sql:".$_trelaciones_->SQL); 
	
	while($row = $_trelaciones_->Fetch()) {
		if ($tiporelacion=='contenidos') $notinids.= $coma.$row['relaciones.ID_CONTENIDO_REL'];
		elseif ($tiporelacion=='tiposcontenidos') $notinids.= $coma.$row['relaciones.PESO'];
		elseif ($tiporelacion=='tipossecciones') $notinids.= $coma.$row['relaciones.PESO'];
		else $notinids.= $coma.$row['relaciones.ID_SECCION_REL'];
		$coma = ",";	
	}
	Debug("not in ids:".$notinids); 
	if ($notinids!="") {
		if ($tiporelacion=='contenidos') {
			$sql_exc = str_replace( "where ","where contenidos.ID NOT IN (".$notinids.") AND ", strtolower($sql) );		
			$sqlcount_exc = str_replace( "where ","where contenidos.ID NOT IN (".$notinids.") AND ", strtolower($sqlcount) );
		} else if ($tiporelacion=='tiposcontenidos') {
			$sql_exc = str_replace( "where ","where tiposcontenidos.ID NOT IN (".$notinids.") AND ", strtolower($sql) );		
			$sqlcount_exc = str_replace( "where ","where tiposcontenidos.ID NOT IN (".$notinids.") AND ", strtolower($sqlcount) );
		} else if ($tiporelacion=='tipossecciones') {
			$sql_exc = str_replace( "where ","where tipossecciones.ID NOT IN (".$notinids.") AND ", strtolower($sql) );		
			$sqlcount_exc = str_replace( "where ","where tipossecciones.ID NOT IN (".$notinids.") AND ", strtolower($sqlcount) );
		} else if ($tiporelacion=='secciones') {
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

	
	$sql_avoid_exc = str_replace("SELECT","SELECCIONAR", $sql_exc);  // se vuelve a colocar "SELECCIONAR" para poder pasarlo
	$sqlcount_avoid_exc = str_replace("SELECT","SELECCIONAR", $sqlcount_exc);
	
	if ($tiporelacion=='secciones') {
		$resstr.= '<SELECT class="select-relaciones" id="_relaciones_IDS_'.$tipodetalle.'" name="_relaciones_IDS_'.$tipodetalle.'" size="6" onchange="javascript:relaciones_confirmadd(\''.$tipodetalle.'\',\''.$tiporelacion.'\', '.$id_contenido_seccion.', \''.$sql_avoid.'\',\''.$sqlcount_avoid.'\',\''.$hide.'\', 0 )">';
	}
	 
	if ($tiporelacion=='contenidos') {
	$resstr.= '<div class="select-relaciones select-relaciones-autocomplete" id="_relaciones_IDS_'.$tipodetalle.'" name="_relaciones_IDS_'.$tipodetalle.'" >';
	}
	
	if ($tiporelacion=='tiposcontenidos') {
	$resstr.= '<div class="select-relaciones" id="_relaciones_IDS_'.$tipodetalle.'" name="_relaciones_IDS_'.$tipodetalle.'" >';
	}	
	
	if ($tiporelacion=='tipossecciones') {
	$resstr.= '<div class="select-relaciones" id="_relaciones_IDS_'.$tipodetalle.'" name="_relaciones_IDS_'.$tipodetalle.'" >';
	}		
	$_tcontenidos_->LimpiarSQL();
	
	$_tcontenidos_->SQL = $sql_exc;
	$_tcontenidos_->SQLCOUNT = $sqlcount_exc;
	Debug("new select sql:".$_tcontenidos_->SQL);
	//echo $_tcontenidos_->SQL;
	$_tcontenidos_->Open();
	if ( $_tcontenidos_->nresultados>0 ) {
		while($_row_ = $_tcontenidos_->Fetch() ) {
			
			if ($tiporelacion=='contenidos') {
				
				$CC = new CContenido($_row_);
				
				$titulo = $CC->Titulo();
				$actualrec = "";
				
				if ($lastterm!="" && $autocomplete_is_on) {
					$titulo = str_ireplace( $lastterm, '<strong>'.$lastterm.'</strong>', $titulo );					
					if ($cc==0) $actualrec = "first-record";
					else if ($cc==$_tcontenidos_->nresultados-1) $actualrec = "last-record";
					( $cc%2 == 0 ) ?  $actualrec.= "even-record" : $actualrec.= "odd-record";
					$actualrec.= " ".$cc."-record";						
				}				
				//$resstr.= '<OPTION value="'.$CC->m_id.'" '.$sel.'>'.$CC->Titulo().'</OPTION>';
				$resstr.= '<div class="record '.$actualrec.' record-link" onclick="javascript:relaciones_confirmadd(\''.$tipodetalle.'\',\''.$tiporelacion.'\', '.$id_contenido_seccion.', \''.$sql_avoid.'\',\''.$sqlcount_avoid.'\',\''.$hide.'\',\''.$CC->m_id.'\',\''.$last_callback.'\' )">
				<input type="hidden" value="'.$CC->m_id.'">'.$titulo.'</div>';
					
			} else if ($tiporelacion=='secciones') {
				
				$CS = new CSeccion($_row_);
				$prof_str = str_repeat( " - ",$CS->m_profundidad );
				in_array( $CS->m_id, $notinids_a ) ? $disabled = "disabled" : $disabled = "";
				$resstr.= '<OPTION '.$disabled.' class="'.$disabled.' profundidad-'.$CS->m_profundidad.' seccion-'.$CS->m_id.'  tiposeccion-'.$CS->m_id_tiposeccion.'" value="'.$CS->m_id.'" '.$sel.'>'.$CS->Nombre().'</OPTION>';
				
			} else if ($tiporelacion=='tiposcontenidos') {
				//$resstr.= '<div class="record">'.print_r($_row_,true).'</div>';
				
				$CTC = new CTipoContenido($_row_);
				$resstr.= '<div class="record" onclick="javascript:relaciones_confirmadd(\''.$tipodetalle.'\',\''.$tiporelacion.'\', '.$id_contenido_seccion.', \''.$sql_avoid.'\',\''.$sqlcount_avoid.'\',\''.$hide.'\',\''.$CTC->m_id.'\',\''.$last_callback.'\' )">
				<input type="hidden" value="'.$CTC->m_id.'">'.$CTC->m_tipo.'</div>';				
				
				
			} else if ($tiporelacion=='tipossecciones') {

				$CTS = new CTipoSeccion($_row_);
				$resstr.= '<div class="record" onclick="javascript:relaciones_confirmadd(\''.$tipodetalle.'\',\''.$tiporelacion.'\', '.$id_contenido_seccion.', \''.$sql_avoid.'\',\''.$sqlcount_avoid.'\',\''.$hide.'\',\''.$CTS->m_id.'\',\''.$last_callback.'\' )">
				<input type="hidden" value="'.$CTS->m_id.'">'.$CTS->m_tipo.'</div>';				
				
			}
																	
		}
	}
	if ($tiporelacion=='tiposcontenidos') {
		$resstr.= '</div>';
	}	
	if ($tiporelacion=='tipossecciones') {
		$resstr.= '</div>';
	}	
	if ($tiporelacion=='contenidos') {
		$resstr.= '</div>';
		if ($autocomplete_is_on) {
			$resstr.= '<input id="nresultados_'.$tipodetalle.'" type="hidden" value="'.$_tcontenidos_->nresultados.'">';
			$resstr.= '<input id="iresultado_'.$tipodetalle.'" type="hidden" value="0">';
		}
	}
	 if ($tiporelacion=='secciones') {
	 	$resstr.=  '</SELECT>';
	 }
	
	echo $resstr;
} else if ($accion=="" || $accion=="confirmadd") {
	//echo "CONFIRMADD<br>";
	//echo "<br>id_contenido_seccion_rel:".$id_contenido_seccion_rel;
	//echo "<br>id_contenido_seccion:".$id_contenido_seccion;
	if ($tiporelacion=='contenidos') { 	
		if ( $_trelaciones_->InsertarRegistro(	array(
		'ID_TIPORELACION'=>$idtiporelacion,
		'ID_CONTENIDO'=>$id_contenido_seccion,
		'ID_SECCION'=>0,
		'ID_CONTENIDO_REL'=>$id_contenido_seccion_rel,
		'ID_SECCION_REL'=>0  ) ) ) {
			//echo "OK!!!";
		} else echo "ERROR!!!";
	} else if ($tiporelacion=='tiposcontenidos') { 	
		if ( $_trelaciones_->InsertarRegistro(	array(
		'ID_TIPORELACION'=>$idtiporelacion,
		'ID_CONTENIDO'=>$id_contenido_seccion,
		'ID_SECCION'=>0,
		'ID_CONTENIDO_REL'=>0,
		'ID_SECCION_REL'=>0,
		'PESO'=>$id_contenido_seccion_rel ) ) ) {
			//echo "OK!!!";
		} else echo "ERROR!!!";
	} else if ($tiporelacion=='tipossecciones') { 	
		if ( $_trelaciones_->InsertarRegistro(	array(
		'ID_TIPORELACION'=>$idtiporelacion,
		'ID_CONTENIDO'=>$id_contenido_seccion,
		'ID_SECCION'=>0,
		'ID_CONTENIDO_REL'=>0,
		'ID_SECCION_REL'=>0,
		'PESO'=>$id_contenido_seccion_rel ) ) ) {
			//echo "OK!!!";
		} else echo "ERROR!!!";
	} else if ($tiporelacion=='secciones') {
		if ( $_trelaciones_->InsertarRegistro(	array(
		'ID_TIPORELACION'=>$idtiporelacion,
		'ID_CONTENIDO'=>$id_contenido_seccion,
		'ID_SECCION'=>0,
		'ID_CONTENIDO_REL'=>0,
		'ID_SECCION_REL'=>$id_contenido_seccion_rel  ) ) ) {
			//echo "OK!!!";
		} else echo "ERROR!!!";
		
	}
  	$accion = "update";
} else if ($accion=="delete") {
	if ( $idtodelete>0 && $_trelaciones_->Borrari($idtodelete) ) {
		//echo "DELETED OK!!!";
	} else echo $_trelaciones_->exito. ":".$_trelaciones_->SQL;
	$accion = "update";
}

//SHOW CONTENT
if ($accion=="clear") { 
	
	if ($tiporelacion=='contenidos') {
		
				$_trelaciones_->LimpiarSQL();
        $_trelaciones_->SQL = 'DELETE FROM relaciones WHERE ID_CONTENIDO='.$id_contenido_seccion.' AND ID_TIPORELACION='.$idtiporelacion;
        $_exito_ = $_trelaciones_->EjecutaSQL();
        if (!$_exito_) DebugError("Clear Error");
                
	}
	
	if ($tiporelacion=='secciones') {
				$_trelaciones_->LimpiarSQL();
        $_trelaciones_->SQL = 'DELETE FROM relaciones WHERE ID_CONTENIDO='.$id_contenido_seccion.' AND ID_TIPORELACION='.$idtiporelacion;
        $_exito_ = $_trelaciones_->EjecutaSQL();		
        if (!$_exito_) DebugError("Clear Error");
	}
	
}
if ($accion=="order" && $idtoorder!="") {
	$list = array();
	echo "ordering...<br>";
	if ($tiporelacion=='contenidos') {
		
		$_trelaciones_->AgregarReferencia("ID_CONTENIDO_REL","","contenidos REL","ID","TITULO");
		$_trelaciones_->LimpiarSQL();
		$_trelaciones_->FiltrarSQL('ID_TIPORELACION','',$idtiporelacion);
		$_trelaciones_->FiltrarSQL('ID_CONTENIDO','',$id_contenido_seccion);
		$_trelaciones_->OrdenSQL('relaciones.ORDEN ASC, REL.TITULO ASC');
		
		$_trelaciones_->Open();
		$nro_orden = 1;

		while( $row = $_trelaciones_->Fetch() ) {
			
			$idrelacion = $row['relaciones.ID'];
			$Contenido = $Sitio->Contenidos->GetContenido($row['relaciones.ID_CONTENIDO_REL']);

			$Contenido->m_rel_order = 10*$nro_orden;

			$Contenido->m_rel_id = $idrelacion;
			if ($idtoorder!=$idrelacion) {
				$list[$Contenido->m_rel_order] = $Contenido;
				$nro_orden+=1;
			} else $list[$order*10+1] = $Contenido;
			
		}
		
		$nro_orden = 1;
		ksort( $list );
		/*echo "countlist: ".count($list)."<br>";*/
		foreach( $list as $ordenid => $Contenido ) {
			/*echo "ordenid: ".$ordenid." nro_orden:".$nro_orden." Titulo:".$Contenido->Titulo()."<br>";*/
			$_trelaciones_->ModificarRegistro(	$Contenido->m_rel_id, array(
										'ID_TIPORELACION'=>$idtiporelacion,
										'ID_CONTENIDO'=>$id_contenido_seccion,
										'ID_SECCION'=>0,
										'ID_CONTENIDO_REL'=>$id_contenido_seccion_rel,
										'ID_SECCION_REL'=>0,
										'ORDEN'=>$nro_orden  ) );
			$nro_orden+=1;
		}
	}/*fin orden contenidos*/
	
}

if ($accion=="update" || $accion=="order") {
	//show all the ids created....
	//echo "UPDATE<br>";
	$list = array();
	if ($tiporelacion=='contenidos') {
		//busca todos las relaciones cuyo contenido sea el id_contenido_seccion seleccionado...
		$_trelaciones_->AgregarReferencia("ID_CONTENIDO_REL","","contenidos REL","ID","TITULO");
		$_trelaciones_->LimpiarSQL();
		$_trelaciones_->FiltrarSQL('ID_TIPORELACION','',$idtiporelacion);
		$_trelaciones_->FiltrarSQL('ID_CONTENIDO','',$id_contenido_seccion);
		//$_trelaciones_->OrdenSQL('REL.TITULO ASC');
		$_trelaciones_->OrdenSQL('relaciones.ORDEN ASC');//$nro_orden
		
		$_trelaciones_->Open();
		$echostr = "";
		while( $row = $_trelaciones_->Fetch() ) {
			$idrelacion = $row['relaciones.ID'];
			$Contenido = $Sitio->Contenidos->GetContenido($row['relaciones.ID_CONTENIDO_REL']);
			$Contenido->m_rel_order = $row['relaciones.ORDEN'];
			$Contenido->m_rel_id = $idrelacion;
			$list[$idrelacion] = $Contenido;
		}
		

		
		
		foreach( $list as $idrelacion => $Contenido ) {
			/*SELECT (ORDER)*/
			$echostr.= '<select title="'.$Contenido->m_rel_order.'" onchange="javascript:relaciones_order( event, \''.$tipodetalle.'\',\''.$tiporelacion.'\', '.$id_contenido_seccion.', \''.$sql_avoid.'\',\''.$sqlcount_avoid.'\', '.$idrelacion.',\''.$hide.'\', \''.$last_callback.'\' );" class="relaciones-ordenar">';
			for( $cl = 1; $cl<=count($list); $cl++) {
					if ($Contenido->m_rel_order==$cl) {
						$sel = "selected";
					} else $sel = "";
					$echostr.= '<option value="'.$cl.'" '.$sel.'>'.$cl.'</option>';
			}
			/**/
			$echostr.= '</select>&nbsp;';
			$echostr.= $Contenido->Titulo();
			$echostr.= ' <a href="javascript:relaciones_delete(\''.$tipodetalle.'\',\''.$tiporelacion.'\', '.$id_contenido_seccion.', \''.$sql_avoid.'\',\''.$sqlcount_avoid.'\', '.$idrelacion.',\''.$hide.'\', \''.$last_callback.'\' );" class="relaciones-eliminar">';
			$echostr.= $CLang->Get("DELETE").'</a>';
			$echostr.= '<br>';
		}
		
		echo $echostr;
	}
	
	if ($tiporelacion=='secciones') {
		//busca todos las relaciones cuyo contenido sea el id_contenido_seccion seleccionado...
		$_trelaciones_->AgregarReferencia("ID_SECCION_REL","","secciones REL","ID","NOMBRE");
		$_trelaciones_->LimpiarSQL();
		$_trelaciones_->FiltrarSQL('ID_TIPORELACION','',$idtiporelacion);
		$_trelaciones_->FiltrarSQL('ID_CONTENIDO','',$id_contenido_seccion);
		$_trelaciones_->OrdenSQL('REL.NOMBRE ASC');
		$_trelaciones_->Open();
		 
		while($row = $_trelaciones_->Fetch()) {
			$idrelacion = $row['relaciones.ID'];
			if ($row['relaciones.ID_CONTENIDO_REL']>0) {
				$Contenido = $Sitio->Contenidos->GetContenido($row['relaciones.ID_CONTENIDO_REL']);
				if (is_object($Contenido)) {
					echo $Contenido->Titulo().' <a href="javascript:relaciones_delete(\''.$tipodetalle.'\',\''.$tiporelacion.'\', '.$id_contenido_seccion.', \''.$sql_avoid.'\',\''.$sqlcount_avoid.'\', '.$idrelacion.',\''.$hide.'\',\''.$last_callback.'\' );" class="relaciones-eliminar">'.$CLang->Get("DELETE").'</a><br>';
				}
			} else {
				$Seccion = $Sitio->Secciones->GetSeccion($row['relaciones.ID_SECCION_REL']);
				if (is_object($Seccion)) {
					echo $Seccion->Nombre().' <a href="javascript:relaciones_delete(\''.$tipodetalle.'\',\''.$tiporelacion.'\', '.$id_contenido_seccion.', \''.$sql_avoid.'\',\''.$sqlcount_avoid.'\', '.$idrelacion.',\''.$hide.'\',\''.$last_callback.'\' );" class="relaciones-eliminar">'.$CLang->Get("DELETE").'</a><br>';
				} else {
					echo "Contact your admin to check database coherence.  relaciones.ID_SECCION_REL [".$row['relaciones.ID_SECCION_REL']."] SQL [".$Sitio->Secciones->m_tsecciones->SQL."]";
				}
			}
		}
		
	}
	
	if ($tiporelacion=='tiposcontenidos') {
		//busca todos las relaciones cuyo contenido sea el id_contenido_seccion seleccionado...
		$_trelaciones_->AgregarReferencia("PESO","","tiposcontenidos REL","ID","TIPO");
		$_trelaciones_->LimpiarSQL();
		$_trelaciones_->FiltrarSQL('ID_TIPORELACION','',$idtiporelacion);
		$_trelaciones_->FiltrarSQL('ID_CONTENIDO','',$id_contenido_seccion);
		$_trelaciones_->OrdenSQL('REL.TIPO ASC');
		
		$_trelaciones_->Open();
		
		while($row = $_trelaciones_->Fetch()) {
			$idrelacion = $row['relaciones.ID'];
			//$Contenido = $Sitio->Contenidos->GetContenido($row['relaciones.ID_CONTENIDO_REL']);
			$TipoContenido = $Sitio->TiposContenidos->GetTipoContenido($row['relaciones.PESO']);
			echo $TipoContenido->m_tipo.' <a href="javascript:relaciones_delete(\''.$tipodetalle.'\',\''.$tiporelacion.'\', '.$id_contenido_seccion.', \''.$sql_avoid.'\',\''.$sqlcount_avoid.'\', '.$idrelacion.',\''.$hide.'\', \''.$last_callback.'\' );" class="relaciones-eliminar">'.$CLang->Get("DELETE").'</a><br>';
		}
		
	}		
	

	if ($tiporelacion=='tipossecciones') {
		//busca todos las relaciones cuyo contenido sea el id_contenido_seccion seleccionado...
		$_trelaciones_->AgregarReferencia("PESO","","tipossecciones REL","ID","TIPO");
		$_trelaciones_->LimpiarSQL();
		$_trelaciones_->FiltrarSQL('ID_TIPORELACION','',$idtiporelacion);
		$_trelaciones_->FiltrarSQL('ID_CONTENIDO','',$id_contenido_seccion);
		$_trelaciones_->OrdenSQL('REL.TIPO ASC');
		
		$_trelaciones_->Open();
		
		while($row = $_trelaciones_->Fetch()) {
			$idrelacion = $row['relaciones.ID'];
			//$Contenido = $Sitio->Contenidos->GetContenido($row['relaciones.ID_CONTENIDO_REL']);
			$TipoSeccion = $Sitio->TiposSecciones->GetTipoSeccion($row['relaciones.PESO']);
			echo $TipoSeccion->m_tipo.' <a href="javascript:relaciones_delete(\''.$tipodetalle.'\',\''.$tiporelacion.'\', '.$id_contenido_seccion.', \''.$sql_avoid.'\',\''.$sqlcount_avoid.'\', '.$idrelacion.',\''.$hide.'\', \''.$last_callback.'\' );" class="relaciones-eliminar">'.$CLang->Get("DELETE").'</a><br>';
		}
		
	}	
	
}


if ($accion=="confirmaddcontent")  {
	global $titulo;
	global $tipocontenido;
	$id_tipocontenido = TiposParsing($tipocontenido);
	
	$titulo = trim($titulo);
	
	//ShowMessage("Chequeando si contenido existe:".$titulo." tipo:".$tipocontenido." tipoid:".$id_tipocontenido);
	
	$_tcontenidos_->LimpiarSQL();
	$_tcontenidos_->FiltrarSQL('ID_TIPOCONTENIDO',"/*SPECIAL*/ contenidos.TITULO like '".$titulo."' ", $id_tipocontenido);
	
	$_tcontenidos_->Open();
	
	if ( $_tcontenidos_->nresultados>0 ) {
		$r = $_tcontenidos_->Fetch();
		$Marca = new CContenido( $r );
		ShowMessage("Esta marca ya está registrada en nuestro sistema.");

					$id_contenido_seccion = $Marca->m_id;
				
					$_trelaciones_->LimpiarSQL();
					$_trelaciones_->FiltrarSQL( 'ID_TIPORELACION', '', $idtiporelacion );
					$_trelaciones_->FiltrarSQL( 'ID_CONTENIDO', '', $id_contenido_seccion );
					if ($tiporelacion=='contenidos') {
						$_trelaciones_->FiltrarSQL( 'ID_CONTENIDO_REL', '', $id_contenido_seccion_rel );
					} else {
						$_trelaciones_->FiltrarSQL( 'ID_SECCION_REL', '', $id_contenido_seccion_rel );
					}
					$_trelaciones_->Open();
					if ($_trelaciones_->nresultados>0) {
													
							ShowMessage("Y ya se encuentra asociada a esta categoría.");
					
					} else {
								ShowMessage("La asociamos a esta categoria");
							//asociamos
								if ($tiporelacion=='contenidos') { 	
									if ( $_trelaciones_->InsertarRegistro(	array(
									'ID_TIPORELACION'=>$idtiporelacion,
									'ID_CONTENIDO'=>$id_contenido_seccion,
									'ID_SECCION'=>0,
									'ID_CONTENIDO_REL'=>$id_contenido_seccion_rel,
									'ID_SECCION_REL'=>0  ) ) ) {
										//ShowError("Intente nuevamente");
									} else ShowError("Intente nuevamente");
								} else {
									if ( $_trelaciones_->InsertarRegistro(	array(
									'ID_TIPORELACION'=>$idtiporelacion,
									'ID_CONTENIDO'=>$id_contenido_seccion,
									'ID_SECCION'=>0,
									'ID_CONTENIDO_REL'=>0,
									'ID_SECCION_REL'=>$id_contenido_seccion_rel  ) ) ) {
										//echo "OK!!!";
									} else ShowError("Intente nuevamente");
									
								}

					}
		
		//si ya existe la relacion con esta categoria entonces... no
		//asociar a esta categoria
		
	} else {
		
		
		
		$Marca = new CContenido();
		$Marca = $Sitio->Contenidos->NuevoContenido($id_tipocontenido);
		
		if (is_object($Marca)) {
			
			$Marca->m_titulo = $titulo;
			$Marca->m_id_seccion = 16;
			$Marca->m_baja = 'S';
			
			ShowMessage("Registrando la marca : " .$Marca->Titulo());
		
			$Marca = $Sitio->Contenidos->CrearContenidoCompleto( $id_tipocontenido, $Marca, true, false );
			if (is_object($Marca)) {
				
				$id_contenido_seccion = $Marca->m_id;
				
				//asociamos
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
				
			} else ShowError("Intente nuevamente");
			
		} else ShowError("Intente nuevamente");
	}
	
} else if ($accion=="confirmsuggest") {
		
		$titulo = trim($titulo);
		
		if ($titulo!="") {
			
			//martinderechinsky@gmail.com
			//"fabricio.costa@moldeointeractive.com.ar";
			$mailtosend = "martinderechinsky@gmail.com";
			$mail_message = "";
			$variables = array();
			$mandatories = array();
			//$embedimages = array("../../inc/images/ecard.jpg");
			$embedimages  = "";
			$site = "www.contactojusto.com.ar";
			
			$mail_message = ' Una sugerencia de marca de parte de los usuarios: '.$titulo;
			
			$Sitio->SendMessage( $variables,
								$mandatories,
								$results,
								"Contacto Justo",								
								"Sugerencia de marca",								
								$mailtosend,								
								$mail_message,
								$embedimages);
													
			if ($results['errores']==0) {
				Debug("Sugerencia enviada");
				//echo '<script>Popup("dialog","Gracias por su sugerencia.");</script>';
				ShowMessage("Gracias por su sugerencia.");
			} else {
				DebugError("No se pudo enviar la sugerencia.".$results['error']);
				ShowError("No se pudo enviar la sugerencia. Intente nuevamente.");
			}
		}
}


?>
