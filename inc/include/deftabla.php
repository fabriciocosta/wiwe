<?Php
//version 4.2 06/09/2006: completado usuarios
//version 4.1 22/08/2006 : agregado referencia a usuario.nombre y usuario.apellido
//version 4.0 18/07/2006
//header('Content-type: text/html; charset=iso-8859-1'); passed to config.php or Dinamik.php

require "../../inc/core/Dinamik.php";
//se saca si antes ya se definio

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
$_tsecciones2_ = new Tabla('secciones',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_tcontenidos_ = new Tabla('contenidos',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_tcontenidos2_ = new Tabla('contenidos',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_tdetalles_ = new Tabla('detalles',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_tusuarios_ =  new Tabla('usuarios',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_tgrupos_ =  new Tabla('grupos',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_ttiposarchivos_ =  new Tabla('tiposarchivos',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_tarchivos_ = new Tabla('archivos',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_ttiposcontenidos_ =  new Tabla('tiposcontenidos',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_ttipossecciones_ =  new Tabla('tipossecciones',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_ttiposdetalles_ =  new Tabla('tiposdetalles',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_tlogs_ = new Tabla('logs',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);

$_ttiposrelaciones_ = new Tabla('tiposrelaciones',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);
$_trelaciones_ = new Tabla('relaciones',$_DB_,$_SERVIDOR_,$_USUARIO_,$_CONTRASENA_,$_TIPODB_);


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
$tadmin->AgregarCampo('PISO',	'Piso',	'TEXTO',		'15%','NULL','','si','si',50);
$tadmin->AgregarCampo('EMPRESA',	'Empresa',	'TEXTO',		'15%','NULL','','si','si',50);
$tadmin->AgregarCampo('OFICINA',	'Oficina',	'TEXTO',		'15%','NULL','','si','si',50);
$tadmin->AgregarCampo('PAGINA',	'Página',	'TEXTO',		'15%','NULL','','si','si',120);

$tadminlog->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$tadminlog->AgregarCampo('ID_USUARIO',	'Id',	'ENTERO',		'15%','NOT NULL','','si','si',25);
$tadminlog->AgregarCampo('NICK_USUARIO',	'Nick',	'TEXTO',		'15%','NOT NULL','','si','si',25);
$tadminlog->AgregarCampo('LOGS',	'',	'ENTERO',		'15%','NOT NULL','1','si','si',25);
$tadminlog->AgregarCampo('ID_SECCION',	'',	'ENTERO',		'15%','NULL','0','si','si',25);
$tadminlog->AgregarCampo('ID_CONTENIDO',	'',	'ENTERO',		'15%','NULL','0','si','si',25);

$_tlogs_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_tlogs_->AgregarCampo('ID_CONTENIDO',		'',		'ENTERO',		'10%','NULL','','si','si',20);
$_tlogs_->AgregarCampo('ID_CONTENIDOAUX',		'',		'ENTERO',		'10%','NULL','','si','si',20);
$_tlogs_->AgregarCampo('ID_USUARIO',		'',		'ENTERO',		'10%','NULL','','si','si',20);
$_tlogs_->AgregarCampo('LOGCODE',		'',		'ENTERO',		'10%','NULL','','si','si',20);
$_tlogs_->AgregarCampo('ACCION',	'',	'TEXTO',		'15%','NULL','','si','si',25);
$_tlogs_->AgregarCampo('VALOR',	'',	'TEXTO',		'15%','NULL','','si','si',25);
$_tlogs_->AgregarCampo('FECHAALTA',	'',	'FECHA',		'15%','NOT NULL','NOW()','si','si',25);


$_tgrupossecciones_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_tgrupossecciones_->AgregarCampo('ID_SECCION',	'Id',	'ENTERO',		'15%','NOT NULL','','si','si',25);
$_tgrupossecciones_->AgregarCampo('ID_GRUPO',	'Id',	'ENTERO',		'15%','NOT NULL','','si','si',25);

$_tgruposusuarios_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_tgruposusuarios_->AgregarCampo('ID_USUARIO',	'Id',	'ENTERO',		'15%','NOT NULL','','si','si',25);
$_tgruposusuarios_->AgregarCampo('ID_GRUPO',	'Id',	'ENTERO',		'15%','NOT NULL','','si','si',25);

$_tsecciones_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_tsecciones_->AgregarCampo('ID_SECCION',	'',	'ENTERO',		'15%','NOT NULL','','si','si',25);
$_tsecciones_->AgregarCampo('ID_TIPOSECCION',	'',	'ENTERO',		'15%','NOT NULL','','si','si',25);
$_tsecciones_->AgregarCampo('ID_CONTENIDO',	'',	'ENTERO',		'15%','NOT NULL','1','si','si',25);
$_tsecciones_->AgregarCampo('NOMBRE',	'',	'TEXTO',		'15%','NULL','','si','si',25);
$_tsecciones_->AgregarCampo('ML_NOMBRE',	'',	'TEXTOML',		'15%','NULL','','si','si',25);
$_tsecciones_->AgregarCampo('DESCRIPCION',	'',	'BLOBTEXTO',		'15%','NULL','','si','si',25);
$_tsecciones_->AgregarCampo('ML_DESCRIPCION',	'',	'BLOBTEXTOML',		'15%','NULL','','si','si',25);
$_tsecciones_->AgregarCampo('PALABRASCLAVE',	'',	'BLOBTEXTO',		'15%','NULL','','si','si',25);
$_tsecciones_->AgregarCampo('ML_PALABRASCLAVE',	'',	'BLOBTEXTOML',		'15%','NULL','','si','si',25);

$_tsecciones_->AgregarCampo('PROFUNDIDAD',	'',	'ENTERO',		'15%','NOT NULL','','si','si',25);
$_tsecciones_->AgregarCampo('ORDEN',	'',	'ENTERO',		'15%','NOT NULL','1','si','si',25);
$_tsecciones_->AgregarCampo('RAMA',	'',	'TEXTO',		'15%','NULL','1','si','si',25);

$_tsecciones_->AgregarCampo('CATEGORIA',	'Es categoria',	'TEXTO',		'0','NOT NULL','N','si','si',2);
$_tsecciones_->AgregarCampo('CARPETA',	'Carpeta',	'TEXTO',		'2%','NULL','','si','si',25);
$_tsecciones_->AgregarCampo('ID_USUARIO_CREADOR',	'',	'ENTERO',		'15%','NULL','1','si','si',25);
$_tsecciones_->AgregarCampo('BAJA',	'',	'TEXTO',		'15%','NULL','N','si','si',2);

$_tcontenidos_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_tcontenidos_->AgregarCampo('ID_CONTENIDO',	'',	'ENTERO',		'15%','NOT NULL','1','si','si',25);
$_tcontenidos_->AgregarCampo('ID_TIPOCONTENIDO',	'',	'ENTERO',		'15%','NOT NULL','','si','si',25);
$_tcontenidos_->AgregarCampo('ID_SECCION',	'',	'ENTERO',		'15%','NOT NULL','','si','si',50);
$_tcontenidos_->AgregarCampo('ORDEN',	'',	'ENTERO',		'15%','NOT NULL','0','si','si',50);
$_tcontenidos_->AgregarCampo('PRINCIPAL',	'',	'TEXTO',		'15%','NOT NULL','N','si','si',25);
$_tcontenidos_->AgregarCampo('URL',	'',	'TEXTO',		'15%','NOT NULL','-','si','si',25);
$_tcontenidos_->AgregarCampo('ICONO',	'',	'TEXTO',		'15%','NOT NULL','-','si','si',25);
$_tcontenidos_->AgregarCampo('VOTOS',	'',	'ENTERO',		'15%','NOT NULL','0','si','si',25);
$_tcontenidos_->AgregarCampo('FECHAEVENTO',	'Fecha',	'FECHA',		'15%','NOT NULL','','si','si',25);
$_tcontenidos_->AgregarCampo('TITULO',	'',	'TEXTO',		'15%','NULL','','si','si',55);
$_tcontenidos_->AgregarCampo('ML_TITULO',	'',	'TEXTOML',		'15%','NULL','','si','si',55);
$_tcontenidos_->AgregarCampo('COPETE',	'',	'BLOBTEXTO',		'15%','NULL','','si','si',55,5);
$_tcontenidos_->AgregarCampo('ML_COPETE',	'',	'BLOBTEXTOML',		'15%','NULL','','si','si',55,5);
$_tcontenidos_->AgregarCampo('CUERPO',	'',	'BLOBTEXTO',		'15%','NULL','','si','si',55,13);
$_tcontenidos_->AgregarCampo('ML_CUERPO',	'',	'BLOBTEXTOML',		'15%','NULL','','si','si',55,13);
$_tcontenidos_->AgregarCampo('PALABRASCLAVE',	'',	'BLOBTEXTO',		'15%','NULL','','si','si',55,13);
$_tcontenidos_->AgregarCampo('ML_PALABRASCLAVE',	'',	'BLOBTEXTOML',		'15%','NULL','','si','si',55,13);
$_tcontenidos_->AgregarCampo('FECHAALTA',	'',	'FECHA',		'15%','NOT NULL','NOW()','si','si',25);
$_tcontenidos_->AgregarCampo('FECHABAJA',	'',	'FECHA',		'15%','NOT NULL','NOW()','si','si',25);
$_tcontenidos_->AgregarCampo('FECHAEVENTO',	'',	'FECHA',		'15%','NOT NULL','NOW()','si','si',25);
$_tcontenidos_->AgregarCampo('ACTUALIZACION',	'',	'FECHA',		'15%','NOT NULL','NOW()','no','si',50);
$_tcontenidos_->AgregarCampo('ID_USUARIO_CREADOR',	'',	'ENTERO',		'15%','NULL','1','no','si',50);
$_tcontenidos_->AgregarCampo('ID_USUARIO_MODIFICADOR',	'',	'ENTERO',		'15%','NULL','1','no','si',50);
$_tcontenidos_->AgregarCampo('AUTOR',	'',	'TEXTO',		'15%','NULL','NULL','si','si',55,13);
$_tcontenidos_->AgregarCampo('BAJA',	'',	'TEXTO',		'15%','NOT NULL','N','si','si',25);

$_tdetalles_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_tdetalles_->AgregarCampo('ID_TIPODETALLE',	'Tipo',	'ENTERO',		'15%','NOT NULL','','si','si',25);
$_tdetalles_->AgregarCampo('ID_CONTENIDO',	'',	'DECIMAL',		'15%','NOT NULL','N','si','si',25);
$_tdetalles_->AgregarCampo('ENTERO',	'Entero',	'ENTERO',		'15%','NULL','','si','si',5);
$_tdetalles_->AgregarCampo('FRACCION',	'Fraccion',	'DECIMAL',		'15%','NULL','','si','si',5);
$_tdetalles_->AgregarCampo('DETALLE',	'Detalle',	'TEXTO',		'15%','NULL','','si','si',50);
$_tdetalles_->AgregarCampo('ML_DETALLE',	'MLDetalle',	'TEXTOML',		'15%','NULL','','si','si',50);
$_tdetalles_->AgregarCampo('TXTDATA',	'TxtData',	'TEXTO',		'15%','NULL','','si','si',50);
$_tdetalles_->AgregarCampo('ML_TXTDATA',	'MLTxtData',	'TEXTOML',		'15%','NULL','','si','si',50);
$_tdetalles_->AgregarCampo('BINDATA',	'BinData',	'BLOB',		'15%','NULL','','si','si',25);

$_tusuarios_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',19);
$_tusuarios_->AgregarCampo('ID_CONTENIDO',		'',		'ENTERO',		'10%','NOT NULL','1','si','si',19);
$_tusuarios_->AgregarCampo('NICK',		'',		'TEXTO',		'10%','NOT NULL','','si','si',19);
$_tusuarios_->AgregarCampo('NOMBRE',		'',		'TEXTO',		'10%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('APELLIDO',		'',		'TEXTO',		'10%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('SEXO',		'',		'TEXTO',		'10%','NULL','','si','si',2);
$_tusuarios_->AgregarCampo('NIVEL',		'',		'ENTERO',		'10%','NOT NULL','4','si','si',19);
$_tusuarios_->AgregarCampo('PAIS',	'',	'TEXTO',		'15%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('PROVINCIA',	'',	'TEXTO',		'15%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('CIUDAD',	'',	'TEXTO',		'15%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('DIRECCION',	'',	'TEXTO',		'15%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('NACIMIENTO',	'',	'FECHA',		'15%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('CP',	'',	'TEXTO',		'15%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('PISO',	'',	'TEXTO',		'15%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('EMPRESA',	'',	'TEXTO',		'15%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('OCUPACION',	'',	'TEXTO',		'15%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('OFICINA',	'',	'TEXTO',		'15%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('CONTACTO',	'',	'BLOBTEXTO',		'15%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('TELEFONO',		'',		'TEXTO',		'10%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('CELULAR',		'',		'TEXTO',		'10%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('INTERNO',		'',		'TEXTO',		'10%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('IDIOMAS',		'',		'BLOBTEXTO',		'10%','NULL','a','si','si',19);
$_tusuarios_->AgregarCampo('PAGINA',		'',		'TEXTO',		'10%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('MAIL',		'',		'TEXTO',		'10%','NOT NULL','','si','si',35);
$_tusuarios_->AgregarCampo('PASSWORD',		'',		'PASSWORD',		'10%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('PASSMD5',		'',		'TEXTO',		'10%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('PASSKEY',		'',		'TEXTO',		'10%','NULL','','si','si',19);
$_tusuarios_->AgregarCampo('BAJA',	'',	'TEXTO',		'15%','NOT NULL','N','si','si',25);
 
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

$_ttiposcontenidos_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_ttiposcontenidos_->AgregarCampo('TIPO',		'',		'TEXTO',		'30%','NULL','','no','no',20);
$_ttiposcontenidos_->AgregarCampo('DESCRIPCION',		'',		'TEXTO',		'30%','NULL','','no','no',20);

$_ttipossecciones_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_ttipossecciones_->AgregarCampo('TIPO',		'',		'TEXTO',		'30%','NULL','','no','no',20);
$_ttipossecciones_->AgregarCampo('DESCRIPCION',		'',		'TEXTO',		'30%','NULL','','no','no',20);

$_ttiposdetalles_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_ttiposdetalles_->AgregarCampo('ID_TIPOCONTENIDO',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_ttiposdetalles_->AgregarCampo('TIPO',		'',		'TEXTO',		'30%','NULL','','no','no',20);
$_ttiposdetalles_->AgregarCampo('DESCRIPCION',		'',		'TEXTO',		'30%','NULL','','no','no',20);
$_ttiposdetalles_->AgregarCampo('TXTDATA',		'',		'BLOBTEXTO',		'30%','NULL','','no','no',20);
$_ttiposdetalles_->AgregarCampo('TIPOCAMPO',		'',		'TEXTO',		'30%','NULL','','no','no',20);


$_ttiposrelaciones_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_ttiposrelaciones_->AgregarCampo('ID_TIPODETALLE',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_ttiposrelaciones_->AgregarCampo('TIPO',		'',		'TEXTO',		'30%','NULL','','no','no',20);
$_ttiposrelaciones_->AgregarCampo('DESCRIPCION',		'',		'TEXTO',		'30%','NULL','','no','no',20);
$_ttiposrelaciones_->AgregarCampo('TXTDATA',		'',		'BLOBTEXTO',		'30%','NULL','','no','no',20);
$_ttiposrelaciones_->AgregarCampo('TIPOCAMPO',		'',		'TEXTO',		'30%','NULL','','no','no',20);

$_trelaciones_->AgregarCampo('ID',		'',		'ENTERO',		'10%','NULL','','no','no',20);
$_trelaciones_->AgregarCampo('ID_TIPORELACION',	'',	'ENTERO',		'15%','NOT NULL','','si','si',25);
$_trelaciones_->AgregarCampo('SENTIDO',	'',	'TEXTO',		'15%','NOT NULL','direct','si','si',25);
$_trelaciones_->AgregarCampo('PESO',	'',	'ENTERO',		'15%','NOT NULL','0','si','si',25);
$_trelaciones_->AgregarCampo('DISTANCIA',	'',	'ENTERO',		'15%','NOT NULL','0','si','si',25);
$_trelaciones_->AgregarCampo('ID_SECCION',	'',	'ENTERO',		'15%','NOT NULL','','si','si',25);
$_trelaciones_->AgregarCampo('ID_CONTENIDO',	'',	'ENTERO',		'15%','NOT NULL','1','si','si',25);
$_trelaciones_->AgregarCampo('ID_SECCION_REL',	'',	'ENTERO',		'15%','NOT NULL','','si','si',25);
$_trelaciones_->AgregarCampo('ID_CONTENIDO_REL',	'',	'ENTERO',		'15%','NOT NULL','1','si','si',25);
$_trelaciones_->AgregarCampo('ORDEN',	'',	'ENTERO',		'15%','NOT NULL','1','si','si',25);
$_trelaciones_->AgregarCampo('ACTUALIZACION',	'',	'FECHA',		'15%','NOT NULL','NOW()','si','si',50);

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
$_tcontenidos_->AgregarIndice( '{BYORDER}','contenidos.ORDEN ASC' );
$_tcontenidos_->AgregarIndice( '{BYLASTID}','contenidos.ID DESC' );
$_tcontenidos_->AgregarIndice( '{BYTITLE}','contenidos.TITULO' );
//$_tcontenidos_->AgregarIndice( '{BYAUTHOR}','AUTOR' );
//$_tcontenidos_->AgregarIndice( '{BYBRIEF}','COPETE');
//$_tcontenidos_->AgregarIndice('DATOS','CUERPO');
//$_tcontenidos_->AgregarIndice('FECHA','FECHAEVENTO');
$_tcontenidos_->AgregarIndice( '{BYDATEIN}','contenidos.FECHAALTA DESC');
$_tcontenidos_->AgregarIndice( '{BYVERIFIED}','contenidos.BAJA');
$_tcontenidos_->AgregarReferenciaCombo('BAJA','',array('N'=>'{NO}','S'=>'{YES}'));

$_tusuarios_->AgregarReferenciaCombo('BAJA','',array('N'=>'{NO}','S'=>'{YES}'));

$_tlogs_->AgregarIndice('ID','','PRIMARIO');


$_tdetalles_->AgregarIndice('ID','','PRIMARIO');
$_ttiposdetalles_->AgregarIndice('ID','','PRIMARIO');

$_tsecciones_->AgregarIndice('ID','','PRIMARIO');
$_tsecciones_->AgregarIndice('ORDEN','secciones.ORDEN');

$_tusuarios_->AgregarIndice('ID','','PRIMARIO');
$_tusuarios_->AgregarIndice( '{BYFIRSTNAMELASTNAME}','NOMBRE,APELLIDO');
$_tusuarios_->AgregarIndice( '{BYLASTNAMEFIRSTNAME}','APELLIDO,NOMBRE');
$_tusuarios_->AgregarIndice( '{BYCOUNTRY}','PAIS,CIUDAD');
$_tusuarios_->AgregarIndice( '{BYCITY}','CIUDAD');
$_tusuarios_->AgregarIndice( '{BYEMAIL}','MAIL');


$_tgrupos_->AgregarIndice('ID','','PRIMARIO');

$_ttiposarchivos_->AgregarIndice('ID','','PRIMARIO');

$_tarchivos_->AgregarIndice('ID','','PRIMARIO');
$_tarchivos_->AgregarIndice('NOMBRE','archivos.NOMBRE','');

$_trelaciones_->AgregarIndice('ID','','PRIMARIO');
/*			  						*/
/*			  						*/
/*	 	Referencias	a tablas		*/
/*			  						*/
/*			  						*/
//$tadminlog->AgregarReferencia('ID_SECCION','Sección','secciones','ID','NOMBRE');
//$tadminlog->AgregarReferencia('ID_CONTENIOD','Sección','secciones','ID','NOMBRE');
$_tsecciones_->AgregarReferencia('ID_TIPOSECCION','','tipossecciones','ID','TIPO');
$_tsecciones_->AgregarAutoReferencia('ID_SECCION','','padres','ID','NOMBRE');

$_tcontenidos_->AgregarReferencia('ID_SECCION','','secciones','ID','NOMBRE');
$_tcontenidos_->AgregarReferencia('ID_TIPOCONTENIDO','','tiposcontenidos','ID','TIPO');

$_tcontenidos_->AgregarReferencia('ID_USUARIO_CREADOR','','usuarios','ID','NICK');
$_tcontenidos_->AgregarReferencia('ID_USUARIO_CREADOR','','usuarios','ID','NOMBRE');
$_tcontenidos_->AgregarReferencia('ID_USUARIO_CREADOR','','usuarios','ID','APELLIDO');
$_tcontenidos_->AgregarReferencia('ID_USUARIO_MODIFICADOR','','usuarios EDITORES','ID','NICK');
$_tcontenidos_->AgregarReferencia('ID_USUARIO_MODIFICADOR','','usuarios EDITORES','ID','NOMBRE');
$_tcontenidos_->AgregarReferencia('ID_USUARIO_MODIFICADOR','','usuarios EDITORES','ID','APELLIDO');


//

$_trelaciones_->AgregarReferencia("ID_CONTENIDO","contenido asociado","contenidos","ID","BAJA" );
$_tdetalles_->AgregarReferencia('ID_TIPODETALLE','','tiposdetalles','ID','TIPO');

/*			  						*/
/*			  						*/
/*	 	Permisos				*/
/*			  						*/
/*			  						*/
/*depende de los permisos del usuario logueado, es dinamico*/
$tadmin->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$tadminlog->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_tgrupossecciones_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_tgruposusuarios_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_tsecciones_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_tcontenidos_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_tlogs_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_tusuarios_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_tgrupos_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_ttiposarchivos_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_tarchivos_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_ttiposdetalles_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_ttipossecciones_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_ttiposcontenidos_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));
$_tdetalles_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));

$_trelaciones_->Permisos(array('agregar'=>'si','modificar'=>'si','borrar'=>'si'));

$_tcontenidos2_->CopiarTabla( $_tcontenidos_ );
$_tsecciones2_->CopiarTabla( $_tsecciones_ );
/*			  						*/
/*			  						*/
/*	 	Debug	Imprime definicion	*/
/*			  						*/
/*			  						*/
//$_debug_='si';
if ($_debug_=='si') {
	//include("../../inc/include/style.php");
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
	$_ttiposcontenidos_->debug = 'si';	

	$_tlogs_->debug = 'si';	
	
	$_tdetalles_->debug = 'si';	
	$_ttiposdetalles_->debug = 'si';	
	//$_tusuarios_->Describe();
	$_tusuarios_->debug = 'si';	
	//$_tgrupos_->Describe();
	$_tgrupos_->debug = 'si';	
	//$_ttiposarchivos_->Describe();
	$_ttiposarchivos_->debug = 'si';	
	//$_tarchivos_->Describe();
	$_tarchivos_->debug = 'si';	
	
	
}
$tabla = &$_tcontenidos_;

//CONSTANTES

$_ttipossecciones_->LimpiarSQL();        
$_ttipossecciones_->OrdenSQL('TIPO DESC');
$_ttipossecciones_->Open();		     
if ( $_ttipossecciones_->nresultados>0 ) {
	while($_row_ = $_ttipossecciones_->Fetch() ) {						
		define($_row_['tipossecciones.TIPO'],$_row_['tipossecciones.ID']);
		$_TIPOS_['tipossecciones'][$_row_['tipossecciones.TIPO']] = $_row_['tipossecciones.ID'];
	}
}
$_ttipossecciones_->Close();

$_ttiposcontenidos_->LimpiarSQL();        
$_ttiposcontenidos_->OrdenSQL('TIPO DESC');
$_ttiposcontenidos_->Open();		     
if ( $_ttiposcontenidos_->nresultados>0 ) {
	while($_row_ = $_ttiposcontenidos_->Fetch() ) {						
		define($_row_['tiposcontenidos.TIPO'],$_row_['tiposcontenidos.ID']);				
		$_TIPOS_['tiposcontenidos'][$_row_['tiposcontenidos.TIPO']] = $_row_['tiposcontenidos.ID'];
	}
}
$_ttiposcontenidos_->Close();


$_ttiposdetalles_->LimpiarSQL();      
$_ttiposdetalles_->OrdenSQL('TIPO DESC');  
$_ttiposdetalles_->Open();		     
if ( $_ttiposdetalles_->nresultados>0 ) {
	while($_row_ = $_ttiposdetalles_->Fetch() ) {						
		define($_row_['tiposdetalles.TIPO'],$_row_['tiposdetalles.ID']);
		$_TIPOS_['tiposdetalles'][$_row_['tiposdetalles.TIPO']] = $_row_['tiposdetalles.ID'];
	}
}
$_ttiposdetalles_->Close();

$_ID_SYSTEM_TYPE_SECTION = SYSTEM;
$_ID_ROOT_TYPE_SECTION = ROOT;

$_ID_SYSTEM_TYPE_CARD = FICHA_SISTEMA;
$_ID_VOID_TYPE_CARD = VOID;

?>