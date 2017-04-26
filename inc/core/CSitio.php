<?
/**
 * class CSitio
 * version 4.5  23/05/2007 se simplifico y mejoro InicializarTemplates.... y se agrego InicializarTemplatesDetalles();
 * version 4.4 se agrego ProcessNewsletter()
 	@version 4.3 se cambio ContinuarSesionCompras para q no entre en conflicto con el administrador
 	@version 4.2 se cambio ContinuarSesion
 	@version 4.1
 * @copyright 2003 
 * ultimo cambio: 06/09/2006
 **/
 
 
class CSitio {

	var $Secciones;
	var $Contenidos;
	var $Archivos;
	var $Detalles;
	var $TiposSecciones;
	var $TiposContenidos;
	var $TiposArchivos;
	var $TiposDetalles;
	var $Logs;
	var $Relaciones,$TiposRelaciones;
	
	var $Usuarios;
	
	var $Debug;

	var $m_Contenido_Actual;
	
	function CSitio(&$__tsecciones__,
					&$__ttipossecciones__,
					&$__tcontenidos__,
					&$__ttiposcontenidos__,
					&$__tarchivos__,
					&$__ttiposarchivos__,
					&$__tdetalles__,
					&$__ttiposdetalles__,
					&$__tlogs__, 
					$__tusuarios__="",
					$__trelaciones__="",
					$__ttiposrelaciones__="") {
		

		$this->TiposDetalles = new CTiposDetalles($__ttiposdetalles__);
		
		$this->Detalles = new CDetalles($__tdetalles__,$this->TiposDetalles);	
		
		if ($__ttiposrelaciones__=="") $__ttiposrelaciones__ = $GLOBALS['_ttiposrelaciones_'];
		$this->TiposRelaciones = new CTiposRelaciones( $__ttiposrelaciones__, $this->TiposDetalles );
						
		if ($__trelaciones__=="") $__trelaciones__ = $GLOBALS['_trelaciones_'];
		$this->Relaciones = new CRelaciones( $__trelaciones__, $this->TiposRelaciones );		

		$this->TiposSecciones = new CTiposSecciones($__ttipossecciones__);

		
		$this->Secciones = new CSecciones($__tsecciones__,$this->TiposSecciones);
		$this->Secciones->m_CRelaciones = $this->Relaciones;		

		
		//DEFINICION DE TIPOS DE CONTENIDOS
		$this->TiposContenidos = new CTiposContenidos($__ttiposcontenidos__,$this->Detalles);
		
		$this->Contenidos = new CContenidos($__tcontenidos__,$this->TiposContenidos,$this->Relaciones,$this->Usuarios);
		$this->Contenidos->m_CRelaciones = $this->Relaciones;

		$this->TiposArchivos = new CTiposArchivos($__ttiposarchivos__);
		
		$this->Archivos = new CArchivos($__tarchivos__,$this->TiposArchivos);		

		$this->Logs = new CLogs( $__tlogs__, $this->Contenidos);
		
		if ($__tusuarios__==null) $__tusuarios__ = &$GLOBALS['_tusuarios_'];
		$this->Usuarios = new CUsuarios( $__tusuarios__, $this->Secciones, $this->Contenidos, $this->Relaciones );
		$this->Contenidos->m_CUsuarios = $this->Usuarios;
		
	} 
	
	function Inicializar() {
		
		global $_accion_;
		global $_contenido_;
		global $_seccion_;
		global $_idfichabase_;
		global $__modulo__;
		
		global $__lang__;
		global $CMultiLang;
		
		global $_csistema_;
		global $_TITLE_;
		global $_KEYWORDS_;
		global $_DESCRIPTION_;
		global $_DOCTYPE_;
		
		$this->GetVariablesSistema();
		
		if ( $_csistema_->m_baja=="N" ) {
		
			$apelar = false;
			
			if (isset($_csistema_->m_detalles["SISTEMA_IPS"])) {
				if ( trim($_csistema_->m_detalles["SISTEMA_IPS"]->m_txtdata)!="" ) {
					$myip = getenv("REMOTE_ADDR");
					$ipss = trim($_csistema_->m_detalles["SISTEMA_IPS"]->m_txtdata);
					
					$res = strpos( $ipss, $myip );
					if (is_numeric($res) && $res>=0) {
						$apelar = true;
					}
					
				}
			}
							
			if (!$apelar)
				header( 'Location: /mantenimiento' ) ;
			
		}
		//Session variables settting
		if ($GLOBALS['_SITIO_SESION']=="auto") {
			$this->ContinuarSesionCompras();
		} else {
			$this->ContinuarSesion();
		}
		//once chosen the language, activate it
		$CMultiLang->SelectLang( $__lang__ );

		//SETTING TITLE
		$_TITLE_ = $_csistema_->Titulo();
		$_KEYWORDS_ = $_csistema_->Cuerpo().$_csistema_->PalabrasClave();
		$_DESCRIPTION_ = $_csistema_->Copete();

		$this->BuscarTitulo();
		$this->InicializarTitulos();		
	
		
	  	$this->InicializarTemplatesColapsados();
		if ($_accion_=='completo') $this->InicializarTemplatesCompletos();   
		if ($_accion_=='resumen') $this->InicializarTemplatesResumenes();	
		
		//forzamos la asignacion de la ficha base
		if ( ($__modulo__=="home") && ($_contenido_!='') ) 
			$_idfichabase_ = $_contenido_;
		
		//tomamos el id de seccion segun el contenido
		if ( ($_contenido_!='') && ($_seccion_=='') ) {
			$cc = $this->Contenidos->GetContenido($_contenido_);
			$_seccion_ = $cc->m_id_seccion;
		}
		
		echo $_DOCTYPE_;
		
		$this->Logs->LogCounter();
	}
	
	function BuscarTitulo() {
		global $__modulo__;
		global $_TITLE_;
		
		if ($__modulo__!="home") {
			$Seccion = $this->Secciones->GetSeccionByName( $__modulo__ );
			if (is_object($Seccion)) {
				$_TITLE_.= " - ".$Seccion->Nombre();
			}
		}
				
		global $_cID_;
		global $_titulo_contenido_;

		if (is_numeric($_cID_)) {
			//OK!
		} else
		if ($_titulo_contenido_!="") {
			$_titulo_contenido_ = str_replace(array("_","/","'",'"'),array(" "," "," "," "),$_titulo_contenido_);
			//$_titulo_contenido_ = "".str_replace(array(" "),array("%"),$_titulo_contenido_)."%";
				
			if (is_object($Seccion)) {
				$CC = $this->Contenidos->GetContenidoPorTitulo($_titulo_contenido_,'',$Seccion->m_id);
				if (!is_object($CC)) {
					$CC = $this->Contenidos->GetContenidoPorTitulo($_titulo_contenido_);
				}
			} else {
				$CC = $this->Contenidos->GetContenidoPorTitulo($_titulo_contenido_);
			}
				
			if (is_object($CC)) {
				$_cID_ = $CC->m_id;
			} else Debug("not found:".$this->Contenidos->m_tcontenidos->SQL);
				
		
		}
	}

