<?php

/**
 * class CSeccion
 *
 * @version 28/11/2003
 * @copyright 2003 
 **/

/*
ID  int(11)    No    auto_increment  Change Drop Primary Index Unique 
ID_TIPOSECCION  int(11)    No  0    Change Drop Primary Index Unique 
ID_SECCION  int(11)    No  0    Change Drop Primary Index Unique 
ID_CONTENIDO  int(11)    No  1    Change Drop Primary Index Unique 
PROFUNDIDAD  int(11)    No  0    Change Drop Primary Index Unique 
NOMBRE  varchar(40)    Yes      Change Drop Primary Index Unique 
DESCRIPCION  varchar(200)    Yes      Change Drop Primary Index Unique 
ICONO  varchar(128)    Yes      Change Drop Primary Index Unique 
ID_USUARIO_CREADOR  int(11)    No  0    Change Drop Primary Index Unique 
ID_USUARIO_MODIFICADOR  int(11)    No  0    Change Drop Primary Index Unique 
ACTUALIZACION  timestamp(14)    Yes      Change Drop Primary Index Unique 
BAJA  char(1)    Yes  N    Change Drop Primary Index Unique 
CATEGORIA  char(1)    No  N    Change Drop Primary Index Unique 
CARPETA  

*/ 
 
class CSeccion {

	var $m_id,
		$m_id_tiposeccion,
		$m_id_seccion,
		$m_id_contenido,
		$m_profundidad,
		$m_rama,
		$m_orden,
		$m_nombre,
		$m_ml_nombre,
		$m_descripcion,
		$m_ml_descripcion,
		$m_palabrasclave,
		$m_ml_palabrasclave,				
		$m_icono,
		$m_id_usuario_creador,
		$m_id_usuario_modificador,
		$m_actualizacion,
		$m_baja,
		$m_categoria,
		$m_carpeta;
		
		//extras
		var $m_nhijos,
		$m_nitem;
		

	function CSeccion($__row__=null,$__nhijos__=0,$__nitem__=0) {
		
		if ( is_numeric($__row__) && $__row__ == 0 ) {			 
			$this->SetEmpty();
		} else ($__row__==null) ? $this->SetFromGlobals() : $this->Set($__row__);		
		
		//extras: campos calculados
		$this->m_nhijos = $__nhijos__;
		$this->m_nitem = $__nitem__;
	}

	function Habilitado() {
		return ($this->m_baja == "S");
	}	
	
	function Nombre($lang='') {
		
		global $__lang__;
		
		if ( trim($lang)=="" ) $lang = $__lang__;
		
		if ( trim($lang)=="" ) {
			return $this->m_nombre;
		} else {			
			$res = TextoML( $this->m_ml_nombre, $__lang__  );
			if (trim($res)=="") $res = $this->m_nombre;
			return $res;			
		}
		
	}
	
	function NombreURL($lang='') {
		
		$res = str_replace( array("_","/","'",'"'), array(" "," "," "," "), trim($this->Nombre($lang)));
		$res = urlencode($res);		
		if ($res=="") $res = $this->m_id;
		return strtolower( $res );
		
	}		
	
	function Descripcion($lang='') {
		
		global $__lang__;
		
		if ( trim($lang)=="" ) $lang = $__lang__;
		
		if ( trim($lang)=="" ) {
			return $this->m_descripcion;
		} else {			
			$res = TextoML( $this->m_ml_descripcion, $__lang__  );
			if (trim($res)=="") $res = $this->m_descripcion;
			return $res;			
		}
	}
	
	function PalabrasClave($lang='') {
		
		global $__lang__;
		
		if ( trim($lang)=="" ) $lang = $__lang__;
		
		if ( trim($lang)=="" ) {
			return $this->m_palabrasclave;
		} else {			
			$res = TextoML( $this->m_ml_palabrasclave, $__lang__  );
			if (trim($res)=="") $res = $this->m_palabrasclave;
			return $res;			
		}
	}	
	
