<?Php

			require "../../inc/include/deftabla.php";
			
			$TiposSecciones = new CTiposSecciones($_ttipossecciones_);
		
			$Secciones = new CSecciones($_tsecciones_,$TiposSecciones);		

			global $_idseccion_;
			
			global $_TIPOS_;

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
						<li><span class="folder">
							<a class="nombre"  title="[ id: *ID*, , tipo: '.$tipo.', id_tiposeccion: '.$idTS.']">*NOMBRE*</a>
						'.$extra.'</span>{MOSTRARRAMA}</li>' );
			}
						
			if ($_idseccion_) {
				$Secciones->MostrarArbol( $_idseccion_ );
			}


?>

