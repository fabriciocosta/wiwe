<?php
//version 4.1 22/08/2006 : corregido el id usuario creador de los contenidos...

function CreateWiWeTable( $__name__ , $__SQL__ ) {
	
	global	$Messages;
	global	$link;
	
	$mymess = "Creating table : `<span class=\"table_name\">".strtoupper( $__name__ )."</span>` >>>>> ";
	
	$__SQL__ = str_replace( "CREATE TABLE", "CREATE TABLE IF NOT EXISTS ", $__SQL__);
	
	$query = mysql_query( $__SQL__, $link);
	
	if (!$query) { 
		$mymess.= "Couldn't create table : ".mysql_error(); 
		$Error.= mysql_error();
		$Messages.= ShowError( $mymess, false );
	}
	else {
		$mymess.= "&nbsp;Success!";
		$Messages.= ShowMessage( $mymess, false);
	}
}

function CreateWiWeContent( $__table_name__, $__message__, $__id__, $__SQL__ ) {
	
	global $link;
	global $Messages;
	
	$mymess = "Inserting data:"."$__table_name__ : `<span class=\"content_name\">".$__message__."</span> >>>>> ";
	
	///first check if already exists
	$__SQL_IDCHECK__ = "SELECT ID FROM `$__table_name__` WHERE ID=".$__id__; 
	$query = mysql_query( $__SQL_IDCHECK__, $link);
	if ($query) {
		if (mysql_num_rows($query)>0) {
			
			//=================
			//  YA EXISTE
			//=================
			$mymess.= "Couldn't insert data : Record already exists<br>"; 
			$Error.= " Record already exists ".$__id__;
			$Messages.= ShowMessage( $mymess, false );		
		} else {
			//=================
			//  LO CREAMOS
			//=================
			$query = mysql_query( $__SQL__, $link);
			if (!$query) { 
				$mymess.= "Couldn't insert data : ".mysql_error()."<br>";
				$Error.= mysql_error();
				$Messages.= ShowError( $mymess, false ); 
			}
			else { 
				$mymess.= "&nbsp;Success!.<br>";
				$Messages.= ShowMessage( $mymess, false );
			}
		}
	}
}


CreateWiWeTable( "archivos", 
"CREATE TABLE archivos (
	   ID int(11) NOT NULL auto_increment,
	   ID_TIPOARCHIVO int(11) DEFAULT '0' NOT NULL,
	   ID_SECCION int(11) DEFAULT '0' NOT NULL,
	   NOMBRE varchar(200),
	   URL varchar(250),
	   ICONO varchar(128),
	   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
	   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
	   ACTUALIZACION timestamp DEFAULT '0000-00-00 00:00:00',
	   BAJA char(1) DEFAULT 'N',
	   DESCRIPCION text,
	   GUARDAR_SECCION char(1) DEFAULT 'N' NOT NULL,
	   PRIMARY KEY (ID),
	   UNIQUE NOMBRE (NOMBRE),
	   UNIQUE IDX_ARCHIVOS_URL (URL)
	)"
);

CreateWiWeTable( "contenidos", 
"CREATE TABLE contenidos (
	   ID int(11) NOT NULL auto_increment,
	   ID_TIPOCONTENIDO int(11) DEFAULT '0' NOT NULL,
	   ID_SECCION int(11) DEFAULT '0' NOT NULL,
	   ID_CONTENIDO int(11) DEFAULT '0',
	   ORDEN	int(11) DEFAULT '0' NOT NULL,
	   TITULO varchar(200),
	   ML_TITULO text,
	   URL varchar(255),
	   COPETE text,
	   ML_COPETE text,
	   CUERPO longtext,
	   ML_CUERPO longtext,
   	   PALABRASCLAVE text,
   	   ML_PALABRASCLAVE text,
	   AUTOR varchar(200),
	   ICONO varchar(128),
	   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
	   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
	   ACTUALIZACION timestamp DEFAULT '0000-00-00 00:00:00',
	   BAJA char(1) DEFAULT 'N',
	   PRINCIPAL char(1) DEFAULT 'N' NOT NULL,
	   FECHAALTA datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	   FECHABAJA datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	   FECHAEVENTO datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	   VOTOS int(11) DEFAULT '0' NOT NULL,
	   PRIMARY KEY (ID)
	)"
);



