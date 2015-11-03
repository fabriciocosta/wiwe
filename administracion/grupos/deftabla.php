<?Php

require '../include/DinamikAdmin.php';

/*			  						*/
/*			  						*/
/*	 	Definición de tablas		*/
/*			  						*/
/*			  						*/

$tabla = new Tabla('grupos',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);

/*			  						*/
/*			  						*/
/*	 	Definición de campos		*/
/*			  						*/
/*			  						*/
/* [nombre , etiqueta , tipo , porcentaje(tabla) , puedesernulo? , defecto , editable , filtrar , tamanio] */
$tabla->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',25);
$tabla->AgregarCampo('GRUPO',	'Grupo',	'TEXTO',		'15%','NOT NULL','','si','si',25);
$tabla->AgregarCampo('PERMISOS_MIEMBROS',	'Permisos miembros',	'ENTERO',		'15%','NOT NULL','3','si','si',20);
$tabla->AgregarCampo('PERMISOS_USUARIOS',	'Permisos usuarios',	'ENTERO',		'15%','NOT NULL','0','si','si',20);
$tabla->AgregarCampo('DESCRIPCION',	'Descripción',	'BLOBTEXTO',		'45%','NULL','','si','si',50);

/*									*/
/*									*/
/*		Indices						*/
/*									*/
/*									*/

$tabla->AgregarIndice('ID','','PRIMARIO');
$tabla->AgregarIndice('GRUPO','grupos.GRUPO','');
$tabla->AgregarIndice('PERMISOS','grupos.PREMISOS_MIEMBROS','');

/*			  						*/
/*			  						*/
/*	 	Referencias	a tablas		*/
/*			  						*/
/*			  						*/
//$tabla->AgregarReferencia('ID_SECCION','Sección','secciones','ID','NOMBRE');
//$tabla->AgregarReferenciaCombo('PERMISOS_MIEMBROS','Permisos miembros',array('0','1','2','3'));
//$tabla->AgregarReferenciaCombo('PERMISOS_USUARIOS','Permisos usuarios',array('0','1','2','3'));

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
