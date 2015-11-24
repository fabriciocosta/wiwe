<?

/**
 * class CTiposContenidos
 * 
 * Esta clase es la que define como se comporta y publica cada tipo de contenido.
 * 
 * Los métodos clásicos de publicación son:
 * colapsado: típicamente para el caso de un listado sin descripción de contenidos
 * resumen: listado más completo y con detalles.
 * completo: publica todo el contenido y detalles asociados de manera completa
 * consulta: listado de contenidos dentro del contexto del administrador de contenidos
 * 
 * Se pueden definir plantillas predeterminados para cada caso.
 * 
 * Aquí también se definen las plantillas 
 *
 * @version	6.0
 * @copyright	2003	Fabricio Costa Alisedo 
 **/

 
class CTiposContenidos extends CErrorHandler {

	var $m_Str2IntArray,$m_Int2StrArray;
	
	var $m_templatescolapsados;
	var $m_templatesresumenes;
	var $m_templatescompletos;
	
	/*ADMIN*/
	var $m_templatesconsulta;
	var $m_templatesedicion;

	var $m_templatesresumenes_limits;
	
	var $m_ttiposcontenidos;//tabla tiposcontenidos
	var $m_CDetalles;//clase de tiposdetalles
	
	var $m_detalles_on;
	
	function PopErrorsCascade() {		
		$fullcascade = $this->m_CDetalles->PopErrorsCascade();
		$fullcascade.= $this->PopAllErrorsFullStr();
		return $fullcascade; 
	}
	
	
	/**
	 * Constructor de la clase
	 *
	 * @param CTabla $__ttiposcontenidos__   la tabla de tipos de contenidos
	 * @param CDetalles $__m_CDetalles__  la clase que administra los detalles
	 * @return CTiposContenidos	devuelve el objeto creado de la clase CTiposContenidos
	 */
	function CTiposContenidos(&$__ttiposcontenidos__,&$__m_CDetalles__) {
		$this->m_class = "CTiposContenidos";
		$this->Set( $__ttiposcontenidos__, $__m_CDetalles__ );
					
	}

	/**
	 * Fija los parámetros de la clase, la inicializa
	 *
	 * @param CTabla $__ttiposcontenidos__   la tabla de tipos de contenidos
	 * @param CDetalles $__m_CDetalles__  la clase que administra los detalles
	 */
	function Set(&$__ttiposcontenidos__,&$__m_CDetalles__) {

		$this->m_detalles_on = array();
		$this->m_ttiposcontenidos = &$__ttiposcontenidos__;
		$this->m_CDetalles = &$__m_CDetalles__;	
		
    	$this->m_ttiposcontenidos->LimpiarSQL();    
    	$this->m_ttiposcontenidos->Open();		     
		if ( $this->m_ttiposcontenidos->nresultados>0 ) {
			while($_row_ = $this->m_ttiposcontenidos->Fetch($this->m_ttiposcontenidos->resultados) ) {
				$tipocontenido = new CTipoContenido( $_row_ );				
				$this->m_Str2IntArray[$tipocontenido->m_tipo] = $tipocontenido->m_id;
				$this->m_Int2StrArray[$tipocontenido->m_id] = $tipocontenido->m_tipo;								
			}
		}
		$this->m_ttiposcontenidos->Close();
					
		parent::CErrorHandler();
	}	
	
	function TipoContenidoExists( $__tipo__) {
		$this->m_ttiposcontenidos->LimpiarSQL();
		$this->m_ttiposcontenidos->FiltrarSQL( 'TIPO', '', trim($__tipo__) );
		$this->m_ttiposcontenidos->Open();
		if ($this->m_ttiposcontenidos->nresultados>0) {
			return true;
		} else return false;
	}
	
	function CrearTipoContenido( &$__CTipoContenido__) {
		if ( !$this->TipoContenidoExists($__CTipoContenido__->m_tipo) && $__CTipoContenido__->m_tipo!="" ) {
			
			$_exito_ = $this->m_ttiposcontenidos->InsertarRegistro( $__CTipoContenido__->FullArray() );
					
			if ($_exito_) {
				$__CTipoContenido__->m_id = $this->m_ttiposcontenidos->lastinsertid;
				return true;
			} 
		} else {
			if ($__CTipoContenido__->m_tipo!="") ShowError("Tipo de contenido: ".$__CTipoContenido__->m_tipo." already exists!");
		}
		return false;
	}
	
	/**
	 * Devuelve el objeto cuyo identificador de tipo de contenido es $__id_tipocontenido__ 
	 *
	 * @param Integer $__id_tipocontenido__  identificador de tipo de contenido
	 * @return CTipoContenido o null si no lo encontró
	 */
	function GetTipoContenido( $__id_tipocontenido__, $__tipo__="") {
			
		$this->m_ttiposcontenidos->LimpiarSQL();			
	    if ($__id_tipocontenido__>0) $this->m_ttiposcontenidos->FiltrarSQL('ID','',$__id_tipocontenido__);
	    if ($__tipo__!='') $this->m_ttiposcontenidos->FiltrarSQL('TIPO','',trim($__tipo__));
	    $this->m_ttiposcontenidos->Open();		
		
		if ( $this->m_ttiposcontenidos->nresultados>0 ) {		
			$_row_ = $this->m_ttiposcontenidos->Fetch();
			$TipoContenido = new CTipoContenido($_row_);
			return $TipoContenido;			
		}	
		
		return null;
		
	}
	
	/**
	* /if spanish Obtiene el nombre del tipo de contenido pasandole el valor /else
	* Get the type /end
	* 
	*/
	function GetTipo( $__id_tipocontenido__ ) {
		return $this->m_Int2StrArray[ $__id_tipocontenido__ ];		
	}
	
	function EliminarDetalles( $__id_contenido__ ) {
		return $this->m_CDetalles->EliminarDetalles( $__id_contenido__ );		
	}
	
