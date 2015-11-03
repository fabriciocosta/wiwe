<?php

/**
 * class CTipoArchivo
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
 
class CTipoArchivo {

var $m_id,
	$m_tipo,
	$m_descripcion,
	$m_icono,
	$m_id_grupo,
	$m_id_usuario_creador,
	$m_id_usuario_modificador,
	$m_actualizacion,
	$m_baja;
		

	function CTipoArchivo($__row__) {
		
		$this->m_id = $__row__['tiposarchivos.ID'];
		$this->m_tipo = $__row__['tiposarchivos.ID_TIPO'];
		$this->m_descripcion = $__row__['tiposarchivos.ID_DESCRIPCION'];
		$this->m_icono = $__row__['tiposarchivos.ICONO'];
		$this->m_id_grupo = $__row__['tiposarchivos.ID_GRUPO'];
		$this->m_id_usuario_creador = $__row__['tiposarchivos.ID_USUARIO_CREADOR'];
		$this->m_id_usuario_modificador = $__row__['tiposarchivos.ID_USUARIO_MODIFICADOR'];
		$this->m_actualizacion = $__row__['tiposarchivos.ACTUALIZACION'];
		$this->m_baja = $__row__['tiposarchivos.BAJA'];
				
	}
	
	function Set($__row__) {
		
		$this->m_id = $__row__['tiposarchivos.ID'];
		$this->m_tipo = $__row__['tiposarchivos.ID_TIPO'];
		$this->m_descripcion = $__row__['tiposarchivos.ID_DESCRIPCION'];
		$this->m_icono = $__row__['tiposarchivos.ICONO'];
		$this->m_id_grupo = $__row__['tiposarchivos.ID_GRUPO'];
		$this->m_id_usuario_creador = $__row__['tiposarchivos.ID_USUARIO_CREADOR'];
		$this->m_id_usuario_modificador = $__row__['tiposarchivos.ID_USUARIO_MODIFICADOR'];
		$this->m_actualizacion = $__row__['tiposarchivos.ACTUALIZACION'];
		$this->m_baja = $__row__['tiposarchivos.BAJA'];
		
	}
	
} 
?>