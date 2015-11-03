<?Php

require "../include/DinamikAdmin.php";
//require "../include/tabla.php";

/*			  						*/
/*			  						*/
/*	 	Definición de tablas		*/
/*			  						*/
/*			  						*/

$tabla = new Tabla('secciones',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);

/*			  						*/
/*			  						*/
/*	 	Definición de campos		*/
/*			  						*/
/*			  						*/
/* [nombre , etiqueta , tipo , porcentaje(tabla) , puedesernulo? , defecto , editable , filtrar , tamanio] */
$tabla->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','si',20);
$tabla->AgregarCampo('NOMBRE',	'Nombre',	'TEXTO',		'15%','NOT NULL','','si','si',40);
$tabla->AgregarCampo('CATEGORIA',	'Es categoria',	'TEXTO',		'0','NOT NULL','N','si','si',40);
$tabla->AgregarCampo('CARPETA',	'Carpeta',	'TEXTO',		'2%','NULL','','si','si',40);
$tabla->AgregarCampo('ID_SECCION',	'',	'ENTERO',		'15%','NULL','','si','si',20);
$tabla->AgregarCampo('ID_TIPOSECCION',	'',	'ENTERO',		'15%','NULL','','si','si',20);
$tabla->AgregarCampo('ID_CONTENIDO',	'',	'ENTERO',		'5%','NULL','','no','no',20);
$tabla->AgregarCampo('RAMA',	'Rama',	'TEXTO',		'15%','NULL','','si','no',20);
$tabla->AgregarCampo('ORDEN',	'Orden',	'ENTERO',		'15%','NOT NULL','1','si','no',20);
$tabla->AgregarCampo('PROFUNDIDAD',	'',	'ENTERO',		'15%','NULL','','si','no',20);
$tabla->AgregarCampo('DESCRIPCION',	'Descripción',	'TEXTO',		'45%','NOT NULL','','si','si',80);
$tabla->AgregarCampo('BAJA',	'Baja',	'TEXTO',		'2%','NOT NULL','N','si','si',10);



/*									*/
/*									*/
/*		Indices						*/
/*									*/
/*									*/

$tabla->AgregarIndice('ID','','PRIMARIO');
$tabla->AgregarIndice('NOMBRE','secciones.NOMBRE','');
$tabla->AgregarIndice('ID_SECCION-NOMBRE','secciones.ID_SECCION,secciones.NOMBRE','');

/*			  						*/
/*			  						*/
/*	 	Referencias	a tablas		*/
/*			  						*/
/*			  						*/
//$tabla->AgregarAutoReferencia('ID_SECCION','Padre','padres','ID','NOMBRE');
$tabla->AgregarReferenciaCombo('CATEGORIA','Es categoría',array('N','S'));
$tabla->AgregarAutoReferencia('ID_SECCION','Padre','padres','ID','NOMBRE');
$tabla->AgregarReferencia('ID_CONTENIDO','Nota principal','contenidos','ID','TITULO');
$tabla->AgregarReferencia('ID_TIPOSECCION','Tipo de sección','tipossecciones','ID','TIPO');


//$tabla->AgregarReferenciaAnidada('ID_SECCION','','padres','ID','grupossecciones','ID_SECCION','ID');
//$tabla->AgregarReferenciaAnidada('ID_SECCION','Idusuario','grupossecciones','ID_GRUPO','gruposusuarios','ID_GRUPO','ID_USUARIO');

//$tabla->AgregarReferencia('ID_SECCION','Grupoid','grupossecciones','ID_SECCION','ID_GRUPO');


//$tabla->AgregarReferencia('ID_SECCION','Grupoid','grupossecciones','ID_SECCION','ID_GRUPO');
//$tabla->AgregarReferencia('ID_SECCION','','grupossecciones','ID_SECCION','ID');
//$tabla->AgregarReferenciaAnidada('ID_SECCION','','grupossecciones','ID_GRUPO','gruposusuarios','ID_GRUPO','ID_USUARIO');
//$tabla->AgregarReferenciaAnidada('ID_SECCION','Seccion','grupossecciones','ID_SECCION','secciones','ID','NOMBRE');
//$tabla->AgregarAutoReferencia('ID_SECCION','Profundidad','padres','ID','PROFUNDIDAD');


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
