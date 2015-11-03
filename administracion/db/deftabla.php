<?Php
//se saca si antes ya se definio
//require "../include/tabla.php";

/*			  						*/
/*			  						*/
/*	 	Definición de tablas		*/
/*			  						*/
/*			  						*/

$tadmin = new Tabla('usuarios',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$tadminlog = new Tabla('logusuarios',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_tgrupossecciones_ = new Tabla('grupossecciones',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_tgruposusuarios_ = new Tabla('gruposusuarios',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_tsecciones_ = new Tabla('secciones',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_tcontenidos_ = new Tabla('contenidos',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_tusuarios_ =  new Tabla('usuarios',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_tgrupos_ =  new Tabla('grupos',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_ttiposarchivos_ =  new Tabla('tiposarchivos',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_tarchivos_ = new Tabla('archivos',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);

//$_tarchivos_

/*			  						*/
/*			  						*/
/*	 	Definición de campos		*/
/*			  						*/
/*			  						*/
/* [nombre , etiqueta , tipo , porcentaje(tabla) , puedesernulo? , defecto , editable , filtrar , tamanio] */
$tadmin->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$tadmin->AgregarCampo('NICK',	'Sobrenombre',	'TEXTO',		'15%','NULL','','si','si',25);
$tadmin->AgregarCampo('PASSWORD',	'Contraseña',	'PASSWORD',		'15%','NOT NULL','','si','si',16);
$tadmin->AgregarCampo('NOMBRE',	'Nombre',	'TEXTO',		'15%','NULL','','si','si',50);
$tadmin->AgregarCampo('APELLIDO',	'Apellido',	'TEXTO',		'15%','NULL','file','si','si',50);
$tadmin->AgregarCampo('MAIL',	'Mail',	'TEXTO',		'15%','NULL','','si','si',80);
$tadmin->AgregarCampo('TELEFONO',	'Teléfono',	'TEXTO',		'15%','NULL','','si','si',30);
$tadmin->AgregarCampo('DIRECCION',	'Dirección',	'TEXTO',		'15%','NULL','','si','si',50);
$tadmin->AgregarCampo('PAGINA',	'Página',	'TEXTO',		'15%','NULL','','si','si',120);

$tadminlog->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$tadminlog->AgregarCampo('ID_USUARIO',	'Id',	'ENTERO',		'15%','NOT NULL','','si','si',25);
$tadminlog->AgregarCampo('NICK_USUARIO',	'Nick',	'TEXTO',		'15%','NOT NULL','','si','si',25);
$tadminlog->AgregarCampo('LOGS',	'',	'ENTERO',		'15%','NOT NULL','1','si','si',25);
$tadminlog->AgregarCampo('ID_SECCION',	'',	'ENTERO',		'15%','NULL','0','si','si',25);
$tadminlog->AgregarCampo('ID_CONTENIDO',	'',	'ENTERO',		'15%','NULL','0','si','si',25);

$_tgrupossecciones_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_tgrupossecciones_->AgregarCampo('ID_SECCION',	'Id',	'ENTERO',		'15%','NOT NULL','','si','si',25);
$_tgrupossecciones_->AgregarCampo('ID_GRUPO',	'Id',	'ENTERO',		'15%','NOT NULL','','si','si',25);

$_tgruposusuarios_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_tgruposusuarios_->AgregarCampo('ID_USUARIO',	'Id',	'ENTERO',		'15%','NOT NULL','','si','si',25);
$_tgruposusuarios_->AgregarCampo('ID_GRUPO',	'Id',	'ENTERO',		'15%','NOT NULL','','si','si',25);

$_tsecciones_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_tsecciones_->AgregarCampo('ID_SECCION',	'Id',	'ENTERO',		'15%','NOT NULL','','si','si',25);
$_tsecciones_->AgregarCampo('ID_CONTENIDO',	'Id',	'ENTERO',		'15%','NULL','','si','si',25);
$_tsecciones_->AgregarCampo('NOMBRE',	'Id',	'TEXTO',		'15%','NULL','','si','si',25);

$_tcontenidos_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_tcontenidos_->AgregarCampo('ID_SECCION',	'Id',	'ENTERO',		'15%','NOT NULL','','si','si',25);
$_tcontenidos_->AgregarCampo('PRINCIPAL',	'Id',	'TEXTO',		'15%','NOT NULL','N','si','si',25);
$_tcontenidos_->AgregarCampo('TITULO',	'Id',	'TEXTO',		'15%','NULL','','si','si',25);

$_tusuarios_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_tusuarios_->AgregarCampo('NICK',		'',		'ENTERO',		'10%','NULL','','no','no',20);

$_tgrupos_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_tgrupos_->AgregarCampo('GRUPO',		'',		'ENTERO',		'10%','NULL','','no','no',20);

$_ttiposarchivos_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_ttiposarchivos_->AgregarCampo('CARPETA',		'',		'TEXTO',		'10%','NULL','','no','no',20);
$_ttiposarchivos_->AgregarCampo('TIPO',		'',		'TEXTO',		'10%','NULL','','no','no',20);

$_tarchivos_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_tarchivos_->AgregarCampo('ID_SECCION',	'',	'ENTERO',		'15%','NOT NULL','','si','si',20);
$_tarchivos_->AgregarCampo('ID_TIPOARCHIVO',	'',	'ENTERO',		'15%','NOT NULL','','si','si',20);
$_tarchivos_->AgregarCampo('GUARDAR_SECCION',	'Guardar seccion',	'TEXTO',		'5%','NULL','N','si','si',20);
$_tarchivos_->AgregarCampo('NOMBRE',	'Nombre',	'TEXTO',		'15%','NOT NULL','','si','si',40);
$_tarchivos_->AgregarCampo('ARCHIVO',	'Archivo',	'ARCHIVO',		'15%','NULL','','si','si',80);
$_tarchivos_->AgregarCampo('DESCRIPCION',	'Descripción',	'BLOBTEXTO',		'15%','NULL','','si','si',80);
$_tarchivos_->AgregarCampo('URL',	'Url',	'TEXTO',		'15%','NULL','','si','si',80);

/*									*/
/*									*/
/*		Indices						*/
/*									*/
/*									*/

$tadmin->AgregarIndice('ID','','PRIMARIO');
$tadmin->AgregarIndice('SOBRENOMBRE','usuarios.NICK','');
$tadmin->AgregarIndice('NOMBRE APELLIDO','usuarios.NOMBRE,usuarios.APELLIDO','');

$tadminlog->AgregarIndice('ID','','PRIMARIO');

$_tgrupossecciones_->AgregarIndice('ID','','PRIMARIO');

$_tgruposusuarios_->AgregarIndice('ID','','PRIMARIO');

$_tcontenidos_->AgregarIndice('ID','','PRIMARIO');

$_tsecciones_->AgregarIndice('ID','','PRIMARIO');

$_tusuarios_->AgregarIndice('ID','','PRIMARIO');

$_tgrupos_->AgregarIndice('ID','','PRIMARIO');

$_ttiposarchivos_->AgregarIndice('ID','','PRIMARIO');

$_tarchivos_->AgregarIndice('ID','','PRIMARIO');
$_tarchivos_->AgregarIndice('NOMBRE','archivos.NOMBRE','');

/*			  						*/
/*			  						*/
/*	 	Referencias	a tablas		*/
/*			  						*/
/*			  						*/
//$tadminlog->AgregarReferencia('ID_SECCION','Sección','secciones','ID','NOMBRE');
//$tadminlog->AgregarReferencia('ID_CONTENIOD','Sección','secciones','ID','NOMBRE');

/*			  						*/
/*			  						*/
/*	 	Permisos					*/
/*			  						*/
/*			  						*/
/*depende de los permisos del usuario logueado, es dinamico*/
$tadmin->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$tadminlog->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_tgrupossecciones_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_tgruposusuarios_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_tsecciones_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_tcontenidos_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_tusuarios_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_tgrupos_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_ttiposarchivos_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_tarchivos_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));


/*			  						*/
/*			  						*/
/*	 	Debug	Imprime definicion	*/
/*			  						*/
/*			  						*/
//$_debug_='si';
if ($_debug_=='si') {
	include("../include/style.php");
	//$tadmin->Describe();
	$tadmin->debug = 'si';
	//$tadminlog->Describe();
	$tadminlog->debug = 'si';	
	//$_tgrupossecciones_->Describe();
	$_tgrupossecciones_->debug = 'si';	
	//$_tgrupossecciones_->Describe();
	$_tgruposusuarios_->debug = 'si';	
	//$_tgrupossecciones_->Describe();
	$_tsecciones_->debug = 'si';	
	//$_tgrupossecciones_->Describe();
	$_tcontenidos_->debug = 'si';	
	//$_tusuarios_->Describe();
	$_tusuarios_->debug = 'si';	
	//$_tgrupos_->Describe();
	$_tgrupos_->debug = 'si';	
	//$_ttiposarchivos_->Describe();
	$_ttiposarchivos_->debug = 'si';	
	//$_tarchivos_->Describe();
	$_tarchivos_->debug = 'si';	
	
	
}
?>
