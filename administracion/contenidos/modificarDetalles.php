<?Php

include "../admin/deftabla.php";

	echo '<table border="0" cellpadding="0" cellspacing="1" bgcolor="#000000">';
	echo '<tr><td bgcolor="#FFFFFF"><span class="titulocampo">DETALLES</span></td></tr>';
	echo '<tr><td bgcolor="#FFFFFF">';
	echo '<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';

		//if ($_modificar_=='si') {
		
		
		//$tabla->Edicion(${'_primario_'.$tabla->primario});
		//tomar el $_e_ID_TIPOCONTENIDO y filtrar tiposdetalles
		$_ttiposdetalles_->LimpiarSQL();		
		$_f_ID_TIPOCONTENIDO = $_e_ID_TIPOCONTENIDO;
		$_ttiposdetalles_->FiltrarSQL('ID_TIPOCONTENIDO');
		$_ttiposdetalles_->Open();
		
		if ($_ttiposdetalles_->nresultados > 0) {
			//imprimos los campos a editar....
			while ($row_tiposdetalles = $_ttiposdetalles_->Fetch($_ttiposdetalles_->resultados)) {
			
				//imprimimos el nombre del campo (TIPO) por cada Tipo de detalle
				
				echo '<tr>';
				echo '<td><span class="titulocampo">'.$row_tiposdetalles['tiposdetalles.DESCRIPCION'].'</span></td>';
				
				if (($_modificar_=='si') or ($_borrar_=='si')) {    		
  				$_tdetalles_->LimpiarSQL();
      		$_f_ID_TIPODETALLE = $row_tiposdetalles['tiposdetalles.ID'];
      		$_tdetalles_->FiltrarSQL('ID_TIPODETALLE');
      		$_f_ID_CONTENIDO = ${'_primario_'.$tabla->primario}; 
      		$_tdetalles_->FiltrarSQL('ID_CONTENIDO');
      		$_tdetalles_->Open();
    		} else if ($_nuevo_=='si') {
    			$_tdetalles_->nresultados = 0;
    		}
				
				$row_detalles = "";
				
				echo '<td>';				
				if ($_tdetalles_->nresultados > 0) { //MODIFICAR
					
					$row_detalles = $_tdetalles_->Fetch($_tdetalles_->resultados);
					
					//$CDetalles = new CDetalle($row_detalles);
					
					//el valor editable
					if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="T") {
						echo '<input name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="text" size="80" value="'.$row_detalles['detalles.DETALLE'].'">';
						echo '<input name="_emldetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="text" size="80" value="'.$row_detalles['detalles.ML_DETALLE'].'">';
					} else if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="N") {												 
						echo '<input name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="text" size="10" value="'.$row_detalles['detalles.DETALLE'].'">';
					} else if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="L") {
						//echo '<textarea name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" cols="80" rows="5">'.$row_detalles['detalles.DETALLE'].'</textarea>';						
						echo '<textarea name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" cols="80" rows="8">'.$row_detalles['detalles.TXTDATA'].'</textarea>';							
						echo '<textarea name="_emldetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" cols="80" rows="8">'.$row_detalles['detalles.ML_TXTDATA'].'</textarea>';
					} else if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="B") {
						//BLOB
						//echo '<textarea name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" cols="50" rows="8">'.$row_detalles['detalles.DETALLE'].'</textarea>';
						echo '<textarea name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" cols="80" rows="8">'.$row_detalles['detalles.TXTDATA'].'</textarea>';
						echo '<textarea name="_emldetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" cols="80" rows="8">'.$row_detalles['detalles.ML_TXTDATA'].'</textarea>';
					} else if (($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="I") || ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="F")) {
						if ($row_detalles['detalles.DETALLE']!='') echo '<img src="'.$row_detalles['detalles.DETALLE'].'" border="0" alt="preview"><br>';
						echo '<input name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="text" size="80" value="'.$row_detalles['detalles.DETALLE'].'"><input name="_fdetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="file">';
					} else if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="C") {
						if ($row_detalles['detalles.DETALLE'] == "si") { $ssi="selected"; $sno="";} else {$ssi=""; $sno="selected";}						
						echo '<select name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" ><option value="si" '.$ssi.'>SI</option><option value="no"  '.$sno.'>NO</option></select>';
					} else if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="X") {
						//aca imprimir el combo de seleccion de caracteristicas
						$AData = XData2Array($row_detalles['detalles.TXTDATA']);
						$ADataDef = XData2Array( $row_tiposdetalles['tiposdetalles.TXTDATA'] );
						echo '<table cellpadding="4" cellspacing="0" border="0">';						
						foreach($ADataDef as $Field=>$Values) {								
							if ($Field!="") {
								echo '<tr><td><span class="modulo_admin_detalle_campo">'.$Field.'</span></td>';
								if ($Values['type']=="select") {
									echo '<td><select name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'_'.$Field.'">';
								} else if ($Values['type']=="checkbox") {
									echo '<td>';
								}
								$SplitXDataValues = split( "\|", $Values['values'] );
								foreach($SplitXDataValues as $Value) {
									if ($Values['type']=="select") {	
										if ($AData[$Field]['values']==$Value) $selected = "selected"; else $selected="";
										echo '<option value="'.$Value.'" '.$selected.'>'.$Value.'</option>';
									} else if ($Values['type']=="checkbox") {											
										$pos = strpos( $AData[$Field]['values'],$Value);
										if (is_numeric($pos)) $selected = "checked"; else $selected="";
										echo '<span class="modulo_admin_detalle_valor">'.$Value.'<input name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'_'.$Field.'_'.WordsToVariable($Value).'" type="checkbox" '.$selected.'></span><br>';
									}
								}
								if ($Values['type']=="select") {
									echo '</select></td></tr>';	
								} else if ($Values['type']=="checkbox") {
									echo '</td></tr>';
								}
							}								
						}
						echo '</table>';							
					} else if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="R") {
						$tipospl = explode( "\n", $row_tiposdetalles['tiposdetalles.TXTDATA'] );
						$tipos = "&_tipocontenido_=";							
						foreach ($tipospl as $k) { $tipos.= "|".$k; }
						echo '<input name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="text" size="50" value="'.$row_detalles['detalles.DETALLE'].'"><a href="javascript:navegadorcontenido(\'confirmar._edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'\',\''.$tipos.'\');"><span class="modulo_admin_detalle_campo">'.$CLang->m_Words['SELECTCONTENT'].'</span></a>';
					} else if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="K") {
						echo '<input name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="text" size="80" value="'.$row_detalles['detalles.DETALLE'].'">';
					}

					//la accion
					echo '<input name="_adetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="hidden" size="50" value="modificar">';
					//el id del detalle
					echo '<input name="_idetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="hidden" size="50" value="'.$row_detalles['detalles.ID'].'">';					
					if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']!="T" &&
						$row_tiposdetalles['tiposdetalles.TIPOCAMPO']!="B" &&
						$row_tiposdetalles['tiposdetalles.TIPOCAMPO']!="L") {
						echo '<input name="_emldetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="text" size="80" value="'.$row_detalles['detalles.ML_DETALLE'].'">';
					}										
				} else { //NUEVOO					
					//el valor editable
					if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="T") {
						echo '<input name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="text" size="80" value="empty">';
						echo '<input name="_emldetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="text" size="80" value="">';
					} else if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="N") {												 
						echo '<input name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="text" size="10" value="0">';						
					} else if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="L") {
						echo '<textarea name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" cols="80" rows="8">empty</textarea>';
						echo '<textarea name="_emldetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" cols="80" rows="8">empty</textarea>';
					} else if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="B") {						
						//BLOB
						echo '<textarea name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" cols="80" rows="8">empty</textarea>';
					} else if (($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="I") || ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="F")) {
						echo '<input name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="text" size="80" value="empty"><input name="_fdetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="file">';
					} else if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="C") {
						echo '<select name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" ><option value="si" >SI</option><option value="no" selected>NO</option></select>';
					} else if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="X") {
						//aca imprimir el combo de seleccion de caracteristicas
						$ADataDef = XData2Array( $row_tiposdetalles['tiposdetalles.TXTDATA'] );
						echo '<table cellpadding="4" cellspacing="0" border="0">';						
						foreach($ADataDef as $Field=>$Values) {								
							if ($Field!="") {
								echo '<tr><td><span class="modulo_admin_detalle_campo">'.$Field.'</span></td>';
								if ($Values['type']=="select") {
									echo '<td><select name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'_'.$Field.'">';
								} else if ($Values['type']=="checkbox") {
									echo '<td>';
								}
								$SplitXDataValues = split( "\|", $Values['values'] );
								foreach($SplitXDataValues as $Value) {
									if ($Values['type']=="select") {												
										echo '<option value="'.$Value.'">'.$Value.'</option>';
									} else if ($Values['type']=="checkbox") {											
										echo '<span class="modulo_admin_detalle_valor">'.$Value.'<input name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'_'.$Field.'_'.WordsToVariable($Value).'" type="checkbox"></span><br>';
									}
								}
								if ($Values['type']=="select") {
									echo '</select></td></tr>';	
								} else if ($Values['type']=="checkbox") {
									echo '</td></tr>';
								}
							}								
						}
						echo '</table>';
					} else if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="R") {							
						$tipospl = explode( "\n", $row_tiposdetalles['tiposdetalles.TXTDATA'] );
						$tipos = "&_tipocontenido_=";							
						foreach ($tipospl as $k) { $tipos.= "|".$k; }
						echo '<input name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="text" size="50" value="empty"><a href="javascript:navegadorcontenido(\'confirmar._edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'\',\''.$tipos.'\');"><span class="modulo_admin_detalle_campo">'.$CLang->m_Words['SELECTCONTENT'].'</span></a>';
					} else if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']=="K") {
						echo '<input name="_edetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="text" size="80" value="empty">';
					}										
					
					echo '<input name="_adetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="hidden" size="50" value="insertar">';					
					echo '<input name="_idetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="hidden" size="50" value="">';
					if ($row_tiposdetalles['tiposdetalles.TIPOCAMPO']!="T" &&
						$row_tiposdetalles['tiposdetalles.TIPOCAMPO']!="B" &&
						$row_tiposdetalles['tiposdetalles.TIPOCAMPO']!="L") {
						echo '<input name="_emldetalle_'.$row_tiposdetalles['tiposdetalles.TIPO'].'" type="text" size="80" value="">';
					}					
				}
				
				
				echo '</td>';
				echo "</tr>";
			}			
		}
		
/*		
	} else if ($_borrar_=='si') {
		//$tabla->Edicion(${'_primario_'.$tabla->primario});
		
	} elseif ($_nuevo_=='si') {
		//$tabla->Nuevo();
		
	}
*/
	echo '</table>';
	echo '</td></tr>';
	echo '</table>';
?>
