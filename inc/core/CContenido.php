<?php

/**
 * class CContenido
 *
 * version 4.1: 22/08/2006 [agregado m_nombre,m_apellido]
 * @version 28/11/2003
 * @copyright 2003 
 **/

class CContenido {

	var $m_id,
		$m_id_tipocontenido,
		$m_id_seccion,
		$m_id_contenido,
		$m_orden,
		$m_titulo,
		$m_ml_titulo,
		$m_url,
		$m_copete,
		$m_ml_copete,		
		$m_cuerpo,
		$m_ml_cuerpo,
		$m_palabrasclave,
		$m_ml_palabrasclave,		
		$m_autor,
		$m_icono,
		$m_id_usuario_creador,
		$m_id_usuario_modificador,
		$m_actualizacion,
		$m_baja,
		$m_principal,
		$m_fechabaja,
		$m_fechaalta,
		$m_fechaevento,
		$m_votos;
		
	var $m_padre_id,$m_padre_titulo;	
	
	var $m_nombre,
		$m_apellido,
		$m_nick;
		
	var $m_seccion_nombre,$m_seccion_ml_nombre;
		
	var $m_nombre_editor,
		$m_apellido_editor,
		$m_nick_editor;		
		
	var $m_detalles;//array
	var $m_specials;	
		
		//extendido
	var	$m_empresa;
	
	var $m_nitem;
		

	function CContenido( $__row__ = "",$__nitem__=0) {

		if ( is_numeric($__row__) && $__row__ == 0 ) {			 
			$this->SetEmpty();
		} else {
			($__row__=="") ? $this->SetFromGlobals() : $this->Set($__row__);	
		}
		
		$this->m_nitem = $__nitem__;
		
	}
	
	function Habilitado() {
		return ($this->m_baja == "S");
	}
	
	function Titulo($lang='') {
		
		global $__lang__;
		
		if ( trim($lang)=="" ) $lang = $__lang__;
		
		if ( trim($lang)=="" ) {
			return $this->m_titulo;
		} else {			
			$res = TextoML( $this->m_ml_titulo, $__lang__  );
			if ($res=="") $res = $this->m_titulo;
			return $res;		
		}
		
		return $res;
	}

	function TituloTitle($lang='') {
		
		$res = str_replace( array("_","/","'",'"'), array(" "," "," "," "), trim($this->Titulo($lang)));
		$res = strip_tags( $res );
		if ($res=="") $res = $this->m_id;
		return $res;
	}	
	
	
	function TituloURL($lang='') {
		
		$res = str_replace( array("_","/","'",'"'), array(" "," "," "," "), trim($this->Titulo($lang)));
		$res = strip_tags( $res );
		$res = urlencode($res);		
		if ($res=="") $res = $this->m_id;
		return $res;
	}	
	
	function Copete($lang='') {
		
		global $__lang__;
		
		if ( trim($lang)=="" ) $lang = $__lang__;
		
		if ( trim($lang)=="" ) {
			return $this->m_copete;
		} else {			
			$res = TextoML( $this->m_ml_copete, $__lang__  );
			if ($res=="") $res = $this->m_copete;
			return $res;		
		}
	}
	
	function CopeteStrip($lang='') {
		
		$res = $this->Copete();
		
		return strip_tags($res);
		
	}
	
	function Cuerpo($lang='') {
		
		global $__lang__;
		
		if ( trim($lang)=="" ) $lang = $__lang__;
		
		if ( trim($lang)=="" ) {
			return $this->m_cuerpo;
		} else {			
			$res = TextoML( $this->m_ml_cuerpo, $__lang__  );
			if ($res=="") $res = $this->m_cuerpo;
			return $res;		
		}
	}

	
	function PalabrasClave($lang='') {
		
		global $__lang__;
		
		if ( trim($lang)=="" ) $lang = $__lang__;
		
		if ( trim($lang)=="" ) {
			$res = $this->m_palabrasclave;
		} else {			
			$res = TextoML( $this->m_ml_palabrasclave, $__lang__  );
			if ($res=="") $res = $this->m_palabrasclave;		
		}
		return strip_tags($res);
	}
	
	function Autor() {

		if ($this->m_autor!=NULL && $this->m_autor!="" && $this->m_autor!="NULL" )
			return $this->m_autor;

		return "";
	}
	