	/**
	 * Fija la plantilla para las vistas de datos colapsados.
	 * 
	 * Esta plantilla tiene formato html y sigue una syntaxis muy sencilla
	 * cada campo se referencia de esta manera entre estrellas:
	 * 				
	 * 				 *NOMBRE_CAMPO*
	 * 
	 * y cada detalle entre estrellas y numerales
	 * 
	 * 				*#NOMBRE_DETALLE#*
	 * 
	 * A su vez las plantillas están preparadas para multidioma y dependen de los términos
	 * definidos en el archivo /inc/lang/languages.csv , y se referencia entre llaves
	 * 
	 *				{TERMINO_MULTIDIOMA}
	 * 
	 * las funciones o campos con algun tratamiento en especial se referencia entre corchetes
	 * 
	 * 				[CASO_ESPECIAL]
	 * 
	 * 
	 * las plantillas predeterminadas se nombran segun el tipo de contenido
	 * FICHA_MI_CONTENIDO.colapsado.html
	 * FICHA_MI_CONTENIDO.resumen.html
	 * FICHA_MI_CONTENIDO.completo.html
	 * FICHA_MI_CONTENIDO.edicion.html
	 * FICHA_MI_CONTENIDO.user.html
	 * 
	 * en caso de ser multidioma, se pueden usar las llaves {NOMBRECAMPO}
	 * o crear templates personalizados para cada idioma, agregando el codigo del idioma al nombre de la plantilla
	 * 
	 * si el codigo es EN para inglés, la plantilla se nombra de esta manera en el directorio de /inc/templates/
	 * 
	 * FICHA_MI_CONTENIDO.colapsado.EN.html
	 * 
	 * 
	 *
	 * @param Integer $__tipocontenido__ id del tipo de contenido
	 * @param Text $__template__  es la plantilla en html
	 */
	function SetTemplateColapsado($__tipocontenido__,$__template__="") {
		global $__lang__;
		if ($__lang__!="") $l = $__lang__."."; else $l = "";
		
		$file = "../../inc/templates/".$this->m_Int2StrArray[$__tipocontenido__].".colapsado.".$l."html";
		if (!file_exists($file)) $file = "../../inc/templates/".$this->m_Int2StrArray[$__tipocontenido__].".colapsado.html";
		if (!file_exists($file)) $file = "../../inc/templates/CONTENIDO.colapsado.html";

		if ( $__template__ == "" ) $__template__ = implode('', file($file));
		if ($__template__=="") $__template__ = "*TITULO*".DebugError("CTiposContenidos::SetTemplateColapsado >>> no template colapsado o vacio:".$this->m_Int2StrArray[$__tipocontenido__]." file:".$file);
		$this->m_templatescolapsados[$__tipocontenido__] = $__template__;	
	}

	/**
	 * Fija la plantilla para las vistas de datos resumidos.
	 *
	 * @param Integer $__tipocontenido__ id del tipo de contenido
	 * @param Text $__template__  es la plantilla en html
	 */
	function SetTemplateResumen($__tipocontenido__,$__template__="") {
		global $__lang__;
		if ($__lang__!="") $l = $__lang__."."; else $l = "";		
		
		$file = "../../inc/templates/".$this->m_Int2StrArray[$__tipocontenido__].".resumen.".$l."html";
		if (!file_exists($file)) $file = "../../inc/templates/".$this->m_Int2StrArray[$__tipocontenido__].".resumen.html";
		if (!file_exists($file)) $file = "../../inc/templates/CONTENIDO.resumen.html";

		if ( $__template__ == "" ) $__template__ = implode('', file($file));	
			
		if ($__template__=="") $__template__ = "*TITULO*".DebugError("CTiposContenidos::SetTemplateResumen >>> no template resumen o vacio:".$this->m_Int2StrArray[$__tipocontenido__]." file:".$file);
		$this->m_templatesresumenes[$__tipocontenido__] = $__template__;	
	}
	
	/**
	 * Fija el limite del texto correspondiente al campo definido por $__field__
	 *
	 * @param Integer $__tipocontenido__ identificador del tipo de contenido
	 * @param Text $__field__ campo
	 * @param Integer $__limit__	limite un numero entre 0 y N
	 */
	function SetTemplateResumenLimit($__tipocontenido__,$__field__,$__limit__) {
		$this->m_templatesresumenes_limits[$__tipocontenido__] = array($__field__=>$__limit__);
	}
	
	/**
	 * Fija la plantilla para la vista completa.
	 *
	 * @param Integer $__tipocontenido__ id del tipo de contenido
	 * @param Text $__template__  es la plantilla en html
	 */
	function SetTemplateCompleto($__tipocontenido__,$__template__="") {
		global $__lang__;
		if ($__lang__!="") $l = $__lang__."."; else $l = "";		
		
		$file = "../../inc/templates/".$this->m_Int2StrArray[$__tipocontenido__].".completo.".$l."html";
		if (!file_exists($file)) $file = "../../inc/templates/".$this->m_Int2StrArray[$__tipocontenido__].".completo.html";
		if (!file_exists($file)) $file = "../../inc/templates/CONTENIDO.completo.html";

		if ( $__template__ == "" ) $__template__ = implode('', file($file));		
		
		if ($__template__=="") $__template__ = "*TITULO*".DebugError("CTiposContenidos::SetTemplateCompleto >>> no template completo o vacio:".$this->m_Int2StrArray[$__tipocontenido__]." file:".$file);
		$this->m_templatescompletos[$__tipocontenido__] = $__template__;	
	}	

