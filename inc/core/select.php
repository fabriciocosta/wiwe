<?
header('Content-Type: text/html; charset=iso-8859-1');


require "../../inc/include/deftabla.php";//modulo de definicion de las tablas

//AQUI CONTRUIMOS EL OBJETO PRINCIPAL DEL SITIO, AL QUE LE ASIGNAMOS LAS TABLAS YA CREADAS
if (!defined("DNK_SITE") and !defined("Sitio")) {
  	define("DNK_SITE","OK");
  	$Sitio = new CSitioExtended($_tsecciones_,$_ttipossecciones_,$_tcontenidos_,$_ttiposcontenidos_,$_tarchivos_,$_ttiposarchivos_,$_tdetalles_,$_ttiposdetalles_,$_tlogs_,$_tusuarios_);
  	$Sitio->Inicializar();    	
}


global $accion; //add or update
global $tipo;//secciones o contenidos

global $filtervalue; //an ID generally

global $selectid; //id del select
global $selectname; // name del select
global $selectscript;// script a ejecutar en caso de change

global $selectedvalue; // el valor pre-seleccionado...
global $firstvalue; // el valor de la primera opcion (lo que el usuario no ve..., si no tiene valor, no aparece la primera opcion)
global $firstoption; // el nombre de la primera opcion (lo que el usuario ve...)

global $fieldid; // nombre del campo a tomar como valor... (sacado de la consulta)
global $fieldlabel; //nombre del campo a tomar como texto a mostrar (sacado de la consulta)

global $sql; //definido dentro del tipo de relacion o tipo de detalle, por ahora lo hacemos dentro del tipo de detalle
global $sqlcount;

global $id_contenido_seccion_rel; //id del contenido o la seccion a RELCIONAR!!!!
global $idtodelete;

$sql = str_replace("SELECCIONAR","SELECT",$sql);
$sqlcount = str_replace("SELECCIONAR","SELECT",$sqlcount);

$sql = str_replace("{FILTERVALUE}",$filtervalue,$sql);
$sqlcount = str_replace("{FILTERVALUE}",$filtervalue,$sqlcount);


/*
 En un futuro proximo:
		aun no se
 */

$resstr = '';


if ($accion=="update") {

	
	$_tcontenidos_->LimpiarSQL();
	
	$_tcontenidos_->SQL = $sql;
	$_tcontenidos_->SQLCOUNT = $sqlcount;
	
	
	$_tcontenidos_->Open();
	
	$resstr = '<select id="'.$selectid.'" name="'.$selectname.'" '.$selectscript.'>';
	if ($firstvalue) $resstr.= '<option value="'.$firstvalue.'">'.$firstoption.'</option>';
	
	if ( $_tcontenidos_->nresultados>0 ) {
		while($_row_ = $_tcontenidos_->Fetch() ) {
			if ($tipo=='contenidos') {
				$CC = new CContenido($_row_);
												
				if ($fieldid=="") {
					$fieldid_str = $CC->m_id;
				} else {
					$fieldid_str = $_row_[$fieldid];
				}
				
				if ($fieldlabel=="") {
					$fieldlabel_str = $CC->Titulo();
				} else {
					$fieldlabel_str = $_row_[$fieldlabel];
				}
				
				if ($selectedvalue!="")
					if ( $fieldid_str == $selectedvalue ) $sel = "selected";
				
				$sel = "";
				$resstr.= '<OPTION value="'.$fieldid_str.'" '.$sel.'>'.$fieldlabel_str.'</OPTION>';
				
			} else {
				$CS = new CSeccion($_row_);				
				
				if ($fieldid=="") {
					$fieldid_str = $CS->m_id;
				} else {
					$fieldid_str = $_row_[$fieldid];
				}
				
				if ($fieldlabel=="") {
					$fieldlabel_str = $CS->Nombre();
				} else {
					$fieldlabel_str = $_row_[$fieldlabel];
				}				
				
				$sel = "";
				if ($selectedvalue!="")
					if ( $fieldid_str == $selectedvalue ) $sel = "selected";
																			
				$resstr.= '<OPTION value="'.$fieldid_str.'" '.$sel.' class="profundidad_'.$CS->m_profundidad.'_">'.$fieldlabel_str.'</OPTION>';
			}
													
		}
	}								

	$resstr.= '</select>';
	
	echo $resstr;	

}

?>