	function InicializarTitulos() {
		global $_cID_;
		global $_TITLE_;
		global $_DESCRIPTION_;
		global $_KEYWORDS_;
		global $_csistema_;
		global $CLang;
		$of = "{OF}";
		
		if (is_numeric( $_cID_) ) {
				
			$Contenido = $this->Contenidos->GetContenido($_cID_);
				
			if (is_object($Contenido)) {
				$UsuarioX = $this->Usuarios->GetUsuario($Contenido->m_id_usuario_creador);
				$_TITLE_ = $Contenido->Titulo()." - ".$_csistema_->Titulo();
				/*." - ".$_csistema_->Titulo()*/
		
				$_DESCRIPTION_ = $Contenido->CopeteStrip();
				$_KEYWORDS_ = $Contenido->PalabrasClave();
			}
		}
				
	}
	
	
	
	function IniciarSesion() {
		Debug("CSitio::IniciarSesion");
		$_SESSION['userip'] = $_SERVER['REMOTE_ADDR'];				
		$_SESSION['encrypt'] = crypt( $_SESSION['userip'],"dmb");
		$this->Counter();			
	}
	
	//-----------------------------------------------------
	
	function ContinuarSesion() {
		Debug("CSitio::ContinuarSesion");
		session_start();		
		
		if ( ($_SESSION['userip']==$_SERVER['REMOTE_ADDR']) || ( $_SESSION['encrypt'] != "" )) {//la sesion existe
			$this->Usuarios->SesionContinue();
		} else {
			$this->IniciarSesion();		
		}
	}
	
	function FinalizarSesion() {
		$_SESSION = array();
		session_destroy();
	}
	
	function IniciarSesionCompras() {
		global $_products_;
		global $__lang__;
		global $setlang;
		global $setlang_lang;				
		
		unset($_SESSION["__lang__"]);
		$_SESSION['userip'] = $_SERVER['REMOTE_ADDR'];				
		$_SESSION['products'] = $_products_;								
		$_SESSION['encrypt'] = crypt( $_SESSION['userip'],"dmb");
		if ($setlang=="1") {
			$_SESSION["userlang"] = $setlang_lang;
			$_SESSION["langset"] = $__lang__." set in IniciarSesionCompras";
		} else {
			if (isset($_SESSION["userlang"])) $__lang__ = $_SESSION["userlang"];
			$_SESSION["langset"] = $__lang__." NOT SET in IniciarSesionCompras";
		}
		$this->Counter();
	}
	
	function ContinuarSesionCompras() {
		global $_products_;
		global $__lang__;
		global $setlang;
		global $setlang_lang;
		
		if ($setlang==1) $setlang_lang = $__lang__;
		session_start();
		unset($_SESSION["__lang__"]);		
		
		if ( (isset($_SESSION['userip']) && $_SESSION['userip']==$_SERVER['REMOTE_ADDR']) || ( isset($_SESSION['encrypt']) && $_SESSION['encrypt'] != "" )) {//la sesion existe
			if ($_products_=="" && isset($_SESSION['products'])) {//debemos recuperar nuestros articulos
				$_products_ = $_SESSION['products'];	
			} else {//no necesitamos recuperarlo , solo lo actualizamos
				$_SESSION['products'] = $_products_;
			}
			if ($setlang=="1") {
				$_SESSION["userlang"] = $setlang_lang;
				$_SESSION["langset"] = $setlang_lang." set in ContinuarSesionCompras";
			} else {
				if (isset($_SESSION["userlang"])) $__lang__ = $_SESSION["userlang"];
				$_SESSION["langset"] = " NOT SET in ContinuarSesionCompras";
			}
			
			//si esta logueado...
			$this->Usuarios->SesionContinue();
			
		} else $this->IniciarSesionCompras();
		
	}	
	
	function FinalizarSesionCompras() {
		$_SESSION = array();
		session_destroy();
	}	
	
	function ProcessProduct() {
		
		global $_products_;
		global $orderproducts;	
		global $nproducts;
		global $_accion_;
		global $_cant_;
		global $_cID_;
		
		$orderproducts = array();
		$nproducts = 0;
		
		
		if (isset($_products_)) {			
		
			if ($_products_!="") {
				$xproducts = explode("|",$_products_);		
				foreach($xproducts as $product) {
					$pr = explode(":",$product);
					if(is_numeric($pr[0])) {
						$id = $pr[0];
					} else {
						$expr = explode("__",$pr[0]);
						$id = $expr[0];
						$opciones = $expr[1];
					}					
					$np = $pr[1];
					if ($_accion_=="removeproduct" && $_cID_==$id) {
						//remove, do nothing
					} else if(isset($orderproducts[$id])) {//si esta repetido , lo agrega..
						if(isset($np)) {
							$orderproducts[$id]['cantidad']+= $np;							
						}
					} else {
						$orderproducts[$id] = array('cantidad'=>$np,'opciones'=>$opciones);
					}
				}
				//reordenamos los id|cant reagrupandolos por id's,
				//generando el nuevo string de _products_
				$_products_ = "";
				$sep="";
				$nproducts = 0;		
				foreach($orderproducts as $id=>$arr) {			
					if ($_accion_=="changeproduct" && $_cID_==$id) {	
						//modificar
						$orderproducts[$id]['cantidad'] = $_cant_;
						$_products_.= $sep.$id."__".$arr['opciones'].":".$_cant_;
						$sep = "|";
						$nproducts+=$_cant_;
					} else {
						$_products_.= $sep.$id."__".$arr['opciones'].":".$arr['cantidad'];
						$sep = "|";
						$nproducts+=$arr['cantidad'];
					}			
				}
				$_SESSION['products'] = $_products_;				
			}
		} else {			
			$_products_ = "";
			$_SESSION['products'] = "";
		}		
	
	}	
	
	function ProcessNewsletter( $newslettermail, $maill="", $msgfrom="Newsletter", $from="",$message="", $merci="", $msgerror="" ) {
		
		global $_tusuarios_;
		
		if ($newslettermail!="") {
			$_tusuarios_->LimpiarSQL();
			$res = $_tusuarios_->InsertarRegistro( array('NOMBRE'=>$newslettermail,
			'APELLIDO'=>$newslettermail,
			'NICK'=>$newslettermail,
			'NIVEL'=>4,
			'PASSWORD'=>'123',
			'_p_PASSWORD_confirm'=>'123', 
			'MAIL'=>$newslettermail,
			'DIRECCION'=>'',
			'PISO'=>'',
			'TELEFONO'=>'',
			'INTERNO'=>'',
			'EMPRESA'=>'',
			'OFICINA'=>'',
			'PAGINA'=>'',
			'PAIS'=>'',
			'CIUDAD'=>'',
			'PASSMD5'=>md5("123"),
			'PASSKEY'=>$this->Crypt("123") ) );
			
			//si ingreso bien los datos:						
			//$maill = 'fcosta@computaciongrafica.com';
			if ($res==true) {
				$mssg =  $newslettermail." ".$message;				
				mail( $maill, $msgfrom,$mssg,'FROM: '.$from);
				return $merci;
			}	else {
				$error['errores']++;
				//$error = $_tusuarios_->exito;
				$error = "";
				echo '<span class="modulo_titulo">';				
				return $msgerror.$error;
				echo '</span>';
			}
		}		
		
	}
			