CreateWiWeTable( "detalles", 
"CREATE TABLE detalles (
   ID int(11) NOT NULL auto_increment,
   ID_TIPODETALLE int(11) DEFAULT '0' NOT NULL,
   ID_CONTENIDO int(11) DEFAULT '0' NOT NULL,
   ENTERO int(11) default '0',
   FRACCION double default '0',   
   DETALLE varchar(200),
   ML_DETALLE text,
   TXTDATA text,
   ML_TXTDATA text,
   BINDATA blob,
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp DEFAULT '0000-00-00 00:00:00',
   BAJA char(1) DEFAULT 'N',
   PRIMARY KEY (ID)
)"
);

CreateWiWeTable( "relaciones", 
"CREATE TABLE relaciones (
   ID int(11) NOT NULL auto_increment,
   ID_TIPORELACION int(11) DEFAULT '0' NOT NULL,
   ID_CONTENIDO int(11) DEFAULT '0' NOT NULL,
   ID_SECCION int(11) DEFAULT '0' NOT NULL,
   ID_CONTENIDO_REL int(11) DEFAULT '0' NOT NULL,
   ID_SECCION_REL int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp DEFAULT '0000-00-00 00:00:00',
   ORDEN	int(11) DEFAULT '0' NOT NULL,
   PRIMARY KEY (ID)
)"
);

CreateWiWeTable( "grupos", 
"CREATE TABLE grupos (
   ID int(11) NOT NULL auto_increment,
   GRUPO varchar(25) NOT NULL,
   DESCRIPCION varchar(200),
   PERMISOS_MIEMBROS int(11) DEFAULT '0' NOT NULL,
   PERMISOS_USUARIOS int(11) DEFAULT '0' NOT NULL,
   ICONO varchar(128),
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp DEFAULT '0000-00-00 00:00:00',
   BAJA char(1) DEFAULT 'N',
   PRIMARY KEY (ID),
   UNIQUE GRUPO (GRUPO)
)"
);



CreateWiWeTable( "detalles", 
"CREATE TABLE grupossecciones (
   ID int(11) NOT NULL auto_increment,
   ID_GRUPO int(11) DEFAULT '0' NOT NULL,
   ID_SECCION int(11) DEFAULT '0' NOT NULL,
   ICONO varchar(128),
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   PERMISOS_SECCION char(3) DEFAULT 'LBM' NOT NULL,
   PERMISOS_CONTENIDOS varchar(4) DEFAULT 'LABM' NOT NULL,
   PERMISOS_SUBSECCIONES varchar(4) DEFAULT 'LABM' NOT NULL,
   ACTUALIZACION timestamp DEFAULT '0000-00-00 00:00:00',
   BAJA char(1) DEFAULT 'N',
   PRIMARY KEY (ID),
   UNIQUE IDX_GRUPOSSECCIONES (ID_SECCION, ID_GRUPO)
)"
);

CreateWiWeTable( "gruposusuarios", 
"CREATE TABLE gruposusuarios (
   ID int(11) NOT NULL auto_increment,
   ID_GRUPO int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO int(11) DEFAULT '0' NOT NULL,
   ICONO varchar(128),
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp DEFAULT '0000-00-00 00:00:00',
   BAJA char(1) DEFAULT 'N',
   PRIMARY KEY (ID),
   UNIQUE IDX_GRUPOSUSUARIOS (ID_USUARIO, ID_GRUPO),
   UNIQUE ICONO (ICONO)
)"
);

CreateWiWeTable( "logs", 
"CREATE TABLE logs (
   ID int(11) NOT NULL auto_increment,
   ID_CONTENIDO int(11),
   ID_CONTENIDOAUX int(11),
   ID_USUARIO int(11),
   ACCION varchar(25),
   VALOR varchar(25),
   LOGCODE int(11),
   FECHAALTA datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   ACTUALIZACION timestamp DEFAULT '0000-00-00 00:00:00',
   PRIMARY KEY (ID),
   KEY ID_CONTENIDOAUX (ID_CONTENIDOAUX)
)"
);


CreateWiWeTable( "logusuarios", 
"CREATE TABLE logusuarios (
   ID int(11) NOT NULL auto_increment,
   ID_USUARIO int(11) DEFAULT '0' NOT NULL,
   NICK_USUARIO varchar(25) NOT NULL,
   ID_SECCION int(11),
   ID_CONTENIDO int(11),
   IP varchar(30),
   LOGS int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp DEFAULT '0000-00-00 00:00:00',
   PRIMARY KEY (ID)
)"
);

