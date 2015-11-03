<?Php

class CMultiLang {

	var $m_activo;
	var $m_arraylangs;//('idioma'=>'COD')
	var $m_default;
	var $m_browser_auto;

	function SetDefault($default) {
		$this->m_default = $default;
	}
	
	function SetBrowserAuto( $auto ) {
		$this->m_browser_auto = $auto;
	}
	
	function CMultiLang( $_arraylangs_ , $_activar_=true, $_default_="EN" ) {
		$this->m_arraylangs = $_arraylangs_;	
		$this->m_activo = $_activar_;
		$this->SetDefault($_default_);
		
		global $__modulo__;
		
		//automaticamente selecciona el idioma de la interface...
		if ($__modulo__=="admin" || $__modulo__=="config") 
			$this->SelectLang( $GLOBALS['_LANG_'] );
	}
	
	function Activar() {		
		$this->m_activo = true;
	}
	
	function Desactivar() {
		$this->m_activo = false;
	}	
	
	function Activo() {
		return $this->m_activo;
	}
	
	function Banderas() {
		if ( $this->Activo() ) {
			$htmlstr = "<table><tr>";
			foreach($this->m_arraylangs as $idioma=>$codigo) {
				$htmlstr.= '<td><a href="javascript:toggleDivAll(\'did'.$codigo.'\');"><img title="'.$idioma.'" src="../../inc/images/flags/'.$codigo.'.jpg" width="29" height="13" border="0"></a></td>';
			}
			$htmlstr.= "</tr></table>";		
			return $htmlstr;
		} else return "";
	}
	
	function Esconder() {
		//javascript:toggleDivAll('didEN');		
		if ( $this->Activo() ) {
			echo '<script>';
			$htmlstr = "";
			foreach($this->m_arraylangs as $idioma=>$codigo) {
				$htmlstr.= 'hideDivAll(\'did'.$codigo.'\');'."\n";
			}
			echo $htmlstr;
			echo '</script>';			
		}
	}

	function RemoveLang( $lang_code ) {
		$newarray = array();
		foreach($this->m_arraylangs as $lang=>$code) {
			if (strtoupper(trim($code))!=strtoupper(trim($lang_code))) {
				$newarray[$lang] = $code;
			}
		}
		$this->m_arraylangs = $newarray;		
	}
	
	function AddLang( $lang_name, $lang_code ) {
		$newarray = array();
		
		$code_founded = false;
		
		foreach($this->m_arraylangs as $lang=>$code) {
			if (strtoupper(trim($code))!=strtoupper(trim($lang_code))) {
				$newarray[$lang] = $code;
			} else $code_founded = true;
		}
		
		if (!$code_founded) $newarray[$lang_name] = $lang_code;
		
		$this->m_arraylangs = $newarray;		
	}	
	
	function SaveLang() {
		
		$file_lang = "../../inc/include/lang.php";
		$str_constructor = "\$CMultiLang = new CMultiLang( array(";
		$coma = "";
		
		foreach($this->m_arraylangs as $lang=>$code) {
			$str_constructor.= $coma."'".$lang."'=>'".$code."'";
			$coma = ",";
		}
		$str_constructor.= "),";
		($this->m_activo) ? $str_constructor.= "true" : $str_constructor.= "false";		
		($this->m_default!="") ? $str_constructor.= ",'".$this->m_default."');" : $str_constructor.= ");"; 
		($this->m_browser_auto) ? $str_constructor.= "
		\$CMultiLang->SetBrowserAuto(true);" : $str_constructor.= "
		\$CMultiLang->SetBrowserAuto(false);";
				
		$filestring = '<?Php
		global $__modulo__;
		
		'.$str_constructor.'
				 
		?>';
		copy( $file_lang, $file_lang.date("d-m-y h-i-s").".bk.php");
		$file = fopen ( $file_lang, "w");
		if ($file) {
			fwrite( $file, $filestring);
			fclose ( $file );
			return true;
		} else return false;
	}
	
	function SelectLang( $lang_code ) {
		global $__lang__;
		global $_LANG_;
		global $CLang;
		
		global $__modulo__;
		
		$__lang__ = $lang_code;
		
		if ($this->m_browser_auto) {
		 	//selection automatique de language....(selon le navigateur)
			if ( $_SESSION["userlang"]=="" && $__modulo__ != "admin" ) {
				foreach($this->m_arraylangs as $idiom=>$code) {
					
					if ( strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'],strtolower( str_replace("SP","ES",$code) ) ) === false ) {
						//echo "not set auto in: ".str_replace("SP","ES",$code);
					} else {
						//echo "Set auto in: ".$code;
						$__lang__ = $code;
					}
					
				}
			}
			
			/*if ( $__lang__=="" && !isset($lang) && $__modulo__ != "admin" ) {
				if ( strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'],'es') === false ) {
					if ( strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'],'fr') === false ) {
						if ( strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'],'de') === false ) {
							$__lang__ = "";	
						} else $__lang__ = "DE";		
					} else $__lang__ = "FR";
				} else $__lang__ = "SP";
			}*/
		}
		
		if (isset($__lang__)) $_LANG_ = $__lang__;
		if ($__lang__=="") { $__lang__= $this->m_default; $_LANG_=$__lang__; } 
		
		$CLang = new CLanguage();
		
		$GLOBALS['str_lang'.$this->m_default] = $this->m_default;
		
		foreach($this->m_arraylangs as $idiom=>$code) {
			$GLOBALS['str_lang'.$code] = $code;
		}

		if ($__lang__==$this->m_default || $__lang__=="") {
			$__lang__="";
			$GLOBALS['str_lang'.$this->m_default] = "<b>".$GLOBALS['str_lang'.$this->m_default]."</b>";
		} else {
			$GLOBALS['str_lang'.$__lang__] = "<b>".$GLOBALS['str_lang'.$__lang__]."</b>";
		}
		
		if ($__lang__==$this->m_default) { $__lang__ = ""; $_LANG_="";}
		
	}

	function HideLangs() {
		$resstr = "<script>";
		foreach($this->m_arraylangs as $idiom=>$code) {
			$resstr.= "\n"."toggleDivAll('did$code');";
		}
		return $resstr."</script>";		
	}
	
	function Translate( &$text ) {
		global $CLang;
		
		return $CLang->Translate($text);
		
	}
	
	function ShowLangOptions() {
		
		global $__lang__;

		$resstr = '<div class="langoptions">';
		$sep = "";
		
		($__lang__=="")? $select="select": $select=""; 
		$resstr.= $sep.'<a href="?setlang=1&__lang__=" class="lang'.$select.'">'.$this->m_default.'</a>';
		$sep = " | ";
		
		foreach($this->m_arraylangs as $idiom=>$code) {
			($__lang__!="" && $__lang__==$code)? $select="select": $select="";
			$resstr.= $sep.'<a href="?setlang=1&__lang__='.$code.'" class="lang'.$select.'">'.$code.'</a>';
			$sep = " | ";
		}
		return $resstr."</div>";	
	}
}
?>