	function Counter() {
		global $CMultiLang;
		global $__lang__;
		
		$CMultiLang->SelectLang( $__lang__ );
		/*
		$this->Logs->LimpiarSQL();
		$this->Logs->FiltrarSQL('ACCION','','counter');
		$this->Logs->Open();
		if ($this->Logs->nresultados>0) {
			$_row_ = $this->Logs->Fetch( $this->Logs->resultados );			
			$this->Logs->LimpiarSQL();
			$this->Logs->ModificarRegistro( $_row_['logs.ID'], 
								  array('ID_CONTENIDO'=> 0,
										'ID_CONTENIDOAUX'=> 0,
										'ID_USUARIO'=> 0,
										'LOGCODE'=> ($_row_['logs.LOGCODE'] + 1),
										'ACCION'=> 'counter',
										'VALOR'=> $_SERVER["REQUEST_URI"] ,
										'ACTUALIZACION' => 'NOW()') );			
		} else {
			$this->Logs->InsertarRegistro(
								  array('ID_CONTENIDO'=> 0,
										'ID_CONTENIDOAUX'=> 0,
										'ID_USUARIO'=> 0,
										'LOGCODE'=> 1,
										'ACCION'=> 'counter',
										'VALOR'=> $_SERVER["REQUEST_URI"] ) );			
		}
		*/
		
		//Si desde el dia anterior este IP no se registro
		$this->Logs->LogCounter();
		
	}
	
	//se imprime secciones y contenidos colapsados del tipo de seccion
	//separando por tipos de contenidos
	function MostrarContenidosColapsadosPorTipoSeccion($__tiposeccion__) {
		
	}	
	
	//empezando por la seccion indicada se imprime secciones y contenidos
	//del subarbol	
	function MostrarContenidosColapsadosPorRama($__seccion__) {
		
	}

	
	
	function GetRamaIdsContenidos( $__raiz__='',$__filtro__='',$__orden__='') {
			
		$__ids__ = '';
		$sep = '';
		
		if ($__raiz__=='') $__raiz__="root";
		
		//echo $__filtro__;		
		
		$this->Secciones->m_tsecciones->LimpiarSQL();
		if ($__raiz__=='root') { //raiz		
			$GLOBALS['_f_PROFUNDIDAD'] = 0;
			$GLOBALS['_tf_PROFUNDIDAD'] = "";
			$this->Secciones->m_tsecciones->FiltrarSQL('PROFUNDIDAD');			
		} else { //ramas
			$ids = $this->Contenidos->GetIdsContenidos($__raiz__,$__filtro__,$__orden__);
			if ($ids!='') { $__ids__.= $sep.$ids; $sep = '|';}
			$GLOBALS['_f_PROFUNDIDAD'] = 1;
			$GLOBALS['_tf_PROFUNDIDAD'] = "_superior_PROFUNDIDAD";						
			$GLOBALS['_f_ID_SECCION'] = $__raiz__;
			$this->Secciones->m_tsecciones->FiltrarSQL('PROFUNDIDAD');			
			$this->Secciones->m_tsecciones->FiltrarSQL('ID_SECCION');			
		}
		$this->Secciones->m_tsecciones->OrdenSQL('ORDEN');				
		$this->Secciones->m_tsecciones->Open();		
		//echo $this->Secciones->m_tsecciones->SQL;
		
		
		if ( $this->Secciones->m_tsecciones->nresultados>0 ) {
			$i = 0;
			while($_row_ = $this->Secciones->m_tsecciones->Fetch() ) {
				$ramas[$__raiz__][$i] = new CSeccion($_row_);
				/*
				if ($__raiz__=="root") {
					$ids = $this->Contenidos->GetIdsContenidos($ramas[$__raiz__][$i]->m_id,$__filtro__);
					if ($ids!='') {$__ids__.=$sep.$ids;  $sep = '|';}					
				}*/
				$i++;
			}
			if ($i>0) {
				foreach($ramas[$__raiz__] as $CRama) {								
					$idshijos = $this->GetRamaIdsContenidos($CRama->m_id,$__filtro__,$__orden__);					
					if ($idshijos!='') { $__ids__.=$sep.$idshijos; $sep = '|';}					
				}
			}
		}			
		
		$this->Secciones->m_tsecciones->Close();
		return $__ids__;
	}
		
	//=================
	//TEMPLATES
	//=================	
	
	function InicializarTemplatesDetalles() {
		if (file_exists('../../inc/include/templatesdetalles.php')) { 
			require '../../inc/include/templatesdetalles.php';
		}		
	}
		
	function InicializarTemplatesColapsados() {
		$this->InicializarTemplatesDetalles();
		if (file_exists('../../inc/include/templatescolapsados.php')) { 
			require '../../inc/include/templatescolapsados.php';
		} else {
			global $_TIPOS_;
			foreach( $_TIPOS_['tiposcontenidos'] as $tipo=>$id ) {
				if ($id>2) $this->TiposContenidos->SetTemplateColapsado($id);
			}			
		}
	}	

		
	function InicializarTemplatesResumenes() {
		$this->InicializarTemplatesDetalles();	
		if (file_exists('../../inc/include/templatesresumenes.php')) { 
			require '../../inc/include/templatesresumenes.php';
		} else {
			global $_TIPOS_;
			foreach( $_TIPOS_['tiposcontenidos'] as $tipo=>$id ) {
				if ($id>2) $this->TiposContenidos->SetTemplateResumen($id);
			}
		}
	}	
	
	function InicializarTemplatesCompletos() {
		$this->InicializarTemplatesDetalles();
		if (file_exists('../../inc/include/templatescompletos.php')) { 
			require '../../inc/include/templatescompletos.php';
		} else {
			global $_TIPOS_;
			foreach( $_TIPOS_['tiposcontenidos'] as $tipo=>$id ) {
				if ($id>2) $this->TiposContenidos->SetTemplateCompleto($id);
			}
		}
	}	
	
	function GetVariablesSistema() {
		
		global $_id_sistema_;
		global $_csistema_;
			
		//$_id_sistema_ = $this->GetRamaIdsContenidos( '', "(contenidos.ID_TIPOCONTENIDO=".$GLOBALS['_ID_SYSTEM_TYPE_CARD'].")");
		$_id_sistema_ = 1;
		$_csistema_ = $this->Contenidos->GetContenidoCompleto($_id_sistema_);		
		if (!is_object($_csistema_)) {
			ShowError("FATAL: falta ficha de sistema... intente nuevamente en unos segundos.");			
		}
				
	}	
	
