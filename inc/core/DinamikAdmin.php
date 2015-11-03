<?php

/**
 * CDinamikAdmin
 *
 * @version $Id$
 * @copyright 2003 
 **/
//si es DINAMIK
if (defined("DINAMIKADMIN_DEF")) {
	//echo DINAMIK_DEF;	
} else {
	define("DINAMIKADMIN_DEF","DINAMIKADMIN definido");
	require "../../inc/include/config.php";	

	require "../../inc/include/lang.php";
	
	require "../../inc/core/DinamikFunctions.php";
		
	require "../../inc/core/CTabla.php";
	
}
 
?>