	/**
	 * Fija la plantilla para la vista a editar.
	 *
	 * @param Integer $__tipocontenido__ id del tipo de contenido
	 * @param Text $__template__  es la plantilla en html
	 * @param Text $__htmlcopete__  "html" si el copete es editable como HTML o "txt" si solo será texto plano sin formato
	 * @param Text $__htmlcuerpo__  "html" si el copete es editable como HTML o "txt" si solo será texto plano sin formato
	 */
	function SetTemplateEdicion($__tipocontenido__,$__template__="",$__htmlcopete__="",$__htmlcuerpo__="") {
		global $__lang__;
		if ($__lang__!="") $l = $__lang__."."; else $l = "";
		if ($__template__=="") {
			$file = "../../inc/templates/".$this->m_Int2StrArray[$__tipocontenido__].".edicion.".$l."html";
			
			if (!file_exists($file)) $file = "../../inc/templates/".$this->m_Int2StrArray[$__tipocontenido__].".edicion.html";
			if (!file_exists($file)) $file = "../../inc/templates/CONTENIDO.edicion.".$l."html";
			if (!file_exists($file)) $file = "../../inc/templates/CONTENIDO.edicion.html";
			
			$__template__ = implode('', file($file));			
		}
		if ($__template__=="") $__template__ = "*TITULO*".DebugError("CTiposContenidos::SetTemplateEdicion >>> no template edicion");
				
		$this->m_templatesedicion[$__tipocontenido__]["template"] = $__template__;
		$this->m_templatesedicion[$__tipocontenido__]["maxtitulo"] = "";
		$this->m_templatesedicion[$__tipocontenido__]["maxcopete"] = "";
		$this->m_templatesedicion[$__tipocontenido__]["maxcuerpo"] = "";		
		$this->m_templatesedicion[$__tipocontenido__]["htmlcopete"] = $__htmlcopete__;
		$this->m_templatesedicion[$__tipocontenido__]["htmlcuerpo"] = $__htmlcuerpo__;
		
	}
	
	/**
	 * Fija la plantilla para la vista a editar para subcontenidos.
	 *
	 * @param Integer $__tipocontenido__ id del tipo de contenido
	 * @param Text $__template__  es la plantilla en html
	 * @param Text $__htmlcopete__  "html" si el copete es editable como HTML o "txt" si solo será texto plano sin formato
	 * @param Text $__htmlcuerpo__  "html" si el copete es editable como HTML o "txt" si solo será texto plano sin formato
	 */
	function SetTemplateSubEdicion($__tipocontenido__,$__template__="",$__htmlcopete__="",$__htmlcuerpo__="") {
		global $__lang__;
		if ($__lang__!="") $l = $__lang__."."; else $l = "";
		if ($__template__=="") {
			$file = "../../inc/templates/".$this->m_Int2StrArray[$__tipocontenido__].".edicion.".$l."html";
			
			if (!file_exists($file)) $file = "../../inc/templates/".$this->m_Int2StrArray[$__tipocontenido__].".edicion.html";
			if (!file_exists($file)) $file = "../../inc/templates/SUB_CONTENIDO.edicion.".$l."html";
			if (!file_exists($file)) $file = "../../inc/templates/SUB_CONTENIDO.edicion.html";
			
			$__template__ = implode('', file($file));			
		}
		if ($__template__=="") $__template__ = "*TITULO*".DebugError("CTiposContenidos::SetTemplateEdicion >>> no template edicion");
				
		$this->m_templatesedicion[$__tipocontenido__]["template"] = $__template__;
		$this->m_templatesedicion[$__tipocontenido__]["maxtitulo"] = "";		
		$this->m_templatesedicion[$__tipocontenido__]["maxcopete"] = "";
		$this->m_templatesedicion[$__tipocontenido__]["maxcuerpo"] = "";				
		$this->m_templatesedicion[$__tipocontenido__]["htmlcopete"] = $__htmlcopete__;
		$this->m_templatesedicion[$__tipocontenido__]["htmlcuerpo"] = $__htmlcuerpo__;
		
	}	

	/**
	 * Fija la plantilla para la vista a editar por los usuarios (WEB 2.0).
	 *
	 * @param Integer $__tipocontenido__ id del tipo de contenido
	 * @param Text $__template__  es la plantilla en html
	 * @param Text $__htmlcopete__  "html" si el copete es editable como HTML o "txt" si solo será texto plano sin formato
	 * @param Text $__htmlcuerpo__  "html" si el copete es editable como HTML o "txt" si solo será texto plano sin formato
	 */	
	function SetTemplateEdicionUsuario($__tipocontenido__,$__template__="",$__htmlcopete__="",$__htmlcuerpo__="") {
		global $__lang__;
		if ($__lang__!="") $l = $__lang__."."; else $l = "";
		if ($__template__=="") {
			$file = "../../inc/templates/".$this->m_Int2StrArray[$__tipocontenido__].".user.".$l."html";
			if (!file_exists($file))	$file = "../../inc/templates/".$this->m_Int2StrArray[$__tipocontenido__].".user.html";
			if (!file_exists($file)) $file = "../../inc/templates/CONTENIDO.edicion.html";
			$__template__ = implode('', file($file));			
		}
		
		if ($__template__=="") $__template__ = "*TITULO*".DebugError("CTiposContenidos::SetTemplateEdicionUsuario >>> no template user");
				
		$this->m_templatesedicion[$__tipocontenido__]["template"] = $__template__;
		$this->m_templatesedicion[$__tipocontenido__]["maxtitulo"] = "";		
		$this->m_templatesedicion[$__tipocontenido__]["maxcopete"] = "";
		$this->m_templatesedicion[$__tipocontenido__]["maxcuerpo"] = "";				
		$this->m_templatesedicion[$__tipocontenido__]["htmlcopete"] = $__htmlcopete__;
		$this->m_templatesedicion[$__tipocontenido__]["htmlcuerpo"] = $__htmlcuerpo__;
		
	}	

	/**
	 * Fija la plantilla para la vista de consulta en el administrador de contenidos /admin.
	 *
	 * @param Integer $__tipocontenido__ id del tipo de contenido
	 * @param Text $__template__  es la plantilla en html
	 */
	function SetTemplateConsulta($__tipocontenido__,$__template__="") {
		global $__lang__;
		if ($__lang__!="") $l = $__lang__."."; else $l = "";
		if ($__template__=="") {
			$file = "../../inc/templates/".$this->m_Int2StrArray[$__tipocontenido__].".consulta.".$l."html";
			if (!file_exists($file))	$file = "../../inc/templates/".$this->m_Int2StrArray[$__tipocontenido__].".consulta.html";
			if (!file_exists($file))	$file = "../../inc/templates/CONTENIDO.consulta.".$l.".html";
			if (!file_exists($file))	$file = "../../inc/templates/CONTENIDO.consulta.html";
			$__template__ = implode('', file($file));
		}
		
		if ($__template__=="") $__template__ = "*TITULO*".DebugError("CTiposContenidos::SetTemplateConsulta >>> no template consulta");
		
		$this->m_templatesconsulta[$__tipocontenido__] = $__template__;	
	}