	function Sistema( $__tipodetalle__, &$texto ) {
		global $__lang__;
		global $_csistema_;
		
		if (!is_object($_csistema_) )
			$this->GetVariablesSistema();
		if (is_object($_csistema_) ) {
			$CDetalle = $_csistema_->m_detalles[$__tipodetalle__];
			if ( is_object($CDetalle) ) {
				$texto = $this->TiposDetalles->Mostrar( $CDetalle );
			} else $texto = "system detail not found";
		}
	}	
	
	//FUNCION GENERAL DE CONSULTA Y NAVEGACION DE FICHAS
	function ModuloConsultaFichas( $idtipocontenido="", $filtros="", $orden="" ) {
		global $_contenido_,$_idfichabase_;
		global $_desde_,$_hasta_;
		global $_intervalo_,$_idsconsulta_,$_nresultados_,$_nxintervalo_,$_nintervalos_;
		global $_buscarhome_;
		global $_buscartexto_;
		global $_buscardetalles_; 
		global $_accion_;
		global $_debug_;
		global $CLang;
		global $_seccion_;		
		
		$_raizrama_ = '';
		
		//aqui filtramos por las secciones seleccionadas
			//el homebuscador nos debe pasar estas mismas!!!

		if ($_contenido_!='') {				
			//SE MUESTRA LA FICHA "RESUMEN" (id _contenido_)
			if ($_idsconsulta_!='' && $_idsconsulta_!="no") {
				$_ids_ = split("\|",$_idsconsulta_);
				$stop = false;
				$anterior = $_contenido_;
				$siguiente = $_contenido_;
				$n = count($_ids_); $i = 0;
				foreach($_ids_ as $idc) {					
					if ($stop) { $siguiente = $idc; break;}					
					if ($_contenido_==$idc) { $stop=true;}										
					if (!$stop) $anterior = $idc;					 
				}
			}			
			//imprime encabezado contenido
			$this->m_Contenido_Actual = $this->Contenidos->GetContenido($_contenido_);
			$CS_localidad = $this->Secciones->GetSeccion($this->m_Contenido_Actual->m_id_seccion);
			$CS_provincia = $this->Secciones->GetSeccionPadre($this->m_Contenido_Actual->m_id_seccion);
			//$CS_pais = $this->Secciones->GetSeccionPadre($CS_provincia->m_id);
			//$CS_pais->m_nombre.'&nbsp;&gt;&nbsp;'.
			$seccionencabezado = $CS_provincia->m_nombre.'&nbsp;&gt;&nbsp;'.$CS_localidad->m_nombre;

			if ($_accion_=='completo') {
				$this->TiposContenidos->m_templatescompletos[$this->m_Contenido_Actual->m_id_tipocontenido] = str_replace('{SECCION}',$seccionencabezado,$this->TiposContenidos->m_templatescompletos[$this->m_Contenido_Actual->m_id_tipocontenido]);
				$this->Contenidos->MostrarContenidoCompleto($_contenido_);
				$this->m_Contenido_Actual = $this->Contenidos->m_CContenido;
			} elseif ($_accion_=='resumen') {				
				$this->TiposContenidos->m_templatesresumenes[$this->m_Contenido_Actual->m_id_tipocontenido] = str_replace('{SECCION}',$seccionencabezado,$this->TiposContenidos->m_templatesresumenes[$this->m_Contenido_Actual->m_id_tipocontenido]);
				$this->Contenidos->MostrarContenidoResumen($_contenido_);
				$this->m_Contenido_Actual = $this->Contenidos->m_CContenido;
				echo '<br><br><a onmouseover="changeImages(\'mas_info_X\',\'../../inc/images/mas-info_01-over.gif\');return true" onmouseout="changeImages(\'mas_info_X\',\'../../inc/images/mas-info_01.gif\');return true"
				 href="home.php?_contenido_='.$_contenido_.'&_accion_=completo&_seccion_='.$this->m_Contenido_Actual->m_id_seccion.'&_idsconsulta_='.$_idsconsulta_.'&_intervalo_='.$_intervalo_.'">';				
				echo '<img id="mas_info_X" src="../../inc/images/mas-info_01.gif" alt="" name="mas_info_X" width="62" height="12" align="top" border="0">';
				echo '</a>';
			} else {
				echo "no se especifico accion para el contenido";
			}			
		} else {
			//GENERACION DE LA BUSQUEDA!!!
			
			if ($_seccion_=='' || $_seccion_=='no') {		
							$_raizrama_='';
			} else {
				$_raizrama_ = $_seccion_;				
			}
		
			$CSRAMA = $this->Secciones->GetSeccion($_raizrama_);
			
			//imprime encabezado contenido			
			if ($CSRAMA!=null) {	
				$CS = $CSRAMA;
				$seccionencabezado = $CS->m_nombre;
				while($CS!=null) {				 	
				 	if ($CS->m_profundidad>0) {
				 	  $CS = $this->Secciones->GetSeccion($CS->m_id_seccion);
				 	  $seccionencabezado = $CS->m_nombre.'&nbsp;&gt;&nbsp;'.$seccionencabezado;
				    } else $CS = null;
				}				
			}	


			//ITERAR SOBRE CADA SECCION HIJA A PARTIR DE LA RAIZ				
			//SACAR LOS IDS DE CONTENIDOS RESPECTIVOS DE LA SECCION, PONERLAS EN UN ARRAY 1|13|4|34
			$hoy = date("Y-m-d H:i:s");
			$maniana = date("Y-m-d",strtotime("+1 day"));
			
			$bajaautomatica = "(contenidos.FECHAALTA<='".$hoy."' AND ( contenidos.FECHABAJA=contenidos.FECHAALTA OR contenidos.FECHABAJA>'".$hoy."') AND contenidos.BAJA='S' )";
			
			if ($filtros!="") {				
				$_filtros_= $filtros." AND ".$bajaautomatica;
			} else {
				$_filtros_= $bajaautomatica;
			}
			//si tenemos un texto de busqueda rapida, incluimos eso en la busqueda
			if ($_buscartexto_!="") {
				$_buscartexto_x_ = str_replace(" ","%",$_buscartexto_);
				$filtrotexto = " AND (contenidos.TITULO LIKE '%".$_buscartexto_x_."%'";
				$filtrotexto.= " OR contenidos.COPETE LIKE '%".$_buscartexto_x_."%'";
				$filtrotexto.= " OR contenidos.CUERPO LIKE '%".$_buscartexto_x_."%'";
				$filtrotexto.=")";				
			} else $filtrotexto = "";
			
			//si venimos de ModuloBuscadorAsistido entonces tenemos que agregar la
			//busqueda dentro de los detalles....			
			if ($_buscardetalles_!='') {
				if ($GLOBALS['_td_sections']!='') { 
					$filtrodetalles = "detalles.ID_TIPODETALLE=".BOIS_SECTIONS." AND detalles.TXTDATA LIKE '%".$GLOBALS['_td_sections']."%'"; 
				}		
				if ($filtrodetalles!='')
					$filtrodetalles =" AND (".$filtrodetalles.")";
			} else $filtrodetalles = "";
			
			if ($idtipocontenido!="") {
				$_filtros_ = 'contenidos.ID_TIPOCONTENIDO='.$idtipocontenido; 				
			} else if ($_filtros_=="") {
				$_filtros_ = '(contenidos.ID_TIPOCONTENIDO<>'.$_ID_SYSTEM_TYPE_CARD.' AND contenidos.ID_TIPOCONTENIDO='.$_ID_VOID_TYPE_CARD.')'; 
				$_raizrama_ ='';
			}
			
			//GENERA LA CONSULTA+DETALLES
			if ($_buscardetalles_!='' && $filtrodetalles!='') 
				$this->Contenidos->m_tcontenidos->AgregarReferencia('ID','Caracteristicas','detalles','ID_CONTENIDO','TXTDATA');
	
			
			if ($_idsconsulta_=='' || $_idsconsulta_=="no") {
				if ($_debug_) echo "(".$_filtros_.")".$filtrotexto.$filtrodetalles;				
				$_idsconsulta_ = $this->GetRamaIdsContenidos( $_raizrama_ , "(".$_filtros_.")".$filtrotexto.$filtrodetalles );
			}

			if ($_buscardetalles_!='' && $filtrodetalles!='') 
				$this->Contenidos->m_tcontenidos->QuitarReferencia( 'ID', 'Caracteristicas' );			
			
			if ($_idsconsulta_!='' && $_idsconsulta_!="no") {
				$_ids_ = split("\|",$_idsconsulta_);
				$_nresultados_ = sizeof($_ids_);
				if ($_intervalo_=='') $_intervalo_ = 1;
				if ($_nxintervalo_=='') $_nxintervalo_ = 4;//esto podria cambiarse en el administrador
				$_desde_ = ($_intervalo_ - 1) * $_nxintervalo_ + 1;
				$_hasta_ = min( ($_desde_+$_nxintervalo_-1) , $_nresultados_);
				$_nintervalos_ = ceil( $_nresultados_ / $_nxintervalo_);
			} else {
				$_intervalo_ = '';
				$_nresultados_ = 0;
			}
			
			$this->ConsultaHeader();			
			
			//diferenciar ids de lo que se muestra...			
			//aqui imprimimos el intervalo
			if ($_intervalo_!='') {
				if ($_idsconsulta_!='') {
					$this->TiposContenidos->UpdateTemplatesColapsados('{CONSULTA}','_accion_=completo&_idsconsulta_='.$_idsconsulta_.'&_intervalo_='.$_intervalo_);
					for($e=$_desde_;$e<=$_hasta_;$e++) {
						$id = $_ids_[$e-1];
						$this->Contenidos->MostrarContenidoColapsado($id);
					}														
				}				
			}
			
			$this->ConsultaFooter();

		}		
		
	}	
	