	function SetEmpty() {
		$this->m_id = 0;
		$this->m_id_tiposeccion = "1";
		$this->m_id_seccion = "1";
		$this->m_id_contenido = "1";
		$this->m_profundidad = "1";		
		$this->m_rama = "1";		
		$this->m_orden = "1";		
		$this->m_nombre = "indefinido";
		$this->m_ml_nombre = "";
		$this->m_descripcion = "indefinido";
		$this->m_ml_descripcion = "";
		$this->m_palabrasclave = "";
		$this->m_ml_palabrasclave = "";		
		$this->m_icono = "";
		$this->m_id_usuario_creador = "1";
		$this->m_id_usuario_modificador = "1";
		$this->m_actualizacion = "NOW()";
		$this->m_baja = "S";
		$this->m_categoria = "N";
		$this->m_carpeta = "indefinida";		
	}
	
	function SetFromGlobals() {
		$this->m_id = $GLOBALS['_primario_ID'];
		$this->m_id_tiposeccion = $GLOBALS['_e_ID_TIPOSECCION'];
		$this->m_id_seccion = $GLOBALS['_e_ID_SECCION'];
		$this->m_id_contenido = $GLOBALS['_e_ID_CONTENIDO'];
		$this->m_profundidad = $GLOBALS['_e_PROFUNDIDAD'];		
		$this->m_rama = $GLOBALS['_e_RAMA'];		
		$this->m_orden = $GLOBALS['_e_ORDEN'];		
		$this->m_nombre = $GLOBALS['_e_NOMBRE'];
		$this->m_ml_nombre = $GLOBALS['_e_ML_NOMBRE'];
		$this->m_descripcion = $GLOBALS['_e_DESCRIPCION'];
		$this->m_ml_descripcion = $GLOBALS['_e_ML_DESCRIPCION'];
		$this->m_palabrasclave = $GLOBALS['_e_PALABRASCLAVE'];
		$this->m_ml_palabrasclave = $GLOBALS['_e_ML_PALABRASCLAVE'];		
		$this->m_icono = $GLOBALS['_e_ICONO'];
		$this->m_id_usuario_creador = $GLOBALS['_e_ID_USUARIO_CREADOR'];
		$this->m_id_usuario_modificador = $GLOBALS['_e_ID_USUARIO_MODIFICADOR'];
		$this->m_actualizacion = $GLOBALS['_e_ACTUALIZACION'];
		$this->m_baja = $GLOBALS['_e_BAJA'];
		$this->m_categoria = $GLOBALS['_e_CATEGORIA'];
		$this->m_carpeta = $GLOBALS['_e_CARPETA'];
	}
	
	function ToGlobals() {
		$GLOBALS['_primario_ID'] = $this->m_id;
		$GLOBALS['_e_ID_TIPOSECCION'] = $this->m_id_tiposeccion;
		$GLOBALS['_e_ID_SECCION'] = $this->m_id_seccion;
		$GLOBALS['_e_ID_CONTENIDO'] = $this->m_id_contenido;
		$GLOBALS['_e_PROFUNDIDAD'] = $this->m_profundidad;		
		$GLOBALS['_e_RAMA'] = $this->m_rama;		
		$GLOBALS['_e_ORDEN'] = $this->m_orden;		
		$GLOBALS['_e_NOMBRE'] = $this->m_nombre;
		$GLOBALS['_e_ML_NOMBRE'] = $this->m_ml_nombre;
		$GLOBALS['_e_DESCRIPCION'] = $this->m_descripcion;
		$GLOBALS['_e_ML_DESCRIPCION'] = $this->m_ml_descripcion;
		$GLOBALS['_e_PALABRASCLAVE'] = $this->m_palabrasclave;
		$GLOBALS['_e_ML_PALABRASCLAVE'] = $this->m_ml_palabrasclave;		
		$GLOBALS['_e_ICONO'] = $this->m_icono;
		$GLOBALS['_e_ID_USUARIO_CREADOR'] = $this->m_id_usuario_creador;
		$GLOBALS['_e_ID_USUARIO_MODIFICADOR'] = $this->m_id_usuario_modificador;
		$GLOBALS['_e_ACTUALIZACION'] = $this->m_actualizacion;
		$GLOBALS['_e_BAJA'] = $this->m_baja;
		$GLOBALS['_e_CATEGORIA'] = $this->m_categoria;
		$GLOBALS['_e_CARPETA'] = $this->m_carpeta;
	}	
	
