<?Php
/**
*
*		ModuloPersonas
*
**/
global $_accion_;

global $_tusuarios_;
global $_pornombre_;
global $_categoria_;
global $_porubicacion_;

global $_cID_;
global $tema_id;

$suscripcion = "";
$mensaje = "";
$solicitar_invitacion = "";

Debug("accion:".$_accion_);

if ($_cID_!="") {

	$this->InicializarTemplatesResumenes();
	$this->InicializarTemplatesCompletos();
	
	$Ficha = $this->Contenidos->GetContenidoCompleto($_cID_);

	$visibilidad = false;
	
	if ($Ficha->m_id_tipocontenido==FICHA_USUARIO) {
		
		$strimagen = "PERSONA_IMAGEN";
		$Persona = $Ficha;	
		Debug("es persona:".$Ficha->m_id_tipocontenido);
					
	} else if ($Ficha->m_id_tipocontenido==FICHA_GRUPO) {
		
		$Grupo = $Ficha;
		Debug("Es grupo: ".$Ficha->m_id_tipocontenido);			
				
		$Usuario = $this->Usuarios->GetSesionUsuario();
		
		//echo "Usuario:[".$Usuario->m_nick."]";
		
		$_accion_ = strtolower( $_accion_ );
				
		if ($_accion_=="desuscribirme" && $this->Usuarios->Logged() ) {
			if ($this->SoyMiembro($Grupo->m_id, $Usuario->m_id_contenido)) {
				if ( $this->Relaciones->EliminarRelacionX( GRUPO_PERSONAS, $Grupo->m_id, $Usuario->m_id_contenido ) ) {
					ShowMessage("Ya no eres miembro de este grupo. Suscripción anulada.");
				}
			} else {
				ShowMessage("No eres miembro de este grupo, no puedes desuscribirte");
			}
			
		} else if ( $_accion_ == "suscribirme" && $this->Usuarios->Logged() ) {
			
			$Usuario = $this->Usuarios->GetSesionUsuario();
			Debug("Suscribiendo");			
			if ( $Grupo->m_detalles["GRUPO_SUSCRIPCION"]->m_detalle == "Abierto" ) {

				if ( $this->Relaciones->CrearRelacionUnica( GRUPO_PERSONAS, $Grupo->m_id, $Usuario->m_id_contenido ) ) {
						ShowMessage("Ahora sos miembro de este grupo.");					
				}
				
			} else {
				ShowMessage("Este es un grupo privado, deberás ser invitado por el administrador");
				//$this->Relaciones->CrearRelacionUnica( GRUPO_INVITADOS, $Grupo->m_id, $Usuario->m_id_contenido );
			}
		} else if ( $_accion_ == "suscribirmeinvitado" && $this->Usuarios->Logged() ) {
			
				if ( $this->Relaciones->CrearRelacionUnica( GRUPO_PERSONAS, $Grupo->m_id, $Usuario->m_id_contenido ) ) {
						ShowMessage("Ahora sos miembro de este grupo.");
						global $id_notificacion;
						$this->Contenidos->Eliminar($id_notificacion);					
				}
		} else if ( $_accion_ == "solicitarinvitacion" && $this->Usuarios->Logged() ) {
			
						/*GENERAR NOTIFICACION POR CADA USUARIO*/
							$Usuario = $this->Usuarios->GetSesionUsuario();
						$Admin = $this->Usuarios->GetUsuario($Grupo->m_id_usuario_creador);
						$CNotificacion = $this->Contenidos->NuevoContenido(FICHA_NOTIFICACIONES);
						$CNotificacion->m_titulo = "Recibiste una solicitud de invitación al grupo ".$Grupo->m_titulo;
						$CNotificacion->m_id_contenido = $Admin->m_id_contenido;
						$CNotificacion->m_id_usuario_creador = $Usuario->m_id;
						$CNotificacion->m_id_usuario_modificador = $Usuario->m_id;
						$CNotificacion->m_baja = 'S';
						$CNotificacion->m_id_seccion = 119;
						
						$CNotificacion = $this->Contenidos->CrearContenidoCompleto( FICHA_NOTIFICACIONES, 
								$CNotificacion, 
								true /*que tome los valores que le pasamos para los detalles*/, 
								false /*no ordenamos para no perder tiempo.... lo haremos al final*/ );
						if (is_object( $CNotificacion )) {
							$CNotificacion->m_copete = '
							¿Querés aceptar como miembro del grupo '.$Grupo->m_titulo.' a '.$Usuario->m_nombre.' '.$Usuario->m_apellido.' mail:'.$Usuario->m_mail.' ?
							<br><a class="inputbutton" href="personas/?_accion_=aceptarinvitacion&id_usuario='.$Usuario->m_id.'&_cID_='.$Grupo->m_id.'&id_notificacion='.$CNotificacion->m_id.'">Aceptar solicitud y Enviar invitación</a>
							';
							$this->Contenidos->Actualizar($CNotificacion, false);
							ShowMessage("Notificación enviada");
						}	else {
							ShowMessage("error enviando notificación");
						}			
		
		} else if ( $_accion_ == "aceptarinvitacion" && $this->Usuarios->Logged() ) {
			
						global $id_usuario;
						global $id_notificacion;
						
						$Usuario = $this->Usuarios->GetSesionUsuario();
						$UsuarioInvitado = $this->Usuarios->GetUsuario($id_usuario);
						
						/*GENERAR NOTIFICACION de invitacion*/
						$CNotificacion = $this->Contenidos->NuevoContenido(FICHA_NOTIFICACIONES);
						$CNotificacion->m_titulo = "Recibiste una invitación a suscribirte al grupo ".$Grupo->m_titulo;
						$CNotificacion->m_id_contenido = $UsuarioInvitado->m_id_contenido;
						$CNotificacion->m_id_usuario_creador = $Usuario->m_id;
						$CNotificacion->m_id_usuario_modificador = $Usuario->m_id;
						$CNotificacion->m_baja = 'S';
						$CNotificacion->m_id_seccion = 119;
						
						$CNotificacion = $this->Contenidos->CrearContenidoCompleto( FICHA_NOTIFICACIONES, 
								$CNotificacion, 
								true /*que tome los valores que le pasamos para los detalles*/, 
								false /*no ordenamos para no perder tiempo.... lo haremos al final*/ );
						if (is_object( $CNotificacion )) {
							$CNotificacion->m_copete = '<a class="inputbutton" href="personas/?_accion_=suscribirmeinvitado&_cID_='.$Grupo->m_id.'&id_notificacion='.$CNotificacion->m_id.'">Aceptar Suscripción</a>';
							$this->Contenidos->Actualizar($CNotificacion, false);
							ShowMessage("Notificacion enviada");
							$this->Contenidos->Eliminar($id_notificacion);
						}	else {
							ShowMessage("Error enviando notificación");
						}					
			
			
		} else if($_accion_=="mandartema"  && $this->Usuarios->Logged()) {
			
			$Usuario = $this->Usuarios->GetSesionUsuario();
			
			if ($this->SoyMiembro($Grupo->m_id, $Usuario->m_id_contenido)) {
				
				global $tema, $mensaje;
				///crear un tema:
				$CNuevo = $this->Contenidos->NuevoContenido( FICHA_MENSAJES );
				$CNuevo->m_titulo = $tema;
				$CNuevo->m_copete = $mensaje;
				$CNuevo->m_cuerpo = $mensaje;
				$CNuevo->m_id_contenido = $Grupo->m_id;
				$CNuevo->m_id_seccion = 120; //seccion de convocatorias
				$CNuevo->m_id_usuario_creador = $Usuario->m_id; //system
				$CNuevo->m_id_usuario_modificador = $Usuario->m_id; //system
				$CNuevo->m_baja = 'S';
				
				$CNuevo = $this->Contenidos->CrearContenidoCompleto( FICHA_MENSAJES, 
											$CNuevo, 
											true /*que tome los valores que le pasamos para los detalles*/, 
											false /*no ordenamos para no perder tiempo.... lo haremos al final*/ );
				if (is_object( $CNuevo )) {			
					ShowMessage("Tema enviado");					
				}	else {
					ShowMessage("Error enviando tema");
				}
			} else {
				ShowMessage("Al no ser miembro de este grupo, no podés crear nuevos temas ni comentarlos.");
			}
		} else if($_accion_=="mandarmensaje"  && $this->Usuarios->Logged()) {
			
			$Usuario = $this->Usuarios->GetSesionUsuario();
			
			if ($this->SoyMiembro($Grupo->m_id, $Usuario->m_id_contenido)) {
				
				global $tema, $mensaje;
				///crear un tema:
				$CNuevo = $this->Contenidos->NuevoContenido( FICHA_MENSAJES );
				$CNuevo->m_titulo = $tema;
				$CNuevo->m_copete = $mensaje;
				$CNuevo->m_cuerpo = $mensaje;
				$CNuevo->m_id_contenido = $tema_id;
				$CNuevo->m_id_seccion = 120; //seccion de convocatorias
				$CNuevo->m_id_usuario_creador = $Usuario->m_id; //system
				$CNuevo->m_id_usuario_modificador = $Usuario->m_id; //system
				$CNuevo->m_baja = 'S';
				
				$CNuevo = $this->Contenidos->CrearContenidoCompleto( FICHA_MENSAJES, 
											$CNuevo, 
											true /*que tome los valores que le pasamos para los detalles*/, 
											false /*no ordenamos para no perder tiempo.... lo haremos al final*/ );
				if (is_object( $CNuevo )) {			
					ShowMessage("Mensaje enviado");					
				}	else {
					ShowMessage("Error enviando mensaje");
				}
			} else {
				ShowMessage("Al no ser miembro de este grupo, no podés crear nuevos temas ni comentarlos.");
			}
		}
		
		 
		if ( $Grupo->m_detalles["GRUPO_SUSCRIPCION"]->m_detalle == "Abierto" ) {
			$visibilidad = true;	
		} else if ($this->Usuarios->Logged()) {
			
			if ($this->SoyMiembro($Grupo->m_id, $Usuario->m_id_contenido)) {
				$visibilidad = true;
			} else {
				$visibilidad = false;
				$solicitar_invitacion = '<a href="/personas/?_accion_=solicitarinvitacion&_cID_=*ID*" class="inputbutton">Solicitar invitación</a>';
			}
			
		} else {
				$visibilidad = false;
				$solicitar_invitacion = '<a href="/perfil/register" class="inputbutton">Solicitar invitación</a>';				
			}
		
		
		$strimagen = "GRUPO_IMAGEN";
		
		
		/*FORMULARIO NUEVO MENSAJE*/
		
		//<button class="inputbutton">Suscribirse</button>&nbsp;&nbsp;<button  class="inputbutton">Mensaje</button>
		$suscripcion = "";
		if (is_object($Usuario)) {
			if ( $this->SoyMiembro($Grupo->m_id, $Usuario->m_id_contenido) ) {				
				
				$nuevotema = '<form class="formmensaje" action="" method="post">
				<input name="_cID_" type="hidden" value="*IDCONTENIDO*">
				<input name="_accion_" type="hidden" value="mandartema">
				<input name="abrir" onclick="togglediv(\'mandartema\');" class="inputbutton" type="button" value="Publicar Nuevo Tema">
				<br>
				<div id="mandartema" class="mandarmensaje" style="display: none;">
					<div class="buscar-campo">
						<label></label>
						<input type="text" name="tema" class="temamensaje" value="Tema del mensaje">
					</div>
					<div class="buscar-campo">
						<label></label>
						<textarea name="mensaje" class="cuerpomensaje">Escribí acá el texto de tu mensaje</textarea>
					</div>
					<div>
						<input name="Submit" class="inputbutton" type="submit" value="Enviar">
					</div>
				</div>
				</form><div style="clear:both"></div>			   
	    
			<div class="clear"></div>	';
				
				$nuevomensaje = '<form class="formmensaje" action="" method="post">
				<input name="tema_id" type="hidden" value="*IDCONTENIDO*">
				<input name="_cID_" type="hidden" value="'.$Grupo->m_id.'">
				<input name="_accion_" type="hidden" value="mandarmensaje">
				<input name="abrir" onclick="togglediv(\'mandarmensaje_*IDCONTENIDO*_\');" class="inputbutton" type="button" value="Comentar">
				<br>
				<div id="mandarmensaje_*IDCONTENIDO*_" class="mandarmensaje" style="display: none;">
					<div class="buscar-campo">
						<label></label>
						<input type="hidden" name="tema" class="temamensaje" value="Comentario a: [TEMA]">
					</div>
					<div class="buscar-campo">
						<label></label>
						<textarea name="mensaje" class="cuerpomensaje">Escribí acá el texto de tu mensaje</textarea>
					</div>
					<div>
						<input name="Submit" class="inputbutton" type="submit" value="Enviar">
					</div>
				</div>
				</form><div style="clear:both"></div>			   
	    
			<div class="clear"></div>	';
			}
		}		
		
		/*MOSTRAR MENSAJES*/
		
		if (
				$Grupo->m_detalles["GRUPO_SUSCRIPCION"]->m_detalle == "Abierto"
			|| (
						is_object($Usuario) 
					&& $this->SoyMiembro($Grupo->m_id, $Usuario->m_id_contenido) 
					)
		) {
			
			$mensajes = "";
			$comentarios = "";
			
			$tc = $this->Contenidos->m_tcontenidos;
			
			$tc->LimpiarSQL();
			$tc->FiltrarSQL('ID_TIPOCONTENIDO','',FICHA_MENSAJES);
			$tc->FiltrarSQL('ID_CONTENIDO','',$Grupo->m_id); //o asociado al tema...
			
						
			$tc->OrdenSQL('contenidos.ID DESC');
			$tc->LimiteSQL(0,20);
			$tc->Open();
			if ( $tc->nresultados > 0 ) {
				while($rr = $tc->Fetch()) {
					$CM = new CContenido($rr);
					$this->TiposContenidos->GetCompleto($CM);
					
					$mensajes.= $this->TiposContenidos->TextoCompleto( $CM, '<div class="tema mensaje">
					
					
					<h6><tema>*TITULO* ([CANTIDADCOMENTARIOS](*ID*))</tema></h6>
					<h6><quote>*CUERPO*</quote></h6>
					<h6><date>Publicado por *NOMBRE* el  *ALTA DIA* - *ALTA HORA*</date></h6>
					<br>
					<a class="ver_comentarios comentarios_[CANTIDADCOMENTARIOS](*ID*)" onclick="togglediv(\'comentarios_*IDCONTENIDO*_\');">Ver/Agregar comentarios ([CANTIDADCOMENTARIOS](*ID*))</a>
						<div id="comentarios_*IDCONTENIDO*_" class="comentarios">
							[COMENTARIOS](*ID*)
							'.$nuevomensaje.'
						</div>
					</div>' );
				}
			}
			if ($mensajes!="") {
				$mensajes = ''.$mensajes;
			}
			$mensaje = $mensajes.$nuevotema;
		}
		
		
	} 
	
	$userid = $Ficha->m_id_usuario_creador;
	$Usuario = $this->Usuarios->GetUsuario( $userid );
			
	$tmpl = $this->TiposContenidos->m_templatescompletos[$Ficha->m_id_tipocontenido];
	
	/*PARA GRUPOS*/
	$tmpl = str_replace( array("[MENSAJE]","[SOLICITARINVITACION]"), 
												array($mensaje,$solicitar_invitacion ) ,  $tmpl  );
	
	$tmpl = $this->TiposContenidos->TextoCompleto( $Ficha, $tmpl );
	
	if (is_object( $Usuario ) ) {
		
		$tmpl = $this->Usuarios->TextoCompleto( $Usuario, $tmpl );		
		
	}
	
	echo $tmpl;
	
} else {
	
	
	$rssurl = '/wiwe/principal/home/rss.php?tipo=persona';
	if (trim($_categoria_)!= "" && strlen(trim($_categoria_))>2 ) {
		$rssurl = $rssurl."&_categoria_=".trim($_categoria_);		
	}	
	if (trim($_porubicacion_)!= "" && strlen(trim($_porubicacion_))>2 ) {
		$rssurl = $rssurl."&_ubicacion_=".trim($_porubicacion_);		
	}		
?>

<div class="buscador1">

	<div class="inputseccion">
        
        <label class="por_seccion">Buscar en esta sección</label>
		<form id="personas" name="personas" method="post" action="/personas">
	<input type="text"  size="18" class="text" id="_pornombre_" name="_pornombre_" value="">
        <input class="buscaricon" type="image" src="/wiwe/inc/imgrrcc/Search.png">
      <a class="avanzada" href="javascript:togglediv('buscador');">¿Querés probar una búsqueda avanzada? +</a>
		</form>
	</div>
      
</div>

<div id="buscador" class="buscador" style="display:none">
    
<form id="personas" name="personas" method="post" action="/personas">    

	<div class="buscar-campo">
		
		<div class="campo">
          
		<label>Palabras clave</label>
        <input type="text" class="text" id="_pornombre_"  name="_pornombre_" value="<?=$_pornombre_?>">

		</div>      
		
        <div class="campo">
        
        <label>Areas de la cultura</label>
        
        <input type="text" class="text" id="_categoria_" name="_categoria_"  value="<?=$_categoria_?>"  autocomplete="off">
        <span class="minibutton_clear agregar-categoria" 
        	onclick="javascript:searchterm_add('_categoria_','text','CONVOCATORIA_CATEGORIAS','','','','');">+</span>
        <div id="div_searchterm__categoria_" class="searchterm" style="display:none;">hola</div>
        <div id="div_searchterm__categoria_loader" class="loader"  style="display:none;"><img src="/wiwe/inc/imgrrcc/loader.gif"></div>

		</div>

		<div class="campo">
        
        <label>Ubicación</label>
        <input type="text" class="text" id="_porubicacion_"  name="_porubicacion_" value="<?=$_porubicacion_?>">
      
		</div>

	</div>

    <div class="avanzada_txt">
		Utilizá estos filtros para una búsqueda más específica. Sólo debes completar los campos que quieras.
	      
	<div class="buscar-boton">
          <input class="submit" type="image" src="/wiwe/inc/imgrrcc/Search_advanced.png">
    		<input type="reset" value="Limpiar campos" class="inputbutton_clear">
	</div>

	</div>
    </form>

</div>

<div class="contenidos">

<!-- // RESULTADOS // -->

<div id="personas_grupos" class="resultados">

<!--PERSONAS--> 

		<div class="personas_lista">
        
<h2>Usuarios registrados</h2>

    <?

	
	$tu = $_tusuarios_;
	
	$resultado_busqueda_personalizada = "";
	$res_coma = "";
	
/*==========================================
 * FILTROS POR NOMBRE
 ==========================================*/
	
		$and = "";
    if ($_pornombre_!="") {
      $and ="";
      $special = "/*SPECIAL*/ ";
      $nombres = explode(" ", $_pornombre_);
      if (count($nombres)>=1) {
      	foreach ($nombres as $k=>$nom) {
	      	$special.= $and." ( usuarios.NOMBRE LIKE '%".$nom."%' OR usuarios.APELLIDO LIKE '%".$nom."%') ";
	      	$and = " AND ";
  	    }
      } else $special.= " ( usuarios.NOMBRE LIKE '%".$_pornombre_."%' OR usuarios.APELLIDO LIKE '%".$_pornombre_."%' ) ";
      
			$resultado_busqueda_personalizada.= $res_coma."por nombre: ".$_pornombre_;
			$res_coma = ", ";
      
      
    } else $special = "";
    
    
/*==========================================
	 * FILTROS POR CATEGORIAS
	 ==========================================*/
	if ( trim($_categoria_!="") && strlen($_categoria_)>3 ) {
			$_categorias_ = explode(",",$_categoria_);
			$or = "";
			$catsin = " (";
			if (count($_categorias_)>1)
				foreach($_categorias_ as $cat) {
					$catsin.= $or." CATEGORIAS.NOMBRE LIKE '%".trim($cat)."%' ";
					$or = " OR ";
				}
			else $catsin.= " CATEGORIAS.NOMBRE LIKE '%".trim($_categoria_)."%' ";;
			$catsin.= ") ";
			//echo $catsin; 
			
			$tu->AgregarReferencias(
				array('CATEGORIAS.NOMBRE'),
				array('contenidos FICHAUSUARIO','secciones CATEGORIAS','relaciones RELCAT'),
				array(
							'FICHAUSUARIO.ID_TIPOCONTENIDO='.FICHA_USUARIO,			
							'usuarios.ID_CONTENIDO=FICHAUSUARIO.ID',
							'CATEGORIAS.ID=RELCAT.ID_SECCION_REL',
							'RELCAT.ID_CONTENIDO=FICHAUSUARIO.ID',
							'RELCAT.ID_TIPORELACION='.PERSONA_CATEGORIAS,
							$catsin
							)
			);
			
			$resultado_busqueda_personalizada.= $res_coma."por áreas de la cultura: ".$_categoria_;
			$res_coma = ", ";
	}    

	
/*==========================================
	 * FILTROS POR UBICACION
	 ==========================================*/
      
      if ($_porubicacion_!="") {
      	if ($special=="")  { 
      		$special = "/*SPECIAL*/ ";
      		$and = "";
      	} else $and = " AND ";
      	
      	$special.= $and." ( usuarios.PAIS LIKE '%".str_replace( " ", "%", stripslashes($_porubicacion_))."%' 
      	OR usuarios.CIUDAD LIKE '%".str_replace( " ", "%", stripslashes($_porubicacion_))."%'
      	) "; 
				
				$resultado_busqueda_personalizada.= $res_coma."por ubicación: ".$_porubicacion_;
      	$res_coma = ", ";    	
      	
      }	
	
/*==========================================
 * FILTROS POR NIVEL
 ==========================================*/    
	
	$tu->LimpiarSQL();	
	$tu->FiltrarSQL('NIVEL',$special,4);
	$tu->OrdenSQL( 'usuarios.ACTUALIZACION DESC');
	
	if ($resultado_busqueda_personalizada!="") {
		$resultado_busqueda_personalizada = '<div class="busqueda_realizada">
			Búsqueda realizada <strong>'.$resultado_busqueda_personalizada.'</strong></div>';
	}	
	
	if ($resultado_busqueda_personalizada!="") {
		echo $tu->Navegacion( 			'
				
					<h2>Resultado de la búsqueda ([COUNT])
						
							<a title="Seguí las actualizaciones de esta búsqueda por RSS" href="'.$rssurl.'">
							<img align="right" class="rss_personas" src="/wiwe/inc/imgrrcc/rss.png" border="0" />
							</a>
						
					</h2>
				
				'
				.$resultado_busqueda_personalizada.'
	      ', array("nxpage_selector"=>false) );	
	} else {
		echo $tu->Navegacion('', array("nxpage_selector"=>false) );
	}

	$tu->Open();
	//echo $tu->SQL;
	
	if ( $tu->nresultados > 0 ) {
		$cc = 0;
		while( $ru = $tu->Fetch() ) {
			
			$cc++;
			
			$Usuario = new CUsuario($ru);
			
			$FichaUsuario = $this->Contenidos->GetContenidoCompleto( $Usuario->m_id_contenido );
			
			/*if ($cc>3) {
				echo '';
				$cc = 0;
			}
			*/

			/*$ubicacion = $coma = "";
			
			if ($Usuario->m_ciudad!="") { $ubicacion.= $coma.$Usuario->m_ciudad ; $coma = ", "; }
			if ($Usuario->m_provincia!="") { $ubicacion.= $coma.$Usuario->m_provincia ; $coma = ", "; }
			if ($Usuario->m_pais!="") { $ubicacion.= $coma.$Usuario->m_pais ; $coma = ", "; }
			
			
			echo $this->TiposContenidos->TextoColapsado( $Ficha );
			*/
			$nombre_str = $Usuario->m_nombre;
			
			//mostrar apellido?
					

			if (is_object($FichaUsuario)) {
				/*PUBLICAR O NO EL APELLIDO*/
				if ($FichaUsuario->m_detalles["PERSONA_PUBLICAR_APELLIDO"]->m_detalle=="[YES]") {
					$nombre_str.= " ".$Usuario->m_apellido." actualizado el ".Fecha($Usuario->m_actualizacion);				
				}					
				
				/*LA IMAGEN*/
				$imagen = $FichaUsuario->m_detalles["PERSONA_IMAGEN"]->m_detalle;
			
				if (trim($imagen)!="") {
					//
				} else {
					$imagen = "/wiwe/inc/imgrrcc/usuarios_mostrar.jpg";
				}
			}			
			
			echo '<div id="persona" class="avatar">
					<a href="/personas/'.$FichaUsuario->m_id.'">
				 			<img class="avatar" height="60" width="60"  title="'.$nombre_str.'" 
				 			src="'.$imagen.'">
					</a>
				</div>';			
			
			
		}
		
		echo $tu->Navegacion( '
							<div id="navegacion" class="navegacion">[START] a [END] de [COUNT] <br/> [PAGES]</div>', 
							array( "nxpage_selector"=>false, 'nview_pages'=>3 ) );
		
	}
    
			
    
    ?>

</div>

<!--GRUPOS--> 

		<div class="grupos">
	<?
global $_tcontenidos_;
	
	$tc = $_tcontenidos_;
	
	global $Usuario;
	
	$resultado_busqueda_personalizada = "";
	$res_coma = "";
	
/*==========================================
	 * FILTROS POR CATEGORIAS
	 ==========================================*/
	if ( trim($_categoria_!="") && strlen($_categoria_)>3 ) {
			$_categorias_ = explode(",",$_categoria_);
			$or = "";
			$catsin = " (";
			if (count($_categorias_)>1)
				foreach($_categorias_ as $cat) {
					$catsin.= $or." CATEGORIAS.NOMBRE LIKE '%".trim($cat)."%' ";
					$or = " OR ";
				}
			else $catsin.= " CATEGORIAS.NOMBRE LIKE '%".trim($_categoria_)."%' ";;
			$catsin.= ") ";
			//echo $catsin; 
			
			$tc->AgregarReferencias(
				array('CATEGORIAS.NOMBRE'),
				array('contenidos FICHAUSUARIO','secciones CATEGORIAS','relaciones RELCAT'),
				array(
							'FICHAUSUARIO.ID_TIPOCONTENIDO='.FICHA_GRUPO,			
							'usuarios.ID_CONTENIDO=FICHAUSUARIO.ID',
							'CATEGORIAS.ID=RELCAT.ID_SECCION_REL',
							'RELCAT.ID_CONTENIDO=FICHAUSUARIO.ID',
							'RELCAT.ID_TIPORELACION='.GRUPO_CATEGORIAS,
							$catsin
							)
			);
			
			$resultado_busqueda_personalizada.= $res_coma."por áreas de la cultura: ".$_categoria_;
			$res_coma = ", ";
	} 	
	
	$tc->LimpiarSQL();
    
/*==========================================
 * FILTROS POR NOMBRE
 ==========================================*/
		if ($_pornombre_!="") {
			
      $special = "/*SPECIAL*/ ( contenidos.TITULO LIKE '%".$_pornombre_."%' ";
      $nombres = explode(" ", $_pornombre_);
      if (count($nombres)>1)
      foreach ($nombres as $k=>$nom) {
      	$special.= " OR contenidos.TITULO LIKE '%".$nom."%'";
      }
      $special.= " )";
      
			$resultado_busqueda_personalizada.= $res_coma."por nombre: ".$_pornombre_;
			$res_coma = ", ";      
      
    } else $special = "";	
    
	$tc->FiltrarSQL( 'ID_TIPOCONTENIDO', $special, FICHA_GRUPO );
	$tc->FiltrarSQL( 'BAJA', '', 'S' );
	
	

	
	if ($resultado_busqueda_personalizada!="") {
		$resultado_busqueda_personalizada = '<div class="busqueda_realizada">
			Búsqueda realizada <strong>'.$resultado_busqueda_personalizada.'</strong></div>';
	}	
	
	
	if ($resultado_busqueda_personalizada=="") echo '<h2>Grupos destacados</h2>';
	else echo '<h2>Grupos</h2>';
/*
	else echo '<div id="grupos"> <h2>Resultado de la búsqueda ('.$tc->nresultados.')</h2>
	'.$resultado_busqueda_personalizada.'
	
	</div>';
*/	
	
	if ($resultado_busqueda_personalizada!="") {
		echo $tu->Navegacion( 			'
				
					<h2>Resultado de la búsqueda ([COUNT])
						
							<a title="Seguí las actualizaciones de esta búsqueda por RSS" href="'.$rssurl.'">
							<img align="right" class="rss_personas" src="/wiwe/inc/imgrrcc/rss.png" border="0" />
							</a>
						
					</h2>
				
				'
				.$resultado_busqueda_personalizada.'
	      ', array("nxpage_selector"=>false) );	
	} else {
		echo $tu->Navegacion('', array("nxpage_selector"=>false) );
	}
	
	//$tc->OrdenSQL( 'contenidos.ID DESC');	
	
			//ultimos actualizados
			
	$tc->OrdenSQL( 'contenidos.ACTUALIZACION DESC');	
	$tc->Open();			
			
	if ( $tc->nresultados > 0 ) {
		$cc = 0;
		while( $rc = $tc->Fetch() ) {
			
			$cc++;
			
			$Grupo = new CContenido( $rc );
			
			if ($cc>3) {
				echo '<div style="clear:both;"></div>';
				$cc = 0;
			}

			$this->TiposContenidos->GetCompleto($Grupo);
			$imagen = $Grupo->m_detalles["GRUPO_IMAGEN"]->m_detalle;
			if (trim($imagen)!="") {
				//
			} else {
				$imagen = "/wiwe/inc/imgrrcc/grupos_mostrar.jpg";
			}

			
			echo $this->TiposContenidos->TextoCompleto( $Grupo, 
			
			'
			<div id="grupo" class="entrada">
				<div class= "titulo">
					<a href= "/grupos/*ID*" title="*COPETE:TITLE*">*TITULO* ([CANTIDAD_MIEMBROS](*ID*))</a>
				</div>
			<div  class="copete">
				<a href="/grupos/*ID*"><img align="left" src="'.$imagen.'" border="0"></a>
				*COPETE:SUB*
			</div>
			<div>Suscripción: *#GRUPO_SUSCRIPCION#*
				</div>
			</div>
			'
			
			);
			
		}
		$tc->QuitarReferencias();
		echo $tc->Navegacion( '<div class="hidden">[PAGENAVIGATION]</div><div id="navegacion" class="navegacion">[START] a [END] de [COUNT] <br/> [PAGES]</div>', 
		array( "nxpage_selector"=>false , 'nview_pages'=>3 ) );
	}
    
			
    
    ?>
</div>
</div>
</div>
<?} ?>