	function ConsultaHeader() {
		
		global $_contenido_,$_idfichabase_;
		global $_intervalo_,$_idsconsulta_,$_nresultados_,$_nxintervalo_,$_nintervalos_;	
		global $seccionencabezado;
		global $_desde_,$_hasta_;
		global $_debug_;
		global $CLang;
		
		
			echo '<table width="100%" cellspacing="0" cellpadding="0"><tr><td>';
			
			echo '<table cellpadding="0" cellspacing="0" width="100%">';
			echo '<tr><td colspan="2" align="left" width="100" height="1"><img src="../../inc/images/spacer.gif" width="100" height="1" border="0"></td></tr>';
			echo '<tr><td align="left"><span class="modulo_txt_intervalo">'.$_desde_.'&nbsp;'.$CLang->m_Words['TO'].'&nbsp;'.$_hasta_.'&nbsp;&nbsp;&nbsp;'.$CLang->m_Words['OF'].'&nbsp;'.$_nresultados_.'&nbsp;'.$CLang->m_Words['RESULTS'].'</span></td>';
				echo '<td align="right">';
				/*
			if ($_intervalo_>1) 
				echo '<a href="#?_idsconsulta_='.$_idsconsulta_.'&_intervalo_='.($_intervalo_-1).'" class="modulo_txt_intervalo">'.$CLang->m_Words['PREVIOUS'].'</a><span class="modulo_txt_intervalo">&nbsp;&nbsp;</span>';
			if ($_intervalo_<$_nintervalos_) 
				echo '<span class="modulo_txt_intervalo">&nbsp;&nbsp;</span><a href="#?_idsconsulta_='.$_idsconsulta_.'&_intervalo_='.($_intervalo_+1).'" class="modulo_txt_intervalo">'.$CLang->m_Words['NEXT'].'</a>';
				*/
				echo'</td></tr>';
				
			echo '<tr><td colspan="2" align="left" width="100" height="1"><img src="../../inc/images/spacer.gif" width="100" height="1" border="0"></td></tr>';
			//echo '<tr><td bgcolor="#7f9db9" colspan="2" align="left" width="300" height="2"><img src="../../inc/images/spacer.gif" width="300" height="2" border="0"></td></tr>';				
			echo '<tr><td colspan="2" align="left" width="100" height="1"><img src="../../inc/images/spacer.gif" width="100" height="1" border="0"></td></tr>';
			if ($seccionencabezado!='') echo '<tr><td colspan="2" align="left"><span class="modulo_txt_intervalo">'.$seccionencabezado.'&nbsp;</span></td></tr>';												
			echo '</table>';		
	
	}

	
	function ConsultaFooter() {
		
		global $_contenido_,$_idfichabase_;
		global $_intervalo_,$_idsconsulta_,$_nresultados_,$_nxintervalo_,$_nintervalos_;	
		global $seccionencabezado;
		global $desde,$hasta;
		global $_debug_;
		global $CLang;
		
			echo '<br><table cellpadding="0" cellspacing="0" width="100%">';
			//echo '<tr><td bgcolor="#7f9db9" align="left" width="300" height="2"><img src="../../inc/images/spacer.gif" width="300" height="1" border="0"></td></tr>';
			echo '<tr><td align="left" width="100" height="1"><img src="../../inc/images/spacer.gif" width="100" height="1" border="0"></td></tr>';
			echo '<tr><td align="left"><span class="modulo_txt_intervalo">'.$CLang->m_Words['RESULTSPAGES'].':&nbsp;&nbsp;</span>';
			
			if ($_intervalo_>1) 
				echo '<a href="'.$GLOBALS['en'].'avisos.php?_idsconsulta_='.$_idsconsulta_.'&_intervalo_='.($_intervalo_-1).'" class="modulo_txt_intervalo">'.$CLang->m_Words['PREVIOUS'].'&nbsp;&lt;</a><span class="modulo_txt_intervalo">&nbsp;&nbsp;</span>';
			echo '<span class="modulo_txt_intervalo_actual">&nbsp;'.$or.'&nbsp;'.$_intervalo_.'&nbsp;&nbsp;</span>';				
			if ($_intervalo_<$_nintervalos_) 
				echo '<span class="modulo_txt_intervalo">&nbsp;&nbsp;</span><a href="'.$GLOBALS['en'].'avisos.php?_idsconsulta_='.$_idsconsulta_.'&_intervalo_='.($_intervalo_+1).'" class="modulo_txt_intervalo">&gt;&nbsp;'.$CLang->m_Words['NEXT'].'</a>';
			echo '<span class="modulo_txt_intervalo"><br></span>';			
			for($e=1,$or="",$br="",$c=0;$e<=$_nintervalos_;$e++,$c++) {
				//if (((($e-6) % 12) == 0) || ($e == 6) ) $br="<br>";
				if ( ($e % 13) == 0) $br="<br>";
				if ($e==$_intervalo_)
					echo '<span class="modulo_txt_intervalo_actual">&nbsp;'.$or.'&nbsp;'.$br.$e.'&nbsp;</span>';
				else
					echo '<span class="modulo_txt_intervalo">&nbsp;'.$or.'&nbsp;</span><a href="'.$GLOBALS['en'].'avisos.php?_idsconsulta_='.$_idsconsulta_.'&_intervalo_='.$e.'" class="modulo_txt_intervalo">'.$br.$e.'</a><span class="modulo_txt_intervalo">&nbsp;</span>';
				$or = "|";	
				$br = "";			
			}			
	
			echo'</td></tr>';
			echo '</table>';
			
			echo'</td></tr></table>';
	
	}

	
	
