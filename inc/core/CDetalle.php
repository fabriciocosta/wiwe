<?php

/**
 * class CDetalle
 *
 * @version 17/10/2004
 * @copyright 2004 
 **/


 
class CDetalle {

	var $m_id,
		$m_id_tipodetalle,		
		$m_id_contenido,
		$m_entero,
		$m_fraccion,
		$m_detalle,
		$m_ml_detalle,
		$m_txtdata,
		$m_ml_txtdata,
		$m_bindata,
		$m_id_usuario_creador,
		$m_id_usuario_modificador,
		$m_actualizacion,
		$m_baja;

	var $m_CReference;

	function CDetalle($__row__="") {
		if ($__row__!="" && is_array($__row__) )
			$this->Set($__row__);
			
		if ( $__row__=="" || $__row__==0 ) $this->SetEmpty();
	}
	
	function SetEmpty() {
		
		$this->m_id = 0;
		$this->m_id_tipodetalle = 0;
		$this->m_id_contenido = 0;
		$this->m_entero = 0;
		$this->m_fraccion = 0;
		$this->m_detalle = "";
		$this->m_ml_detalle = "";
		$this->m_txtdata = "";
		$this->m_ml_txtdata = "";
		$this->m_bindata = "";
		$this->m_id_usuario_creador = 1;
		$this->m_id_usuario_modificador = 1;
		$this->m_actualizacion = "";
		$this->m_baja = "S";
				
	}
	
	function Set($__row__) {
		
		$this->m_id = $__row__['detalles.ID'];
		$this->m_id_tipodetalle = $__row__['detalles.ID_TIPODETALLE'];
		if (isset($__row__['detalles.ID_CONTENIDO'])) $this->m_id_contenido = $__row__['detalles.ID_CONTENIDO'];
		$this->m_entero = $__row__['detalles.ENTERO'];
		$this->m_fraccion = $__row__['detalles.FRACCION'];
		$this->m_detalle = stripslashes($__row__['detalles.DETALLE']);
		$this->m_ml_detalle = stripslashes($__row__['detalles.ML_DETALLE']);
		$this->m_txtdata = stripslashes($__row__['detalles.TXTDATA']);
		$this->m_ml_txtdata = stripslashes($__row__['detalles.ML_TXTDATA']);
		$this->m_bindata = $__row__['detalles.BINDATA'];
		if (isset($__row__['detalles.ID_USUARIO_CREADOR'])) $this->m_id_usuario_creador = $__row__['detalles.ID_USUARIO_CREADOR'];
		if (isset($__row__['detalles.ID_USUARIO_MODIFICADOR'])) $this->m_id_usuario_modificador = $__row__['detalles.ID_USUARIO_MODIFICADOR'];
		if (isset($__row__['detalles.ACTUALIZACION'])) $this->m_actualizacion = $__row__['detalles.ACTUALIZACION'];
		if (isset($__row__['detalles.BAJA'])) $this->m_baja = $__row__['detalles.BAJA'];
		
		$__row2__ = array();
		$cc = 0;
		$cs = 0;
		$this->m_CReference = null;
		$isRCont = false;
		$isRSec = false;
		
		foreach($__row__ as $key=>$value) {
			if (!$isRCont) $isRCont = is_numeric( strpos( $key, "REFERENCIA.TITULO") );
			if (!$isRSec) $isRSec = is_numeric( strpos( $key, "REFERENCIA.NOMBRE") );
		}
		
		foreach($__row__ as $key=>$value) {
				
			if ( ! (strpos( $key, "REFERENCIA.")===false) ) {
				if ($isRCont) { $__row2__[ str_replace( "REFERENCIA.", "contenidos.", $key ) ] = $value; $cc++; }
				else if ($isRSec) { $__row2__[ str_replace( "REFERENCIA.", "secciones.", $key ) ] = $value; $cs++; }
				//echo "<br>".$key."=>".$value;				
			}
		}
		//must be superior to 1 (default value)		
		if ($cc>1 && $__row2__["contenidos.ID"]>1) {
			//echo print_r($__row2__,true);
			$this->m_CReference = new CContenido( $__row2__ );
		}
		
		//must be superior to 1 (default value)		
		if ($cs>1 && $__row2__["secciones.ID"]>1) {
			//echo print_r($__row2__,true);
			$this->m_CReference = new CSeccion( $__row2__ );
		}		
					
	}
	
	function ToGlobals() {
		
		$GLOBALS["_e_ID"] = $this->m_id;
		$GLOBALS["_e_ID_TIPODETALLE"] = $this->m_id_tipodetalle;
		$GLOBALS["_e_ID_CONTENIDO"] = $this->m_id_contenido;
		$GLOBALS["_e_ENTERO"] = $this->m_entero;
		$GLOBALS["_e_FRACCION"] = $this->m_fraccion;
		$GLOBALS["_e_DETALLE"] = $this->m_detalle;
		$GLOBALS["_e_ML_DETALLE"] = $this->m_ml_detalle;
		$GLOBALS["_e_TXTDATA"] = $this->m_txtdata;
		$GLOBALS["_e_ML_TXTDATA"] = $this->m_ml_txtdata;
		$GLOBALS["_e_BINDATA"] = $this->m_bindata;
		$GLOBALS["_e_ID_USUARIO_CREADOR"] = $this->m_id_usuario_creador;
		$GLOBALS["_e_ID_USUARIO_MODIFICADOR"] = $this->m_id_usuario_modificador;
		$GLOBALS['detalles.ACTUALIZACION'] = $this->m_actualizacion;
		$GLOBALS['detalles.BAJA'] = $this->m_baja;		
		
	}

	function FullArray() {
		
		return array(
			'ID'=>$this->m_id,
			'ID_TIPODETALLE'=>$this->m_id_tipodetalle,
			'ID_CONTENIDO'=>$this->m_id_contenido,
			'ENTERO'=>$this->m_entero,
			'FRACCION'=>$this->m_fraccion,
			'DETALLE'=>$this->m_detalle,
			'ML_DETALLE'=>$this->m_ml_detalle,
			'TXTDATA'=>$this->m_txtdata,
			'ML_TXTDATA'=>$this->m_ml_txtdata,
			'BINDATA'=>$this->m_bindata,
			'ID_USUARIO_CREADOR'=>$this->m_id_usuario_creador,
			'ID_USUARIO_MODIFICADOR'=>$this->m_id_usuario_modificador,
			'ACTUALIZACION'=>$this->m_actualizacion,
			'BAJA'=>$this->m_baja );
		
	}
}

?>