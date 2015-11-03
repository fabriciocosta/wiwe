<?
/**
 * 
 * "C" => contenido => content
 * "S" => seccion => section
 * "D" => detalle => detail
 * "U" => usuario => user
 * "R" => relacion => relationship
 * 
 * "TC" => tipocontenido => type content
 * "TS" => tiposeccion => type section
 * "TD" => tipodetalle => type detail
 * "TR" => tiporelacion => type relationship
 * 
 * "RC" => relacion a un contenido (id)
 * "RS" => relacion a una seccuib (id) 
 * "RD" => relacion a un detalle (id)
 * "RR" => relacion a una relacion (id)
 * 
 * "RCx"=> relacion a multiples contenidos (ids)
 * "RSx"=> relacion a multiples secciones (ids) 
 * "RDx"=> relacion a multiples detalles (ids) 
 * "RRx"=> relacion a multiples relaciones (ids)
 *  
 * "RTCx"=> relacion a multiples tipos de contenidos (ids)
 * "RTSx"=> relacion a multiples tipos de secciones (ids) 
 * "RTDx"=> relacion a multiples tipos de detalles (ids) 
 * "RTRx"=> relacion a multiples tipos de relaciones (ids)
 * 
 * "T"=>"texto"
 * "B"=>"blob de texto"
 * "BH"=>"blob de texto html"
 * "E"=>"decimal exponencial"
 * "N"=>"entero"
 * "N"=>"entero"
 *  * */

$namemod = "perfil";

$tiposcontenidos = array( 
					"Usuario"=>array( "tipo"=>"FICHA_USUARIO","descripcion"=>"Ficha de usuario"),
					"Rol"=>array( "tipo"=>"FICHA_ROL","descripcion"=>"Ficha de rol")
					);

$tiposdetalles = array(
					"Usuario"=>array( 
									/* TIPO => tipocampo,  */
									"USUARIO_ROLES"=>array( "descripcion"=>"Roles asignados a este usuario", "tipocampo"=>"RCx", "txtdata"=>"COMBO\nselect * from contenidos where id_tipocontenido=FICHA_ROL\nselect COUNT(*) from contenidos where id_tipocontenido=FICHA_ROL" ),
									"USUARIO_AMIGOS"=>array( "descripcion"=>"Amigos de este usuario.", "tipocampo"=>"RCx", "txtdata"=>"COMBO\nselect * from contenidos where id_tipocontenido=FICHA_USUARIO\nselect COUNT(*) from contenidos where id_tipocontenido=FICHA_USUARIO" )
							 ),
								
					"Rol"=>array(
								"ROL_HEREDADOS"=>array( "descripcion"=>"Roles heredados por este rol","tipocampo"=>"RCx", "txtdata"=>"COMBO\nselect * from contenidos where id_tipocontenido=FICHA_ROL\nselect COUNT(*) from contenidos where id_tipocontenido=FICHA_ROL" ),
								"ROL_SECCIONES"=>array( "descripcion"=>"Secciones asociadas a este rol","tipocampo"=>"RSx", "txtdata"=>"COMBO\nselect * from secciones where id_tiposeccion>2\nselect COUNT(*) from contenidos where id_tiposeccion>2" ),
								"ROL_TIPOSCONTENIDOS"=>array( "descripcion"=>"Tipos de contenidos asociados a este rol","tipocampo"=>"RTS", "txtdata"=>"COMBO\nselect * from tiposcontenidos where  where id>2\nselect COUNT(*) from tiposcontenidos where id>2" )
								
								)
					);

/* tipos de detalles que se pueden customizar... */
$tiposdetalles_custom = array(

					"Usuario"=>array(
						
						)

					);					
					
$contenidos = array();


$tipossecciones = array( 
				
				"Usuarios"=>array( "tipo"=>"SECCION_USUARIOS","descripcion"=>"Sección de fichas de usuarios" ),
				"Roles"=>array( "tipo"=>"SECCION_ROLES","descripcion"=>"Roles de usuarios" )
				
				);

$secciones = array(
				"Usuarios"=>array( 
				"Fichas de usuarios"=>array(
						"id_seccion"=>"1", 
						"id_tiposeccion"=>"SECCION_USUARIOS", 
						"nombre"=>"Usuarios", 
						"descripcion"=>"Usuarios de este sitio", 
						"carpeta"=>"usuarios", 
						"categoria"=>"N", 
						"baja"=>"S" )
						),
				"Roles"=>array( 
					"Fichas de roles"=>array(
							"id_seccion"=>"1",
							"id_tiposeccion"=>"SECCION_ROLES", 
							"nombre"=>"Roles", 
							"descripcion"=>"Roles de los usuarios", 
							"carpeta"=>"roles", 
							"categoria"=>"N", 
							"baja"=>"S"
						)
						)
				);

$relaciones = array();
$tiposrelaciones = array();

$moduloarchivos = array(
				"../modules/perfil/inc/modules/ModuloPerfil.php"=>"../../inc/modules/ModuloPerfil.php",
				"../modules/perfil/inc/css/perfil.css"=>"../../inc/css/perfil.css",
				"../modules/perfil/inc/lang/perfil.csv"=>"../../inc/lang/perfil.csv",
				"../modules/perfil/principal/home/perfil.php"=>"../../principal/home/perfil.php",
				"../modules/perfil/inc/templates/USUARIO.userprofile.html"=>"../../inc/templates/USUARIO.userprofile.html"
				);

?>