	function SetEmpty() {
		
		$this->m_id = 0;
		$this->m_id_contenido = 1;
		$this->m_id_seccion = 0;
		$this->m_id_tipocontenido = 0;
		$this->m_id_usuario_creador = 1;
		$this->m_id_usuario_modificador = 1;
		$this->m_titulo = "";
		$this->m_ml_titulo = "";
		$this->m_copete = "";
		$this->m_ml_copete = "";
		$this->m_cuerpo = "";
		$this->m_ml_cuerpo = "";
		$this->m_palabrasclave = "";
		$this->m_ml_palabrasclave = "";		
		$this->m_autor = "";
		$this->m_icono = "";
		$this->m_actualizacion = "NOW()";
		$this->m_principal = "N";
		$this->m_fechabaja = "NOW()";
		$this->m_fechaalta = "NOW()";
		$this->m_fechaevento = "NOW()";
		$this->m_votos = 0;
		$this->m_orden = 1;	
		
	}
	
	function Get( $__field__ ) {
		switch($__field__) {
			/*TIPICOS*/
			case 'TITULO': return $this->m_titulo;
			case 'ML_TITULO': return $this->m_ml_titulo;
			case 'COPETE': return $this->m_copete;
			case 'ML_COPETE': return $this->m_ml_copete;
			case 'CUERPO': return $this->m_cuerpo;
			case 'ML_CUERPO': return $this->m_ml_cuerpo;
			case 'FECHAEVENTO': return $this->m_fechaevento;
			case 'PALABRASCLAVE': return $this->m_palabrasclave;
			case 'ML_PALABRASCLAVE': return $this->m_ml_palabrasclave;			
			case 'FECHAALTA': return $this->m_fechaalta;
			case 'FECHABAJA': return $this->m_fechabaja;
			case 'AUTOR': return $this->m_autor;
			case 'BAJA': return $this->m_baja;
			case 'ACTUALIZACION': return $this->m_actualizacion;
			case 'ID_CONTENIDO': return $this->m_id_contenido;
			/*AUTO*/
			case 'ID_TIPOCONTENIDO': return $this->m_id_tipocontenido;			
			case 'ID_USUARIO_CREADOR': return $this->m_id_usuario_creador;
			case 'ID_USUARIO_MODIFICADOR': return $this->m_id_usuario_modificador;
			case 'VOTOS': return $this->m_votos;
			case 'ORDEN': return $this->m_orden;
			case 'BAJA': return $this->m_baja;
			case 'URL': return $this->m_url;
			default:
				/*if ( is_array($this->m_detalles) ) {
					if ( is_object( $this->m_detalles[$__field__] ) ) {
						
					}	
				}*/
				if(isset($GLOBALS["_edetalle_".$__field__])) {
					return $GLOBALS["_edetalle_".$__field__];
				} else return "";
				break;
		}
		return '';
	}
	
