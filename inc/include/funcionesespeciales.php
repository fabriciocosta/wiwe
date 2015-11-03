<?php

		//echo $this->Contenidos->m_tcontenidos->SQL;
	
		if ( 
						(isset($GLOBALS['_f_ID_TIPOCONTENIDO']) && $GLOBALS['_f_ID_TIPOCONTENIDO']>0) 
				||	( isset($GLOBALS['_f_ID_TIPOCONTENIDO']) && is_object($this->UsuarioAdmin))
				
				)	{
			echo	'<div class="funciones">';
			echo	'<select id="funciones" name="funciones" onChange="javascript:eval(document.consultar.funciones.options[document.consultar.funciones.selectedIndex].value);">';
				echo	'<option class="APPLYTOSELECTION" value="">'.$CLang->m_Words["APPLYTOSELECTION"].'</option>';
				
				if (
						$GLOBALS['_f_ID_TIPOCONTENIDO']==FICHA_EVENTO
						|| $GLOBALS['_f_ID_TIPOCONTENIDO']==FICHA_CONVOCATORIA
						|| $GLOBALS['_f_ID_TIPOCONTENIDO']==FICHA_ORGANIZACION
						|| $GLOBALS['_f_ID_TIPOCONTENIDO']==FICHA_NOTICIA
						|| $GLOBALS['_f_ID_TIPOCONTENIDO']==FICHA_CURSO
						|| $GLOBALS['_f_ID_TIPOCONTENIDO']==FICHA_DOCUMENTO
				) {
					echo	'<option class="APPLYTOSELECTION" value="destacarseleccion();">Destacar</option>';
					echo	'<option class="APPLYTOSELECTION" value="nodestacarseleccion();">No destacar</option>';
				}
				

				echo	'<option class="VERIFY" value="verificarseleccion();">'.$CLang->m_Words["VERIFY"].'</option>'; 	
							
				if ($this->UsuarioAdmin->m_nivel<=1 || $this->AdminPermisos["ROL_APPROVAL"]==true) {
					echo	'<option class="ACTIVATE" value="habilitarseleccion();">'.$CLang->m_Words["ACTIVATE"].'</option>';
					echo	'<option class="DEACTIVATE" value="deshabilitarseleccion();">'.$CLang->m_Words["DEACTIVATE"].'</option>'; 
					echo	'<option class="BANNED" value="desaprobarseleccion();">'.$CLang->m_Words["BANNED"].'</option>';					
					echo	'<option class="DELETE" value="borrarseleccion(\''.$GLOBALS['tabla']->nombre.'\');">'.$CLang->m_Words["DELETE"].'</option>';
				}				
				
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
?>