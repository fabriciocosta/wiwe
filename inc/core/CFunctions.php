<?php

function IP() {
	
	return getenv("REMOTE_ADDR");
}

class CFunctions extends CErrorHandler {

	var $m_functions_names;
		


	function CFunctions( ) {
		parent::CErrorHandler();		
		
		$this->m_functions_names = array();

		$this->AddFunction("IP");
		//echo "<pre>".	print_r($this->m_functions_names)."</pre>";
	}
	
	function AddFunction( $function_name ) {
		
		$this->m_functions_names[$function_name] = true;
		
	}
	
	function ExecuteFunction( $function_name, $parameters ) {
		
		$parameters_ex = explode(",",$parameters);
		
		if ( $this->FunctionExists( $function_name ) ) {
			
			$functiontoexecute = "".$function_name;
			if ( is_array($parameters_ex) && count($parameters_ex)>1 ) {
				switch( count($parameters_ex)) {
					case 2:
						return $functiontoexecute( $parameters_ex[0], $parameters_ex[1] );
						break;
					case 3:
						return $functiontoexecute( $parameters_ex[0], $parameters_ex[1], $parameters_ex[2] );
						break;
					case 4:
						return $functiontoexecute( $parameters_ex[0], $parameters_ex[1], $parameters_ex[2], $parameters_ex[3] );
						break;
					case 5:
						return $functiontoexecute( $parameters_ex[0], $parameters_ex[1], $parameters_ex[2], $parameters_ex[3], $parameters_ex[4] );
						break;
					case 6:
						return $functiontoexecute( $parameters_ex[0], $parameters_ex[1], $parameters_ex[2], $parameters_ex[3], $parameters_ex[4], $parameters_ex[5] );
						break;						
					case 7:
						return $functiontoexecute( $parameters_ex[0], $parameters_ex[1], $parameters_ex[2], $parameters_ex[3], $parameters_ex[4], $parameters_ex[5], $parameters_ex[6] );
						break;						
					case 8:
						return $functiontoexecute( $parameters_ex[0], $parameters_ex[1], $parameters_ex[2], $parameters_ex[3], $parameters_ex[4], $parameters_ex[5], $parameters_ex[6], $parameters_ex[7] );
						break;						
				}
				
			}
			return $functiontoexecute( $parameters );
			
		} else {
			echo " CFunctions:: function ".$function_name." doesn't exist. ";
		}
		return "";
	}
	
	function ParseFunctions( &$__template__ ) {
		
		///parsing depending on [#FUNCTIONAME#](PARAMETERS)
		///FUNCTIONNAME has no spaces
		///PARAMETERS has variables separated by comas...
		
	}
	
	function FunctionExists( $function_name ) {
		if ( $this->m_functions_names[$function_name] ) {
			return true;
		}
		return false;
	}
	
	function Process(&$text) {

		$matches = array();

		/*FUNCTIONS*/
		preg_match_all( "/\[(.*?)\]\((.*?)\)/", $text, $matches );
//		echo "<pre>".	print_r($matches,true)."</pre>";

		foreach( $matches[0] as $k=>$match) {
			//$match_txt = substr( $match[0], 1, strlen($match)-2 );
			$function_name = $matches[1][$k];
			$parameters = $matches[2][$k];
			//Debug("CFunction::Process > MATCH:".$match." function_name: \"".$function_name."\" parameters: \"".$parameters."\"");
			if ( $this->FunctionExists($function_name) ) {
				$text = str_replace( $match, $this->ExecuteFunction( $function_name, $parameters ), $text);
			}
		}

		/*CONSTANTS*/		
		
		return $text;

	}
	
	function Test() {
		$test = "
TEST IP(1) = [IP](1) 
<br> 
TEST OP(2) = [OP](2)
<br> 
TEST IP() = [IP]()
";

		echo $test;
		echo "<br>result: <br><b>".$this->Process( $test )."</b>";
	}
	
}

global $CFun;

$CFun =  new CFunctions();
//$CFun->Test();

if (file_exists('../../inc/include/CFunctionsExtended.php')) { 
	require '../../inc/include/CFunctionsExtended.php';
}

?>