<?Php

require '../include/DinamikAdmin.php';

/*			  						*/
/*			  						*/
/*	 	Definici�n de tablas		*/
/*			  						*/
/*			  						*/

$tabla = new Tabla('grupossecciones',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);

/*			  						*/
/*			  						*/
/*	 	Definici�n de campos		*/
/*			  						*/
/*			  						*/
/* [nombre , etiqueta , tipo , porcentaje(tabla) , puedesernulo? , defecto , editable , filtrar , tamanio] */
$tabla->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$tabla->AgregarCampo('ID_GRUPO',	'',	'ENTERO',		'15%','NOT NULL','','si','si',20);
$tabla->AgregarCampo('ID_SECCION',	'',	'ENTERO',		'15%','NOT NULL','','si','si',20);

/*									*/
/*									*/
/*		Indices						*/
/*									*/
/*									*/

$tabla->AgregarIndice('ID','','PRIMARIO');
$tabla->AgregarIndice('GRUPO','grupos.GRUPO','');
$tabla->AgregarIndice('SECCION','secciones.NOMBRE','');

/*			  						*/
/*			  						*/
/*	 	Referencias	a tablas		*/
/*			  						*/
/*			  						*/
$tabla->AgregarReferencia('ID_GRUPO','Grupo','grupos','ID','GRUPO');
$tabla->AgregarReferencia('ID_SECCION','Secci�n','secciones','ID','NOMBRE');


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
