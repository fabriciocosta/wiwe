<?

global $Contenidos, $TiposContenidos, $Detalles, $TiposDetalles, $Secciones, $TiposSecciones, $Relaciones, $TiposRelaciones, $Usuarios;
 
global $_nombre_modulo_;

global $_seccion_modulo_;
global $_ficha_contenido_modulo_;
global $_ficha_sistema_modulo_;
global $_sistema_modulo_; 


error_reporting(E_ERROR|E_WARNING);

function ModuloTemplateSelect() {
	global $CLang;
	$resstr = '<select id="modulostemplates" name="modulostemplates" onchange="javascript:document.getElementById(\'_nombre_modulo_\').value=document.getElementById(\'modulostemplates\').options[document.getElementById(\'modulostemplates\').selectedIndex].value;">';
	$resstr.= '<option value="">'.$CLang->Get('SELECT').'</option>'; 
	
	$ignore = array( '.', '..' ); 
	$myDirectory = opendir("../modules/");

	// get each entry
	while($entryName = readdir($myDirectory)) {
		$dirArray[] = $entryName;
	}
	
	// close directory
	closedir($myDirectory);
	
	foreach($dirArray as $entry) {
		if( !in_array( $entry, $ignore ) && is_dir("../modules/".$entry)) {
			$entry = strtolower(trim($entry));
			$resstr.= '<option value="'.$entry.'">'.$entry.'</option>';
		}
	}
	
	
	$resstr.= '</select>';
	return $resstr; 
}