	///**************************************************************
	/// Shop functions
	///**************************************************************
	
	///Manda un mail al cliente notificandole la compra realizada
	function ShopMailConfirmation( $mailssellers, $fromname, $confirmationsubject, $messagetemplate ) {
		
		global $dd;
		global $total;
		global $subtransport;
		global $nproducts;
		global $Usuarios;
		global $productdescription,$total,$subtransport;
		
		if ($_SESSION['logged'] == "si") {
			$userdid = $_SESSION['loggedid'];
			
			$CU = $Usuarios->GetUsuario($userdid);
			if ($CU!=null) {
				$mail = new PHPMailer();
				$mail->IsMail();
				$mail->FromName = $fromname;
				$mail->AddAddress( $CU->m_mail, $CU->m_nombre." ".$CU->m_apellido);
				if ($dd=="s") {
					$mail->AddReplyTo("fcosta@computaciongrafica.com", "Webmaster");				
				} else {
					foreach($mailssellers as $m) {
						$mail->AddReplyTo($m);
					}
				}
				$mail->IsHTML(true);				
				$mail->Subject = $confirmationsubject;
				$mail->AltBody = str_replace(
				array('{NOMBRE}','{APELLIDO}','{FECHA}','{PRODUCTS}','{DESCRIPTION}','{PRECIO}','{DIRECCION}'),
				array( $CU->m_nombre, $CU->m_apellido, date("d/n/Y"), $nproducts, $productdescription, FormatPrice($total+$subtransport), $CU->m_direccion)
				, $messagetemplate);
				$mail->Body    = str_replace("\n","<br>",$mail->AltBody);
				if (!$mail->Send()) {
					//$error['errores']=1;
					//echo $CLang->m_ErrorMessages['MAILNOTSENT'];
				}				
			}
			
		}
				
		
	}

	///Manda un mail al administrador 	
	function ShopPaiementNotification( $sellername, $notificationsubject, $messagetemplate, $producttemplate  ) {
		
		global $_accion_;
		global $Comptes;
		global $Usuarios;
		global $_exito_;
		global $dd;
		
		$userid = $_SESSION['loggedid'];
			//Avis de reception de paiement
			foreach( $Comptes as $adminid=>$commande) {
				$CAdmin = $Usuarios->GetUsuario($adminid);				
				$commandestr = "";
				$comptetotal = 0;
				foreach($commande as $idproduit=>$data) {
					if ($data['opciones']=="undefined") $data['opciones']="-";
					$commandestr.= str_replace('{PRODUCT}', $data['producto']->m_titulo." ".$data['opciones']." X ".$data['cantidad'], $producttemplate );
					$commandestr.=  str_replace('{SUBTOTAL}', $data['subtotal'], $producttemplate);
					$comptetotal+= $data['subtotal'];
					if ($data['cantidad']>0) {
						//reduire stock
						$CP = $data['producto'];
						$CD = $CP->m_detalles['PRODUIT_DISPONIBLES'];
						$disps = $CD->m_entero;
						if(is_numeric($disps)) {
							if($disps>0) {
								$disps-= $data['cantidad'];
								if($disps<=0) {
									$disps = 0;
									//notification de stock en baisse....!!!!
									$commandestr.=" STOCK EN BAISSE!!!";
								}									
							}														
							 $_exito_ = $this->Detalles->m_tdetalles->ModificarRegistro( $CD->m_id ,
																	 array('ENTERO'=>$disps,
																	'FRACCION'=>$disps,
																	'ID_CONTENIDO'=>$CD->m_id_contenido,
																	'ID_TIPODETALLE'=>$CD->m_id_tipodetalle,
																	'DETALLE'=>''.$disps.'',
																	'ML_DETALLE'=>''.$disps.'',
																	'TXTDATA'=>''.$disps.'', 
																	'ML_TXTDATA'=>''.$disps.'',
																	'BINDATA'=>'') );
						}
					}
					$commandestr.= "\n\n";					
				}
				$CUser = $Usuarios->GetUsuario($userid);	
				$commandestr = str_replace( array('{NOMBRE}','{APELLIDO}','{DIRECCION}', '{TOTAL}','{PRODUCTS}'),array($CUser->m_nombre, $CUser->m_apellido, $CUser->m_direccion,"\n\nTOTAL: ".$comptetotal, $commandestr), $messagetemplate);
				//$mail->From = "damien@alternatifshop.com";
				$mail = new PHPMailer();
				$mail->IsMail();
				$mail->ClearAllRecipients();
				$mail->FromName = $sellername;
				if ($dd=="s") {
					$mail->AddAddress("fcosta@computaciongrafica.com", "WEBMASTER");
				} else {
					$mail->AddAddress($CAdmin->m_mail, $sellername.$CAdmin->m_nombre." ".$CAdmin->m_apellido);				
					$mail->AddReplyTo($CAdmin->m_mail, $sellername.$CAdmin->m_nombre." ".$CAdmin->m_apellido);	
				}
				$mail->IsHTML(true);
				
				$mail->Subject = $notificationsubject;
				$mail->Body    = str_replace("\n","<br>",$commandestr);
				$mail->AltBody = $commandestr;			
				if (!$mail->Send()) {
					//$error['errores']=1;
					//$msg = $CLang->m_ErrorMessages['MAILNOTSENT'];
				}				
				
			}//FIN RECEPTION PAIEMENT		
		
	}
	
	
	///Deslogua al usuario	
	function ShopLogout() {
		global $_accion_, $_nombre_, $_apellido_ , $_email1_ , $_email_ , $_direccion2_ , $_direccion_ , $_telefono_ , $_password_ , $_password2_ , $_empresa_;
		
		$_SESSION['logged'] = "no";
		$_SESSION['loggedid'] = 0;
		$_SESSION['productdescription'] = "";
		
		$_accion_ = "";
		$_nombre_ = "";
		$_apellido_ = "";
		$_email1_ = "";
		$_email_ = "";
		$_direccion2_ = "";
		$_direccion_ = "";
		$_telefono_ = "";
		$_password_ = "";
		$_password2_ = "";
		$_empresa_ = "";
	}
	