CreateWiWeTable( "secciones", 
"CREATE TABLE secciones (
   ID int(11) NOT NULL auto_increment,
   ID_TIPOSECCION int(11) DEFAULT '0' NOT NULL,
   ID_SECCION int(11) DEFAULT '0' NOT NULL,
   ID_CONTENIDO int(11) DEFAULT '1' NOT NULL,
   ORDEN int(11) DEFAULT '0' NOT NULL,
   PROFUNDIDAD int(11) DEFAULT '0' NOT NULL,
   RAMA varchar(255),
   NOMBRE varchar(40),
   ML_NOMBRE text,
   DESCRIPCION varchar(200),
   ML_DESCRIPCION text,
   PALABRASCLAVE text,
   ML_PALABRASCLAVE text,
   ICONO varchar(128),
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp DEFAULT '0000-00-00 00:00:00',
   BAJA char(1) DEFAULT 'N',
   CATEGORIA char(1) DEFAULT 'N' NOT NULL,
   CARPETA varchar(200),
   PRIMARY KEY (ID),
   KEY RAMA (RAMA)
)"
);

CreateWiWeTable( "tiposarchivos", 
"CREATE TABLE tiposarchivos (
   ID int(11) NOT NULL auto_increment,
   TIPO varchar(100),
   DESCRIPCION varchar(200),
   ICONO varchar(128),
   ID_GRUPO int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp DEFAULT '0000-00-00 00:00:00',
   BAJA char(1) DEFAULT 'N',
   CARPETA varchar(200),
   PRIMARY KEY (ID),
   UNIQUE CARPETA (CARPETA)
)"
);

CreateWiWeTable( "tiposcontenidos", 
"CREATE TABLE tiposcontenidos (
   ID int(11) NOT NULL auto_increment,
   TIPO varchar(100),
   DESCRIPCION varchar(200),
   ICONO varchar(128),
   ID_GRUPO int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp DEFAULT '0000-00-00 00:00:00',
   BAJA char(1) DEFAULT 'N',
   PUBLICO char(1) DEFAULT 'S' NOT NULL,
   ADMIN char(1) DEFAULT 'S' NOT NULL,
   PRIMARY KEY (ID)
)"
);

CreateWiWeTable( "tiposdetalles", 
"CREATE TABLE tiposdetalles (
   ID int(11) NOT NULL auto_increment,
   ID_TIPOCONTENIDO int(11) DEFAULT '0' NOT NULL,
   TIPO varchar(100),
   DESCRIPCION varchar(200),
   TXTDATA text,
   ICONO varchar(128),
   TIPOCAMPO char(5),
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp DEFAULT '0000-00-00 00:00:00',
   BAJA char(1) DEFAULT 'N',
   PUBLICO char(1) DEFAULT 'S' NOT NULL,
   ADMIN char(1) DEFAULT 'S' NOT NULL,
   PRIMARY KEY (ID)
)"
);



CreateWiWeTable( "tipossecciones", 
"CREATE TABLE tipossecciones (
   ID int(11) NOT NULL auto_increment,
   TIPO varchar(100),
   DESCRIPCION varchar(200),
   ICONO varchar(128),
   ID_GRUPO int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp DEFAULT '0000-00-00 00:00:00',
   BAJA char(1) DEFAULT 'N' NOT NULL,
   PUBLICO char(1) DEFAULT 'S' NOT NULL,
   ADMIN char(1) DEFAULT 'S' NOT NULL,
   PERMISOS_SECCION char(3) DEFAULT 'LBM' NOT NULL,
   PERMISOS_CONTENIDOS varchar(4) DEFAULT 'LABM' NOT NULL,
   PERMISOS_SUBSECCIONES varchar(4) DEFAULT 'LABM' NOT NULL,
   PRIMARY KEY (ID),
   UNIQUE ID (ID),
   KEY ID_2 (ID)
)"
);

CreateWiWeTable( "tiposrelaciones", 
"CREATE TABLE tiposrelaciones (
   ID int(11) NOT NULL auto_increment,
   ID_TIPODETALLE int(11) DEFAULT '0' NOT NULL,
   TIPO varchar(100),
   DESCRIPCION varchar(200),
   TXTDATA text,
   ICONO varchar(128),
   TIPOCAMPO char(1),
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp DEFAULT '0000-00-00 00:00:00',
   BAJA char(1) DEFAULT 'N',
   PUBLICO char(1) DEFAULT 'S' NOT NULL,
   ADMIN char(1) DEFAULT 'S' NOT NULL,
   PRIMARY KEY (ID)
)"
);

