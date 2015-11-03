<?Php

require '../include/DinamikAdmin.php';

/*			  						*/
/*			  						*/
/*	 	Definición de tablas		*/
/*			  						*/
/*			  						*/

$tabla = new Tabla('archivos',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);

/*			  						*/
/*			  						*/
/*	 	Definición de campos		*/
/*			  						*/
/*			  						*/
/* [nombre , etiqueta , tipo , porcentaje(tabla) , puedesernulo? , defecto , editable , filtrar , tamanio] */
$tabla->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$tabla->AgregarCampo('ID_SECCION',	'',	'ENTERO',		'15%','NOT NULL','','si','si',20);
$tabla->AgregarCampo('ID_TIPOARCHIVO',	'',	'ENTERO',		'15%','NOT NULL','','si','si',20);
$tabla->AgregarCampo('GUARDAR_SECCION',	'Guardar seccion',	'TEXTO',		'5%','NULL','S','si','si',20);
$tabla->AgregarCampo('NOMBRE',	'Nombre',	'TEXTO',		'15%','NOT NULL','','si','si',40);
$tabla->AgregarCampo('ARCHIVO',	'Archivo',	'ARCHIVO',		'15%','NULL','','si','si',80);
$tabla->AgregarCampo('DESCRIPCION',	'Descripción',	'BLOBTEXTO',		'15%','NULL','','si','si',80);
$tabla->AgregarCampo('URL',	'Url',	'TEXTO',		'15%','NULL','','si','si',80);
$tabla->AgregarCampo('EXTERNO',	'Externo',	'TEXTO',		'5%','NOT NULL','N','si','si',20);


/*									*/
/*									*/
/*		Indices						*/
/*									*/
/*									*/

$tabla->AgregarIndice('ID','','PRIMARIO');
$tabla->AgregarIndice('SECCION','secciones.NOMBRE','');
$tabla->AgregarIndice('NOMBRE','archivos.NOMBRE','');

/*			  						*/
/*			  						*/
/*	 	Referencias	a tablas		*/
/*			  						*/
/*			  						*/
$tabla->AgregarReferenciaCombo('GUARDAR_SECCION','Guardar en la carpeta?',array('N','S'));
$tabla->AgregarReferenciaCombo('EXTERNO','Es link?',array('N','S'));
$tabla->AgregarReferencia('ID_SECCION','Sección','secciones','ID','NOMBRE');
$tabla->AgregarReferencia('ID_SECCION','','secciones','ID','CARPETA');
$tabla->AgregarReferencia('ID_TIPOARCHIVO','Tipo de archivo','tiposarchivos','ID','TIPO');
$tabla->AgregarReferencia('ID_TIPOARCHIVO','Carpeta','tiposarchivos','ID','CARPETA');

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
