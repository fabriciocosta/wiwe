# phpMyAdmin MySQL-Dump
# http://phpwizard.net/phpMyAdmin/
#
# Host: localhost Database : uv0160_cg

# --------------------------------------------------------
#
# Table structure for table 'archivos'
#

CREATE TABLE archivos (
   ID int(11) NOT NULL auto_increment,
   ID_TIPOARCHIVO int(11) DEFAULT '0' NOT NULL,
   ID_SECCION int(11) DEFAULT '0' NOT NULL,
   NOMBRE varchar(200),
   URL varchar(250),
   ICONO varchar(128),
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp(14),
   BAJA char(1) DEFAULT 'N',
   DESCRIPCION text,
   GUARDAR_SECCION char(1) DEFAULT 'N' NOT NULL,
   PRIMARY KEY (ID),
   UNIQUE IDX_ARCHIVOS_URL (URL),
   UNIQUE NOMBRE (NOMBRE)
);


# --------------------------------------------------------
#
# Table structure for table 'contenidos'
#

CREATE TABLE contenidos (
   ID int(11) NOT NULL auto_increment,
   ID_TIPOCONTENIDO int(11) DEFAULT '0' NOT NULL,
   ID_SECCION int(11) DEFAULT '0' NOT NULL,
   TITULO varchar(200),
   URL varchar(255),
   COPETE text,
   CUERPO longtext,
   AUTOR varchar(200),
   ICONO varchar(128),
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp(14),
   BAJA char(1) DEFAULT 'N',
   PRINCIPAL char(1) DEFAULT 'N' NOT NULL,
   FECHAALTA datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   FECHABAJA datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   FECHAEVENTO datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   VOTOS int(11) DEFAULT '0' NOT NULL,
   PRIMARY KEY (ID)
);


# --------------------------------------------------------
#
# Table structure for table 'grupos'
#

CREATE TABLE grupos (
   ID int(11) NOT NULL auto_increment,
   GRUPO varchar(25) NOT NULL,
   DESCRIPCION varchar(200),
   PERMISOS_MIEMBROS int(11) DEFAULT '0' NOT NULL,
   PERMISOS_USUARIOS int(11) DEFAULT '0' NOT NULL,
   ICONO varchar(128),
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp(14),
   BAJA char(1) DEFAULT 'N',
   PRIMARY KEY (ID),
   UNIQUE GRUPO (GRUPO)
);


# --------------------------------------------------------
#
# Table structure for table 'grupossecciones'
#

CREATE TABLE grupossecciones (
   ID int(11) NOT NULL auto_increment,
   ID_GRUPO int(11) DEFAULT '0' NOT NULL,
   ID_SECCION int(11) DEFAULT '0' NOT NULL,
   ICONO varchar(128),
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp(14),
   BAJA char(1) DEFAULT 'N',
   PRIMARY KEY (ID),
   UNIQUE IDX_GRUPOSSECCIONES (ID_SECCION, ID_GRUPO)
);


# --------------------------------------------------------
#
# Table structure for table 'gruposusuarios'
#

CREATE TABLE gruposusuarios (
   ID int(11) NOT NULL auto_increment,
   ID_GRUPO int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO int(11) DEFAULT '0' NOT NULL,
   ICONO varchar(128),
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp(14),
   BAJA char(1) DEFAULT 'N',
   PRIMARY KEY (ID),
   UNIQUE IDX_GRUPOSUSUARIOS (ID_USUARIO, ID_GRUPO),
   UNIQUE ICONO (ICONO)
);


# --------------------------------------------------------
#
# Table structure for table 'logusuarios'
#

CREATE TABLE logusuarios (
   ID int(11) NOT NULL auto_increment,
   ID_USUARIO int(11) DEFAULT '0' NOT NULL,
   NICK_USUARIO varchar(25) NOT NULL,
   ID_SECCION int(11),
   ID_CONTENIDO int(11),
   IP varchar(30),
   LOGS int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp(14),
   PRIMARY KEY (ID)
);


# --------------------------------------------------------
#
# Table structure for table 'miniusuarios'
#

CREATE TABLE miniusuarios (
   ID int(11) NOT NULL auto_increment,
   NICK varchar(25) NOT NULL,
   NOMBRE varchar(60),
   MAIL varchar(60),
   ICONO varchar(25),
   ACTUALIZACION timestamp(14),
   BAJA char(1) DEFAULT 'N',
   PRIMARY KEY (ID),
   UNIQUE NICK_2 (NICK),
   UNIQUE NICK (NICK),
   UNIQUE ICONO (ICONO)
);


# --------------------------------------------------------
#
# Table structure for table 'secciones'
#

CREATE TABLE secciones (
   ID int(11) NOT NULL auto_increment,
   ID_SECCION int(11) DEFAULT '0' NOT NULL,
   ID_CONTENIDO int(11) DEFAULT '1' NOT NULL,
   PROFUNDIDAD int(11) DEFAULT '0' NOT NULL,
   NOMBRE varchar(40),
   DESCRIPCION varchar(200),
   ICONO varchar(128),
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp(14),
   BAJA char(1) DEFAULT 'N',
   CATEGORIA char(1) DEFAULT 'N' NOT NULL,
   CARPETA varchar(200),
   PRIMARY KEY (ID)
);


# --------------------------------------------------------
#
# Table structure for table 'tiposarchivos'
#

CREATE TABLE tiposarchivos (
   ID int(11) NOT NULL auto_increment,
   TIPO varchar(100),
   DESCRIPCION varchar(200),
   ICONO varchar(128),
   ID_GRUPO int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp(14),
   BAJA char(1) DEFAULT 'N',
   CARPETA varchar(200),
   PRIMARY KEY (ID),
   UNIQUE CARPETA (CARPETA)
);


# --------------------------------------------------------
#
# Table structure for table 'tiposcontenidos'
#

CREATE TABLE tiposcontenidos (
   ID int(11) NOT NULL auto_increment,
   TIPO varchar(100),
   DESCRIPCION varchar(200),
   ICONO varchar(128),
   ID_GRUPO int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp(14),
   BAJA char(1) DEFAULT 'N',
   PRIMARY KEY (ID)
);


# --------------------------------------------------------
#
# Table structure for table 'usuarios'
#

CREATE TABLE usuarios (
   ID int(11) NOT NULL auto_increment,
   NICK varchar(25) NOT NULL,
   PASSWORD varchar(16) NOT NULL,
   NOMBRE varchar(50),
   APELLIDO varchar(50),
   NIVEL int(11) DEFAULT '0' NOT NULL,
   MAIL varchar(80),
   TELEFONO varchar(30),
   DIRECCION varchar(50),
   PAGINA varchar(200),
   ICQ varchar(15),
   ICONO varchar(128),
   ID_USUARIO_CREADOR int(11) DEFAULT '0' NOT NULL,
   ID_USUARIO_MODIFICADOR int(11) DEFAULT '0' NOT NULL,
   ACTUALIZACION timestamp(14),
   BAJA char(1) DEFAULT 'N',
   PRIMARY KEY (ID),
   UNIQUE NICK (NICK)
);


