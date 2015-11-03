<?Php

require '../include/DinamikAdmin.php';

/*			  						*/
/*			  						*/
/*	 	Definición de tablas		*/
/*			  						*/
/*			  						*/

$tabla = new Tabla('usuarios',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);

/*			  						*/
/*			  						*/
/*	 	Definición de campos		*/
/*			  						*/
/*			  						*/
/* [nombre , etiqueta , tipo , porcentaje(tabla) , puedesernulo? , defecto , editable , filtrar , tamanio] */
$tabla->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$tabla->AgregarCampo('NICK',	'Sobrenombre',	'TEXTO',		'15%','NOT NULL','','si','si',25);
$tabla->AgregarCampo('PASSWORD',	'Contraseña',	'PASSWORD',		'15%','NOT NULL','','si','si',16);
$tabla->AgregarCampo('NOMBRE',	'Nombre',	'TEXTO',		'15%','NOT NULL','','si','si',50);
$tabla->AgregarCampo('APELLIDO',	'Apellido',	'TEXTO',		'15%','NOT NULL','file','si','si',50);
$tabla->AgregarCampo('MAIL',	'Mail',	'TEXTO',		'15%','NULL','','si','si',80);
$tabla->AgregarCampo('TELEFONO',	'Teléfono',	'TEXTO',		'15%','NULL','','si','si',30);
$tabla->AgregarCampo('PAIS',	'Pais',	'TEXTO',		'15%','NULL','','si','si',50);
$tabla->AgregarCampo('CIUDAD',	'Ciudad',	'TEXTO',		'15%','NULL','','si','si',50);
$tabla->AgregarCampo('DIRECCION',	'Dirección',	'TEXTO',		'15%','NULL','','si','si',50);
$tabla->AgregarCampo('PISO',	'Piso',	'TEXTO',		'15%','NULL','','si','si',50);
$tabla->AgregarCampo('EMPRESA',	'Empresa',	'TEXTO',		'15%','NULL','','si','si',50);
$tabla->AgregarCampo('OFICINA',	'Oficina',	'TEXTO',		'15%','NULL','','si','si',50);
$tabla->AgregarCampo('PAGINA',	'Página',	'TEXTO',		'15%','NULL','','si','si',120);
$tabla->AgregarCampo('NIVEL',	'Nivel (0:max,<br>1:administradores,<br>2:dataentry,<br>3:superusuarios,<br>4:usuarios',	'TEXTO',		'15%','NULL','','si','si',30);


/*									*/
/*									*/
/*		Indices						*/
/*									*/
/*									*/

$tabla->AgregarIndice('ID','','PRIMARIO');
$tabla->AgregarIndice('SOBRENOMBRE','usuarios.NICK','');
$tabla->AgregarIndice('NOMBRE APELLIDO','usuarios.NOMBRE,usuarios.APELLIDO','');

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
