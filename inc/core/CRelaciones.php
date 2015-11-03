<?php

 
class CRelaciones  extends CErrorHandler {

	var $m_trelaciones;//tabla archivos
	var $m_CTiposRelaciones;//miembro de la clase CTiposArchivos
	var $m_CContenidos;
	var $m_CSecciones;
	var $m_CRelacion;
	
	function CRelaciones(&$__trelaciones__,&$__m_CTiposRelaciones__) {
		$this->Set( $__trelaciones__, $__m_CTiposRelaciones__);
	}
	
	function Set(&$__trelaciones__,&$__m_CTiposRelaciones__) {
		$this->m_CTiposRelaciones = &$__m_CTiposRelaciones__;
		$this->m_CContenidos = &$__m_CContenidos__;
		$this->m_CSecciones = &$__m_CSecciones__;
		$this->m_trelaciones = &$__trelaciones__;
		parent::CErrorHandler();
	}
	
	function Actualizar( &$__CRelacion__ ) {
		return $this->m_trelaciones->ModificarRegistro( 
						$__CRelacion__->m_id, $__CRelacion__->FullArray() );		
	}
	
	/**
	 * Crea una relacion en función de los parámetros
	 * contenido a contenido
	 * contenido a seccion
	 * o seccion a seccion
	 * 
	 * se pueden generar relaciones de 2 a 1 , o de 1 a 2, aun no tiene aplicacion (dependencias, polimorfismos, etc)
	 *  
	 *
	 * @param Integer $__id_tiporelacion__  el id que representa a la relacion, atencion ahora esta asociada tambien al id_tipodetalle
	 * @param Integer $__id_contenido__
	 * @param Integer $__id_contenido_rel__
	 * @param Integer $__id_seccion__
	 * @param Integer $__id_seccion_rel__
	 * @param String $__sentido__
	 * @param Integer $__peso__
	 * @param Integer $__distancia__
	 */
	function CrearRelacion( $__id_tiporelacion__, $__id_contenido__=0, $__id_contenido_rel__=0, $__id_seccion__=0, $__id_seccion_rel__=0, $__sentido__="direct", $__peso__=0, $__distancia__=0 ) {
		
		$CRelacion = new CRelacion(0); ///empty
		$CRelacion->m_id_tiporelacion = $__id_tiporelacion__;
		$CRelacion->m_id_contenido = $__id_contenido__;
		$CRelacion->m_id_contenido_rel = $__id_contenido_rel__;
		$CRelacion->m_id_seccion = $__id_seccion__;
		$CRelacion->m_id_seccion_rel = $__id_seccion_rel__;
		
		$exito = $this->m_trelaciones->InsertarRegistro(	
			$CRelacion->FullArray() ) ;
				
		if ($exito) {
			return true;
		} else {
			DebugError("No se pudo crear la relacion");
			return false;
		}
		
	}
	
	function RelacionExists( $__id_tiporelacion__, $__id_contenido__, $__id_contenido_rel__, $__id_seccion__, $__id_seccion_rel__ ) {
		
		$this->m_trelaciones->LimpiarSQL();
		//$GLOBALS['_f_ID'] = $__idseccion__;			
		
		
		$this->m_trelaciones->FiltrarSQL('ID_TIPORELACION', '', $__id_tiporelacion__ );
		$this->m_trelaciones->FiltrarSQL('ID_CONTENIDO', '', $__id_contenido__ );
		$this->m_trelaciones->FiltrarSQL('ID_CONTENIDO_REL', '', $__id_contenido_rel__ );
		$this->m_trelaciones->FiltrarSQL('ID_SECCION', '', $__id_seccion__ );
		$this->m_trelaciones->FiltrarSQL('ID_SECCION_REL', '', $__id_seccion_rel__ );
		
		 
		$this->m_trelaciones->Open();		
			
		if ( $this->m_trelaciones->nresultados>=1 ) {			
			return true;									
		}	else {
			return false;
		}			
		
	}
	
	function CrearRelacionUnica( $__id_tiporelacion__, $__id_contenido__=0, $__id_contenido_rel__=0, $__id_seccion__=0, $__id_seccion_rel__=0, $__sentido__="direct", $__peso__=0, $__distancia__=0 ) {
		$exists = $this->RelacionExists( $__id_tiporelacion__, $__id_contenido__, $__id_contenido_rel__, $__id_seccion__, $__id_seccion_rel__ );
		if (!$exists) {
			return $this->CrearRelacion( $__id_tiporelacion__, $__id_contenido__, $__id_contenido_rel__, $__id_seccion__, $__id_seccion_rel__, $__sentido__, $__peso__, $__distancia__ );
		}
		return true;//ya existe! todo ok
	}
	
	
	function EliminarRelacion( $__id_relacion__='' ) {
					$_exito_ = true;
					$this->m_trelaciones->LimpiarSQL();
	        $this->m_trelaciones->SQL = 'DELETE FROM relaciones WHERE ID='.$__id_relacion__;
	        $_exito_ = $this->m_trelaciones->EjecutaSQL();
	        if (!$_exito_) DebugError("relaciones id_contenido");
	        return $_exito_;
	}
	
