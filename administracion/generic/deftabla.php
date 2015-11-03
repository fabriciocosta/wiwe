<?Php

require "../include/tabla.php";

/*			  						*/
/*			  						*/
/*	 	Definición de tablas		*/
/*			  						*/
/*			  						*/

$tabla = new Tabla('GENERICA',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);

/*			  						*/
/*			  						*/
/*	 	Definición de campos		*/
/*			  						*/
/*			  						*/
/* [nombre , etiqueta , tipo , porcentaje(tabla) , puedesernulo? , defecto , editable , filtrar , tamanio] */
$tabla->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$tabla->AgregarCampo('CAMPO1',	'',	'ENTERO',		'15%','NOT NULL','','si','si',20);
$tabla->AgregarCampo('CAMPO2',	'Campo 2',	'TEXTO',		'15%','NULL','','si','si',20);
$tabla->AgregarCampo('CAMPO3',	'Campo 3',	'DECIMAL',		'15%','NULL','','si','si',20);
$tabla->AgregarCampo('CAMPO4',	'Campo 4',	'TIMESTAMP',	'15%','NULL','','no','no',20);
$tabla->AgregarCampo('CAMPO5',	'Campo 5',	'BLOBTEXTO',	'15%','NULL','','no','no',20);
$tabla->AgregarCampo('CAMPO6',	'Campo 6',	'BLOB',			'10%','NULL','','no','no',20);


/*									*/
/*									*/
/*		Indices						*/
/*									*/
/*									*/

$tabla->AgregarIndice('ID','','PRIMARIO');
$tabla->AgregarIndice('CAMPO1','GENERICA.CAMPO1','');
$tabla->AgregarIndice('CAMPO2','GENERICA.CAMPO2','');
$tabla->AgregarIndice('CONTINENTE','CONTINENTES.DESCRIPCION,LOOKUP.PAIS','');
$tabla->AgregarIndice('HEMISFERIO','HEMISFERIO.DESCRIPCION,LOOKUP.PAIS','');

/*			  						*/
/*			  						*/
/*	 	Referencias	a tablas		*/
/*			  						*/
/*			  						*/

$tabla->AgregarReferencia('CAMPO1','Pais','LOOKUP','ID','PAIS');
$tabla->AgregarReferenciaAnidada('CAMPO1','Continente','LOOKUP','ID_CONTINENTE','CONTINENTES','ID','DESCRIPCION');
$tabla->AgregarReferenciaAnidada('CAMPO1','Hemisferio','LOOKUP','ID_HEMISFERIO','HEMISFERIO','ID','DESCRIPCION');
$tabla->AgregarReferenciaCombo('CAMPO2','Siglas',array('ARG','ANT','AUS','CON'));

/*			  						*/
/*			  						*/
/*	 	Permisos					*/
/*			  						*/
/*			  						*/
$tabla->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));

/*			  						*/
/*			  						*/
/*	 	Debug	Imprime definicion	*/
/*			  						*/
/*			  						*/
//$_debug_='si';
if ($_debug_=='si') {
	include("../include/style.php");
	$tabla->Describe();
	$tabla->debug = 'si';
}
?>
