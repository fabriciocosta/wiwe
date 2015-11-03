<?
/*									*/
/*									*/
/*			SCRIPTS 				*/
/*									*/
/*									*/

	require "../../inc/include/scripts.php";

?>
<script src="../../inc/js/os3grid.js" type="text/javascript"></script>
<script src="../../inc/js/form_validators.js" type="text/javascript"></script>
  
<script>

function selecttab() {

<?
	global $_accion_;
	global $tabla;
	global $tab;
	global $_nombre_;
	global $_mod_;
	
	$tab = "configuracion";
	
	if (is_object($tabla)) $_nombre_ = $tabla->m_nombre;
	
	if ($_nombre_!="") {
		
		switch($_nombre_) {
			case "contenidos":
			case "tiposcontenidos":
			case "detalles":
			case "tiposdetalles":
				$tab = "fichas";
				break;
				
			case "secciones":
			case "tipossecciones":
				$tab = "secciones";
				break;

			case "usuarios":
			case "grupos":
			case "gruposusuarios":
			case "grupossecciones":
			case "logusuarios":
				$tab = "usuarios";
				break;
				
			default:
				$tab = "configuracion";
				break;			
				
		}
	}
	

	
	if ($_accion_=="newmodule" || $_mod_=="modulos") {
		
		$tab = "modulos";
	} else if ($_mod_=="funciones") {
		$tab = "funciones";	
	} else if ($_accion_=="newtrans") {
		
		$tab = "traducciones";
		
	} else if ($_accion_=="set") {
		
		$tab = "configuracion";
	}

?>
	//alert('stab');
	
	show_module('<? echo $tab ?>');
}

function findPos(obj) {
	var curleft = curtop = 0;
	if (obj.offsetParent) {
		curleft = obj.offsetLeft
		curtop = obj.offsetTop
		//curtop+=obj.offsetHeight;
		while (obj = obj.offsetParent) {
			curleft += obj.offsetLeft
			curtop += obj.offsetTop			
		}
	}
	
	return [curleft,curtop];
}

function conf_refreshseccion( idseccion ) {
	DynamicRequest( 'div_'+idseccion, '../admin/ramasecciones.php', '_idseccion_='+idseccion );
}

function conf_nuevaseccion( idseccion ) {
	var el;
	el = document.getElementById('div_'+idseccion);
	var pos = findPos(el);
	posdiv('div_editar_seccion', pos[0], pos[1] );
	DynamicRequest( 'div_editar_seccion', '../admin/accionseccion.php', '_accion_=new&_idseccion_='+idseccion );
}

function conf_modificarseccion( idseccion ) {
	DynamicRequest( 'div_editar_seccion', '../admin/accionseccion.php', '_accion_=edit&_idseccion_='+idseccion );
}

function conf_borrarseccion( idseccion ) {
	DynamicRequest( 'div_editar_seccion', '../admin/accionseccion.php', '_accion_=delete&_idseccion_='+idseccion );
}

function conf_okseccion( idparent ) {
	//make the call....
	var accion;
	var idseccion;
	var params;
	
	accion = document.form_editar_seccion._accion_.value;
	params = '_accion_='+accion;
	params+= '&_primario_ID='+document.form_editar_seccion._primario_ID.value;
	
	if (accion!="confirmdelete") {
		params+= '&_e_ID_SECCION='+document.form_editar_seccion._e_ID_SECCION.value;
		params+= '&_e_ID_TIPOSECCION='+document.form_editar_seccion._e_ID_TIPOSECCION.value;
		params+= '&_e_NOMBRE='+document.form_editar_seccion._e_NOMBRE.value;
		params+= '&_e_ML_NOMBRE='+document.form_editar_seccion._e_ML_NOMBRE.value;	
		params+= '&_e_DESCRIPCION='+document.form_editar_seccion._e_DESCRIPCION.value;
		params+= '&_e_ML_DESCRIPCION='+document.form_editar_seccion._e_ML_DESCRIPCION.value;
		
		params+= '&_e_ID_USUARIO_CREADOR='+document.form_editar_seccion._e_ID_USUARIO_CREADOR.value;
		params+= '&_e_PROFUNDIDAD='+document.form_editar_seccion._e_PROFUNDIDAD.value;
		params+= '&_e_ORDEN='+document.form_editar_seccion._e_ORDEN.value;
		params+= '&_e_RAMA='+document.form_editar_seccion._e_RAMA.value;
		params+= '&_e_CARPETA='+document.form_editar_seccion._e_CARPETA.value;
		params+= '&_e_BAJA='+document.form_editar_seccion._e_BAJA.value;
	}
	//alert(params);
	
	DynamicRequest( 'div_editar_seccion', '../admin/accionseccion.php', params, 'conf_refreshseccion('+idparent+')' );

}

