<?php

/**
 * class CLogs
 *
 * @version 4.0 29/08/2012
 * @copyright 2012 
 **/
 
class CLogs extends CErrorHandler {

	var $m_tlogs;//tabla logs
	var $m_Str2IntArray,$m_Int2StrArray;
	var $m_tiposlogs;
	
	var $m_CContenidos;
	
	var $m_templates;	
	
	function CLogs(&$__tlogs__,&$__m_CContenidos__) {
				
		$this->m_tiposlogs = $GLOBALS["_ttiposlogs_"];
		$this->Set($__tlogs__, $__m_CContenidos__);
	}
	
	function Set(&$__tlogs__,&$__m_CContenidos__) {

		global $__base__;
		
		$__base__ = array(
			"template"=>"",
			"templateheader"=>"",
			"templatefooter"=>"",
			"templatespacer"=>"",
			"templaterows"=>"",
			"templatecolumns"=>"",
			"tipocampo"=>"",
			"objeto"=>"",
			"multiplicator"=>"",
			"rounded"=>"",
			"rows"=>"",
			"cols"=>"",
		);
		$this->m_CContenidos = &$__m_CContenidos__;
		$this->m_tlogs = &$__tlogs__;
		
		
	  	
		parent::CErrorHandler();
	}
	
	function IsValid( &$__CLog__ ) {
		$valid = false;
		
		$valid = ($this->m_tiposlogs[$__CLog__->m_logcode]!="");
		
		return $valid;
	}
	
	
	function GetLog( $_id_ ) {
		$tl = $this->m_tlogs;
		
		$tl->LimpiarSQL();			
	    $tl->FiltrarSQL('ID','',$_id_);
	    $tl->Open();		
		
		if ( $tl->nresultados>0 ) {		
			$_row_ = $tl->Fetch();
			$Log = new CLog($_row_);
			return $Log;			
		}	
		
		return null;
		
	}
	
	
	function SetTemplate($__logcode__,$__template__="",$__header__="",$__footer__="",$__spacer__="",$__rows__="",$__columns__="") {
		$this->m_templates[$__logcode__]["template"] = $__template__;
		$this->m_templates[$__logcode__]["templateheader"] = $__header__;
		$this->m_templates[$__logcode__]["templatefooter"] = $__footer__;
		$this->m_templates[$__logcode__]["templatespacer"] = $__spacer__;
		$this->m_templates[$__logcode__]["templaterows"] = $__rows__;
		$this->m_templates[$__logcode__]["templatecolumns"] = $__columns__;
	}
		
	function Log( &$_CLog_ ) {
		
		$Log = $_CLog_;
		
		if (is_array($_CLog_)) {
			$Log = new CLog($_CLog_);			
		} 
		
		if (is_object($Log)) {
			if ($this->IsValid($Log)) {
				$tl = $this->m_tlogs;
				
				$fullarray = $Log->FullArray();
				return $tl->InsertarRegistro($fullarray);				
			}
		}
		
	}
	
	/**
	 * 
	 * 
	 * 
	 * 
	 * @param CLog $__CNewLog__
	 * @param array $__fields_to_filter_by__
	 * @return true or false on insert fail
	 */
	function LogFiltered( &$__CNewLog__, $__fields_to_filter_by__ ) {
		
		$tl = $this->m_tlogs;
		
		$tl->LimpiarSQL();		
		foreach( $__fields_to_filter_by__ as $filter) {
			
			$field = $filter[0];
			$filter_op = $filter[1];
			$filter_value = $filter[2];
			
			$tl->FiltrarSQL( $field, '', $filter_value, $filter_op );
		}		
		$tl->Open();
		if ($tl->nresultados==0) {
			
			//ShowMessage($tl->SQL);
			
			$res = $this->Log( $__CNewLog__ );
			if (!$res) {
				//ShowError("LogFiltered insert error");
			} else {
				//ShowMessage("LogFiltered Insert OK ");
			}
			return $res;				
		} else {
			//ShowMessage("LogFiltered was Filtered");
		}
		return true;
	}
	
	function LogCounter() {
		
		$ip_remote = getenv("REMOTE_ADDR");
		
		$tl = $this->m_tlogs;
		$tl->LimpiarSQL();
		$tl->FiltrarSQL( 'LOGCODE','',LOGCODE_SITE_ACCESS);
		$tl->FiltrarSQL( 'FECHAALTA', '', date('Y-m-d'),'>' );
		$tl->FiltrarSQL( 'FECHAALTA', '', date('Y-m-d',strtotime('+1 day')),'<' );
		$tl->FiltrarSQL( 'VALOR','',"".$ip_remote."");
		$tl->Open();
		//ShowMessage($tl->SQL);
		if ($tl->nresultados==0) {
			
			$NewLog = new CLog();
			
			$NewLog->m_logcode = LOGCODE_SITE_ACCESS;
			$NewLog->m_accion = "counter";
			$NewLog->m_valor = $ip_remote;
			
			$res = $this->Log( $NewLog );
			if (!$res) {
				//ShowError("Log insert error");
			} else {
				//ShowMessage("Log OK");
			}
			return $res;
		} else {
			//ShowMessage("IP".$ip_remote." Already Counted");
		}
		return true;		
		
	}
	
	function GetCounter() {
		$tl = $this->m_tlogs;
		$tl->LimpiarSQL();
		$tl->FiltrarSQL('ACCION', '', 'counter');
		$tl->FiltrarSQL('LOGCODE', '', LOGCODE_SITE_ACCESS);
		$tl->Open();		
		if ($tl->nresultados>0) {
			return $tl->nresultados;
		} else return 0;
		
	}
	
	function GetColumn( $_log_field_name_, $_resultados_ ) {
		if ( in_array($_log_field_name_,$this->m_tlogs->m_campos) ) {
			
			if ($_resultados_) {
				while($rl=$this->m_tlogs->Fetch($_resultados_)) {
					$Log = new CLog($rl);
				}
			}
			
			
		}
	}
	
	function Graficar( $_field_A, $_field_B, $_template_, $_resultado_ ) {
		if ($_field_A_=="ID_CONTENIDO") {
			//traer el contenido
			$this->m_CContenidos->GetContenidoCompleto($xxx);
		}
	}
	
	
} 
?>