	function Set($__row__) {
		
		$this->m_id = $__row__['contenidos.ID'];
		$this->m_id_tipocontenido = $__row__['contenidos.ID_TIPOCONTENIDO'];
		$this->m_id_seccion = $__row__['contenidos.ID_SECCION'];
		$this->m_id_contenido = $__row__['contenidos.ID_CONTENIDO'];
		$this->m_orden = $__row__['contenidos.ORDEN'];
		$this->m_titulo = $__row__['contenidos.TITULO'];
		$this->m_ml_titulo = $__row__['contenidos.ML_TITULO'];
		$this->m_url = $__row__['contenidos.URL'];
		$this->m_copete = $__row__['contenidos.COPETE'];
		$this->m_ml_copete = $__row__['contenidos.ML_COPETE'];
		$this->m_cuerpo = $__row__['contenidos.CUERPO'];
		$this->m_ml_cuerpo = $__row__['contenidos.ML_CUERPO'];
		$this->m_palabrasclave = $__row__['contenidos.PALABRASCLAVE'];
		$this->m_ml_palabrasclave = $__row__['contenidos.ML_PALABRASCLAVE'];		
		$this->m_autor = $__row__['contenidos.AUTOR'];
		$this->m_icono = $__row__['contenidos.ICONO'];
		$this->m_id_usuario_creador = $__row__['contenidos.ID_USUARIO_CREADOR'];
		$this->m_id_usuario_modificador = $__row__['contenidos.ID_USUARIO_MODIFICADOR'];
		$this->m_actualizacion = $__row__['contenidos.ACTUALIZACION'];
		$this->m_baja = $__row__['contenidos.BAJA'];
		$this->m_principal = $__row__['contenidos.PRINCIPAL'];
		$this->m_fechabaja = $__row__['contenidos.FECHABAJA'];
		$this->m_fechaalta = $__row__['contenidos.FECHAALTA'];
		$this->m_fechaevento = $__row__['contenidos.FECHAEVENTO'];
		$this->m_votos = $__row__['contenidos.VOTOS'];

		//extendido
		if (isset($__row__['usuarios.NOMBRE'])) $this->m_nombre = $__row__['usuarios.NOMBRE'];
		if (isset($__row__['usuarios.APELLIDO'])) $this->m_apellido = $__row__['usuarios.APELLIDO'];		
		if (isset($__row__['usuarios.NICK'])) $this->m_nick = $__row__['usuarios.NICK'];
		if (isset($__row__['usuarios.EMPRESA'])) $this->m_empresa = $__row__['usuarios.EMPRESA'];

		if (isset($__row__['EDITORES.NOMBRE'])) $this->m_nombre_editor = $__row__['EDITORES.NOMBRE'];
		if (isset($__row__['EDITORES.APELLIDO'])) $this->m_apellido_editor = $__row__['EDITORES.APELLIDO'];		
		if (isset($__row__['EDITORES.NICK'])) $this->m_nick_editor = $__row__['EDITORES.NICK'];
		
		if (isset($__row__['secciones.NOMBRE'])) $this->m_seccion_nombre = $__row__['secciones.NOMBRE'];
		if (isset($__row__['secciones.ML_NOMBRE'])) $this->m_seccion_ml_nombre = $__row__['secciones.ML_NOMBRE'];
		
		if (isset($__row__['padres.TITULO'])) $this->m_padre_titulo = $__row__['padres.TITULO'];
		if (isset($__row__['padres.ML_TITULO'])) $this->m_padre_titulo = $__row__['padres.ML_TITULO'];
		if (isset($__row__['padres.ID'])) $this->m_padre_id = $__row__['padres.ID'];
	}
	
	function SetFromGlobals() {
		//$this->m_id = $__row__['contenidos.ID'];
		//$this->m_id = $GLOBALS['_e_ID'];
		$this->m_id_tipocontenido = $GLOBALS['_e_ID_TIPOCONTENIDO'];
		$this->m_id_seccion = $GLOBALS['_e_ID_SECCION'];
		$this->m_id_contenido = $GLOBALS['_e_ID_CONTENIDO'];
		if (isset($GLOBALS['_e_ORDEN'])) $this->m_orden = $GLOBALS['_e_ORDEN'];
		$this->m_titulo = $GLOBALS['_e_TITULO'];
		$this->m_ml_titulo = $GLOBALS['_e_ML_TITULO'];
		$this->m_url = $GLOBALS['_e_URL'];
		$this->m_copete = $GLOBALS['_e_COPETE'];
		$this->m_ml_copete = $GLOBALS['_e_ML_COPETE'];
		$this->m_cuerpo = $GLOBALS['_e_CUERPO'];
		$this->m_ml_cuerpo = $GLOBALS['_e_ML_CUERPO'];
		$this->m_palabrasclave = $GLOBALS['_e_PALABRASCLAVE'];
		$this->m_ml_palabrasclave = $GLOBALS['_e_ML_PALABRASCLAVE'];		
		$this->m_autor = $GLOBALS['_e_AUTOR'];
		$this->m_icono = $GLOBALS['_e_ICONO'];
		$this->m_id_usuario_creador = $GLOBALS['_e_ID_USUARIO_CREADOR'];
		$this->m_id_usuario_modificador = $GLOBALS['_e_ID_USUARIO_MODIFICADOR'];
		$this->m_actualizacion = $GLOBALS['_e_ACTUALIZACION'];
		$this->m_baja = $GLOBALS['_e_BAJA'];
		$this->m_principal = $GLOBALS['_e_PRINCIPAL'];
		$this->m_fechabaja = $GLOBALS['_e_FECHABAJA'];
		$this->m_fechaalta = $GLOBALS['_e_FECHAALTA'];
		$this->m_fechaevento = $GLOBALS['_e_FECHAEVENTO'];
		$this->m_votos = $GLOBALS['_e_VOTOS'];		
	}
	