function conf_cancelseccion() {
	
	hidediv('div_editar_seccion');
}


	var conf_module;

function changeRule(theNumber) {
	var theRules = new Array();
	if (document.styleSheets[0].cssRules) {
		theRules = document.styleSheets[0].cssRules;
	} else if (document.styleSheets[0].rules) {
		theRules = document.styleSheets[0].rules;
	}
	theRules[theNumber].style.backgroundColor = '#FF0000';
}


	var modulos = new Array('configuracion','secciones','fichas','usuarios','templates','traducciones','modulos','funciones');

	 function show_module( module ) {
	 	
	 	conf_module = module;
	 	var divid = 'div_'+module;
	 	
	 	var divmod;
	 	
	 	var divcons = document.getElementById('div_consulta');
	 	//alert(divcons);
	 	if (divcons!=null) {
	 		hidediv( 'div_consulta' );
	 	}
	 	
	 	for( i=0; i<modulos.length; i++) {
	 		 document.getElementById('td_'+modulos[i]+'_left').className = 'conf_menuitem_left';
	 		document.getElementById('td_'+modulos[i]+'_center').className = 'conf_menuitem';
	 		document.getElementById('td_'+modulos[i]+'_right').className = 'conf_menuitem_right';
	 		hidediv( 'div_'+modulos[i] );		
	 	}
	 	
		document.getElementById('td_'+module+'_left').className = 'conf_menuitem_left conf_menuitem_left_sel';
	 	document.getElementById('td_'+module+'_center').className = 'conf_menuitem conf_menuitem_sel';
	 	document.getElementById('td_'+module+'_right').className = 'conf_menuitem_right conf_menuitem_right_sel';
	 	showdiv( divid );
	 
	 }


	function showimg(srcimg) {
		window.event.srcElement.src = srcimg;
		return true;
	}		

	function restoreimg(srcimg) {
		window.event.srcElement.src = srcimg;
		return true;
	}		
	
	function admin() {
		window.location.href = "../admin/admin.php?_nombre_=<?=$tabla->m_nombre?>";
	}

	function filtrarcombos() {
		document.consultar._consulta_.value = 'no';
		document.consultar.submit();
	}

	function filtrarcombose() {
		document.confirmar.action = 'modificar.php';
		document.confirmar._filtrando_.value = 'si';
		document.confirmar.submit();
	}	
	
	function consultar() {
		document.consultar.submit();
	}

	function ordenar() {
		document.consultar.submit();
	}

