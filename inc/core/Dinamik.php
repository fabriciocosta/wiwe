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
		require "../../inc/recaptcha/recaptchalib.php";
		require "../../inc/include/config.php";	
		
		require "../../inc/core/CLang.php";
		require "../../inc/core/CMultiLang.php";
		require "../../inc/include/lang.php";
		
		require "../../inc/core/DinamikFunctions.php";
		
		require "../../inc/core/CTabla.php";
	}
	
	require "../../inc/core/CErrores.php";
	
	require "../../inc/core/CFunctions.php";
			
	//Secciones funcs
	require "../../inc/core/CSeccion.php";
	require "../../inc/core/CTipoSeccion.php";	
	require "../../inc/core/CTiposSecciones.php";
	require "../../inc/core/CSecciones.php";		
	//Contenidos funcs
	require "../../inc/core/CContenido.php";
	require "../../inc/core/CTipoContenido.php";	
	require "../../inc/core/CTiposContenidos.php";
	require "../../inc/core/CContenidos.php";		
	//Archivos funcs	
	require "../../inc/core/CArchivo.php";
	require "../../inc/core/CTipoArchivo.php";	
	require "../../inc/core/CTiposArchivos.php";
	require "../../inc/core/CArchivos.php";			
	//Detalles funcs
	require "../../inc/core/CDetalle.php";
	require "../../inc/core/CTipoDetalle.php";	
	require "../../inc/core/CTiposDetalles.php";
	require "../../inc/core/CDetalles.php";
	//Usuarios
	require "../../inc/core/CUsuario.php";
	require "../../inc/core/CUsuarios.php";
	//Relaciones
	require "../../inc/core/CTipoRelacion.php";	
	require "../../inc/core/CTiposRelaciones.php";
	require "../../inc/core/CRelacion.php";
	require "../../inc/core/CRelaciones.php";
	
	//Logs
	require "../../inc/core/CLog.php";
	require "../../inc/core/CLogs.php";

	require "../../inc/core/CSitio.php";	
	
	
}



?>