	///Desemcripta o resetea la contrase\F1a y se la manda al email del usuario
	function ShopRememberPassword( $motivo, $from, $fromname ) {
		
		global $error;
		global $res;
		global $CLang;
		global $msg;
		global $_accion_;
		global $_email1_, $_email_, $_passkey_;
		global $_nombre_,$_apellido_,$_telefono_,$_direccion2_,$_direccion_,$_email_,$_password_,$_password2_;
		
		$this->Usuarios->SesionOut();
		
		$Usuario = $this->Usuarios->GetUsuario( 0, $_email1_);
		
		if ( $Usuario != null ) {
			if ( $Usuario->m_password=="" || $Usuario->m_passmd5=="" || $Usuario->m_passkey=="") {		
				if ($this->Usuarios->ResetearPassword( $Usuario )) {
					$Usuario = $this->Usuarios->GetUsuario( $Usuario->m_id );
				}
			}
			
			$dec = $this->Usuarios->GetPassword( $Usuario  );			
			
			require "../../inc/core/validateemail.php";	
			require "../../inc/include/phpmailer/class.phpmailer.php";
		
			$mail = new PHPMailer();
			$mail->IsMail();
			
			$mensaje1 = "".$CLang->m_Users["USERFIRSTNAME"].":".$Usuario->m_nombre." ".$Usuario->m_apellido;
			$mensaje1.= "\n\r".$CLang->m_Users["USEREMAIL"].":".$Usuario->m_nick;
			$mensaje1.= "\n\r".$CLang->m_Users["USERPASSWORD"].":".$dec;
			
			$mail->From = $from;
			$mail->FromName = $fromname;
			$mail->AddAddress( $Usuario->m_mail, $Usuario->m_nombre." ".$Usuario->m_apellido);	
			$mail->IsHTML(true);
			
			$mail->Subject = $motivo;
			$mail->Body    = str_replace("\n","<br>",$mensaje1);
			$mail->AltBody = $mensaje1;			
			if (!$mail->Send()) {
				$error['errores']=1;
				$msg = $CLang->m_ErrorMessages['MAILNOTSENT'];
				$res = false;
			} else {
				$msg = $CLang->m_Messages['PASSWORDMAILSENT'];
				$error['errores']=0;
				$_accion_ = "";
				$_email1_= "";
				$_nombre_ = "";
				$res = false;								
			}
		} else {
			echo $CLang->m_ErrorMessages['USERDONTEXIST'];
			$error['errores']++;
			$res = false;
		}
	}	
	
	///Loguea al usuario
	function ShopLogUser() {
		
		global $error;
		global $_accion_;
		global $res;
		global $CLang;
		global $productdescription;
		global $_email_,$_nombre_,$_apellido_,$_direccion_,$_telefono_,$_direccion2_;
		global $_email1_,$_password1_;
		global $res;
				
			if ( $this->Usuarios->SesionIn( $_email1_, $_password1_ ) ) {
				$_email_ = $this->Usuarios->m_CSesionUsuario->m_mail;
				$_nombre_ = $this->Usuarios->m_CSesionUsuario->m_nombre;
				$_apellido_ = $this->Usuarios->m_CSesionUsuario->m_apellido;
				$_direccion_ = $this->Usuarios->m_CSesionUsuario->m_direccion;				
				$_telefono_ = $this->Usuarios->m_CSesionUsuario->m_telefono;				
				$_direccion2_ = $this->Usuarios->m_CSesionUsuario->m_contact;
				$_ciudad_ = $this->Usuarios->m_CSesionUsuario->m_ciudad;
				$_pais_ = $this->Usuarios->m_CSesionUsuario->m_pais;

				$error['errores']++;
				$error['verificar'] = $CLang->m_Messages["UPDATEYOURDATA"];
				$res = false;
				$_accion_ = "log";	

			} else {
				echo $CLang->m_ErrorMessages['LOGERROR'];				
				$_SESSION['logged'] = "no";
				$error['errores']++;
				$res = false;
			}
		
	}
	
	///construye la descripcion del producto
	function ShopProductDescription() {
		
		global $orderproducts;
		global $productdescription,$cr,$poids,$paypalproducts;
		global $total,$subtotal,$subtransport,$quantitetotal;
		
		echo '<br><br><table border="0"  cellpadding="0" cellspacing="1" bgcolor="#FFFFFF"><tr><td>';	
		echo '<table border="0"  cellpadding="5" cellspacing="0" bgcolor="#FFFFFF">';
		echo '<tr bgcolor="#EEEEEE"><td>'.$CLang->m_Words['SHOPPINGLISTITEMS'].'</td><td>'.$CLang->m_Words['ARTICLEPRICE'].'</td><td>'.$CLang->m_Words['ARTICLENUMBER'].'</td><td>Total</td></tr>';
		
		$cr = 0;
		$poids = 0;
		$paypalproducts = "";
		$productdescription = "";
	
		$ss = "";
		foreach( $orderproducts as $id=>$arr) {
			$cant = $arr['cantidad'];
			$opciones = $arr['opciones'];
			
			$CProducto = $this->Contenidos->GetContenido($id);
			if ($CProducto!=null) $CProducto->m_detalles = $this->Detalles->GetDetallesColapsados($id);
			
			//ATTENTION: must be a number!!!
			$subtotal = $cant * $CProducto->m_detalles['PRODUIT_TARIF']->m_fraccion;
			$quantitetotal+= $cant;
			$total+= $subtotal;
			
			$cr++;
			if (($cr % 2)==0) echo '<tr bgcolor="#EEEEEE">';
			else echo '<tr>';
			echo '<td><span>'.$CProducto->m_titulo.' '. $opciones.'</span></td>';		
			echo '<td><span>'.FormatPrice($CProducto->m_detalles['PRODUIT_TARIF']->m_detalle).' \80 TTC</span></td>';
			echo '<td>'.$cant.'</td>';				
			echo '<td>'.FormatPrice($subtotal).' \80 TTC</td>';
			echo '</tr>';					
	
			$productdescription.= $ss.$CProducto->m_titulo." ".$opciones." x".$cant;
			$productdescriptionpaypal.= $ss.$CProducto->m_titulo." ".$opciones." x".$cant." Compte:".$CAdmin->m_nick;						
			$ss = ", ";
			$paypalproducts.= '<input type="hidden" name="amount_"'.$cr.' value="'.$CProducto->m_detalles['PRODUIT_TARIF']->m_detalle.'">';
			$paypalproducts.= '<input type="hidden" name="quantity_"'.$cr.' value="'.$cant.'">';
			$paypalproducts.= '<input type="hidden" name="item_name_"'.$cr.' value="'.$CProducto->m_titulo.'">';
			$paypalproducts.= '<input type="hidden" name="item_number_"'.$cr.' value="'.$CProducto->m_id.'">';
	
		}
			
		//au dela de 400 \80 transports gratuits
		if ($total>=80) {
			$subtransport = 0;
		} else $subtransport = 5;
		
		echo '<tr bgcolor="#FFFFFF"><td></td><td></td><td>'.$CLang->m_Words['SUBTOTAL'].'<br>'.$CLang->m_Words['SHIPPINGCOST'].'<br><hr>'.$CLang->m_Words['TOTAL'].'</td><td>&nbsp;'.$total.' \80 TTC <br>&nbsp;'.$subtransport.' \80 TTC<br><hr>&nbsp;'.($total+$subtransport).' \80 TTC </td></tr>';
		echo '</table>';
		echo '</td></tr></table>';
		echo '</td></tr></table>';
		
		$productdescription.= "\n\nDEPARTEMENT:".$_SESSION['descriptiondepartement']."\n\n".$CLang->m_Words['TOTAL'].":".$total. "\nTRANSPORT:".$subtransport."\n".$CLang->m_Words['TOTAL'].":".( $total + $subtransport );
		
		
	}
		