	/**
	 * Fija la plantilla para la vista de subcontenidos como consulta en el administrador de contenidos /admin.
	 *
	 * @param Integer $__tipocontenido__ id del tipo de contenido
	 * @param Text $__template__  es la plantilla en html
	 */
	function SetTemplateSubConsulta($__tipocontenido__,$__template__="") {
		global $__lang__;
		if ($__lang__!="") $l = $__lang__."."; else $l = "";
		if ($__template__=="") {
			$file = "../../inc/templates/".$this->m_Int2StrArray[$__tipocontenido__].".consulta.".$l."html";
			if (!file_exists($file))	$file = "../../inc/templates/".$this->m_Int2StrArray[$__tipocontenido__].".consulta.html";
			if (!file_exists($file))	$file = "../../inc/templates/SUB_CONTENIDO.consulta.".$l.".html";
			if (!file_exists($file))	$file = "../../inc/templates/SUB_CONTENIDO.consulta.html";
			$__template__ = implode('', file($file));
		}
		
		if ($__template__=="") $__template__ = "*TITULO*".DebugError("CTiposContenidos::SetTemplateConsulta >>> no template consulta");
		
		$this->m_templatesconsulta[$__tipocontenido__] = $__template__;	
	}	
	
	/**
	 * Reemplazos en todas las plantillas de tipo resumen, ideal para traducciones o cambios genéricos.
	 *
	 * @param Text $__search__  texto a buscar
	 * @param Text $__replace__ texto de reemplazo
	 */
	function UpdateTemplatesResumenes( $__search__, $__replace__) {
		foreach($this->m_templatesresumenes as $key=>$template) {
			$this->m_templatesresumenes[$key] = str_replace($__search__,$__replace__,$template);
		}
	}	
	
	/**
	 * Reemplazos en todas las plantillas de tipo completo, ideal para traducciones o cambios genéricos.
	 *
	 * @param Text $__search__  texto a buscar
	 * @param Text $__replace__ texto de reemplazo
	 */
	function UpdateTemplatesCompletos( $__search__, $__replace__) {
		foreach($this->m_templatescompletos as $key=>$template) {
			$this->m_templatescompletos[$key] = str_replace($__search__,$__replace__,$template);
		}
	}
	
	/**
	 * Reemplazos en todas las plantillas de tipo colapsado, ideal para traducciones o cambios genéricos.
	 *
	 * @param Text $__search__  texto a buscar
	 * @param Text $__replace__ texto de reemplazo
	 */
	function UpdateTemplatesColapsados( $__search__, $__replace__) {
		foreach($this->m_templatescolapsados as $key=>$template) {
			$this->SetTemplateColapsado($key,str_replace($__search__,$__replace__,$template) );
		}
	}	
	
	/**
	 * Procesa el contenido dado a través de la plantilla asociada, en el modo completo.
	 * Todos los campos son procesados.
	 *
	 * @param CContenido $__CContenido__
	 * @param Text $__template__
	 * @return Text el texto resultante del procesamiento de la plantilla
	 */
	function TextoCompleto( &$__CContenido__,$__template__='') {
		global $__lang__;		

		if ($__template__=='' && isset($this->m_templatescompletos[$__CContenido__->m_id_tipocontenido])) $__template__ = $this->m_templatescompletos[$__CContenido__->m_id_tipocontenido];
		if ($__template__=='') $__template__ = "*TITULO*".DebugError("CTiposContenidos::TextoCompleto >> Template is empty!!"); 
		
		
		/*EL CUERPO SOLO SE MUESTRA EN EL CONTENIDO COMPLETO*/		
		if (
						is_numeric( strpos( strtolower($__CContenido__->Cuerpo()), "<br" ) ) 
				|| is_numeric( strpos( strtolower($__CContenido__->Cuerpo()), "<p" ))
				|| is_numeric( strpos( strtolower($__CContenido__->Cuerpo()), "<div" ))
				) {
			//$__template__ = str_replace("*CUERPO*",CloseHtml($__CContenido__->Cuerpo()),$__template__);
			$__template__ = str_replace("*CUERPO*",$__CContenido__->Cuerpo(),$__template__);
		} else {		
			$__template__ = str_replace("*CUERPO*",str_replace( "\n", "<br/>", $__CContenido__->Cuerpo() ),$__template__);
		}
		
		$__template__ = PanelEdit($__CContenido__->m_id).$__template__;
		
		
		/*TODOS DATOS POSIBLES ADICIONALES*/
		//$__template__ = str_replace("*EMPRESA*",$__CContenido__->m_empresa,$__template__);
		
		if (is_array($__CContenido__->m_specials)) {
			foreach($__CContenido__->m_specials as $field=>$value) {
				$__template__ = str_replace("{".$field."}",$value,$__template__);												
			}					
		}

		$__template__ = $this->TextoColapsado( $__CContenido__, $__template__ );

		/*REFORMATEAMOS LOS LINKS POR LAS DUDAS*/
		ReformatLinks($__template__);
		return $__template__;
												
	}

