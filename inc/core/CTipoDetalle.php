<?php

/**
 * class CTipoDetalle
 *
 * @version 24/05/2007
2004 **/

 
class CTipoDetalle {

var $m_id,
	$m_id_tipocontenido,
	$m_tipo,
	$m_descripcion,
	$m_txtdata,
	$m_icono,
	$m_tipocampo,
	$m_id_usuario_creador,
	$m_id_usuario_modificador,
	$m_actualizacion,
	$m_baja;
		

	function CTipoDetalle($__row__='') {
		if ($__row__!='')
			$this->Set($__row__);
		else
			$this->SetEmpty();
				
	}
	
	function Descripcion() {
		return $this->m_descripcion;
	}
	
	function SetEmpty() {
		$this->m_id = 0;
		$this->m_id_tipocontenido = 1;
		$this->m_tipo = "INDEFINIDO";
		$this->m_tipocampo = "T";
		$this->m_descripcion = "indefinido";
		$this->m_txtdata = "indefinido";
		$this->m_icono = "";
		$this->m_id_usuario_creador = "1";
		$this->m_id_usuario_modificador = "1";
		$this->m_actualizacion = "NOW()";
		$this->m_baja = "S";		
	}
	
	function Set($__row__) {
		
		$this->m_id = $__row__['tiposdetalles.ID'];
		$this->m_id_tipocontenido = $__row__['tiposdetalles.ID_TIPOCONTENIDO'];		
		$this->m_tipo = $__row__['tiposdetalles.TIPO'];
		$this->m_descripcion = $__row__['tiposdetalles.DESCRIPCION'];
		$this->m_txtdata =  $__row__['tiposdetalles.TXTDATA'];
		if (isset($__row__['tiposdetalles.ICONO'])) $this->m_icono = $__row__['tiposdetalles.ICONO'];
		$this->m_tipocampo = $__row__['tiposdetalles.TIPOCAMPO'];
		if (isset($__row__['tiposdetalles.ID_USUARIO_CREADOR'])) $this->m_id_usuario_creador = $__row__['tiposdetalles.ID_USUARIO_CREADOR'];
		if (isset($__row__['tiposdetalles.ID_USUARIO_MODIFICADOR'])) $this->m_id_usuario_modificador = $__row__['tiposdetalles.ID_USUARIO_MODIFICADOR'];
		if (isset($__row__['tiposdetalles.ACTUALIZACION'])) $this->m_actualizacion = $__row__['tiposdetalles.ACTUALIZACION'];
		if (isset($__row__['tiposdetalles.BAJA'])) $this->m_baja = $__row__['tiposdetalles.BAJA'];
		
	}
	
	function FullArray() {
		return array(
		'ID_TIPOCONTENIDO'=>$this->m_id_tipocontenido,
		'TIPO'=>$this->m_tipo,
		'TIPOCAMPO'=>$this->m_tipocampo,
		'DESCRIPCION'=>$this->m_descripcion,
		'TXTDATA'=>$this->m_txtdata,
		'ICONO'=>$this->m_icono,
		'ID_USUARIO_CREADOR'=>$this->m_id_usuario_creador,
		'ID_USUARIO_MODIFICADOR'=>$this->m_id_usuario_modificador,
		'ACTUALIZACION'=>$this->m_actualizacion,
		'BAJA'=>$this->m_baja
		);
	}	
	
} 
?>