/*	
	function modificar(id,campos) {		
		//newwindow = window.open('modificar.php?<?=$tabla->primario?>='+id+'&'+campos,'edicion','width=400,height=400');

		while (!newwindow.closed) {
			//wait, espera
			if (newwindow.closed) {window.location.reload(); }
		}
	}

	function borrar(id) {
		newwindow = window.open('modificar.php?_borrar_=si&<?=$tabla->primario?>='+id,'edicion','width=400,height=400');
		
	}	
*/	

	function nuevo() {
		document.consultar._primario_<?=$tabla->primario?>.value = 0;
		document.consultar._borrar_.value = 'no';
		document.consultar._modificar_.value = 'no';
		document.consultar._nuevo_.value = 'si';		
		document.consultar.action = 'modificar.php';
		document.consultar.submit();
	}

	function modificar(id) {		
		document.consultar._primario_<?=$tabla->primario?>.value = id;
		document.consultar._borrar_.value = 'no';
		document.consultar._modificar_.value = 'si';
		document.consultar._nuevo_.value = 'no';	
		document.consultar.action = 'modificar.php';			
		document.consultar.submit();
	}

	function borrar(id) {
		document.consultar._primario_<?=$tabla->primario?>.value = id;
		document.consultar._borrar_.value = 'si';
		document.consultar._modificar_.value = 'no';
		document.consultar._nuevo_.value = 'no';	
		document.consultar.action = 'modificar.php';					
		document.consultar.submit();
	}	
	
	function confirmar() {
		document.confirmar.submit();
	}	

	function cerrar() {
		window.close();
	}
	
	function volver() {
		//window.history.go(-1);	
		consultar();			
	}

	function cancelar() {
		//window.history.go(-1);
		document.confirmar._borrar_.value = 'no';		
		document.confirmar._modificar_.value = 'no';
		document.confirmar._nuevo_.value = 'no';
		document.confirmar._cancelar_.value = 'si';
		document.confirmar.submit();		
	}
	
	function filtroempieza(campo) {
		var che;
		eval("che = document.consultar._empieza_"+campo+".checked");
		if (che==true) {
			eval("document.consultar._tf_"+campo+".value = \'_empieza_"+campo+"\';");
			eval("document.consultar._contiene_"+campo+".checked = false;");
		} else {
			eval("document.consultar._tf_"+campo+".value = \'\';");
		}
		eval("document.consultar._f_"+campo+".focus();");					
	}
	
	function filtrocontiene(campo) {
		var che;
		eval("che = document.consultar._contiene_"+campo+".checked");
		if (che==true) {
			eval("document.consultar._tf_"+campo+".value = \'_contiene_"+campo+"\';");
			eval("document.consultar._empieza_"+campo+".checked = false");
		} else {
			eval("document.consultar._tf_"+campo+".value = \'\';");
		}
		eval("document.consultar._f_"+campo+".focus();");					
	}
	
	function filtroinferior(campo) {
		var che;
		eval("che = document.consultar._inferior_"+campo+".checked");
		if (che==true) {
			eval("document.consultar._tf_"+campo+".value = \'_inferior_"+campo+"\';");
			eval("document.consultar._superior_"+campo+".checked = false;");
		} else {
			eval("document.consultar._tf_"+campo+".value = \'\';");
		}
		eval("document.consultar._f_"+campo+".focus();");					
	}
	
	function filtrosuperior(campo) {
		var che;
		eval("che = document.consultar._superior_"+campo+".checked");
		if (che==true) {
			eval("document.consultar._tf_"+campo+".value = \'_superior_"+campo+"\';");
			eval("document.consultar._inferior_"+campo+".checked = false");
		} else {
			eval("document.consultar._tf_"+campo+".value = \'\';");
		}
		eval("document.consultar._f_"+campo+".focus();");					
	}	


	function modificartipocontenido() {				
		document.confirmar.action = 'modificar.php';			
		document.confirmar.submit();
	}	
</script>

<SCRIPT TYPE="text/javascript">
<!--

function newImage(arg) {
	if (document.images) {
		rslt = new Image();
		rslt.src = arg;
		return rslt;
	}
}

function changeImages() {
	if (document.images && (preloadFlag == true)) {
		for (var i=0; i<changeImages.arguments.length; i+=2) {
			document[changeImages.arguments[i]].src = changeImages.arguments[i+1];
		}
	}
}

var preloadFlag = false;
function preloadImages() {
	if (document.images) {
		INVESTIGACION_over = newImage("images/INVESTIGACION-over.gif");
		INVESTIGACION_down = newImage("images/INVESTIGACION-down.gif");
		MONITOREO_over = newImage("images/MONITOREO-over.gif");
		MONITOREO_down = newImage("images/MONITOREO-down.gif");
		LATNNEXOS_over = newImage("images/LATNNEXOS-over.gif");
		LATNNEXOS_down = newImage("images/LATNNEXOS-down.gif");
		QUIENES_SOMOS_over = newImage("images/QUIENES-SOMOS-over.gif");
		QUIENES_SOMOS_down = newImage("images/QUIENES-SOMOS-down.gif");
		IDIOMA_over = newImage("images/IDIOMA-over.gif");
		IDIOMA_down = newImage("images/IDIOMA-down.gif");
		MAIL_over = newImage("images/MAIL-over.gif");
		MAIL_down = newImage("images/MAIL-down.gif");
		preloadFlag = true;
	}
}

// -->
</SCRIPT>