	/**
	 * Procesa el contenido dado a través de la plantilla asociada, en el modo colapsado.
	 * Solo los siguientes campos son procesados....
	 * TITULO,ID_SECCION,ID_CONTENIDO,ID_TIPOCONTENIDO,ID_USUARIO_CREADOR,FECHA_ALTA,FECHA_BAJA,FECHAEVENTO
	 * PRINCIPAL, COPETE, NOMBRE (usuario creador)
	 *
	 * @param CContenido $__CContenido__
	 * @param Text $__template__
	 * @return Text el texto resultante del procesamiento de la plantilla
	 */
	function TextoColapsado( &$__CContenido__,$__template__='') {
		if ($__template__=='' && isset($this->m_templatescolapsados[$__CContenido__->m_id_tipocontenido])) $__template__ = $this->m_templatescolapsados[$__CContenido__->m_id_tipocontenido];
		if ($__template__=='') $__template__ = "*TITULO*".DebugError("CTiposContenidos::TextoColapsado >> Template is empty!!"); 
		
		$subcopete_len = 350;
		
		$subcopete = trim(substr(str_replace(array("<br>","<br/>"),array("",""),strip_tags($__CContenido__->Copete()) ), 0, $subcopete_len ));
		if ( strlen($__CContenido__->Copete()) > $subcopete_len ) $subcopete.= "[VERMAS](*TITULO:URL*,*ID_TIPOCONTENIDO*)";
		
		global $__modulo__;
		global $CFun;
		
		$__template__ = str_replace( array("[FICHA]"),$this->m_Int2StrArray[$__CContenido__->m_id_tipocontenido],$__template__);
		$__template__ = str_replace( array("[MODULO]"),$__modulo__,$__template__);
		
		$_CSeccion_ = new CSeccion();
		$_CSeccion_->m_nombre = $__CContenido__->m_seccion_nombre;
		$_CSeccion_->m_ml_nombre = $__CContenido__->m_seccion_ml_nombre;
		//reduce consecutive space characters to single space character
		$cpt_str = preg_replace('!\s+!', ' ', str_replace("&nbsp;","",$__CContenido__->Copete()) );
		
		/*EL CUERPO SOLO SE MUESTRA EN EL CONTENIDO COMPLETO*/
		if (
						is_numeric( strpos( strtolower($__CContenido__->Copete()), "<br" ) ) 
				|| is_numeric( strpos( strtolower($__CContenido__->Copete()), "<p" ) )
				|| is_numeric( strpos( strtolower($__CContenido__->Copete()), "<div" ) )
				) {
			$__template__ = str_replace("*COPETE*", CloseHtml($cpt_str), $__template__);
		} else {
			$__template__ = str_replace("*COPETE*",str_replace( "\n", "<br/>", $cpt_str ),$__template__);
		}		
		
		
		
		$__template__ = str_replace("*COPETE:TXT*", strip_tags( $cpt_str), $__template__);
		$__template__ = str_replace("*COPETE:HTML*", CloseHtml($cpt_str), $__template__);
		$__template__ = str_replace("*COPETE:SUB*", $subcopete, $__template__);
		
		$__template__ = str_replace( array("*IDSECCION*","*ID_SECCION*"),$__CContenido__->m_id_seccion,$__template__);
		$__template__ = str_replace( array("*SECCION:NOMBRE*","*SECCION:NOMBREURL*"),array( $_CSeccion_->Nombre(), $_CSeccion_->NombreURL() ) ,$__template__);
		$__template__ = str_replace( array("*IDCONTENIDO*","*ID*"),$__CContenido__->m_id,$__template__);
		
		$__template__ = str_replace(array("*ID_CONTENIDO*"),$__CContenido__->m_id_contenido,$__template__);
		
		$__template__ = str_replace(array("*ID_CONTENIDO*"),$__CContenido__->m_padre_titulo ,$__template__);
		$__template__ = str_replace(array("*ID_CONTENIDO:ID*","*ID_CONTENIDO*"),$__CContenido__->m_padre_id,$__template__);
		$__template__ = str_replace(array("*ID_CONTENIDO:TITULO*"),$__CContenido__->m_padre_titulo,$__template__);

		$__template__ = str_replace("*PRINCIPAL*",$__CContenido__->m_principal,$__template__);
		$__template__ = str_replace(array("*IDUSUARIO*","*ID_USUARIO_CREADOR*"),$__CContenido__->m_id_usuario_creador,$__template__);
		$__template__ = str_replace(array("*IDEDITOR*","*ID_USUARIO_MODIFICADOR*"),$__CContenido__->m_id_usuario_modificador,$__template__);
		$__template__ = str_replace("*NOMBRE*",$__CContenido__->m_nombre." ".$__CContenido__->m_apellido,$__template__);
		$__template__ = str_replace("*EDITOR*",$__CContenido__->m_nombre_editor." ".$__CContenido__->m_apellido_editor,$__template__);
		$__template__ = str_replace("*AUTOR*",$__CContenido__->Autor(),$__template__);
		$__template__ = str_replace( array("*IDTIPOCONTENIDO*","*ID_TIPOCONTENIDO*"),$__CContenido__->m_id_tipocontenido,$__template__);
		$__template__ = str_replace( array("*IDTIPOCONTENIDO:TIPO*","*ID_TIPOCONTENIDO:TIPO*"),$this->m_Int2StrArray[$__CContenido__->m_id_tipocontenido],$__template__);
		$__template__ = str_replace( array("*IDTIPOCONTENIDO:FICHA_TIPO*","*ID_TIPOCONTENIDO:FICHA_TIPO*"),substr( $this->m_Int2StrArray[$__CContenido__->m_id_tipocontenido],6 ),$__template__);								
		$__template__ = str_replace("*COPETE:TITLE*",str_replace(array('"',"<br>","<br/>"),array("'","",""),$__CContenido__->Copete()),$__template__);
		$__template__ = str_replace("*COPETE:TXT*",str_replace("\n","<br>",$__CContenido__->Copete()),$__template__);
		$__template__ = str_replace("*TITULO*",$__CContenido__->Titulo(),$__template__);				
		$__template__ = str_replace("*TITULO:URL*",$__CContenido__->TituloURL(),$__template__);		
		$__template__ = str_replace("*TITULO:TITLE*",$__CContenido__->TituloTitle(),$__template__);
		
		$__template__ = str_replace(array("*BAJA:FULL*","*FECHABAJA:FULL*"),$__CContenido__->m_fechabaja, $__template__);
		$__template__ = str_replace(array("*ALTA:FULL*","*FECHAALTA:FULL*"),$__CContenido__->m_fechaalta, $__template__);
		$__template__ = str_replace(array("*FECHAEVENTO:FULL*"),$__CContenido__->m_fechaevento, $__template__);
		$__template__ = str_replace(array("*ACTUALIZACION:FULL*"),$__CContenido__->m_actualizacion, $__template__);
		
		$__template__ = str_replace(array("*ALTA*","*FECHAALTA*"),Fecha($__CContenido__->m_fechaalta,"ddmmyyyy")." ".Hora($__CContenido__->m_fechaalta,"hh:mm"),$__template__);
		$__template__ = str_replace(array("*BAJA*","*FECHABAJA*"),Fecha($__CContenido__->m_fechabaja,"ddmmyyyy")." ".Hora($__CContenido__->m_fechabaja,"hh:mm"),$__template__);
		$__template__ = str_replace(array("*ALTA DIA*","*FECHAALTA DIA*","*FECHAALTA:DIA*"),Fecha($__CContenido__->m_fechaalta,"ddmmyyyy"),$__template__);						
		$__template__ = str_replace(array("*ALTA HORA*","*FECHAALTA HORA*","*FECHAALTA:HORA*"),Hora($__CContenido__->m_fechaalta,"hh:mm"),$__template__);						
		$__template__ = str_replace(array("*BAJA DIA*","*FECHABAJA DIA*","*FECHABAJA:DIA*"),Fecha($__CContenido__->m_fechabaja,"ddmmyyyy"),$__template__);						
		$__template__ = str_replace(array("*BAJA HORA*","*FECHABAJA HORA*","*FECHABAJA:HORA*"),Hora($__CContenido__->m_fechabaja,"hh:mm"),$__template__);				
		$__template__ = str_replace(array("*FECHAEVENTO DIA*","*FECHAEVENTO:DIA*","*FECHAEVENTO:DIA*"),Fecha($__CContenido__->m_fechaevento,"ddmmyyyy"),$__template__);
		$__template__ = str_replace(array("*FECHAEVENTO HORA*","*EVENTO HORA*","*FECHAEVENTO:HORA*"),Hora($__CContenido__->m_fechaevento,"hh:mm"),$__template__);				
		$__template__ = str_replace(array("*FECHAEVENTO*"),Fecha($__CContenido__->m_fechaevento,"ddmmyyyy")." ".Hora($__CContenido__->m_fechaevento,"hh:mm"),$__template__);
		$__template__ = str_replace(array("*ACTUALIZACION*"),Fecha($__CContenido__->m_actualizacion,"ddmmyyyy")." ".Hora($__CContenido__->m_actualizacion,"hh:mm"),$__template__);	
		
		$this->m_CDetalles->MostrarDetallesColapsados($__CContenido__->m_id,$__template__);
		
		if (is_object($CFun)) {
			$CFun->Process($__template__);
		}
		
		ReformatLinks($__template__);
		return $__template__;
	}	
	
