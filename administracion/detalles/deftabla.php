<?Php

require "../include/DinamikAdmin.php";

/*			  						*/
/*			  						*/
/*	 	Definición de tablas		*/
/*			  						*/
/*			  						*/

$tabla = new Tabla('detalles',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);

/*			  						*/
/*			  						*/
/*	 	Definición de campos		*/
/*			  						*/
/*			  						*/
/* [nombre , etiqueta , tipo , porcentaje(tabla) , puedesernulo? , defecto , editable , filtrar , tamanio] */
/*
$tabla->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$tabla->AgregarCampo('ID_CONTENIDO',	'',	'ENTERO',		'15%','NOT NULL','','si','si',20);
$tabla->AgregarCampo('ID_TIPODETALLE',	'',	'ENTERO',		'15%','NOT NULL','','si','si',20);
$tabla->AgregarCampo('DETALLE',	'Detalle',	'TEXTO',		'15%','NOT NULL','','si','si',40);
$tabla->AgregarCampo('TXTDATA',	'TxtData',	'BLOBTEXTO',		'15%','NULL','','si','si',80,10);
*/

$tabla->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$tabla->AgregarCampo('ID_TIPODETALLE',	'Tipo',	'ENTERO',		'15%','NOT NULL','','si','si',25);
$tabla->AgregarCampo('ID_CONTENIDO',	'',	'ENTERO',		'15%','NOT NULL','N','si','si',25);
$tabla->AgregarCampo('DETALLE',	'Detalle',	'TEXTO',		'15%','NULL','','si','si',50);
$tabla->AgregarCampo('ML_DETALLE',	'MLDetalle',	'BLOBTEXTO',		'15%','NULL','','si','si',80,10);
$tabla->AgregarCampo('TXTDATA',	'TxtData',	'BLOBTEXTO',		'15%','NULL','','si','si',80,10);
$tabla->AgregarCampo('ML_TXTDATA',	'MLTxtData',	'BLOBTEXTO',		'15%','NULL','','si','si',80,10);
//$tabla->AgregarCampo('BINDATA',	'BinData',	'BLOB',		'15%','NULL','','si','si',25);

/*									*/
/*									*/
/*		Indices						*/
/*									*/
/*									*/

$tabla->AgregarIndice('ID','','PRIMARIO');
$tabla->AgregarIndice('TIPO DE DETALLE','tiposdetalles.TIPO','');
$tabla->AgregarIndice('TIPO DE CAMPO','tiposdetalles.TIPOCAMPO','');
$tabla->AgregarIndice('DETALLE','DETALLE','');
$tabla->AgregarIndice('TITULO','contenidos.TITULO','');

/*			  						*/
/*			  						*/
/*	 	Referencias	a tablas		*/
/*			  						*/
/*			  						*/
$tabla->AgregarReferencia('ID_CONTENIDO','Contenido','contenidos','ID','TITULO');
$tabla->AgregarReferencia('ID_TIPODETALLE','Tipo detalle','tiposdetalles','ID','TIPO');
$tabla->AgregarReferencia('ID_TIPODETALLE','Tipo Campo','tiposdetalles','ID','TIPOCAMPO');



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
