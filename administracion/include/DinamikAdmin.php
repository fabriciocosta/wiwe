<?php

global $__modulo__;

$__modulo__ = "config";

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
	require "../../inc/include/config.php";
	
	require "../../inc/core/CMultiLang.php";
	require "../../inc/core/CLang.php";
	require "../../inc/include/lang.php";

	require "../../inc/core/DinamikFunctions.php";
		
	require "../../inc/core/CTabla.php";
	define("DINAMIKADMIN_DEF","DINAMIKADMIN definido");
}
 
?>