	function MostrarColapsado( &$__CContenido__, $__template__='') {
		echo $this->TextoColapsado( $__CContenido__, $__template__ );
		
	}

	/**
	 * Procesa el contenido dado a través de la plantilla asociada, en el modo resumen.
	 * El campo copete es procesado además de los de la version colapsado
	 *
	 * @param CContenido $__CContenido__
	 * @param Text $__template__
	 * @return Text el texto resultante del procesamiento de la plantilla
	 */
	
	function TextoResumen( &$__CContenido__,$__template__='') {
		global $_DIR_SITEABS;
		$limcopete = 16000; ///maximo del campo permitido
		if (is_array($this->m_templatesresumenes_limits[$__CContenido__->m_id_tipocontenido])) {
			foreach($this->m_templatesresumenes_limits[$__CContenido__->m_id_tipocontenido] as $field=>$limit)	{
				if ($field=="COPETE") $limcopete = $limit;
			}
		}
		//reduce consecutive space characters to single space character
		$cpt_str = preg_replace('!\s+!', ' ', str_replace("&nbsp;","",$__CContenido__->Copete() ) );
		$copete = substr( $cpt_str,0,$limcopete );		

		if ($__template__=='' && isset($this->m_templatesresumenes[$__CContenido__->m_id_tipocontenido])) $__template__ = $this->m_templatesresumenes[$__CContenido__->m_id_tipocontenido];
		if ($__template__=='') $__template__ = "*TITULO*".DebugError("CTiposContenidos::TextoResumen >> Template is empty!!"); 
		
		
		$__template__ = str_replace("*COPETE*", $copete, $__template__);
		$__template__ = str_replace("*COPETE:TXT*", str_replace("\n","<br>",$copete) , $__template__);
		
		$__template__ = $this->TextoColapsado($__CContenido__,$__template__);			
		return $__template__;
	}

	function MostrarResumen( &$__CContenido__,$__template__='') {
		global $CMultiLang;
		$texto = $this->TextoResumen( $__CContenido__, $__template__ );
		$CMultiLang->Translate( $texto );		
		echo $texto;	
	}
	
	function MostrarCompleto( &$__CContenido__, $__template__='' ) {
		global $CMultiLang;
		$texto = $this->TextoCompleto( $__CContenido__, $__template__ );
		$CMultiLang->Translate( $texto );		
		echo $texto;		
	}
	