function ModuloTemplateCrear($namemod) {
	
	$namemod = strtolower(trim($namemod));
	global $Contenidos, $TiposContenidos, $Detalles, $TiposDetalles, $Secciones, $TiposSecciones, $Relaciones, $TiposRelaciones;
	
	require "../modules/".$namemod."/".$namemod.".info.php";
			
		/** ============================================================================================
		 * 
		 * 
		 *  AUTOMATIZACION DEL PROCESO DE CREACION E INSTALACION DE MODULO 
		 * 
		 *  ============================================================================================ 
		 */				
		
		$_nombre_modulo_base = strtolower( str_replace(" ","_",$namemod) );
		
		$_nombre_modulo_total = "Modulo".str_replace( " ","",ucwords( strtolower( $namemod ) ) );
		$_nombre_modulo_modules = "../../inc/modules/".$_nombre_modulo_total.".php"; 
		$_nombre_modulo_principal = "../../principal/home/".$_nombre_modulo_base.".php";				
						
		/*crear los tipos de contenidos necesarios*/
		
		foreach($tiposcontenidos as $tipocontenido=>$data ) {
			
			$TipoContenido = new CTipoContenido();
			$TipoContenido->m_tipo = $data["tipo"];
			$TipoContenido->m_descripcion = $data["descripcion"];
			if ( $TiposContenidos->CrearTipoContenido( $TipoContenido ) ) {
				ShowMessage("Tipo de contenido: ".$TipoContenido->m_tipo." creado id=".$TipoContenido->m_id);				
			} else ShowError("Tipo de contenido: ".$TipoContenido->m_tipo." error");
			
			$TipoContenido = $TiposContenidos->GetTipoContenido( 0, $data["tipo"]);
			
			if ($TipoContenido!=null && $TipoContenido->m_id>0) {
				/*Creamos los detalles*/
				foreach($tiposdetalles[$tipocontenido] as $detalle=>$data) {
					$TipoDetalle = new CTipoDetalle();
					$TipoDetalle->m_id_tipocontenido = $TipoContenido->m_id;
					$TipoDetalle->m_tipo = $detalle;			
					$TipoDetalle->m_descripcion = $data["descripcion"];
					$TipoDetalle->m_tipocampo = $data["tipocampo"];
					$TipoDetalle->m_txtdata = $data["txtdata"];
					if ( $TiposDetalles->CrearTipoDetalle( $TipoDetalle ) ) {
						ShowMessage("Tipo de detalle: ".$TipoDetalle->m_tipo." creado id=".$TipoDetalle->m_id);
					} else ShowError("Tipo de detalle: ".$TipoDetalle->m_tipo." error");
				}
			} else {
				ShowError("Tipo de contenido no válido: ".$data["tipo"]." ob:".$TipoContenido." id:".$TipoContenido->m_id." ");
			}
		}
		
		/*crear los tipos de detalles asociados*/
		
		/*crear los tipos de secciones*/
		/*crear las secciones, luego los contenidos*/
		foreach($tipossecciones as $tiposeccion=>$data ) {
			$TipoSeccion = new CTipoSeccion();
			$TipoSeccion->m_tipo = $data["tipo"];
			$TipoSeccion->m_descripcion = $data["descripcion"];
			if ( $TiposSecciones->CrearTipoSeccion( $TipoSeccion ) ) {
				ShowMessage("Tipo de seccion: ".$TipoSeccion->m_tipo." creado id=".$TipoSeccion->m_id);				
			} else ShowError("Tipo de seccion: ".$TipoSeccion->m_tipo." error");
			
			$TipoSeccion = $TiposSecciones->GetTipoSeccion( 0, $data["tipo"]);
			
			if ($TipoSeccion!=null && $TipoSeccion->m_id>0) {
				
				/*Creamos las secciones*/		
				foreach($secciones[$tiposeccion] as $seccion=>$data ) {
					$Seccion = new CSeccion(0);
					$Seccion->m_id_seccion = $data["id_seccion"];
					$Seccion->m_id_tiposeccion = $TipoSeccion->m_id;
					$Seccion->m_nombre = $data['nombre'];
					$Seccion->m_categoria = $data['categoria'];
					$Seccion->m_descripcion = $data["descripcion"];
					$Seccion->m_carpeta = $data["carpeta"];
					$Seccion->m_orden = $data["orden"];
					
					if ( $Secciones->CrearSeccion( $Seccion ) ) {
						ShowMessage("Seccion: ".$Seccion->m_nombre." creado id=".$Seccion->m_id);
					} else ShowError("Seccion: ".$Seccion->m_nombre." error");
				}
			
			} else {
				ShowError("Tipo de sección no válida: ".$data["tipo"]." ob:".$TipoSeccion." id:".$TipoSeccion->m_id." ");
			}	
		}
		
		
		/*crear los archivos:
		principal/home/<modulo>.php
		inc/modules/Modulo<modulo>.php
		inc/css/<modulo>.css
		
		agregar la funcion a CSitioExtended.php
		
		function Modulo<modulo> {
		}
		
		agregar a templateadmin.php
		agregar templates
		agregar a postprocess...
		
		*/
		
		foreach($moduloarchivos as $file_source=>$file_destination) {
				if (file_exists($file_source)) {
					$file_src_str = implode('', file($file_source));
					ShowMessage("file:".$file_source." is ok.");
					
					$fhandle_dst = fopen( $file_destination, "w");
					///if (function_exists(chmod)) chmod("../../inc/modules", 777 );
					if ($fhandle_dst) {
						fwrite( $fhandle_dst, $file_src_str);
						fclose($fhandle_dst);
						ShowMessage("OK writting at ".$file_destination);
					} else {
						ShowError('cannot open '.$file_destination);
						ShowMessage(' > Activate write permissions in inc/modules with <pre>
			chmod 777 inc/modules
			chmod 777 inc/templates
			chmod 777 principal/home
			chmod 666 inc/include/CSitioExtended.php</pre> !');
						return false;
					}	
						
					
				} else ShowError("file:".$file_source." doesnt exist");
				
				
			
		}
		
		
		$Lignes = implode('',file("../../inc/include/CSitioExtended.php"));
		
		if ( strpos( $Lignes, $_nombre_modulo_total.'()')===false ) {
		
			$fhandle = fopen( "../../inc/include/CSitioExtended.php", "w");
			if ($fhandle) {
				if ( strpos($Lignes, $_nombre_modulo_total.'(')===false ) {
					echo 'ok no function';
				
					$Lignes = str_replace( "//**ADDMODULE**//", '//**ADDMODULE**//
			
					//ADDED BY CONFIG MANAGER
					function '.$_nombre_modulo_total.'() {
						global $__modulo__;
						if ($__modulo__=="'.$_nombre_modulo_base.'") {
							require("../../inc/modules/'.$_nombre_modulo_total.'.php");
						}
					}
					
					', $Lignes );
				} else {
					ShowError("function already declared");
				}
				//echo $line;
				fwrite( $fhandle, $Lignes );
		
				fclose($fhandle);
		
				ShowMessage("OK rewriting "."../../inc/include/CSitioExtended.php");
			} else {
				ShowError("cannot open "."../../inc/include/CSitioExtended.php");
				return false;
			}
		} else {
			ShowError("../../inc/include/CSitioExtended.php already have a function named ".$_nombre_modulo_total."()" );
			return false;
		}

	
}

function ModuloTemplateExists( $namemod ) {
	/*chequeamos en el directorio de modulos si existe*/
	if (file_exists("../modules/".$namemod."/".$namemod.".info.php")) {
		return true;
	}
	return false;
}


function ModuleCreateSystemVar( $namemod, $opciones ) {
	
	global $_ttiposdetalles_;
	global $CTiposDetalles;
	global $_debug_;
	
	global $Secciones;
	global $TiposSecciones;
	global $Contenidos;
	global $TiposContenidos;
	
	//$_ttiposdetalles_->debug = 'si';
	$_nombre_modulo_base = strtolower( str_replace(" ","_",$namemod) );
	
	$_ficha_contenido_ = strtoupper('FICHA_'.$_nombre_modulo_base);
	$_tipo_seccion_ = strtoupper('SECCION_'.$_nombre_modulo_base);
	$_nombre_seccion_ = trim($namemod);
	$_ficha_sistema_ = strtoupper('FICHA_SISTEMA_'.$_nombre_modulo_base);
	
	/*CONTENIDO*/
	$_seccion_modulo_ = $opciones["_seccion_modulo_"];
	$_ficha_contenido_modulo_ = $opciones["_ficha_contenido_modulo_"];
	
	/*CONFIGURACION*/
	$_sistema_modulo_ = $opciones["_sistema_modulo_"];
	$_ficha_sistema_modulo_ = $opciones["_ficha_sistema_modulo_"];
	
	
	//if ($_sistema_modulo_=="on") {
	
	
	//}
	echo "<br><br>_seccion_modulo_: ".$_seccion_modulo_;
	if ($_seccion_modulo_=="on") {
		echo "<br><b>Creando tipo y sección `$_nombre_seccion_` de tipo `$_tipo_seccion_` </b>";
		
		$TipoSeccion = new CTipoSeccion();
		$TipoSeccion->m_tipo = $_tipo_seccion_;
		$TipoSeccion->m_descripcion = $_nombre_seccion_;
		
		$id_tiposeccion = $TiposSecciones->GetId($_tipo_seccion_);		
		
		if ( is_numeric( $id_tiposeccion ) && $id_tiposeccion>0 ) {
			echo "<br>tipo sección ya existe: ".$id_tiposeccion;
			$TipoSeccion->m_id = $id_tiposeccion;
		}
		
		if ($id_tiposeccion==-1) {
			$res = $TiposSecciones->CrearTipoSeccion( $TipoSeccion );
			if ($res ) {
				ShowMessage("Tipo ".$_tipo_seccion_." creada.");
				$TipoSeccion = $TiposSecciones->GetTipoSeccion(0,$_tipo_seccion_);
				$id_tiposeccion = $TipoSeccion->m_id;
			} else {
				ShowError("Error creando tipo ".$_tipo_seccion_.".");
			}
		}
		
		///CREANDO LA SECCION
		$Root = $Secciones->GetRoot();
		$Seccion = new CSeccion(0);
		$Seccion->m_id_seccion = $Root->m_id;
		$Seccion->m_id_tiposeccion = $id_tiposeccion;
		$Seccion->m_nombre = $_nombre_seccion_;
		$Seccion->m_descripcion = $_nombre_seccion_;
		
		if ( $Secciones->CrearSeccion($Seccion) ) {
			//ok
			ShowMessage("Sección ".$_nombre_seccion_." creada.");
		} else {
			ShowError("Sección ".$_nombre_seccion_." no se pudo crear.");
		}
	}
	
	echo "<br><br>_ficha_contenido_modulo_: ".$_ficha_contenido_modulo_;
	if ($_ficha_contenido_modulo_=="on") {
		echo "<br><b>Creando tipo $_ficha_contenido_</b>";
		$TipoContenido = new CTipoContenido();
		$TipoContenido->m_tipo = $_ficha_contenido_; 
		$TipoContenido->m_descripcion = $_ficha_contenido_;
		if ($TiposContenidos->CrearTipoContenido($TipoContenido)) {
			ShowMessage("Tipo de Contenido ".$_ficha_contenido_." creada.");
		} else {
			ShowError("Contenido ".$_ficha_contenido_." no se pudo crear.");
		}
	}	
	
	echo "<br><br>_ficha_sistema_modulo_: ".$_ficha_sistema_modulo_;
	if ($_ficha_sistema_modulo_=="on") {
		echo "<br><b>Creando tipo y ficha de sistema: $_ficha_sistema_</b>";
		
	}	
		
	echo "<br><br>_sistema_modulo_: ".$_sistema_modulo_;	
	if ($_sistema_modulo_=="on" || $_sistema_modulo_==true || $_sistema_modulo_=="checked") {
		
		///creamos la variable de sistema:
		
		/*
		$TipoDetalle = new CTipoDetalle();					
		$TipoDetalle->m_id_tipocontenido = FICHA_SISTEMA;
		$TipoDetalle->m_id_usuario_creador = 1;
		$TipoDetalle->m_id_usuario_modificador = 1;
		$TipoDetalle->m_tipo = "SISTEMA_".strtoupper($_nombre_modulo_base);
		$TipoDetalle->m_tipocampo = "B"; 
		$TipoDetalle->m_txtdata = "html";
		*/
		
		/*
		$_ttiposdetalles_->LimpiarSQL();
		$_ttiposdetalles_->FiltrarSQL( 'TIPO', '', "SISTEMA_".strtoupper($_nombre_modulo_base) );
		$_ttiposdetalles_->Open();
		if ($_ttiposdetalles_->nresultados==0) { 
			if ($_ttiposdetalles_->InsertarRegistro( 
	array ( 
		"ID_TIPOCONTENIDO"=>FICHA_SISTEMA,
	 	"TIPO"=>"SISTEMA_".strtoupper($_nombre_modulo_base),
		"DESCRIPCION"=>ucwords($_nombre_modulo_base),
		"TIPOCAMPO"=>"B",
		"TXTDATA"=>"html" )
			) ) {
				echo "<br>"."SISTEMA_".strtoupper($_nombre_modulo_base)." created succesfully";	
			} else {
				echo "<br>"."SISTEMA_".strtoupper($_nombre_modulo_base)." error";
			}
		} else {
				echo "<br>"."SISTEMA_".strtoupper($_nombre_modulo_base)." already there.";
		}	
*/
	}	
	
	
	///Limitar el filtrosadmin> asociar seccion a tipo de contenido
			$Lignes = file("../../inc/include/filtrosadmin.php");
			
			$fhandle = fopen( "../../inc/include/filtrosadmin.php", "w");
			if ($fhandle) {
				foreach($Lignes as $line) {

					$line = str_replace( "//**ADDCASE**//", '//**ADDCASE**//
	
		//ADDED BY CONFIG MANAGER
						case '.$_tipo_seccion_.':
							$_f_ID_TIPOCONTENIDO = '.$_ficha_contenido_.';
							$this->Contenidos->m_tcontenidos->FiltrarCampo(\'ID_TIPOCONTENIDO\',\'\',\'escondido\');
							break;
					
					', $line );
					//echo $line;
					fwrite( $fhandle, $line );
				}
				fclose($fhandle);				
				
				ShowMessage("OK rewriting "."../../inc/include/filtrosadmin.php");
			}
	
	
	
}


function ModuleExists( $namemod ) {
	$ex = true;
	$ex&= file_exists( "../../inc/modules/"."Modulo".str_replace( " ","",ucwords( strtolower( $namemod ) ) ).".php" );
	$ex&= file_exists( "../../principal/home/".strtolower( str_replace(" ","_",$namemod) ).".php" );
	return $ex;
}

function ModuleCreate( $namemod ) {
	
	if (ModuleExists($namemod)) {
		
		ShowError("Error: módulo ya existe");
		
	} else {
		
		if (ModuloTemplateExists($namemod)) {
			
			ShowMessage("Existe una plantilla para este módulo: se ejecutará antes de crearse el módulo");
			
			return ModuloTemplateCrear($namemod);
		}
		
		//eliminate the white spaces replacing them by underscores
		$_nombre_modulo_base = strtolower( str_replace(" ","_",$namemod) );
		
		$_nombre_modulo_total = "Modulo".str_replace( " ","",ucwords( strtolower( $namemod ) ) );
		$_nombre_modulo_modules = "../../inc/modules/".$_nombre_modulo_total.".php"; 
		$_nombre_modulo_principal = "../../principal/home/".$_nombre_modulo_base.".php";

		$LignesMod = file("../../administracion/modules/ModuloGeneric.php");
		
		$fhandle = fopen( $_nombre_modulo_modules, "w");
		///if (function_exists(chmod)) chmod("../../inc/modules", 777 );
		if ($fhandle) {
			
			fwrite( $fhandle, "<?Php"."\n" );
			fwrite( $fhandle, "/**"."\n*".$_nombre_modulo_total."\n*"."\n*"."*/" );
			fwrite( $fhandle, "?>"."\n" );
	
			$_ficha_contenido_ = strtoupper('FICHA_'.$_nombre_modulo_base);			
			
			foreach($LignesMod as $line) {

					$line = str_replace( "//**TIPOCONTENIDO**//", $_ficha_contenido_, $line );
					//echo $line;
					fwrite( $fhandle, $line );
				}			
			
			fclose($fhandle);
			
			echo "OK writting ".$_nombre_modulo_modules;
		} else {
			echo '<div class="error"> cannot open '.$_nombre_modulo_modules;
			echo ' > Activate write permissions in inc/modules with <pre>
chmod 777 inc/modules
chmod 777 inc/templates
chmod 777 principal/home
chmod 666 inc/include/CSitioExtended.php</pre> ! </div> ';
			return false;
		}
		///if (function_exists(chmod)) chmod("../../inc/modules", 755 );

		$fhandle = fopen( $_nombre_modulo_principal, "w");
		if ($fhandle) {
			fwrite( $fhandle, '<?
require "../../inc/include/deftabla.php";//modulo de definicion de las tablas

$__modulo__= "'.$_nombre_modulo_base.'";

//AQUI CONTRUIMOS EL OBJETO PRINCIPAL DEL SITIO, AL QUE LE ASIGNAMOS LAS TABLAS YA CREADAS
if (!defined("DNK_SITE") and !defined("Sitio")) {
  	define("DNK_SITE","OK");
  	$Sitio = new CSitioExtended($_tsecciones_,$_ttipossecciones_,$_tcontenidos_,$_ttiposcontenidos_,$_tarchivos_,$_ttiposarchivos_,$_tdetalles_,$_ttiposdetalles_,$_tlogs_);
  	$Sitio->Inicializar();
}

?>
<html>
<head>
<title><?=$_TITLE_?></title>
<? require "../../inc/include/scripts.php";//los scripts mas comunes?>
<? require "../../inc/include/style.php";//los estilos?>
</head>
<? require "../../inc/include/siteheader.php";//el encabezado comun al sitio (marco del sitio-toc-cartelera-etc)?>
<? require "../../inc/include/pageheader.php";//el encabezado comun a la pagina (marco de la pagina-por ahora nada en particular)?>
<!-- PAGINA --><?
$Sitio->'.$_nombre_modulo_total.'();
?><!-- FIN PAGINA -->
<? require "../../inc/include/pagefooter.php";?>
<? require "../../inc/include/sitefooter.php";?>
</html>' );

			fclose($fhandle);
			
			echo "<br>OK writting ".$_nombre_modulo_principal;
			
			$Lignes = file("../../inc/include/CSitioExtended.php");
			
			$fhandle = fopen( "../../inc/include/CSitioExtended.php", "w");
			if ($fhandle) {
				foreach($Lignes as $line) {
					$line = str_replace( "//**ADDMODULE**//", '//**ADDMODULE**//
	
		//ADDED BY CONFIG MANAGER
		function '.$_nombre_modulo_total.'() {
			global $__modulo__;
			if ($__modulo__=="'.$_nombre_modulo_base.'") {
				require("../../inc/modules/'.$_nombre_modulo_total.'.php");
			}
		}
					
					', $line );
					//echo $line;
					fwrite( $fhandle, $line );
				}
				fclose($fhandle);
				
				

				
				ShowMessage("OK rewriting "."../../inc/include/CSitioExtended.php");
				return true;
			} else {
				ShowError("cannot open "."../../inc/include/CSitioExtended.php");
				return false;
			}
				
		} else {
			
			echo "cannot open ".$_nombre_modulo_principal;
			return false;
		}		

	}
	return false;
}

function ModuleRemove( $namemod ) {
	
	return false;
}


if ($_accion_=="newmodule") {

	require "../../inc/include/deftabla.php";

	$_ttiposcontenidos_->campos["TIPO"]["editable"]='si';
	$_ttiposcontenidos_->campos["DESCRIPCION"]["editable"]='si';
	
	$_ttipossecciones_->campos["TIPO"]["editable"]='si';
	$_ttipossecciones_->campos["DESCRIPCION"]["editable"]='si';	
	
	$_ttiposdetalles_->campos["ID_TIPOCONTENIDO"]["editable"]='si';
	$_ttiposdetalles_->campos["TIPO"]["editable"]='si';
	$_ttiposdetalles_->campos["TIPOCAMPO"]["editable"]='si';
	$_ttiposdetalles_->campos["TXTDATA"]["editable"]='si';
	$_ttiposdetalles_->campos["DESCRIPCION"]["editable"]='si';
	
	$TiposSecciones = new CTiposSecciones($_ttipossecciones_);
	$Secciones = new CSecciones($_tsecciones_,$TiposSecciones);		
	
	
	$TiposDetalles = new CTiposDetalles($_ttiposdetalles_);
	$Detalles = new CDetalles($_tdetalles_,$TiposDetalles);	
	
	$TiposRelaciones = new CTiposRelaciones( $_ttiposrelaciones_, $TiposDetalles );		
	$Relaciones = new CRelaciones( $_trelaciones_, $TiposRelaciones );	
	
	//DEFINICION DE TIPOS DE CONTENIDOS
	$TiposContenidos = new CTiposContenidos($_ttiposcontenidos_,$Detalles);
	$Contenidos = new CContenidos($_tcontenidos_,$TiposContenidos,$Relaciones,$Usuarios);		
	
	$Usuarios = new CUsuarios( $_tusuarios_, $Secciones, $Contenidos, $Relaciones );
	
	if ($_nombre_modulo_!="") {
		$result = ModuleCreate($_nombre_modulo_);
		if ($result==false) {
			ShowError('Error while trying to create module: '.$_nombre_modulo_);
		}
		
		ModuleCreateSystemVar($_nombre_modulo_, array( 
																										'_seccion_modulo_'=>$_seccion_modulo_,
																										'_ficha_contenido_modulo_'=>$_ficha_contenido_modulo_,
																										'_ficha_sistema_modulo_'=>$_ficha_sistema_modulo_,
																										'_sistema_modulo_'=>$_sistema_modulo_ 
																									) );
	}
}


?>
<div id="div_modulos" style="display:none;">
<form action="../admin/admin.php" method="post" name="formsetup">
<input type="hidden" value="newmodule" name="_accion_">
<table>
	<tr>
		<td valign="middle">Ingresar nombre del módulo</td>
		<td><input type="text" value="" size="40" id="_nombre_modulo_" name="_nombre_modulo_"/> <?=ModuloTemplateSelect()?></td>
	</tr>
	<tr>
		<td>crear SECCION</td>
		<td><input type="checkbox" id="_seccion_modulo_" name="_seccion_modulo_"/></td>
	</tr>	
	<tr>
		<td>crear FICHA CONTENIDO</td>
		<td><input type="checkbox" id="_ficha_contenido_modulo_" name="_ficha_contenido_modulo_"/></td>
	</tr>
	<tr>
		<td>crear FICHA SISTEMA</td>
		<td><input type="checkbox" id="_ficha_sistema_modulo_" name="_ficha_sistema_modulo_"/></td>
	</tr>		
	<tr>
		<td>Crear VARIABLE SISTEMA
		<td><input type="checkbox" id="_sistema_modulo_" name="_sistema_modulo_"/></td>
	</tr>
	<tr>
		<td colspan="2"  valign="top"><button class="inputbutton" type="submit">Crear Nuevo Módulo</button>
		</td>
	</tr>
</table>

</form>

</div>