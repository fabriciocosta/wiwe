<?php
 
class CTipoRelacion {

var $m_id,
	$m_id_tipodetalle,
	$m_tipo,
	$m_descripcion,
	$m_icono,
	$m_id_grupo,
	$m_id_usuario_creador,
	$m_id_usuario_modificador,
	$m_actualizacion,
	$m_baja;
		

	function CTipoRelacion($__row__) {
		
		$this->m_id = $__row__['tiposrelaciones.ID'];
		$this->m_tipo = $__row__['tiposrelaciones.TIPO'];
		$this->m_descripcion = $__row__['tiposrelaciones.DESCRIPCION'];
		$this->m_icono = $__row__['tiposrelaciones.ICONO'];
		$this->m_id_grupo = $__row__['tiposrelaciones.ID_GRUPO'];
		$this->m_id_usuario_creador = $__row__['tiposrelaciones.ID_USUARIO_CREADOR'];
		$this->m_id_usuario_modificador = $__row__['tiposrelaciones.ID_USUARIO_MODIFICADOR'];
		$this->m_actualizacion = $__row__['tiposrelaciones.ACTUALIZACION'];
		$this->m_baja = $__row__['tiposrelaciones.BAJA'];
				
	}
	
	function Set($__row__) {
		
		$this->m_id = $__row__['tiposrelaciones.ID'];
		$this->m_tipo = $__row__['tiposrelaciones.TIPO'];
		$this->m_descripcion = $__row__['tiposrelaciones.DESCRIPCION'];
		$this->m_icono = $__row__['tiposrelaciones.ICONO'];
		$this->m_id_grupo = $__row__['tiposrelaciones.ID_GRUPO'];
		$this->m_id_usuario_creador = $__row__['tiposrelaciones.ID_USUARIO_CREADOR'];
		$this->m_id_usuario_modificador = $__row__['tiposrelaciones.ID_USUARIO_MODIFICADOR'];
		$this->m_actualizacion = $__row__['tiposrelaciones.ACTUALIZACION'];
		$this->m_baja = $__row__['tiposrelaciones.BAJA'];
		
	}
	
} 
?>