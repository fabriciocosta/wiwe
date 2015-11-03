<?php

/**
 * class CError
 *
 * @version 06/09/2006
 * @copyright 2006 
 **/

 
class CError {

	var $m_tipo,
		$m_mensaje;

	function CError( $__tipo__, $__extra_message__="" ) {
		$this->Set( $__tipo__, $__extra_message__ );
	}
	
	function Set($__tipo__, $__extra_message__="") {
		$this->m_tipo = $__tipo__;
		$this->m_mensaje = $__extra_message__;
	}
}

class CErrorHandler {
	
	var $m_errores;
	var $m_class;
	
	function CErrorHandler() {
		
		$this->m_errores = array();	
	
	}
	
	function ErrorsCount() {
		return count($this->m_errores);
	}
	
	function PopError() {
		if ( count($this->m_errores) > 0) {
			$last = count($this->m_errores)-1;
			$CError = $this->m_errores[ $last ];
			$this->m_errores = array_slice( $this->m_errores , 0, $last );
		} else {
			$CError = null;
		}			
		return $CError;
	}
	
	function PushError( $CError ) {
		$this->m_errores[count($this->m_errores)] = $CError;
	}
	
	function GetLastError() {
		if ( count($this->m_errores) > 0) {
			$last = count($this->m_errores)-1;
			$CError = $this->m_errores[ $last ];
		} else {
			$CError = null;
		}			
		return $CError;
	}
	
	function PopErrorStr() {
		global $CLang;
		$CError = $this->PopError();
		if (is_object($CError)) {
			return $CLang->Get($CError->m_tipo,true);
		}
		return "<br/>\nNo more errors in class [$this->m_class]";
	}	
	
	function PopErrorFullStr() {
		global $CLang;
		$CError = $this->PopError();
		if (is_object($CError)) {
			return "Error class [$this->m_class]:".$CError->m_mensaje." Type:".$CError->m_tipo." Detail: ".$CLang->Get($CError->m_tipo,true);
		}
		return "";
		return "<br/>\nNo more errors in class [$this->m_class] ";
	}	
	
	function PopAllErrorsFullStr() {
		global $CLang;
		$allerrors = "";		
		while( $this->ErrorsCount()>0 ) {
			$CError = $this->PopError();
			if (is_object($CError)) {
				$allerrors.= "<br/><br/>Error class [$this->m_class]:".$CError->m_mensaje." Type:".$CError->m_tipo." Detail: ".$CLang->Get($CError->m_tipo,true);
			}	
		}		
		if ($allerrors!="") return $allerrors;
				
		return "<br/>\nNo more errors in class [$this->m_class]";
	}
	
	function PopErrorsCascade() {
		return $this->PopAllErrorsFullStr();
	}
	
}

 
?>