	function Set($__row__,$__nhijos__=0,$__nitem__=0) {
		
		$this->m_id = $__row__['secciones.ID'];
		$this->m_id_tiposeccion = $__row__['secciones.ID_TIPOSECCION'];
		$this->m_id_seccion = $__row__['secciones.ID_SECCION'];
		$this->m_id_contenido = $__row__['secciones.ID_CONTENIDO'];
		$this->m_profundidad = $__row__['secciones.PROFUNDIDAD'];		
		$this->m_rama = $__row__['secciones.RAMA'];		
		$this->m_orden = $__row__['secciones.ORDEN'];			
		$this->m_nombre = $__row__['secciones.NOMBRE'];
		$this->m_ml_nombre = $__row__['secciones.ML_NOMBRE'];
		$this->m_descripcion = $__row__['secciones.DESCRIPCION'];
		$this->m_ml_descripcion = $__row__['secciones.ML_DESCRIPCION'];
		$this->m_palabrasclave = $__row__['secciones.PALABRASCLAVE'];
		$this->m_ml_palabrasclave = $__row__['secciones.ML_PALABRASCLAVE'];		
		if (isset($__row__['secciones.ICONO'])) $this->m_icono = $__row__['secciones.ICONO'];
		if (isset($__row__['secciones.ID_USUARIO_CREADOR'])) $this->m_id_usuario_creador = $__row__['secciones.ID_USUARIO_CREADOR'];
		if (isset($__row__['secciones.ID_USUARIO_MODIFICADOR'])) $this->m_id_usuario_modificador = $__row__['secciones.ID_USUARIO_MODIFICADOR'];
		if (isset($__row__['secciones.ACTUALIZACION'])) $this->m_actualizacion = $__row__['secciones.ACTUALIZACION'];
		$this->m_baja = $__row__['secciones.BAJA'];
		$this->m_categoria = $__row__['secciones.CATEGORIA'];
		$this->m_carpeta = $__row__['secciones.CARPETA'];
		
		//extras: campos calculados
		$this->m_nhijos = $__nhijos__;
		$this->m_nitem = $__nitem__;		
	}
	
	function FullArray() {
		
		return array(
		'ID_TIPOSECCION' => $this->m_id_tiposeccion,
		'ID_SECCION' => $this->m_id_seccion,
		'ID_CONTENIDO' => $this->m_id_contenido,
		'PROFUNDIDAD' => $this->m_profundidad,	
		'RAMA' => $this->m_rama,		
		'ORDEN' => $this->m_orden,		
		'NOMBRE' => $this->m_nombre,
		'ML_NOMBRE' => $this->m_ml_nombre,
		'DESCRIPCION' => $this->m_descripcion,
		'ML_DESCRIPCION' => $this->m_ml_descripcion,
		'PALABRASCLAVE' => $this->m_palabrasclave,
		'ML_PALABRASCLAVE' => $this->m_ml_palabrasclave,
		'ICONO' => $this->m_icono,
		'ID_USUARIO_CREADOR' => $this->m_id_usuario_creador,
		'ID_USUARIO_MODIFICADOR' => $this->m_id_usuario_modificador,
		'ACTUALIZACION' => $this->m_actualizacion,
		'BAJA' => $this->m_baja,
		'CATEGORIA' => $this->m_categoria,
		'CARPETA' => $this->m_carpeta );
		
	}
	
	function ToStr() {
		
		$row = $this->FullArray();
		
		foreach($row as $key=>$value) {
			$str.= "$key: $value"."<br>";
		}
		
		return $str;
		
	}
}

?>