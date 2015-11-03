<?Php
   function register_global_array( $superglobal)
	{
	foreach($superglobal as $varname => $value)
	{
	global $$varname;
	$$varname = $value;
	}
	}

	function register_globals($order = 'egpcs')
	{
	
	   
	    $order = explode("\r\n", trim(chunk_split($order, 1)));
	    foreach($order as $k)
	    {
	        switch(strtolower($k))
	        {
	            case 'e':    register_global_array($_ENV);        break;
	            case 'g':    register_global_array($_GET);        break;
	            case 'p':    register_global_array($_POST);        break;
	            case 'c':    register_global_array($_COOKIE);    break;
	            case 's':    register_global_array($_SERVER);    break;
	        }
	    }
	}
	
	register_globals();
	
global $directorio;
$output = array();
if ($directorio=="") {
	echo "Debe especificar el directorio: wiwe-install.php?directorio=../midirectorio   o   wiwe-install.php?directorio=midirectorio";
} else {
	echo "Directorio:".$directorio;
	echo "<br>Resultado:[".exec( "unzip wiwe.zip -d ".$directorio, $output)."]";
	foreach($output as $line) {
		print "<br>".$line;
	}
}
?>