<?php


class CRelacion {

	var $m_id,
		$m_id_tiporelacion,
		$m_sentido,
		$m_peso,
		$m_distancia,
		$m_id_seccion,
		$m_id_contenido,
		$m_id_seccion_rel,
		$m_id_contenido_rel,
		$m_id_usuario_creador,
		$m_id_usuario_modificador,
		$m_actualizacion,
		$m_orden,
		$m_baja;
				

	function CRelacion($__row__=null) {
		
		if (is_numeric($__row__) && $__row__==0 ) {
			$this->SetEmpty();
		} else ($__row__==null) ? $this->SetFromGlobals() : $this->Set($__row__);

	}

	function SetFromGlobals() {
		$this->m_id = $GLOBALS['_primario_ID'];
		$this->m_id_tiporelacion = $GLOBALS['_e_ID_TIPORELACION'];
		$this->m_sentido = $GLOBALS['_e_SENTIDO'];
		$this->m_peso = $GLOBALS['_e_PESO'];
		$this->m_distancia = $GLOBALS['_e_ID_DISTANCIA'];
		$this->m_id_seccion = $GLOBALS['_e_ID_SECCION'];
		$this->m_id_contenido = $GLOBALS['_e_ID_CONTENIDO'];
		$this->m_id_seccion_rel = $GLOBALS['_e_ID_SECCION_REL'];
		$this->m_id_contenido_rel = $GLOBALS['_e_ID_CONTENIDO_REL'];
		$this->m_id_usuario_creador = $GLOBALS['_e_ID_USUARIO_CREADOR'];
		$this->m_id_usuario_modificador = $GLOBALS['_e_ID_USUARIO_MODIFICADOR'];
		$this->m_actualizacion = $GLOBALS['_e_ACTUALIZACION'];
		$this->m_orden = $GLOBALS['_e_ORDEN'];		
		$this->m_baja = $GLOBALS['_e_BAJA'];
	}
	
	function SetEmpty() {
		$this->m_id = 0;
		$this->m_id_tiporelacion = "1";
		$this->m_sentido = "direct";
		$this->m_peso = "0";
		$this->m_distancia = "0";
		
		$this->m_id_contenido = 0;
		$this->m_id_contenido_rel = 0;
		$this->m_id_seccion = 0;
		$this->m_id_seccion_rel = 0;
		
		$this->m_id_usuario_creador = 1;
		$this->m_id_usuario_modificador = 1;
		$this->m_actualizacion = "NOW()";
		$this->m_baja = "S";
	}	
	
	function ToGlobals() {
		$GLOBALS['_primario_ID'] = $this->m_id;
		$GLOBALS['_e_ID_TIPORELACION'] = $this->m_id_tiporelacion;
		$GLOBALS['_e_SENTIDO'] = $this->m_sentido;
		$GLOBALS['_e_PESO'] = $this->m_peso;
		$GLOBALS['_e_DISTANCIA'] = $this->m_distancia;
		$GLOBALS['_e_ID_SECCION'] = $this->m_id_seccion;
		$GLOBALS['_e_ID_CONTENIDO'] = $this->m_id_contenido;
		$GLOBALS['_e_ID_SECCION_REL'] = $this->m_id_seccion_rel;
		$GLOBALS['_e_ID_CONTENIDO_REL'] = $this->m_id_contenido_rel;
		$GLOBALS['_e_ID_USUARIO_CREADOR'] = $this->m_id_usuario_creador;
		$GLOBALS['_e_ID_USUARIO_MODIFICADOR'] = $this->m_id_usuario_modificador;
		$GLOBALS['_e_ACTUALIZACION'] = $this->m_actualizacion;
		$GLOBALS['_e_ORDEN'] = $this->m_orden;		
		$GLOBALS['_e_BAJA'] = $this->m_baja;
	}	
	
	function Set($__row__) {
		
		$this->m_id = $__row__['relaciones.ID'];
		$this->m_id_tiporelacion = $__row__['relaciones.ID_TIPORELACION'];
		$this->m_sentido = $__row__['relaciones.SENTIDO'];
		$this->m_peso = $__row__['relaciones.PESO'];
		$this->m_distancia = $__row__['relaciones.DISTANCIA'];		
		$this->m_id_seccion = $__row__['relaciones.ID_SECCION'];
		$this->m_id_contenido = $__row__['relaciones.ID_CONTENIDO'];
		$this->m_id_seccion_rel = $__row__['relaciones.ID_SECCION_REL'];
		$this->m_id_contenido_rel = $__row__['relaciones.ID_CONTENIDO_REL'];
		$this->m_id_usuario_creador = $__row__['relaciones.ID_USUARIO_CREADOR'];
		$this->m_id_usuario_modificador = $__row__['relaciones.ID_USUARIO_MODIFICADOR'];
		$this->m_actualizacion = $__row__['relaciones.ACTUALIZACION'];
		$this->m_orden = $__row__['relaciones.ORDEN'];			
		$this->m_baja = $__row__['relaciones.BAJA'];
		
	}
	
	function FullArray() {
		
		return array(
		'ID_TIPORELACION' => $this->m_id_tiporelacion,
		'SENTIDO' => $this->m_sentido,
		'PESO' => $this->m_peso,
		'DISTANCIA' => $this->m_distancia,		
		'ID_SECCION' => $this->m_id_seccion,
		'ID_CONTENIDO' => $this->m_id_contenido,
		'ID_SECCION_REL' => $this->m_id_seccion_rel,
		'ID_CONTENIDO_REL' => $this->m_id_contenido_rel,
		'ID_USUARIO_CREADOR' => $this->m_id_usuario_creador,
		'ID_USUARIO_MODIFICADOR' => $this->m_id_usuario_modificador,
		'ACTUALIZACION' => $this->m_actualizacion,
		'ORDEN' => $this->m_orden,		
		'BAJA' => $this->m_baja );
		
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