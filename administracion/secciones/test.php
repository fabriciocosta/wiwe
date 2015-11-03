<?php

/**
 * testeo de ftp
 *
 * @version $Id$
 * @copyright 2003 
 **/
 
 require "../include/config.php";

function mkdir_ftp($dir) {
    $ftp_server = $GLOBALS["_FTPSERVER_"];
	$ftp_user_name = $GLOBALS["_FTPUSUARIO_"];
	$ftp_user_pass = $GLOBALS["_FTPCONTRASENA_"];
	$ftp_dir = $GLOBALS["_DIR_FTPREL"];	
	$conn_id = ftp_connect($ftp_server); 
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 
	if ((!$conn_id) || (!$login_result)) { 
        echo "FTP connection has failed!";
        //echo "Attempted to connect to $ftp_server for user $ftp_user_name"; 
        return false;
	}
	
	if(ftp_mkdir($conn_id,$ftp_dir.$dir)) {
		ftp_site($conn_id,"CHMOD +775 ".$ftp_dir.$dir);
		return true;
	} else {
		return false;
	}
	ftp_close($conn_id);		
}

if (mkdir_ftp('/secciones/test00')) {
	echo "terminado";
}
?>