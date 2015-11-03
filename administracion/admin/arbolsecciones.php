<?Php

			require "../../inc/include/deftabla.php";
			
			$TiposSecciones = new CTiposSecciones($_ttipossecciones_);
		
			$Secciones = new CSecciones($_tsecciones_,$TiposSecciones);		
/*

 		$this->TiposDetalles = new CTiposDetalles($__ttiposdetalles__);
		
		$this->Detalles = new CDetalles($__tdetalles__,$this->TiposDetalles);	
		
		//DEFINICION DE TIPOS DE CONTENIDOS
		$this->TiposContenidos = new CTiposContenidos($__ttiposcontenidos__,$this->Detalles);
		
		$this->Contenidos = new CContenidos($__tcontenidos__,$this->TiposContenidos);		

		$this->TiposArchivos = new CTiposArchivos($__ttiposarchivos__);
		
		$this->Archivos = new CArchivos($__tarchivos__,$this->TiposArchivos);		

		$this->Usuarios = new CUsuarios( $__tusuarios__, $this->Secciones, $this->Contenidos );		
	
		$this->Logs = $__tlogs__;
*/

?>
			
<html>
<head>
<title><?=$CLang->m_Words['ADMINISTRATION']?> --- </title>
<? require "../include/style.php"; ?>
<? require "../include/scripts.php"; ?>
</head>
<body>			 
			
			
			<?
			global $_TIPOS_;
/*			
			foreach( $_TIPOS_['tipossecciones'] as $tipo=>$id ) {
				$TiposSecciones->SetTemplateArbolNodo($id,'<table cellpadding="0" cellspacing="0"><tr><td valign="middle"><div id ="div_expand_*ID*" 
				style="position:relative;display:none;margins:0px;" 
				onclick="javascript:showdiv(\'div_collapse_*ID*\');hidediv(\'div_expand_*ID*\');showdiv(\'div_*ID*\');"><img src="../images/expand.png" border="0"></div>
				<div id ="div_collapse_*ID*" 
				style="position:relative;display:inline;margins:0px;" 
				onclick="javascript:showdiv(\'div_expand_*ID*\');hidediv(\'div_collapse_*ID*\');hidediv(\'div_*ID*\');"><img src="../images/collapse.png" border="0"></div>
				</td><td valign="middle">*NOMBRE* <span style="color:#44A;font-weight:bold;font-size:9px;">'.$tipo.' : '.$id.']</span></td><td  valign="middle"> 
				<a href="javascript:conf_nuevaseccion(*ID*);"><img src="../../inc/images/agregarmini.gif" border="0"></a>
				<a href="javascript:conf_modificarseccion(*ID*);"><img src="../../inc/images/editarmini.gif" border="0"></a>
				<a href="javascript:conf_borrarseccion(*ID*);"><img src="../../inc/images/borrarmini.gif" border="0"></a>
				<a href="javascript:conf_refreshseccion(*ID*);">O</a>
				</td></tr></table>
				{MOSTRARRAMA}','','','','','');
				$Secciones->SetTemplateArbolRama($id, '<table border="0"><tr><td style="padding-left:20px;"><div id="div_*ID*loader" name="div_*ID*loader" style="margin:0px;padding:0px;display:none;"><img src="../../inc/images/ajax-loader.gif" border="0" /></div>
				<div id="div_*ID*" style="position:relative;display:inline;" align="left">','</div></td></tr></table>');
			}
*/			

			foreach( $_TIPOS_['tipossecciones'] as $tipo=>$idTS ) {
				$extra = '<a href="javascript:conf_nuevaseccion(*ID*);"><img src="../../inc/images/agregarmini.gif" border="0"></a>
				<a href="javascript:conf_modificarseccion(*ID*);"><img src="../../inc/images/editarmini.gif" border="0"></a>
				<a href="javascript:conf_borrarseccion(*ID*);"><img src="../../inc/images/borrarmini.gif" border="0"></a>
				<a href="javascript:conf_refreshseccion(*ID*);">O</a>';
								
				$Secciones->SetTemplateArbolRama( $idTS, '<div id="div_*ID*loader" name="div_*ID*loader" style="margin:0px;padding:0px;display:none;">
						<img src="../../inc/images/ajax-loader.gif" border="0" />
						</div>
						<ul id="div_*ID*" class="rama">','</ul>' );
				
				$TiposSecciones->SetTemplateArbolNodo( $idTS, '
						<li><span class="folder"><a class="nombre" title="[ id: *ID*, tipo: '.$tipo.' , id_tiposeccion: '.$idTS.']">*NOMBRE*</a>						
						'.$extra.'</span>{MOSTRARRAMA}</li>' );
			}
				
			
			//$TiposSecciones->SetTemplateArbolNodo( 2, '{MOSTRARRAMA}');
			
			echo '<ul id="arbolito" class="filetree treeview">';
			//ShowMessage("<pre>".print_r( $this->Secciones->rama, true)."</pre>");
			$Secciones->MostrarArbol();
			echo '</ul>';
			echo "<script>
				
			$('#arbolito').treeview({
			persist: 'location',
			collapsed: true,
			unique: true,
			/*persist: 'cookie',*/
			animated: 'fast'
			});
				
			</script>
			";
			
			
?>
<div id="div_editar_seccionloader" name="div_editar_seccionloader" style="margin:0px;padding:0px;display:none;"><img src="../../inc/images/ajax-loader.gif" border="0" /></div>
<div id="div_editar_seccion" name="div_editar_seccion" style="position:absolute;left:0px;top:0px;display:none;">
-
</div>

</body>
</html>

