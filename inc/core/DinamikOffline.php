<?

/*
	modificado: 27/10/2006 CMultiLang
	modificado: 06/09/2006	
	modificado: 17/10/2004

*/

//si es DINAMIK
if (defined("DINAMIK_DEF")) {
	//echo DINAMIK_DEF;	
} else {
	
	define("DINAMIK_DEF","DINAMIK definido");
	
	if (!defined("DINAMIKADMIN_DEF")) {
		require "../../inc/include/config.offline.php";	
		
		require "../../inc/core/CLang.php";
		require "../../inc/core/CMultiLang.php";
		require "../../inc/include/lang.php";
		
		require "../../inc/core/DinamikFunctions.php";
		
		require "../../inc/core/CTabla.php";
	}
	
	require "../../inc/core/CErrores.php";
	
	require "../../inc/core/CFunctions.php";	
	
	
}



?>