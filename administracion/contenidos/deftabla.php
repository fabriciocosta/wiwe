<?Php

require "../include/DinamikAdmin.php";

/*			  						*/
/*			  						*/
/*	 	Definición de tablas		*/
/*			  						*/
/*			  						*/

$tabla = new Tabla('contenidos',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);

/*			  						*/
/*			  						*/
/*	 	Definición de campos		*/
/*			  						*/
/*			  						*/
/* [nombre , etiqueta , tipo , porcentaje(tabla) , puedesernulo? , defecto , editable , filtrar , tamanio] */
$tabla->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$tabla->AgregarCampo('ID_SECCION',	'',	'ENTERO',		'15%','NOT NULL','','si','si',20);
$tabla->AgregarCampo('ID_USUARIO_CREADOR',	'',	'ENTERO',		'15%','NOT NULL','','si','si',20);
$tabla->AgregarCampo('ID_USUARIO_MODIFICADOR',	'',	'ENTERO',		'15%','NOT NULL','','si','si',20);
$tabla->AgregarCampo('ID_TIPOCONTENIDO',	'',	'ENTERO',		'15%','NOT NULL','1','si','si',20);
$tabla->AgregarCampo('TITULO',	'Título',	'TEXTO',		'15%','NULL','','si','si',80);
$tabla->AgregarCampo('ML_TITULO',	'Título ML',	'TEXTO',		'15%','NULL','','si','si',80);
$tabla->AgregarCampo('COPETE',	'Copete',	'BLOBTEXTO',		'15%','NULL','','si','si',80,3);
$tabla->AgregarCampo('ML_COPETE',	'Copete ML',	'BLOBTEXTO',		'15%','NULL','','si','si',80,3);
$tabla->AgregarCampo('CUERPO',	'Cuerpo',	'BLOBTEXTO',		'15%','NULL','','si','si',80,10);
$tabla->AgregarCampo('ML_CUERPO',	'Cuerpo ML',	'BLOBTEXTO',		'15%','NULL','','si','si',80,10);
$tabla->AgregarCampo('AUTOR',	'Autor',	'TEXTO',		'15%','NULL','','si','si',40);
$tabla->AgregarCampo('BAJA',	'',	'TEXTO',		'15%','NULL','','si','si',2);
$tabla->AgregarCampo('FECHAEVENTO',	'',	'FECHA',		'15%','NULL','','si','si',25);
$tabla->AgregarCampo('FECHAALTA',	'',	'FECHA',		'15%','NULL','','si','si',25);
$tabla->AgregarCampo('FECHABAJA',	'',	'FECHA',		'15%','NULL','','si','si',25);
$tabla->AgregarCampo('PRINCIPAL',	'Nota principal',	'TEXTO',		'15%','NOT NULL','N','si','si',40);
$tabla->AgregarCampo('ID_CONTENIDO',		'',		'ENTERO',		'10%','NULL','0','si','si',20);
/*									*/
/*									*/
/*		Indices				*/
/*									*/
/*									*/

$tabla->AgregarIndice('ID','','PRIMARIO');
$tabla->AgregarIndice('TITULO','contenidos.TITULO','');
$tabla->AgregarIndice('AUTOR','contenidos.AUTOR','');
$tabla->AgregarIndice('TIPO DE CONTENIDO','tiposcontenidos.TIPO','');
$tabla->AgregarIndice('SECCION','secciones.NOMBRE','');
$tabla->AgregarIndice('CONTENIDO ASOCIADO','contenidos.ID_CONTENIDO','');
$tabla->AgregarIndice('VISIBLE','contenidos.BAJA','');
$tabla->AgregarIndice('FECHAEVENTO ASC','contenidos.FECHAEVENTO ASC','');
$tabla->AgregarIndice('FECHAEVENTO DESC','contenidos.FECHAEVENTO DESC','');
$tabla->AgregarIndice('FECHAALTA ASC','contenidos.FECHAEVENTO ASC','');
$tabla->AgregarIndice('FECHAALTA DESC','contenidos.FECHAEVENTO DESC','');
$tabla->AgregarIndice('FECHABAJA ASC','contenidos.FECHAEVENTO ASC','');
$tabla->AgregarIndice('FECHABAJA DESC','contenidos.FECHAEVENTO DESC','');
$tabla->AgregarIndice('CREADOR','contenidos.ID_USUARIO_CREADOR','');
$tabla->AgregarIndice('MODIFICADOR','contenidos.ID_USUARIO_MODIFICADOR','');


/*			  						*/
/*			  						*/
/*	 	Referencias	a tablas		*/
/*			  						*/
/*
/*			  						*/


$tabla->AgregarReferenciaCombo('PRINCIPAL','Como nota principal',array('N','S'));
$tabla->AgregarReferenciaCombo('BAJA','Visible',array('N','S'));

$tabla->AgregarReferencia('ID_USUARIO_CREADOR', 'Creador','usuarios','ID','NICK');
$tabla->AgregarReferencia('ID_USUARIO_MODIFICADOR', 'Modificador','usuarios EDITORES','ID','NICK');

$tabla->AgregarReferencia('ID_TIPOCONTENIDO','Tipo contenido','tiposcontenidos','ID','TIPO');
$tabla->AgregarReferencia('ID_SECCION','Sección','secciones','ID','NOMBRE');
$tabla->AgregarAutoReferencia('ID_CONTENIDO','Contenido','cont','ID','TITULO');



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
