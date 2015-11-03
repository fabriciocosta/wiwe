<?php

/**
 * class CLog
 *
 * @version 29/08/2012
2012 **/

 
class CLog {

var $m_id,
	$m_id_contenido,
	$m_id_contenidoaux,
	$m_id_usuario,
	$m_logcode,
	$m_accion,
	$m_valor,		
	$m_fechaalta;
		

	function CLog($__row__='') {
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
		$this->m_id_contenido = 1;
		$this->m_id_contenidoaux = 1;
		$this->m_id_usuario = 1;		
		$this->m_logcode = -1;
		$this->m_accion = "";
		$this->m_fechaalta = "NOW()";
	}
	
	function Set($__row__) {
		
		$this->m_id = $__row__['logs.ID'];
		$this->m_id_contenido = $__row__['logs.ID_CONTENIDO'];
		$this->m_id_contenidoaux = $__row__['logs.ID_CONTENIDOAUX'];
		$this->m_id_usuario = $__row__['logs.ID_USUARIO'];
		$this->m_logcode = $__row__['logs.LOGCODE'];
		$this->m_accion = $__row__['logs.ACCION'];
		$this->m_fechaalta = $__row__['logs.FECHAALTA'];
		
	}
	
	function FullArray() {
		return array(
		'ID_CONTENIDO'=>$this->m_id_contenido,
		'ID_CONTENIDOAUX'=>$this->m_id_contenidoaux,
		'ID_USUARIO'=>$this->m_id_usuario,
		'LOGCODE'=>$this->m_logcode,
		'ACCION'=>$this->m_accion,
		'VALOR'=>$this->m_valor,
		'FECHAALTA'=>$this->m_fechaalta,		
		);
	}	
	
	
	function GetIpFilter() {
		
		$ip_remote = getenv("REMOTE_ADDR");
		
		return array(
				array( "LOGCODE",""),
				array( "FECHAALTA",">",date("Y-m-d") ),
				array( "FECHAALTA","<",date("Y-m-d",strtotime("+1 day"))),
				array( "VALOR","=",$ip_remote)
				);
	}
	
} 
?>