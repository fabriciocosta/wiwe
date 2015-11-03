#
# Dumping data for table 'tiposarchivos'
#

INSERT INTO tiposarchivos VALUES ( '1', 'Binario', 'Binario: .exe/.zip/.tar/.gz/.tgz/.sit/.bz2', '', '0', '1', '1', '20030220012740', 'N', 'binario');
INSERT INTO tiposarchivos VALUES ( '2', 'Documentación', 'Docs: .rtf/.pdf/.txt/.doc', '', '0', '1', '1', '20030220012740', 'N', 'documentacion');
INSERT INTO tiposarchivos VALUES ( '3', 'Imagen', 'Imágenes: .jpeg/.png/.tga/.gif/.bmp/.pcx', '', '0', '1', '1', '20030220012740', 'N', 'imagen');
INSERT INTO tiposarchivos VALUES ( '4', 'Animación', 'Animaciones: .mng/.gif', '', '0', '1', '1', '20030220012740', 'N', 'animacion');
INSERT INTO tiposarchivos VALUES ( '5', 'Película', 'Películas: .mov/.mpeg/.mpg/.mov/.avi', '', '0', '1', '1', '20030220012740', 'N', 'pelicula');
INSERT INTO tiposarchivos VALUES ( '6', 'Sonido', 'Sonidos: .wav/.mod/.mp3/.mid', '', '0', '1', '1', '20030220012740', 'N', 'sonido');
INSERT INTO tiposarchivos VALUES ( '7', 'Stream', 'Streams: .asf/.ram/.rpm/.wvs', '', '0', '1', '1', '20030220012740', 'N', 'stream');



#
# Dumping data for table 'tiposcontenidos'
#

INSERT INTO tiposcontenidos VALUES ( '1', 'Link', 'Hipervínculo comentado. Dirección de página web.', NULL, '0', '1', '1', '20030220012617', 'N');
INSERT INTO tiposcontenidos VALUES ( '2', 'Tutorial', 'Ayuda. Cursillo.', NULL, '0', '1', '1', '20030220012617', 'N');
INSERT INTO tiposcontenidos VALUES ( '3', 'Referencia', 'Listado de funciones comentadas sobre librerias, lenguajes, métodos. Contiene autoreferencias para mejor navigabilidad.', NULL, '0', '1', '1', '20030220012617', 'N');
INSERT INTO tiposcontenidos VALUES ( '4', 'Fuente', 'Código fuente coloreado y comentado.', NULL, '0', '1', '1', '20030220012617', 'N');
INSERT INTO tiposcontenidos VALUES ( '5', 'Nota', 'Noticia, entrevista, reseña con imágenes y texto.', NULL, '0', '1', '1', '20030220012617', 'N');

INSERT INTO tiposcontenidos VALUES ( '6', 'Programa', 'Programa, Plan de estudio, Estrategia, etc', NULL, '0', '1', '1', '20030220012617', 'N');
INSERT INTO tiposcontenidos VALUES ( '7', 'Curso-Taller Pago', 'Cursos y talleres pagos', NULL, '0', '1', '1', '20030220012617', 'N');
INSERT INTO tiposcontenidos VALUES ( '8', 'Curso-Taller No Pago', 'Cursos y talleres no pagos', NULL, '0', '1', '1', '20030220012617', 'N');
INSERT INTO tiposcontenidos VALUES ( '9', 'Evento', 'Evento.', NULL, '0', '1', '1', '20030220012617', 'N');
INSERT INTO tiposcontenidos VALUES ( '10', 'Evento-Efemeride', 'Efemeride, evento archivado.', NULL, '0', '1', '1', '20030220012617', 'N');
INSERT INTO tiposcontenidos VALUES ( '11', 'Compra-venta, compra', 'Compra', NULL, '0', '1', '1', '20030220012617', 'N');
INSERT INTO tiposcontenidos VALUES ( '12', 'Compra-venta, venta', 'Venta', NULL, '0', '1', '1', '20030220012617', 'N');
INSERT INTO tiposcontenidos VALUES ( '13', 'Compra-venta, trueque', 'Trueque', NULL, '0', '1', '1', '20030220012617', 'N');

#
# Dumping data for table 'usuarios'
#

INSERT INTO usuarios VALUES ( '1', 'cg_admin', '5a71c228260b641e', 'Bryphe', 'Costa', '1', 'fabri@fibertel.com.ar', '1550258151', '5a71c228260b641e', '', '106211340', NULL, '0', '0', '20030220035200', 'N');


#
# Dumping data for table 'grupos'
#

INSERT INTO grupos VALUES ( '1', 'grupo_cg_admin', 'Grupo de acceso personalizado: cg_admin', '3', '0', NULL, '1', '1', '20030220041801', 'N');

#
# Dumping data for table 'gruposusuarios'
#

INSERT INTO gruposusuarios VALUES ( '5', '1', '1', NULL, '0', '0', '20030225161252', 'N');


#
# Dumping data for table 'grupossecciones'
#

INSERT INTO grupossecciones VALUES ( '1', '1', '1', NULL, '1', '1', '20030216205955', 'N');
INSERT INTO grupossecciones VALUES ( '2', '1', '2', NULL, '1', '1', '20030216205955', 'N');
INSERT INTO grupossecciones VALUES ( '3', '1', '3', NULL, '1', '1', '20030216205955', 'N');