	function TextoConsulta(&$__CContenido__,$__template__='') {
		if ($__template__=='' && isset($this->m_templatesconsulta[$__CContenido__->m_id_tipocontenido])) $__template__ = $this->m_templatesconsulta[$__CContenido__->m_id_tipocontenido];
		if ($__template__=='') $__template__ = "*TITULO*".DebugError("CTiposContenidos::TextoResumen >> Template is empty!!"); 
		
		$__template__ = str_replace("*ORDEN*",$__CContenido__->m_orden,$__template__);				
		$__template__ = str_replace(array("*NICK*","*USUARIO:NICK*"),$__CContenido__->m_nick,$__template__);
		$__template__ = str_replace("*EDITOR:NICK*",$__CContenido__->m_nick_editor,$__template__);		
		$__template__ = str_replace("*BAJA*",$__CContenido__->m_baja,$__template__);
		
		$__template__ = $this->TextoColapsado( $__CContenido__, $__template__ );		
				
		if (is_array($__CContenido__->m_specials)) {
			foreach($__CContenido__->m_specials as $field=>$value) {
				$__template__ = str_replace("{".$field."}",$value,$__template__);												
			}					
		}
		ReformatLinks($__template__);
		return $__template__;										
		
	}	
	
	function MostrarConsulta(&$__CContenido__,$__template__='') {
		global $CMultiLang;
		$texto = $this->TextoConsulta( $__CContenido__, $__template__ );
		$CMultiLang->Translate( $texto );
		echo $texto;
	}

	
	function NuevoContenido( $__id_tipocontenido__, &$__CContenido__ ) {
		
		$__CContenido__->m_detalles = $this->m_CDetalles->NuevosDetalles( $__id_tipocontenido__, $__CContenido__ );
		
	}
	
	/**
	 * Crea los detalles para completar la ficha de contenido
	 *
	 * @param CContenido $__CContenido__ objeto de base de este contenido
	 */
	function CrearCompleto( &$__CContenido__ ) {
		
		$__CContenido__->m_detalles = $this->m_CDetalles->CrearDetallesCompletos( $__CContenido__ );
		
	}

	/**
	 * Trae de la base los detalles correspondiente al contenido referenciado
	 *
	 * @param CContenido $__CContenido__ objeto de base de este contenido
	 */
	function GetCompleto( &$__CContenido__ ) {
		
		$__CContenido__->m_detalles = $this->m_CDetalles->GetDetallesCompletos( $__CContenido__->m_id );
		
		
	}
	
	function EditarDetalle( &$__CContenido__, &$__CTipoDetalle__, $__action__, $template = "" ) {
		global $CLang;
		global $CMultiLang;
		
		$t_td = $this->m_CDetalles->m_CTiposDetalles->m_ttiposdetalles;
		$t_d = $this->m_CDetalles->m_tdetalles;
	
		$resstr = '';
		
		if ($template=="") {
			$resstr.= '<div class="MAD_EDIT_DETALLE">';
		}
		
		//ShowMessage( "CTiposContenidos::EditarDetalle > ".$__CTipoDetalle__->m_descripcion );
		
		/*
		$t_td->LimpiarSQL();		
		$t_td->FiltrarSQL('ID_TIPOCONTENIDO','',$__CContenido__->m_id_tipocontenido);
		$t_td->Open();
		
		if ($t_td->nresultados > 0) {//por cada tipo de detalles iteramos
			//imprimos los campos a editar....
			while ($row_tiposdetalles = $t_td->Fetch()) {
			*/
				//imprimimos el nombre del campo (TIPO) por cada Tipo de detalle
				$inputs = "";
				if ($template=="") {
					$resstr.= '<div class="MAD_EDIT_DET '.$__CTipoDetalle__->m_tipo.'">';
					$resstr.= '<div class="MAD_EDIT_DET_TIT">
							<label class="MAD_EDIT_DET_TIT">'.$__CTipoDetalle__->m_descripcion.'</label>
							</div>';
				}
				 
				if (($__action__=='modificar') or ($__action__=='borrar')) { //busca los reg. de detalles existentes para el contenido 
					//$this->Detalles->m_tdetalles->debug='si';	
	  				$t_d->LimpiarSQL();
		      		$t_d->FiltrarSQL( 'ID_TIPODETALLE', '', $__CTipoDetalle__->m_id );
		      		$t_d->FiltrarSQL( 'ID_CONTENIDO', '', $__CContenido__->m_id );
		      		$t_d->Open();
	    		} elseif ($__action__=='nuevo') {
	    			$t_d->nresultados = 0;
	    		}
				
				$row_detalles = "";

				$CTipoDetalle = $__CTipoDetalle__;
				if ($template=="") {
					$resstr.= '<div class="MAD_EDIT_DET_FIELD">';
				}
				
				if ($t_d->nresultados > 0) { //MODIFICAR 						
					$row_detalles = $t_d->Fetch();						
					$CDetalle = new CDetalle($row_detalles);						
					//la accion
					$inputs.= '<input name="_adetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" value="modificar">';
					//el id del detalle
					$inputs.= '<input name="_idetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" value="'.$CDetalle->m_id.'">';								
				} else {
					$CDetalle = new CDetalle(array(	'detalles.ID'=>'',
													'detalles.ID_TIPODETALLE'=>$CTipoDetalle->m_id,
													'detalles.ENTERO'=>0,
													'detalles.FRACCION'=>0,
													'detalles.DETALLE'=>'',
													'detalles.ML_DETALLE'=>'',
													'detalles.TXTDATA'=>'',
													'detalles.ML_TXTDATA'=>'',
													'detalles.BINDATA'=>'',
													'detalles.ID_USUARIO_CREADOR'=>'1',
													'detalles.ID_USUARIO_MODIFICADOR'=>'1',
													'detalles.ACTUALIZACION'=>'NOW()',
													'detalles.BAJA'=>'N'));
					
					$inputs.= '<input name="_adetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" value="insertar">';					
					$inputs.= '<input name="_idetalle_'.$CTipoDetalle->m_tipo.'" type="hidden" value="">';					
					
				}

				
				
				$result = $this->m_CDetalles->Edit( $CDetalle, $CTipoDetalle );				
				//if ($__CTipoDetalle__->m_tipo=="USUARIO_REDES") ShowMessage( "CTiposContenidos::EditarDetalle a CDetalles->Edit > ".$__CTipoDetalle__->m_descripcion." <textarea>".$result."</textarea>" );
				$inputs.= $result;				
				
				if ($template=="") {
					$resstr.= $inputs;								
					$resstr.= '</div>';
					$resstr.= '</div>';
				} else {
					
					$on = is_numeric( strpos( $template, "*#".$CTipoDetalle->m_tipo."#*" ) );					
					
					//$this->m_detalles_on["*#".$CTipoDetalle->m_tipo."#*"] = $on;
					$this->m_detalles_on["*#".$CTipoDetalle->m_tipo."#*"]["Estado"] = $on;
					
					if ($on)
						$template = str_replace( "*#".$CTipoDetalle->m_tipo."#*", $inputs, $template );
					
				}
				/*
			}			
		}
			*/
		if ($template=="") {	
			$resstr.= '</div>';
			return $resstr;
		} else return $template;		
	}
	
