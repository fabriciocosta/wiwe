<?php

/**
 * class CArchivo
 *
 * @version 28/11/2003
 * @copyright 2003 
 **/

/*
ID  int(11)    No    auto_increment  Change Drop Primary Index Unique 
ID_TIPOARCHIVO  int(11)    No  0    Change Drop Primary Index Unique 
ID_SECCION  int(11)    No  0    Change Drop Primary Index Unique 
NOMBRE  varchar(200)    Yes      Change Drop Primary Index Unique 
URL  varchar(250)    Yes      Change Drop Primary Index Unique 
ICONO  varchar(128)    Yes      Change Drop Primary Index Unique 
ID_USUARIO_CREADOR  int(11)    No  0    Change Drop Primary Index Unique 
ID_USUARIO_MODIFICADOR  int(11)    No  0    Change Drop Primary Index Unique 
ACTUALIZACION  timestamp(14)    Yes      Change Drop Primary Index Unique 
BAJA  char(1)    Yes  N    Change Drop Primary Index Unique 
DESCRIPCION  text    Yes      Change Drop Primary Index Unique 
GUARDAR_SECCION  char(1)    No  N    Change Drop Primary Index Unique 
EXTERNO  char(1)
*/ 
 
class CArchivo {

	var $m_id,
		$m_id_tipoarchivo,
		$m_id_seccion,
		$m_nombre,
		$m_url,
		$m_icono,
		$m_id_usuario_creador,
		$m_id_usuario_modificador,
		$m_actualizacion,
		$m_baja,
		$m_descripcion,
		$m_guardar_seccion,
		$m_externo;
		

	function CArchivo($__row__) {
		
		$this->m_id = $__row__['archivos.ID'];
		$this->m_id_tipoarchivo = $__row__['archivos.ID_TIPOARCHIVO'];
		$this->m_id_seccion = $__row__['archivos.ID_SECCION'];
		$this->m_nombre = $__row__['archivos.NOMBRE'];
		$this->m_url = $__row__['archivos.URL'];
		$this->m_icono = $__row__['archivos.ICONO'];
		$this->m_id_usuario_creador = $__row__['archivos.ID_USUARIO_CREADOR'];
		$this->m_id_usuario_modificador = $__row__['archivos.ID_USUARIO_MODIFICADOR'];
		$this->m_actualizacion = $__row__['archivos.ACTUALIZACION'];
		$this->m_baja = $__row__['archivos.BAJA'];
		$this->m_descripcion = $__row__['archivos.DESCRIPCION'];
		$this->m_guardar_seccion = $__row__['archivos.GUARDAR_SECCION'];
		$this->m_externo = $__row__['archivos.EXTERNO'];
		
	}
	
	function Set($__row__) {
		
		$this->m_id = $__row__['archivos.ID'];
		$this->m_id_tipoarchivo = $__row__['archivos.ID_TIPOARCHIVO'];
		$this->m_id_seccion = $__row__['archivos.ID_SECCION'];
		$this->m_nombre = $__row__['archivos.NOMBRE'];
		$this->m_url = $__row__['archivos.URL'];
		$this->m_icono = $__row__['archivos.ICONO'];
		$this->m_id_usuario_creador = $__row__['archivos.ID_USUARIO_CREADOR'];
		$this->m_id_usuario_modificador = $__row__['archivos.ID_USUARIO_MODIFICADOR'];
		$this->m_actualizacion = $__row__['archivos.ACTUALIZACION'];
		$this->m_baja = $__row__['archivos.BAJA'];
		$this->m_descripcion = $__row__['archivos.DESCRIPCION'];
		$this->m_guardar_seccion = $__row__['archivos.GUARDAR_SECCION'];
		$this->m_externo = $__row__['archivos.EXTERNO'];
		
	}
	
}

 
?>