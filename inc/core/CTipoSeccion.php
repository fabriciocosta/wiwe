<?php

/**
 * class CTipoSeccion
 *
 * @version 28/11/2003
 * @copyright 2003 
 **/
/*
* 
ID  int(11)    No    auto_increment  Change Drop Primary Index Unique 
TIPO  varchar(100)    Yes      Change Drop Primary Index Unique 
DESCRIPCION  varchar(200)    Yes      Change Drop Primary Index Unique 
ICONO  varchar(128)    Yes      Change Drop Primary Index Unique 
ID_GRUPO  int(11)    No  0    Change Drop Primary Index Unique 
ID_USUARIO_CREADOR  int(11)    No  0    Change Drop Primary Index Unique 
ID_USUARIO_MODIFICADOR  int(11)    No  0    Change Drop Primary Index Unique 
ACTUALIZACION  timestamp(14)    Yes      Change Drop Primary Index Unique 
BAJA  char(1)  

*/
 
class CTipoSeccion {

var $m_id,
	$m_tipo,
	$m_descripcion,
	$m_icono,
	$m_id_grupo,
	$m_id_usuario_creador,
	$m_id_usuario_modificador,
	$m_actualizacion,
	$m_baja;
		

	function CTipoSeccion($__row__='') {
		if ($__row__!='')
			$this->Set($__row__);
		else
			$this->SetEmpty();
				
	}
	
	function SetEmpty() {
		$this->m_id = 0;
		$this->m_tipo = "INDEFINIDO";
		$this->m_descripcion = "indefinido";
		$this->m_icono = "";
		$this->m_id_grupo = "1";
		$this->m_id_usuario_creador = "1";
		$this->m_id_usuario_modificador = "1";
		$this->m_actualizacion = "NOW()";
		$this->m_baja = "S";		
	}
	
	function Set($__row__) {
		
		$this->m_id = $__row__['tipossecciones.ID'];
		$this->m_tipo = $__row__['tipossecciones.TIPO'];
		$this->m_descripcion = $__row__['tipossecciones.DESCRIPCION'];
		if (isset($__row__['tipossecciones.ICONO'])) $this->m_icono = $__row__['tipossecciones.ICONO'];
		if (isset($__row__['tipossecciones.ID_GRUPO'])) $this->m_id_grupo = $__row__['tipossecciones.ID_GRUPO'];
		if (isset($__row__['tipossecciones.ID_USUARIO_CREADOR'])) $this->m_id_usuario_creador = $__row__['tipossecciones.ID_USUARIO_CREADOR'];
		if (isset($__row__['tipossecciones.ID_USUARIO_MODIFICADOR'])) $this->m_id_usuario_modificador = $__row__['tipossecciones.ID_USUARIO_MODIFICADOR'];
		if (isset($__row__['tipossecciones.ACTUALIZACION'])) $this->m_actualizacion = $__row__['tipossecciones.ACTUALIZACION'];
		if (isset($__row__['tipossecciones.BAJA'])) $this->m_baja = $__row__['tipossecciones.BAJA'];
		
	}
	
	function Tipo() {
		return $this->m_tipo;
	}

	function Descripcion() {
		return $this->m_descripcion;
	}
	
	
	function FullArray() {
		return array(
		'TIPO'=>$this->m_tipo,
		'DESCRIPCION'=>$this->m_descripcion,
		'ICONO'=>$this->m_icono,
		'ID_GRUPO'=>$this->m_id_grupo,
		'ID_USUARIO_CREADOR'=>$this->m_id_usuario_creador,
		'ID_USUARIO_MODIFICADOR'=>$this->m_id_usuario_modificador,
		'ACTUALIZACION'=>$this->m_actualizacion,
		'BAJA'=>$this->m_baja
		);
	}	
	
} 
?>