	function ToGlobals() {
		$GLOBALS['_e_ID'] = $this->m_id;		
		$GLOBALS['_e_ID_TIPOCONTENIDO'] = $this->m_id_tipocontenido;
		$GLOBALS['_e_ID_SECCION'] = $this->m_id_seccion;
		$GLOBALS['_e_ID_CONTENIDO'] = $this->m_id_contenido;
		$GLOBALS['_e_ORDEN'] = $this->m_orden;
		$GLOBALS['_e_TITULO'] = $this->m_titulo;
		$GLOBALS['_e_ML_TITULO'] = $this->m_ml_titulo;
		$GLOBALS['_e_URL'] = $this->m_url;
		$GLOBALS['_e_COPETE'] = $this->m_copete;
		$GLOBALS['_e_ML_COPETE'] = $this->m_ml_copete;
		$GLOBALS['_e_CUERPO'] = $this->m_cuerpo;
		$GLOBALS['_e_ML_CUERPO'] = $this->m_ml_cuerpo;
		$GLOBALS['_e_PALABRASCLAVE'] = $this->m_palabrasclave;
		$GLOBALS['_e_ML_PALABRASCLAVE'] = $this->m_ml_palabrasclave;		
		$GLOBALS['_e_AUTOR'] = $this->m_autor;
		$GLOBALS['_e_ICONO'] = $this->m_icono;
		$GLOBALS['_e_ID_USUARIO_CREADOR'] = $this->m_id_usuario_creador;
		$GLOBALS['_e_ID_USUARIO_MODIFICADOR'] = $this->m_id_usuario_modificador;
		$GLOBALS['_e_ACTUALIZACION'] = $this->m_actualizacion;
		$GLOBALS['_e_BAJA'] = $this->m_baja;
		$GLOBALS['_e_PRINCIPAL'] = $this->m_principal;
		$GLOBALS['_e_FECHABAJA'] = $this->m_fechabaja;
		$GLOBALS['_e_FECHAALTA'] = $this->m_fechaalta;
		$GLOBALS['_e_FECHAEVENTO'] = $this->m_fechaevento;
		$GLOBALS['_e_VOTOS'] = $this->m_votos;		
	}
	
	function FullArray() {
		
		return array(
			'ID_TIPOCONTENIDO'=>$this->m_id_tipocontenido,
			'ID_SECCION'=>$this->m_id_seccion,
			'ID_CONTENIDO'=>$this->m_id_contenido,
			'ORDEN'=>$this->m_orden,
			'TITULO'=>$this->m_titulo,
			'ML_TITULO'=>$this->m_ml_titulo,
			'URL'=>$this->m_url,
			'COPETE'=>$this->m_copete,
			'ML_COPETE'=>$this->m_ml_copete,
			'CUERPO'=>$this->m_cuerpo,
			'ML_CUERPO'=>$this->m_ml_cuerpo,
			'PALABRASCLAVE'=>$this->m_palabrasclave,
			'ML_PALABRASCLAVE'=>$this->m_ml_palabrasclave,		
			'AUTOR'=>$this->m_autor,
			'ICONO'=>$this->m_icono,
			'ID_USUARIO_CREADOR'=>$this->m_id_usuario_creador,
			'ID_USUARIO_MODIFICADOR'=>$this->m_id_usuario_modificador,
			'ACTUALIZACION'=>$this->m_actualizacion,
			'BAJA'=>$this->m_baja,
			'PRINCIPAL'=>$this->m_principal,
			'FECHABAJA'=>$this->m_fechabaja,
			'FECHAALTA'=>$this->m_fechaalta,
			'FECHAEVENTO'=>$this->m_fechaevento,
			'VOTOS'=>$this->m_votos );
		
	}

	/**
	 * Atencion tratar tambien los campos del detalle....en Get()
	 *
	 * @param unknown_type $requiredfields
	 * @param unknown_type $error
	 * @return unknown
	 */
	function RequiredFields( &$requiredfields, $error ) {
		$missing = false;
		foreach($requiredfields as $key=>$value) {

			$valor = $this->Get( $key );
			Debug("$key => ".$valor);
			if ( trim($valor) == "" ) {
				$missing = true;
				$requiredfields[$key] = $error;
				DebugError("$key => ".$error);
			}
			
		}

		return $missing;
		
	}
	
	function UpdateRequiredFields( &$requiredfields, &$templ ) {
		foreach($requiredfields as $key=>$mess) {
			$templ = str_replace( "#".$key."#", $mess, $templ );
		}
	}


}

 
?>