	///Si el usuario esta logueado esta funcion trae los datos del usuario
	function ShopLoggedUser() {

		global $error;
		global $res;
		global $CLang;
		global $productdescription;
		global $_email_,$_nombre_,$_apellido_,$_direccion_,$_telefono_,$_direccion2_,$_pais_,$_ciudad_;
		
		$this->Usuarios->SesionContinue();

		$Usuario = $this->Usuarios->m_CSesionUsuario;
		
		if ($Usuario!=null) {
			$_email_ = $Usuario->m_mail;
			$_nombre_ = $Usuario->m_nombre;
			$_apellido_ = $Usuario->m_apellido;
			$_direccion_ = $Usuario->m_direccion;
			$_telefono_ = $Usuario->m_telefono;				
			$_direccion2_ = $Usuario->m_empresa;
			
			$_pais_ = $Usuario->m_pais;
			$_ciudad_ = $Usuario->m_ciudad;
				
			$_SESSION['productdescription'] = $productdescription;
			$res = true;
		} else {
			$this->Usuarios->SesionOut();
			echo $CLang->m_ErrorMessages['LOGERROR'];				
			$error['errores']++;
			$res = false;
		}
		
	}
	
	///Chequea que los datos del formulario est\E9n todos
	function ShopCheckUserData() {
		
		global $CLang;
		global $error;
		global $msgerror;
		global $msgmailerror;
		global $_nombre_,$_apellido_,$_telefono_,$_direccion2_,$_direccion_,$_email_,$_password_,$_password2_;
		
		//CAMPOS OBLIGATORIOS
		$msgerror = '<span class="error"><br>'.$CLang->m_ErrorMessages['REQUIREDFIELD'].'</span>';
		$msgmailerror = '<span class="error"><br>'.$CLang->m_ErrorMessages['INVALIDEMAIL'].'</span>';
		
		if ($_nombre_ == "") { $error['errores']++; $error['_nombre_'] = $msgerror;}
		if ($_apellido_ == "") { $error['errores']++; $error['_apellido_'] = $msgerror;}		
		if ($_telefono_ == "") { $error['errores']++; $error['_telefono_'] = $msgerror;}
		if ($_direccion_ == "") { $error['errores']++; $error['_direccion_'] = $msgerror;}		
		if ($_direccion2_ == "") { $error['errores']++; $error['_direccion2_'] = $msgerror;}		
		if ($_email_ == "") { $error['errores']++; $error['_email_'] = $msgerror;}
		if ( ( $_password_ != "" ) && ( $_password_ != $_password2_) ) { $error['errores']++; $error['_password_'] = $msgerror;}
		//else if (checkEmail($_email_)==false) { $error['errores']++; $error['_email_'].= $msgmailerror;}					
		return ($error['errores']>0); 
	}
	
	///Genera un nuevo usuario con los datos pasados
	function ShopNewUser() {

		global $CLang;
		global $_SESSION;
		global $res;
		global $_nombre_,$_apellido_,$_telefono_,$_contact_,$_direccion2_,$_email_,$_password_,$_password2_;
		global $productdescription;

		$NuevoUsuario = new CUsuario( 
		array( 	'usuarios.NICK'=>$_email_,
				'usuarios.NOMBRE'=>$_nombre_, 
			   	'usuarios.APELLIDO'=>$_apellido_,
			   	'usuarios.TELEFONO'=>$_telefono_,
				'usuarios.DIRECCION'=>$_direccion_,
				'usuarios.CONTACT'=>$_direccion2_,
				'usuarios.EMAIL'=>$_email_,
				'usuarios.PASSWORD'=>$_password_) );
		
		if ($this->Usuarios->NuevoUsuario( $NuevoUsuario )) {
			$_SESSION['logged'] = 'si';
			$_SESSION['loggedid'] = $NuevoUsuario->m_id;
			$_SESSION['productdescription'] = $productdescription;			
		} else {
			$res = false;			
			$_accion_ = "";
			if ($this->Usuarios->NickUtilizado( $NuevoUsuario->m_nick )) {
				$error['errores']++;
				$error['_email_'] = $msgerror;
				return $CLang->m_Words["NICKUSED"];				
			}
			return $CLang->m_Words["REGISTERFAILED"];
		}
	}
	
	///Actualiza los datos del usuario logueado
	function ShopUpdateUser( $usid = "" ) {

		global $res;
		global $_nombre_,$_apellido_,$_telefono_,$_direccion2_,$_direccion_,$_email_,$_password_,$_password2_;
		
		if ($usid=="") $usid = $_SESSION['loggedid'];
		$UsuarioActual = $this->Usuarios->GetUsuario($usid);
		
		$UsuarioActual->m_nombre = $_nombre_;
		$UsuarioActual->m_apellido = $_apellido_;
		$UsuarioActual->m_telefono = $_telefono_;
		$UsuarioActual->m_direccion = $_direccion_;
		$UsuarioActual->m_contact = $_direccion2_;
		$UsuarioActual->m_password = $_password_;
		
		$res = $this->Usuarios->ActualizarUsuario( $usid, $UsuarioActual, $_password2_);
		return $res;		
		
	}

}

if (file_exists('../../inc/include/CSitioExtended.php')) { 
	require '../../inc/include/CSitioExtended.php';
}

?>
