<?Php

include "../admin/deftabla.php";

if ($_cancelar_=='no') {
	//$GLOBALS['_debug_'] = 'si';	
	//$_tdetalles_->debug = 'si';
	
  $_ttiposdetalles_->LimpiarSQL();
  $_f_ID_TIPOCONTENIDO = $_e_ID_TIPOCONTENIDO;
  $_ttiposdetalles_->FiltrarSQL('ID_TIPOCONTENIDO');
  $_ttiposdetalles_->Open();
  
	if ($_ttiposdetalles_->nresultados > 0) {
		//imprimos los campos a editar....
		while ($row_tiposdetalles = $_ttiposdetalles_->Fetch($_ttiposdetalles_->resultados)) {  
			$tipodetalle = $row_tiposdetalles['tiposdetalles.TIPO'];
			$idtipodetalle = $row_tiposdetalles['tiposdetalles.ID'];
			//$_tdetalles_->debug = 'si';
    	if (($_modificar_=='si') or ($_nuevo_=='si')) {      	
    		if (${'_adetalle_'.$tipodetalle}=='modificar') {
				if (($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=='I') || ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=='F')) {
    				$tmpname = $_FILES['_fdetalle_'.$tipodetalle]["tmp_name"];
    				$name = $_FILES['_fdetalle_'.$tipodetalle]["name"];
    				$tmpmov = $tipodetalle.$GLOBALS['_idetalle_'.$tipodetalle].$name;
    				if (is_uploaded_file($tmpname)) {
    					$_exito_ = rename($tmpname,$_SITEROOT_.'/tmp/'.$tmpmov);
						if($_exito_) $_exito_ = rename_ftp('/archivos/imagen/'.$tmpmov,'/tmp/'.$tmpmov);
    					if($_exito_) {
    						chmod_ftp('/archivos/imagen/'.$tmpmov);
    						//thumbnail(S_SITEROOT_,$_urlnuevo_,121,dirname($_urlnuevo_)."/thm",basename($_urlnuevo_));
    						thumbnail( $_SITEROOT_, "/archivos/imagen/".$tmpmov, 114, "/archivos/imagen/thm", $tmpmov, 77);
    						$GLOBALS['_edetalle_'.$tipodetalle] = '../../archivos/imagen/'.$tmpmov;
    					}
    				} else if ($GLOBALS['_edetalle_'.$tipodetalle]!="empty") {
    					$tmpmov = basename( $GLOBALS['_edetalle_'.$tipodetalle] );		    					
    					//tratamos de generar el thumbnail de la imagen:
    					thumbnail( $_SITEROOT_, "/archivos/imagen/".$tmpmov, 114, "/archivos/imagen/thm", $tmpmov, 77);
    				}  				
    			}
    			
				if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=='X') {
					//procesamos los campos select		    				
					$ADataDef = XData2Array($row_tiposdetalles['tiposdetalles.TXTDATA']);
					$GLOBALS['_edetalle_'.$tipodetalle] = "";
					//X PARSE
					foreach($ADataDef as $Field=>$Values) {	//FIELD PARSE	    							    					
						if ($Field!="") {
							$FieldInputName = '_edetalle_'.$tipodetalle.'_'.$Field;
							$FieldValues = "";
							$or="";
	
							$SplitXDataValues = split( "\|", $Values['values'] );
							foreach($SplitXDataValues as $Value) {//VALUE PARSE										
								if ($Values['type']=="select") {
									if ($GLOBALS[$FieldInputName]==$Value) {
										$FieldValues = $Value;
									}
								} else if ($Values['type']=="checkbox") {
									$ValueVar = WordsToVariable($Value);
									//echo $FieldInputName."_".$ValueVar.":".$GLOBALS[$FieldInputName."_".$ValueVar]."<br>";
									if ( $GLOBALS[$FieldInputName."_".$ValueVar] == "on" ) {
										$FieldValues.= $or.$Value;
										$or="\|";
									}												
								}
							}//FIN VALUE PARSE								
							$GLOBALS['_edetalle_'.$tipodetalle].= "<".$Field." type=".$Values['type']." values=".$FieldValues."/>";
						} 									    							    					    					
					}//FIN FIELD PARSE    				
				} //X FIN PARSEO   			
    			    	
				if($_exito_) {
					$reg = 	array('DETALLE'=>${'_edetalle_'.$tipodetalle},'TXTDATA'=>${'_edetalle_'.$tipodetalle});					
  					$_exito_ = $_tdetalles_->ModificarRegistro( ${'_idetalle_'.$tipodetalle} , $reg );
				}
      	} else if (${'_adetalle_'.$tipodetalle}=='insertar') {
    			if (($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=='I') || ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=='F')) {
    				$tmpname = $_FILES['_fdetalle_'.$tipodetalle]["tmp_name"];
    				$name = $_FILES['_fdetalle_'.$tipodetalle]["name"];
    				$tmpmov = $tipodetalle.$GLOBALS['_idetalle_'.$tipodetalle].$name;
    				if ( is_uploaded_file($tmpname) ) {
    					$_exito_ = rename( $tmpname, $_SITEROOT_.'/tmp/'.$tmpmov );
						if($_exito_) $_exito_ = rename_ftp('/archivos/imagen/'.$tmpmov,'/tmp/'.$tmpmov);
    					if($_exito_) {
    						chmod_ftp('/archivos/imagen/'.$tmpmov);
    						//thumbnail(S_SITEROOT_,$_urlnuevo_,121,dirname($_urlnuevo_)."/thm",basename($_urlnuevo_));
    						thumbnail( $_SITEROOT_, "/archivos/imagen/".$tmpmov, 114, "/archivos/imagen/thm", $tmpmov, 77);
    						$GLOBALS['_edetalle_'.$tipodetalle] = '../../archivos/imagen/'.$tmpmov;
    					}
    				}
    			}
    			
				if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=='X') {
    				//procesamos los campos select		    				
    				$ADataDef = XData2Array($row_tiposdetalles['tiposdetalles.TXTDATA']);
    				$GLOBALS['_edetalle_'.$tipodetalle] = "";
    				//X PARSE
    				foreach($ADataDef as $Field=>$Values) {	//FIELD PARSE	    							    					
						if ($Field!="") {
							$FieldInputName = '_edetalle_'.$tipodetalle.'_'.$Field;
							$FieldValues = "";
							$or="";

							$SplitXDataValues = split( "\|", $Values['values'] );
							foreach($SplitXDataValues as $Value) {//VALUE PARSE										
								if ($Values['type']=="select") {
									if ($GLOBALS[$FieldInputName]==$Value) {
										$FieldValues = $Value;
									}
								} else if ($Values['type']=="checkbox") {
									$ValueVar = WordsToVariable($Value);
									//echo $FieldInputName."_".$ValueVar.":".$GLOBALS[$FieldInputName."_".$ValueVar]."<br>";
									if ( $GLOBALS[$FieldInputName."_".$ValueVar] == "on" ) {
										$FieldValues.= $or.$Value;
										$or="\|";
									}												
								}
							}//FIN VALUE PARSE								
							$GLOBALS['_edetalle_'.$tipodetalle].= "<".$Field." type=".$Values['type']." values=".$FieldValues."/>";
						} 									    							    					    					
    				}//FIN FIELD PARSE    				
    			} //X FIN PARSEO

    			if($_exito_) {
	    			$reg = array('ID_CONTENIDO'=>${'_primario_'.$tabla->primario},
	      				'ID_TIPODETALLE'=>$idtipodetalle,      		
						'DETALLE'=>${'_edetalle_'.$tipodetalle},
						'ML_DETALLE'=>${'_emldetalle_'.$tipodetalle},
						'TXTDATA'=>${'_edetalle_'.$tipodetalle},
						'ML_TXTDATA'=>${'_emldetalle_'.$tipodetalle},
						'BINDATA'=>'');	    			
	      			$_exito_ = $_tdetalles_->InsertarRegistro( $reg );
      			}
			}        	
      } else if ($_borrar_=='si') {
        	//borrarlos
        	$_tdetalles_->LimpiarSQL();
        	$_tdetalles_->SQL = 'DELETE FROM detalles WHERE ID_CONTENIDO='.${'_primario_'.$tabla->primario};
        	$_exito_ = $_tdetalles_->EjecutaSQL();
      }
  	}
  }

}
?>
