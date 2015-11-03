<?Php


require '../include/DinamikAdmin.php';

/*			  						*/
/*			  						*/
/*	 	Definición de tablas		*/
/*			  						*/
/*			  						*/

$tabla = new Tabla('logusuarios',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);

/*			  						*/
/*			  						*/
/*	 	Definición de campos		*/
/*			  						*/
/*			  						*/
/* [nombre , etiqueta , tipo , porcentaje(tabla) , puedesernulo? , defecto , editable , filtrar , tamanio] */
$tabla->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$tabla->AgregarCampo('ID_USUARIO',	'Id',	'ENTERO',		'15%','NOT NULL','','si','si',25);
$tabla->AgregarCampo('NICK_USUARIO',	'Nick',	'TEXTO',		'25%','NOT NULL','','si','si',25);
$tabla->AgregarCampo('LOGS',	'Logs',	'ENTERO',		'25%','NOT NULL','','si','si',25);
$tabla->AgregarCampo('IP',	'Ip',	'TEXTO',		'25%','NOT NULL','','si','si',25);


/*									*/
/*									*/
/*		Indices						*/
/*									*/
/*									*/

$tabla->AgregarIndice('ID','','PRIMARIO');
$tabla->AgregarIndice('NICK_USUARIO','logusuarios.NICK_USUARIO','');

/*			  						*/
/*			  						*/
/*	 	Referencias	a tablas		*/
/*			  						*/
/*			  						*/
//$tabla->AgregarReferencia('ID_SECCION','Sección','secciones','ID','NOMBRE');

/*			  						*/
/*			  						*/
/*	 	Permisos					*/
/*			  						*/
/*			  						*/
/*depende de los permisos del usuario logueado, es dinamico*/
$tabla->Permisos(array('agregar'=>'no','modificar'=>'no','borrar'=>'si'));

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
