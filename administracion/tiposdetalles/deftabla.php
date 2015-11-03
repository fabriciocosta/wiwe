<?Php
//version 4.0 11/08/2006
require '../include/DinamikAdmin.php';
require '../admin/deftabla.php';

/*			  						*/
/*			  						*/
/*	 	Definición de tablas		*/
/*			  						*/
/*			  						*/

$tabla = new Tabla('tiposdetalles',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);

/*			  						*/
/*			  						*/
/*	 	Definición de campos		*/
/*			  						*/
/*			  						*/
/* [nombre , etiqueta , tipo , porcentaje(tabla) , puedesernulo? , defecto , editable , filtrar , tamanio] */
$tabla->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','si',20);
$tabla->AgregarCampo('ID_TIPOCONTENIDO',		'',		'ENTERO',		'10%','NOT NULL','','si','si',20);
$tabla->AgregarCampo('TIPO',	'Tipo',	'TEXTO',		'35%','NOT NULL','','si','si',40);
$tabla->AgregarCampo('DESCRIPCION',	'Descripción',	'TEXTO',		'35%','NOT NULL','','si','si',80);
$tabla->AgregarCampo('TXTDATA',	'TxtData',	'BLOBTEXTO',		'35%','NOT NULL','','si','si',80);
$tabla->AgregarCampo('TIPOCAMPO',	'Tipo Campo',	'TEXTO',		'10%','NOT NULL','','si','si',40);


/*									*/
/*									*/
/*		Indices						*/
/*									*/
/*									*/

$tabla->AgregarIndice('ID','','PRIMARIO');
$tabla->AgregarIndice('TIPO','tiposdetalles.TIPO');
$tabla->AgregarIndice('DESCRIPCION','tiposdetalles.DESCRIPCION');
$tabla->AgregarIndice('TXTDATA','tiposdetalles.TXTDATA');
$tabla->AgregarIndice('TIPOCONTENIDO','tiposcontenidos.TIPO');
$tabla->AgregarIndice('TIPOCAMPO','TIPOCAMPO');

/*			  						*/
/*			  						*/
/*	 	Referencias	a tablas		*/
/*			  						*/
/*			  						*/
//T: texto simple, una linea
//I: imagen, link a imagen
//A: un archivo , file de cualquier tipo
//L: Lista de lineas de texto
//B: Texto completo
//C: un checkbox (valor booleano): true o false
//X: datos especiales formateados como XML... <campo type=select values=A|B|C/><campo type=checkbox values=A|B|C/>
//K: link
//R: Referencia a un contenido
//G: Galeria de imagenes
//D: Documento PDF-DOC-RTF-ZIP-RAR
//M: Maletin de documentos
//W: Lista de videos
$tabla->AgregarReferenciaCombo('TIPOCAMPO','Tipo Campo',array('T'=>'Texto  [T]',
'N'=>'Número entero  [N]',
'E'=>'Número decimal - Exponencial  [E]',
'FDT'=>'Fecha/Hora (datetime) [FDT]',
'FD'=>'Fecha (date) [FD]',
'FTT'=>'Hora (time) [FTT]',
'FT'=>'Tiempo (timestamp) [FT]',
'I'=>'Imagen  [I]',
'F'=>'Foto  [F]',
'V'=>'Video  [V]',
'A'=>'Archivo  [A]',
'L'=>'Lista  [L]',
'S'=>'Select  [S]',
'B'=>'Blob texto  [B]',
'C'=>'Checkbox (true/false) [C]',
'X'=>'XML data  [X]',
'Y'=>'XML table  [Y]',
'K'=>'linK [K]',
'R'=>'Referencia a contenido [R]',
'RC'=>'Referencia a contenido [RC]',
'H'=>'Referencia a seccion [H]',
'RS'=>'Referencia a seccion [RS]',
'O'=>'Referencias a contenidos  [O]',
'RCx'=>'Referencias a contenidos  [RCx]',
'RTCx'=>'Referencias a tipos de contenidos  [RTCx]',
'P'=>'Referencias a secciones  [P]',
'RSx'=>'Referencias a secciones  [RSx]',
'RTSx'=>'Referencias a tipos de secciones  [RTSx]',
'U'=>'sUb-contenidos  [U]',
'G'=>'Galería de fotos  [G]',
'D'=>'Documento PDF-DOC-RTF-ZIP-RAR  [D]',
'M'=>'Maletín de documentos  [M]',
'GDOC'=>'Maletín de documentos  [GDOC]',
'W'=>'Lista de videos  [W]',
'GVID'=>'Lista de videos  [GVID]'));
$tabla->AgregarReferencia('ID_TIPOCONTENIDO','Tipo de Contenido','tiposcontenidos','ID','TIPO');

/*			  						*/
/*			  						*/
/*	 	Permisos					*/
/*			  						*/
/*			  						*/
/*depende de los permisos del usuario logueado, es dinamico*/
$tabla->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));

/*			  						*/
/*			  						*/
/*	 	Debug	Imprime definicion	*/
/*			  						*/
/*			  						*/
//$_debug_='si';
if ($_debug_=='si') {
	include("../include/style.php");
	//$tabla->Describe();
	$tabla->debug = 'si';
}
?>