	function EliminarRelacionX( $__id_tiporelacion__='', $__id_contenido__=0, $__id_contenido_rel__=0, $__id_seccion__=0, $__id_seccion_rel__=0 ) {

					$_exito_ = true;
					$this->m_trelaciones->LimpiarSQL();
	        $this->m_trelaciones->SQL = 'DELETE FROM relaciones WHERE ID_TIPORELACION='.$__id_tiporelacion__.' AND ID_CONTENIDO='.$__id_contenido__.' AND ID_CONTENIDO_REL='.$__id_contenido_rel__.' AND ID_SECCION='.$__id_seccion__.' AND ID_SECCION_REL='.$__id_seccion_rel__;
	        $_exito_ = $this->m_trelaciones->EjecutaSQL();
	        if (!$_exito_) DebugError("EliminarRelacionX");
	        return $_exito_;
		
	}	
	
	function EliminarRelaciones( $__id_contenido__='', $__id_seccion__='' ) {
	    
		$_exito_ = true;
	    
		if ($__id_contenido__!='') {
			
	        $this->m_trelaciones->LimpiarSQL();
	        $this->m_trelaciones->SQL = 'DELETE FROM relaciones WHERE ID_CONTENIDO='.$__id_contenido__;
	        $_exito_ = $this->m_trelaciones->EjecutaSQL();
	        if (!$_exito_) DebugError("relaciones id_contenido");
	        
	        $this->m_trelaciones->LimpiarSQL();
	        $this->m_trelaciones->SQL = 'DELETE FROM relaciones WHERE ID_CONTENIDO_REL='.$__id_contenido__;
	        $_exito_ = $this->m_trelaciones->EjecutaSQL();
	        if (!$_exito_) DebugError("relaciones id_contenido_rel");
		}
		
		if ($__id_seccion__!='') {
			
	        $this->m_trelaciones->LimpiarSQL();
	        $this->m_trelaciones->SQL = 'DELETE FROM relaciones WHERE ID_SECCION='.$__id_seccion__;
	        $_exito_ = $this->m_trelaciones->EjecutaSQL();
	        if (!$_exito_) DebugError("relaciones id_seccion");
	        
	        $this->m_trelaciones->LimpiarSQL();
	        $this->m_trelaciones->SQL = 'DELETE FROM relaciones WHERE ID_SECCION_REL='.$__id_seccion__;
	        $_exito_ = $this->m_trelaciones->EjecutaSQL();
	        if (!$_exito_) DebugError("relaciones id_seccion_rel");
	        
		}
		
		return $_exito_;
	}
	
	
	/**
	 * Connect a content to a content
	 *
	 * @param CContenido $Contenido_1
	 * @param CContenido $Contenido_2
	 * @return true on success, false on failure
	  */
	function ConnectC2C( $__id_tiporelacion__, &$Contenido_1, &$Contenido_2 ) {
		return $this->CrearRelacion( $__id_tiporelacion__, $Contenido_1->m_id, $Contenido_2->m_id );
	}

	/**
	 * Connect a content to a section...
	 *
	 * @param CSeccion $Seccion
	 * @param CContenido $Contenido
	 * @return true on success, false on failure
	 */
	function ConnectC2S( $__id_tiporelacion__, &$Contenido, &$Seccion ) {
		return $this->CrearRelacion( $__id_tiporelacion__, $Contenido_1->m_id, 0, 0, $Seccion->m_id );
	}
	
	/**
	 * Connect a section to a section
	 *
	 * @param CSeccion $Seccion_1
	 * @param CSeccion $Seccion_2
	 * @return true on success, false on failure
	 */	
	function ConnectS2S( $__id_tiporelacion__, &$Seccion_1, &$Seccion_2 ) {
		return $this->CrearRelacion( $__id_tiporelacion__, 0, 0, $Seccion_1->m_id, $Seccion_2->m_id );
	}
	
	function GetRelacion( $__id_relacion__, $__filtro__='' ) {
		
	
		$this->m_trelaciones->LimpiarSQL();
		//$GLOBALS['_f_ID'] = $__idseccion__;			
		if ($__id_relacion__!='')  {
			$this->m_trelaciones->FiltrarSQL('ID',$__filtro__, $__id_relacion__);
		} else {
			$this->m_trelaciones->FiltrarSQL('ID',$__filtro__,'0','_superior_ID');
		} 
		$this->m_trelaciones->Open();		
			
		if ( $this->m_trelaciones->nresultados==1 ) {			
			$_row_ = $this->m_trelaciones->Fetch();
			$this->m_CRelacion = new CRelacion($_row_);
			return $this->m_CRelacion;									
		}	else {
			return null;
		}			
	}
	
	function GetRelacionPorTipo( $__id_tiporelacion__, $__filtro__='' ) {

		if ($__filtro__!="") $__filtro__ = "/*SPECIAL*/ ".$__filtro__; 
	
		$this->m_trelaciones->LimpiarSQL();
		//$GLOBALS['_f_ID'] = $__idseccion__;			
		if ($__id_tiporelacion__!='')  {
			$this->m_trelaciones->FiltrarSQL('ID_TIPORELACION',$__filtro__, $__id_tiporelacion__);
		} else {
			$this->m_trelaciones->FiltrarSQL('ID',$__filtro__,'0','_superior_ID');
		} 
		$this->m_trelaciones->Open();		
			
		if ( $this->m_trelaciones->nresultados>0 ) {			
			$_row_ = $this->m_trelaciones->Fetch();
			$this->m_CRelacion = new CRelacion($_row_);
			return $this->m_CRelacion;									
		}	else {
			return null;
		}			
	}	
	
}
?>