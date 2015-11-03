<?Php
//version 4.2 10/10/2006
//version 4.1 06/10/2006
//version 4.0 18/07/2006
function file_extension($filename)
		{
			$path_info = pathinfo($filename);
			return $path_info['extension'];
		}
		
class CLanguage {
	
	var $m_ErrorMessages;
	var $m_Messages;
	var $m_Words;
	var $m_Users;
	
	function CLanguage() {		
		
		global $__lang__;
		
		$ignore = array( '.', '..' ); 
		$myDirectory = opendir("../../inc/lang");
		// get each entry
		while($entryName = readdir($myDirectory)) {
			$dirArray[] = $entryName;
		}
		// close directory
		closedir($myDirectory);		
		
		foreach($dirArray as $entry) {
			if( !in_array( $entry, $ignore ) && is_file("../../inc/lang/".$entry) && file_extension($entry)=="csv" ) {
				$entry = "../../inc/lang/".trim($entry);
					
		
				
				$Lignes = file($entry);
				
				$ln = $Lignes[0];
				$langi = -1;
				$lnx = explode( ";" , $ln );
				for( $i = 0; $i < count($lnx); $i++) {
					if ($lnx[$i]==$__lang__) {
						$langi = $i;
						break;
					}
				}
				
				if ( !is_numeric($langi) || $langi==-1 ) {
					/*die("No $__lang__ column in $entry file.");*/
				} else	{			
					for( $cn = 1; $cn < count($Lignes); $cn++ ) {
					 	$ln = $Lignes[$cn];
					 	$lnx = explode( ";" , $ln );
					 	if (count($lnx)>1) {
							 	$group = $lnx[0];
							 	$code = strtoupper(trim($lnx[1]));
							 	$text = $lnx[$langi];
							 	if ($code!="")
								 	switch($group) {
								 		case "USERS":
								 			$this->m_Users[$code] = $text;
								 			break;
								 		case "MESSAGES":
								 			$this->m_Messages[$code] = $text;
								 			break;
								 		case "ERRORMESSAGES":
								 			$this->m_ErrorMessages[$code] = $text;
								 			break;
								 		default:
								 			$this->m_Words[$code] = $text;
								 			break;			 						 						 			
								 	}
					 	} ///count  > 1
					 	
				 	}
				}
		
			 	$this->m_Words["XXX"] = "XXX";
			}
		}

	}
	
	function Get( $__str_code__, $showcode=false ) {
		
		if ( isset( $this->m_ErrorMessages[$__str_code__] ) ) {
			return $this->m_ErrorMessages[$__str_code__];	
		} else if (isset( $this->m_Messages[$__str_code__])) {
			return $this->m_Messages[$__str_code__];
		} else if (isset( $this->m_Words[$__str_code__])) {
			return $this->m_Words[$__str_code__];
		} else if (isset( $this->m_Users[$__str_code__])) {
			return $this->m_Users[$__str_code__];
		} else if (isset( $this->m_Users["USER".$__str_code__])) {
			///hay muchos que usan USER antes del campo... como USEREMAIL, USERFIRSTNAME
			return $this->m_Users["USER".$__str_code__];
		} else {
			/*return '<span class="error">CLanguage ERROR ['.$__str_code__.'] not found</span>';*/
			if ($showcode) return $__str_code__;
			return '';
		}	
	}
	
	function Translate(&$text) {

		$matches = array();
			
		preg_match_all( "/\{(.*?)\}/", $text, $matches );
		
		foreach( $matches[0] as $match) {
			$match_txt = substr( $match, 1, strlen($match)-2 );
			//echo "MATCH:".$match." : ".$match_txt."<br>";
			$translation = $this->Get( $match_txt );
			if ($translation!="") {
				$text = str_replace( $match, $translation, $text);
			}
		}
		
		return $text;
		
	}	
}


?>