CreateWiWeTable( "usuarios", 
"CREATE TABLE usuarios (
  ID int(11) NOT NULL auto_increment,
  NICK varchar(80) NOT NULL,
  PASSWORD varchar(32) NOT NULL,
  PASSMD5 varchar(32) default NULL,
  PASSKEY tinytext,
  NOMBRE varchar(50) default NULL,
  APELLIDO varchar(50) default NULL,
  SEXO char(1) default NULL,
  NIVEL int(11) NOT NULL default '0',
  MAIL varchar(80) default NULL,
  TELEFONO varchar(30) default NULL,
  CELULAR varchar(30) default NULL,
  INTERNO varchar(16) default NULL,
  PAIS varchar(40) default NULL,
  CIUDAD varchar(40) default NULL,
  DIRECCION varchar(50) default NULL,
  CP varchar(12)  default NULL,
  PISO varchar(5) default NULL,
  EMPRESA varchar(40) default NULL,
  OFICINA varchar(5) default NULL,
  CONTACTO tinytext,
  PAGINA varchar(200) default NULL,
  IDIOMAS tinytext,
  ICQ varchar(15) default NULL,
  ICONO varchar(128) default NULL,
  ID_CONTENIDO int(11) default NULL,
  ID_USUARIO_CREADOR int(11) NOT NULL default '0',
  ID_USUARIO_MODIFICADOR int(11) NOT NULL default '0',
  NACIMIENTO timestamp NOT NULL default '0000-00-00 00:00:00',
  ACTUALIZACION timestamp NOT NULL default '0000-00-00 00:00:00',
  BAJA char(1) default 'N',
  PRIMARY KEY  (ID),
  UNIQUE KEY NICK (NICK),
  KEY NIVEL (NIVEL),
  KEY MAIL (MAIL),
  KEY NOMBRE (NOMBRE),
  KEY APELLIDO (APELLIDO),
  KEY ID_USUARIO_CREADOR (ID_USUARIO_CREADOR)
)"
);

//======================================================
//
//	REGISTROS DE BASE
//
//======================================================

/**
 * Creación de tipos de secciones
 * SYSTEM : de sistema
 * ROOT :  de raiz de contenidos
 */

CreateWiWeContent( "tipossecciones", 
"Creando tipo de sección: SYSTEM", 1, 
"INSERT INTO tipossecciones VALUES ( '1', 'SYSTEM', 'System, variables and preferences', NULL, '0', '0', '0', '0000-00-00 00:00:00', 'N','S','S','LBM','LABM','LABM')"
);

CreateWiWeContent( "tipossecciones", 
"Creando tipo de sección: ROOT", 2, 
"INSERT INTO tipossecciones VALUES ( '2', 'ROOT', 'Contents root', NULL, '0', '0', '0', '0000-00-00 00:00:00', 'N','S','S','LBM','LABM','LABM')"
);

/**
 * Creación de secciones
 * SYSTEM : de sistema
 * ROOT :  de raiz de contenidos
 */

CreateWiWeContent( "secciones", 
"Creando sección: SYSTEM", 1, 
"INSERT INTO secciones VALUES ( '1', '1', '1', '1', '0', '0', '0', 'Sistema','', 'Sistema y Configuración','','Palabras clave','', NULL, '0', '0', '0000-00-00 00:00:00', 'N', 'S', 'system')"
);


CreateWiWeContent( "secciones", 
"Creando sección: ROOT", 2, 
"INSERT INTO secciones VALUES ( '2', '2', '2', '1', '0', '0', '1', 'Raíz','', 'Raiz de arbol de contenidos','','Palabras clave','', NULL, '0', '0', '0000-00-00 00:00:00', 'N', 'S', 'root')"
);

/**
 * Creación de tipos de contenidos
 * FICHA_SISTEMA
 * VOID
 */

CreateWiWeContent( "tiposcontenidos", 
"Creando tipo de contenido: FICHA_SISTEMA", 1, 
"INSERT INTO tiposcontenidos VALUES ( '1', 'FICHA_SISTEMA', 'Ficha Sistema', NULL, '0', '0', '0', '0000-00-00 00:00:00', 'N','S','S')"
);

CreateWiWeContent( "tiposcontenidos", 
"Creando tipo de contenido: VOID", 2, 
"INSERT INTO tiposcontenidos VALUES ( '2', 'VOID', 'void', NULL, '0', '0', '0', '0000-00-00 00:00:00', 'N','S','S')"
);



/**
 * Creación de contenidos
 * Ficha sistema del sitio
 * Ficha vacía tipo void para referencias nulas
 */


CreateWiWeContent( "contenidos", 
"Creando contenido: Ficha de sistema del sitio", 1, 
"INSERT INTO contenidos VALUES ( '1', '1', '1', '1', '0', 'Título del sitio','', NULL, 'Descripción del sitio','', 'Palabras clave del sitio','', '','', '', NULL, '1', '1', '0000-00-00 00:00:00', 'S', 'N', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2006-04-10 09:38:46', '0')"
);

