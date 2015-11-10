<?php
		if ( 
						(isset($GLOBALS['_f_ID_TIPOCONTENIDO']) && $GLOBALS['_f_ID_TIPOCONTENIDO']>0) 
				||	( isset($GLOBALS['_f_ID_TIPOCONTENIDO']) && is_object($this->UsuarioAdmin) && $this->UsuarioAdmin->m_nick=="cg_admin")
				
				)	{
			echo	'<div class="funciones">';
			echo	'<select id="funciones" name="funciones" onChange="javascript:eval(document.consultar.funciones.options[document.consultar.funciones.selectedIndex].value);">';
				echo	'<option class="APPLYTOSELECTION" value="">'.$CLang->m_Words["APPLYTOSELECTION"].'</option>';
				echo	'<option class="ACTIVATE" value="habilitarseleccion();">'.$CLang->m_Words["ACTIVATE"].'</option>';
				echo	'<option class="DEACTIVATE" value="deshabilitarseleccion();">'.$CLang->m_Words["DEACTIVATE"].'</option>'; 
				if ($GLOBALS['_f_ID_TIPOCONTENIDO']==FICHA_GALERIA) {
					echo	'<option class="CHANGESECTION" value="accionseleccion(\'cambiar_seccion\');">mover a</option>'; 
				}
				echo	'<option class="DELETE" value="borrarseleccion(\''.$GLOBALS['tabla']->nombre.'\');">'.$CLang->m_Words["DELETE"].'</option>';
			echo	'</select></div>';
		}				
		
		/*SOLO SE PUEDEN HACER OPERACIONES MASIVAS SOBRE USUARIOS*/
		if ( isset($GLOBALS['_f_NIVEL']) && 
					( $GLOBALS['_f_NIVEL']==4 
						|| 
						( is_object($this->UsuarioAdmin) && $GLOBALS['_f_NIVEL']==1 && $this->UsuarioAdmin->m_nick=="cg_admin" ) 
					) 
					)	{
			echo	'<div class="funciones">';
			echo	'<select id="funciones" name="funciones" onChange="javascript:eval(document.consultar.funciones.options[document.consultar.funciones.selectedIndex].value);">';
			echo	'<option class="APPLYTOSELECTION" value="">'.$CLang->m_Words["APPLYTOSELECTION"].'</option>';
			echo	'<option class="DELETE" value="borrarseleccion(\'usuarios\');">'.$CLang->m_Words["DELETE"].'</option>';
			echo	'<option class="LISTMAILS" value="javascript:mailsseleccion();">'.$CLang->Get('LISTMAILS').'</option>
					</select>
					</div>';			
		}
		
		
		if ( $GLOBALS['_f_ID_TIPOSECCION'] == SECCION_GALERIA || $GLOBALS['_f_ID_TIPOSECCION'] == SECCION_GALERIA_CATEGORIA) {
				
						global $_fcomboe_cambiar_seccion;
						$nested = ' secciones.ID_TIPOSECCION>='.SECCION_GALERIA.' AND secciones.ID_TIPOSECCION<='.SECCION_GALERIA_CATEGORIA.' ';
						echo '<div class="sel_categorias">categorías: </div><div class="sel_categorias">';
						$this->Contenidos->m_tcontenidos->Combo(
							'e'   ,
							'cambiar_seccion',
							''     ,
							'secciones',
							'ID',
							'NOMBRE',
							''        ,
							''        ,
							''          ,
							''       ,
							$nested,
							'',
							'');							
						echo '</div>';

						
												
		}		
?>