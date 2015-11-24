<?Php
/**
*ModuloPanel
*
**/
global $CLang;
global $_accion_;
global $_confirmaccion_;
global $_cID_;
global $ID_TIPOCONTENIDO;
global $_DIR_SITEABS;

global $Contenido;
global $Usuario;
global $_template_consulta_;
global $_template_edicion_;
global $Sitio;
$execute = "";

//$_template_consulta_ = implode('',file("../../inc/templates/CONTENIDO.panel.consulta.html"));
//$_template_edicion_ = implode('',file("../../inc/templates/CONTENIDO.panel.edicion.html"));

$_template_consulta_ = "";
$_template_edicion_ = "";

$Usuario = $this->Usuarios->GetSesionUsuario();
?>

<div class="clear"></div>
<div class="panel">
<?
if (  $this->Usuarios->Logged() && $Usuario->m_nivel<=4) {	
	
	if ( $_accion_!="vermas" && $_accion_!="" ) {
		
		///choose the form depending on ID_TIPOCONTENIDO
		if ($_cID_!="") {
			$Contenido = $this->Contenidos->GetContenido($_cID_);
		} else $Contenido = null;
		
		$tipocontenido = $Sitio->TiposContenidos->GetTipo($ID_TIPOCONTENIDO);
		$_template_edicion_ = implode( '', file("../../inc/templates/FICHA_".$tipocontenido.".panel.consulta.html") );
		if ($_template_edicion_=="") 
			$_template_edicion_ = implode( '', file("../../inc/templates/CONTENIDO.panel.edicion.html") );	
		//error_reporting(E_ALL);
		
		if ( $_accion_=="agregar" ) {
			
			$SeccionPanel = $this->Secciones->GetSeccionByType(SECCION_PANEL);
			$idseccion = $SeccionPanel->m_id;
			/*
			if ($ID_TIPOCONTENIDO==FICHA_CONVOCATORIA) {
				$idseccion = 3;
			} else if ($ID_TIPOCONTENIDO==FICHA_ORGANIZACION) {
				$idseccion = 4;
			} else if ($ID_TIPOCONTENIDO==FICHA_EVENTO) {
				$idseccion = 6;
			} else if ($ID_TIPOCONTENIDO==FICHA_DOCUMENTO) {
				$idseccion = 8;
			} else if ($ID_TIPOCONTENIDO==FICHA_GRUPO) {
				$idseccion = 12;
			}
			*/
			$TC = $this->TiposContenidos->GetTipoContenido($ID_TIPOCONTENIDO);
			ShowMessage( $CLang->Get('ADDINGCARD').' > '.$TC->m_descripcion );
			
			//create a sosie to copy from...
			$ContenidoNuevo = new CContenido(0);
			$ContenidoNuevo->m_id_tipocontenido = $ID_TIPOCONTENIDO;
			$ContenidoNuevo->m_id_seccion = $idseccion;
			$ContenidoNuevo->m_id_contenido = 1;
			$ContenidoNuevo->m_titulo = "";
			$ContenidoNuevo->m_ml_titulo = "";
			$ContenidoNuevo->m_copete = "";
			$ContenidoNuevo->m_ml_copete = "";
			$ContenidoNuevo->m_cuerpo = "";
			$ContenidoNuevo->m_ml_cuerpo = "";
			$ContenidoNuevo->m_id_usuario_creador = $Usuario->m_id;
			$ContenidoNuevo->m_id_usuario_modificador = $Usuario->m_id;
			$ContenidoNuevo->m_baja = "N";		
			//create edit form
			
			//create a new one and save...
			//then open to edit...that's better for gallerys, if something fail, we delete it
			$NewRecord = $this->Contenidos->CrearContenidoCompleto( $ID_TIPOCONTENIDO, $ContenidoNuevo );//baja sera N...
			
			if ($NewRecord!=null) {
				$NewRecord = $this->Contenidos->GetContenidoCompleto($NewRecord->m_id);
				$_cID_ = $NewRecord->m_id;
			} else ShowError( "Couldn't create record" );
			
			$RECORDEDIT = $this->UserAdminRecordEdit( "editrecord", $ID_TIPOCONTENIDO, $ContenidoNuevo, $_template_edicion_ );
			
			$_confirmaccion_ = "confirmedit";

		} else if ( $_accion_=="modificar" ) {
			
			$ID_TIPOCONTENIDO = $Contenido->m_id_tipocontenido;
			$TC = $this->TiposContenidos->GetTipoContenido($ID_TIPOCONTENIDO);
			
			ShowMessage( $CLang->Get('MODIFYINGCARD').' > '.$Contenido->m_titulo );
			
			$RECORDEDIT = $this->UserAdminRecordEdit( "editrecord", $ID_TIPOCONTENIDO, $Contenido, $_template_edicion_ );
			
			$_confirmaccion_ = "confirmedit";
			
		} else if ( $_accion_=="borrar" ) {
			
			ShowMessage( $CLang->Get('DELETINGCARD').' > '.$Contenido->m_titulo );
			ShowError( $CLang->Get("RECORD_DELETION_WARNING") );
			ShowError( "<br>".$CLang->m_Messages["CONFIRMATION"] );			

			$RECORDEDIT = $this->UserAdminRecordEdit( "editrecord", $ID_TIPOCONTENIDO, $Contenido, $_template_edicion_ );

			if ($RECORDEDIT!="X") {
				$RECORDEDIT = '<div style="display:none">'.$RECORDEDIT.'</div>';
				$RECORDEDIT.= '<button type="submit" class="inputbutton" onclick="javascript:submit();">'.$CLang->m_Words['YES'].'</button>';				
				$RECORDEDIT.= '<button type="button" class="inputbutton" onclick="javascript:window.location.href=\'/panel\';">'.$CLang->m_Words['NO'].'</button>';
				$_confirmaccion_ = "confirmdelete";
			}			
			
		} else if ($_accion_=="confirmedit") {

			$ConfirmRecord = new CContenido();//create from globals
			$ConfirmRecord->m_id = $_cID_;
			$ConfirmRecord->m_id_usuario_creador = $Usuario->m_id;
			$ConfirmRecord->m_id_usuario_modificador = $Usuario->m_id;
			$ConfirmRecord->m_titulo = alphawithaccents($ConfirmRecord->m_titulo);
			$ConfirmRecord->m_ml_titulo = "";
			$ConfirmRecord->m_ml_copete = "";
			$ConfirmRecord->m_ml_cuerpo = "";
			$ConfirmRecord->ToGlobals();

					
			
			if ($this->UserAdminRecordConfirm( "confirmeditrecord", $ConfirmRecord, $_template_edicion_ )) {
				echo '<span style="color:#00CC00">[old record confirmed]</span>';
				//post process:
				$this->UserAdminRecordPostProcess( $ConfirmRecord );
				//$this->UserAdminRecordPostProcessAll();
				echo "<script>window.location.href='/panel';</script>";
			} else {
				echo '<span style="color:#CC0000">[old record NOT confirmed]</span>';
				//echo $this->Detalles->GetLastError();
				$recorderror = '<span style="color:#CC0000">'.$CLang->m_ErrorMessages[$this->Detalles->GetLastError()->m_tipo].'</span>';
				$RECORDEDIT = $this->UserAdminRecordEdit( "editrecord", $ID_TIPOCONTENIDO, $ConfirmRecord );
				$_confirmaccion_ = "confirmedit";
			}			
			
			
		} else if ($_accion_=="confirmdelete") {
			
				$ConfirmRecord = new CContenido();//create from globals
				$ConfirmRecord->m_id = $_cID_;
				$ConfirmRecord->m_id_usuario_creador = $Usuario->m_id;
				$ConfirmRecord->m_id_usuario_modificador = $Usuario->m_id;
				$ConfirmRecord->m_baja = "S";
				$ConfirmRecord->ToGlobals();			
				if ($this->UserAdminRecordConfirm( "confirmdeleterecord", $ConfirmRecord, $_template_edicion_ )) {
					//echo '<span style="color:#00CC00">[record deletion confirmed]</span>';
					echo "<script>window.location.href='/panel';</script>";
				}
			
		}
	?>
		<form autocomplete="off" name="register" id="register" method="post"  enctype="multipart/form-data" action="/panel">
			<input type="hidden" name="_accion_" value="<?=$_confirmaccion_?>">
			<input type="hidden" name="_cID_" value="<?=$_cID_?>">
	<?
	echo $RECORDEDIT;
	echo $execute;
	?>
</form>		
		
		
		<?
		
		
	} else {
	
	function UserABMContent( $str_miscontenidos, $str_contenido, $lnk_contenido, $id_tipocontenido ) {
		
		global $_tcontenidos_;
		global $Usuario;
		global $ID_TIPOCONTENIDO;
		global $_accion_;
		global $Sitio;
		global $_template_consulta_;
		
		if ($_template_consulta_=="") {
			
			//_template_consulta_ = implode('',file("../../inc/templates/FICHA_PROYECTO.panel.consulta.html"));
			//$strID_TIPOCONTENIDO = ;
			$tipocontenido = $Sitio->TiposContenidos->GetTipo($id_tipocontenido);
			
			$_template_consulta_ = implode( '', file("../../inc/templates/FICHA_".$tipocontenido.".panel.consulta.html") );
			if ($_template_consulta_=="") 
				$_template_consulta_ = implode( '', file("../../inc/templates/CONTENIDO.panel.consulta.html") );
			
		}
		?>
		<div class="panelcontent">
		<h3><?=$str_miscontenidos?></h3>
    	<div>
      </div>
      <div class="panelrecords">
		<?
		
		$tc = $_tcontenidos_;
         
		$tc->LimpiarSQL();
		$tc->FiltrarSQL( 'ID_TIPOCONTENIDO','',$id_tipocontenido );
		//SOLO SI EL USUARIO LO CREO
		//if ($Usuario->m_nick!="cg_admin" || $Usuario->m_nivel!=0)
						$tc->FiltrarSQL( 'ID_USUARIO_CREADOR','', $Usuario->m_id );
	      
	    if ( $_accion_=="vermas" && $ID_TIPOCONTENIDO==$id_tipocontenido) {
      		//$tc->LimiteSQL( 0, 5 );
      	} else $tc->LimiteSQL( 0, 10 );
      	
      	
		$tc->OrdenSQL('contenidos.ID DESC');      
      
		$tc->Open();
	    $cc = 0;
	    if ($tc->nresultados>0) {
	    	
	      while( $rr = $tc->Fetch() ) {
	      	$cc++;
	      	$Contenido = new CContenido( $rr );      	
	      	
	      	//*FECHAEVENTO DIA* | 	
	      	
	      	$titulo = $Contenido->m_titulo; 
	      	if (strlen($titulo)>20) $titulo = substr( $Contenido->m_titulo, 0, 24)."...";
	      	/*
	      	$Sitio->TiposContenidos->MostrarCompleto( $Contenido, '
		      <div title="*TITULO*" class="panelrecord" onmouseover="javascript:showdiv(\'drec*IDCONTENIDO*_menu\');"  onmouseout="javascript:hidediv(\'drec*IDCONTENIDO*_menu\');">
		      <a  href="'.$lnk_contenido.'">'.$titulo.'</a>
			      <div id="drec*IDCONTENIDO*_menu" style="float:right;display:none;">
			      	<a  class="panelitem" href="/panel/_accion_=borrar&_cID_=*IDCONTENIDO*">Eliminar</a> | <a  class="panelitem" href="/panel/_accion_=modificar&_cID_=*IDCONTENIDO*">Modificar</a>
			      </div>
		    </div>
		    <div class="clear"></div>
		      ' );
				*/
	      	
	      	$Sitio->TiposContenidos->MostrarConsulta( $Contenido, str_replace("[LINK_CONTENIDO]", $lnk_contenido, $_template_consulta_) );
	      	
	      }
	      
	    }		

	    ?>
	    </div>
	    <div class="panelcontent_menu"><hr>
		    <a href="/panel?_accion_=vermas&ID_TIPOCONTENIDO=<?=$id_tipocontenido?>" class="panelitem">Ver todo</a> | 
		    <a href="/panel?_accion_=agregar&ID_TIPOCONTENIDO=<?=$id_tipocontenido?>" class="panelitem">Agregar <?=$str_contenido?></a>
	    </div>
	    </div>
	    <?
	}
	?>

<div class="paneles">
      <?
      UserABMContent( "Proyectos", "Proyectos", "/proyecto/*IDCONTENIDO*", FICHA_PROYECTO );
      ?>
      <?
      //UserABMContent( "Tutoriales", "Tutoriales", "/tutoriales/*IDCONTENIDO*", FICHA_TUTORIAL );
      ?>
      <?
      //UserABMContent( "Desarrollos", "Desarrollos", "/desarrollos/*IDCONTENIDO*", FICHA_DESARROLLO );
      ?>
      <?
      //UserABMContent( "Productos", "Productos", "/productos/*IDCONTENIDO*", FICHA_PRODUCTO );
      ?>
      <?
      //UserABMContent( "Tecnologías", "Tecnologías", "/tecnologias/*IDCONTENIDO*", FICHA_TECNOLOGIA );
      ?>
		<?
      //UserABMContent( "Trabajos", "Trabajos", "/trabajos/*IDCONTENIDO*", FICHA_TRABAJO );
      ?></div> 	
	<?	
	}
	
} else {
	
	ShowError( $CLang->Get("PERMISSION_NOT_GRANTED") );
	
}
?>
</div>