CreateWiWeContent( "contenidos", 
"Creando contenido: Ficha vacía tipo void para referencias nulas", 2, 
"INSERT INTO contenidos VALUES ( '2', '2', '2', '2', '0','Void','', NULL, 'void','', 'void','', '','', '', NULL, '1', '1', '0000-00-00 00:00:00', 'S', 'N', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2006-04-10 09:38:46', '0')"
);


/**
 * Creación de usuarios
 * cg_admin		wiwe	nivel 0 : administrador de sistema
 * admin		admin	nivel 1 : administrador de contenidos
 */


//new password: *3D32FCB3C54931F009E950A8C20DAFDCFE5A6703
//old password: 2b060272d081f1b18050155871af64db
CreateWiWeContent( "usuarios", 
"Creando usuario: cg_admin", 1, 
"INSERT INTO `usuarios` VALUES (1, 
								'cg_admin', 
								'0b956a3a357d18ff',
								'2b060272d081f1b18050155871af64db', 
								NULL, 
								NULL, 
								NULL, 
								NULL, 
								0, 
								'admin@localhost',
								 
								NULL, 
								NULL, 
								NULL, 
								NULL, 
								NULL, 
								NULL, 
								
								NULL, 
								NULL, 
								NULL, 
								NULL, 
								NULL, 
								
								NULL, 
								NULL, 
								NULL, 
								NULL, 
								NULL, 
								0, 
								0, 
								'2009-04-06 05:07:29', 
								'0000-00-00 00:00:00', 
								'N')"
								);
								

CreateWiWeContent( "usuarios", 
"Creando usuario: admin", 2, 
"INSERT INTO `usuarios` VALUES (2, 
								'admin', 
								'43e9a4ab75570f5b', 
								MD5('admin'), 
								NULL, 
								NULL, 
								NULL, 
								NULL, 
								'1', 
								'admin@admin.com',
								 
								NULL, 
								NULL, 
								NULL, 
								NULL, 
								NULL, 
								NULL, 
								
								NULL, 
								NULL, 
								NULL, 
								NULL, 
								NULL, 
								
								NULL, 
								NULL, 
								NULL, 
								NULL, 
								NULL, 
								0, 
								0, 
								'2009-04-06 05:07:29', 
								'0000-00-00 00:00:00', 
								'N')"
);



/**
 * Creación de grupos por usuario
 */

CreateWiWeContent( "grupos", 
"Creando usuario: grupo_cg_admin", 1, 
"INSERT INTO grupos VALUES ( '1', 'grupo_cg_admin', 'Grupo de acceso personalizado: cg_admin', '3', '0', NULL, '1', '1', '2003-02-20 04:18:01', 'N')"
);


CreateWiWeContent( "grupos", 
"Creando usuario: grupo_admin", 2, 
"INSERT INTO grupos VALUES ( '2', 'grupo_admin', 'Grupo de acceso personalizado: admin', '0', '0', NULL, '0', '0', '2005-06-03 07:14:37', 'N')"
);


/**
 * Asignación de usuarios a grupos
 */


CreateWiWeContent( "gruposusuarios", 
"Creando usuario: cg_admin<>grupo_cg_admin", 1, 
"INSERT INTO gruposusuarios VALUES ( '1', '1', '1', NULL, '0', '0', '0000-00-00 00:00:00', 'N')"
);

CreateWiWeContent( "gruposusuarios", 
"Creando usuario: admin<>grupo_admin", 2, 
"INSERT INTO gruposusuarios VALUES ( '2', '2', '2', NULL, '0', '0', '0000-00-00 00:00:00', 'N')"
);


/**
 * Asignación de permisos sobre secciones por grupo
 */

CreateWiWeContent( "grupossecciones", 
"Creando usuario: grupo_cg_admin => SISTEMA", 1, 
"INSERT INTO grupossecciones VALUES ( '1', '1', '1', NULL, '0', '0', 'LBM', 'LABM', 'LABM', '0000-00-00 00:00:00', 'N')"
);

CreateWiWeContent( "grupossecciones", 
"Creando usuario: grupo_cg_admin => ROOT", 2, 
"INSERT INTO grupossecciones VALUES ( '2', '1', '2', NULL, '0', '0', 'LBM', 'LABM', 'LABM', '0000-00-00 00:00:00', 'N')"
);

CreateWiWeContent( "grupossecciones", 
"Creando usuario: grupo_admin => ROOT", 3, 
"INSERT INTO grupossecciones VALUES ( '3', '2', '2', NULL, '0', '0', 'LBM', 'LABM', 'LABM', '0000-00-00 00:00:00', 'N')"
);

?>