	function EditarDetalles( &$__CContenido__, $__action__, $template = "" ) {
		global $CLang;
		global $CMultiLang;
		
		$t_td = $this->m_CDetalles->m_CTiposDetalles->m_ttiposdetalles;
		$t_d = $this->m_CDetalles->m_tdetalles;
	
		$resstr = '';
		if ($template=="") {
			$resstr.= '<div class="MAD_EDIT_DETALLES">';
		}
		
		$t_td->LimpiarSQL();		
		$t_td->FiltrarSQL('ID_TIPOCONTENIDO','',$__CContenido__->m_id_tipocontenido);
		$t_td->Open();			
		
		if ($t_td->nresultados > 0) {//por cada tipo de detalles iteramos
			//imprimimos los campos a editar....
			while ($row_tiposdetalles = $t_td->Fetch()) {
				
				$CTipoDetalle = new CTipoDetalle($row_tiposdetalles);
				
				$this->m_detalles_on["*#".$CTipoDetalle->m_tipo."#*"] = array( "CTD"=>$CTipoDetalle, "Estado"=>false );
				
				$inputs = $this->EditarDetalle( $__CContenido__, $CTipoDetalle, $__action__, $template );
				
				if ($template=="") {
					$resstr.= $inputs;
				} else {
					$template = $inputs;
				}
			}			
		}
		
		
		$detalles_a_completar = "";
		foreach( $this->m_detalles_on as $tipo=>$D ) {
			
			$CTipoDetalle = $D["CTD"];
			$encontrado = $D["Estado"];
			
			if (!$encontrado) {
				$detalles_a_completar.= $this->EditarDetalle( $__CContenido__, $CTipoDetalle, $__action__);		
			}
			
		}
		
		if ($detalles_a_completar!="") {
			
			if ( 	
						$template!="" 
						&&
						is_numeric( strpos( $template,"*DETALLES*" ) ) 
					) {	
				$template = str_replace( "*DETALLES*", $detalles_a_completar, $template );				
			}
			
		}
			
		if ($template=="") {	
			$resstr.= '</div>';
			return $resstr;
		} else return $template;
	}

	function ConfirmarDetalles( $__action__, $__id_tipocontenido__, $__id_contenido__ ) {

		//global $_exito_;
	
		$_exito_ = true;
		
		if ( !is_numeric($__id_tipocontenido__) || $__id_tipocontenido__<1 ) {
			DebugError("PARAMETER_MISSING");
			$this->PushError( new CError( "PARAMETER_MISSING", "id tipo contenido faltante" ) );
			$_exito_ = false;
			return $_exito_;
		}		
		
		if ( !is_numeric($__id_contenido__) || $__id_contenido__<1 ) {
			DebugError("PARAMETER_MISSING");
			$this->PushError( new CError( "PARAMETER_MISSING", "id contenido faltante" ) );
			$_exito_ = false;
			return $_exito_;
		}
		
		if ($__action__=='delete') {			
	        
			$_exito_ = $this->EliminarDetalles( $__id_contenido__ );
	        
		} else if ($__action__!='cancel') {
			
		  $this->m_CDetalles->m_CTiposDetalles->m_ttiposdetalles->LimpiarSQL();
		  $this->m_CDetalles->m_CTiposDetalles->m_ttiposdetalles->FiltrarSQL('ID_TIPOCONTENIDO','',$__id_tipocontenido__);
		  $this->m_CDetalles->m_CTiposDetalles->m_ttiposdetalles->Open();
		  
			if ($this->m_CDetalles->m_CTiposDetalles->m_ttiposdetalles->nresultados > 0) {//por cada tipodedetalle de este contenido
				//imprimos los campos a editar....
				while ($row_tiposdetalles = $this->m_CDetalles->m_CTiposDetalles->m_ttiposdetalles->Fetch()) {  
					
					$TipoDetalle = new CTipoDetalle($row_tiposdetalles);					
					$CDetalle = new CDetalle(array(
											'detalles.ID'=>$GLOBALS['_idetalle_'.$TipoDetalle->m_tipo],
											'detalles.ID_TIPODETALLE'=>$TipoDetalle->m_id,
											'detalles.ID_CONTENIDO'=>$__id_contenido__,
											'detalles.ENTERO'=>0,
											'detalles.FRACCION'=>0,
											'detalles.DETALLE'=>$GLOBALS['_edetalle_'.$TipoDetalle->m_tipo],
											'detalles.ML_DETALLE'=>$GLOBALS['_emldetalle_'.$TipoDetalle->m_tipo],
											'detalles.TXTDATA'=>$GLOBALS['_edetalle_'.$TipoDetalle->m_tipo],
											'detalles.ML_TXTDATA'=>$GLOBALS['_emldetalle_'.$TipoDetalle->m_tipo],
											'detalles.BINDATA'=>''));
					
					if ($__action__=='nuevo' || $__action__=='modificar') {
						$_exitoX_ = $this->m_CDetalles->Confirm( $CDetalle, $TipoDetalle );
						$_exito_ = $_exito_ && $_exitoX_; 
						if (!$_exitoX_) {
							DebugError("Error confirmando detalle ".$TipoDetalle->m_tipo);
						}
		      		}
		  	}
		  }
		
		}
		return $_exito_;
		
	}	
} 
?>